<?php

namespace Brasa\RecursoHumanoBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BuscarEmpleadoController extends Controller
{
    var $strDqlLista = "";        
    
     /**
     * @Route("/rhu/buscar/empleado", name="brs_rhu_buscar_empleado")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);        
        return $this->render('BrasaRecursoHumanoBundle:Buscar:empleado.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->buscarDql(
                $session->get('filtroEmpleadoNombre'), 
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),                
                $session->get('filtroIdentificacion'),
                $session->get('filtroRhuCodigoEmpleado')
                ); 
    }    
    
    private function filtrarLista($form) {
        $session = new Session;
        $codigoCentroCosto = "";
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();    
        }        
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);        
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEmpleadoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroRhuCodigoEmpleado', $form->get('TxtCodigo')->getData());
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;           
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)                                           
            ->add('estadoActivo', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'ACTIVOS' => '1', 'INACTIVOS' => '0')))                            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroRhuCodigoEmpleado')))                                            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }                   
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CODIGO CONTRATO')                    
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'IBC')
                    ->setCellValue('H1', 'IBP');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arIngresosBase = new \Brasa\RecursoHumanoBundle\Entity\RhuIngresoBase();
        $arIngresosBase = $query->getResult();
        foreach ($arIngresosBase as $arIngresoBase) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIngresoBase->getCodigoIngresoBasePk())
                    ->setCellValue('B' . $i, $arIngresoBase->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arIngresoBase->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arIngresoBase->getCodigoContratoFk())                    
                    ->setCellValue('E' . $i, $arIngresoBase->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('F' . $i, $arIngresoBase->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arIngresoBase->getvrIngresoBaseCotizacion())
                    ->setCellValue('H' . $i, $arIngresoBase->getvrIngresoBasePrestacion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IngresosEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="IngresosEmpleado.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }        
}
