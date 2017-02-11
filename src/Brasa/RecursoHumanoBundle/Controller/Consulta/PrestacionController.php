<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class PrestacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/consulta/prestacion", name="brs_rhu_consulta_prestacion")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }

        }
        $arPrestaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Prestacion:lista.html.twig', array(
            'arPrestaciones' => $arPrestaciones,
            'form' => $form->createView()
            ));
    }     
    
    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPrestacion')->listaDql(                    
                    $session->get('filtroAnio'),
                    $session->get('filtroMes'),
                    $session->get('filtroIdentificacion')
                    );
    }  

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            }  else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }         
        $form = $this->createFormBuilder()                                    
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))                
            ->add('anio', TextType::class, array('label'  => 'Numero','data' => $session->get('filtroAnio')))                                                   
            ->add('mes', TextType::class, array('label'  => 'Numero','data' => $session->get('filtroMes')))                                                               
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                            
            ->getForm();        
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->get('session');                               
        $session->set('filtroAnio', $form->get('anio')->getData());
        $session->set('filtroMes', $form->get('mes')->getData());
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager(); 
        $session = $this->get('session');
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        for($col = 'A'; $col !== 'X'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                }
        for($col = 'H'; $col !== 'J'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }                 
        for($col = 'L'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'ANIO')
                    ->setCellValue('C1', 'MES')
                    ->setCellValue('D1', 'IDENT')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'DIAS')                    
                    ->setCellValue('H1', 'SALARIO')
                    ->setCellValue('I1', 'VACACION')
                    ->setCellValue('J1', 'DESDE')
                    ->setCellValue('K1', 'DIAS')
                    ->setCellValue('L1', 'SALARIO')
                    ->setCellValue('M1', 'CESANTIA')
                    ->setCellValue('N1', 'INTERESES');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPrestaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuPrestacion();
        $arPrestaciones = $query->getResult();
        
        foreach ($arPrestaciones as $arPrestacion) {              
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPrestacion->getCodigoPrestacionPk())
                    ->setCellValue('B' . $i, $arPrestacion->getAnio())
                    ->setCellValue('C' . $i, $arPrestacion->getMes())
                    ->setCellValue('D' . $i, $arPrestacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPrestacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPrestacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'))
                    ->setCellValue('G' . $i, $arPrestacion->getDiasVacaciones())
                    ->setCellValue('H' . $i, $arPrestacion->getVrSalarioVacaciones())
                    ->setCellValue('I' . $i, $arPrestacion->getVrVacaciones())
                    ->setCellValue('J' . $i, $arPrestacion->getFechaUltimoPagoCesantias()->format('Y-m-d'))
                    ->setCellValue('K' . $i, $arPrestacion->getDiasCesantias())
                    ->setCellValue('L' . $i, $arPrestacion->getVrSalarioPromedioCesantias())
                    ->setCellValue('M' . $i, $arPrestacion->getVrCesantias())
                    ->setCellValue('N' . $i, $arPrestacion->getVrInteresesCesantias());            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('prestaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Prestaciones.xlsx"');
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
