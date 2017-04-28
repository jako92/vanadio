<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLiquidacionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LiquidacionController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/consulta/liquidacion/", name="brs_rhu_consulta_liquidacion")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 9, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioLista();
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }
        }
        
        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 100);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Liquidacion:lista.html.twig', array('arLiquidaciones' => $arLiquidaciones, 'form' => $form->createView()));
    }
   
    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->listaDql(
               $session->get('filtroIdentificacion'),
               $session->get('filtroGenerado'),
               $session->get('filtroCodigoCentroCosto'),
               $session->get('filtroPagado'));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
        
        $arrayPropiedadesCentroCosto = array(
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
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedadesCentroCosto)    
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoGenerado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'),'data' => $session->get('filtroGenerado')))
            ->add('estadoPagado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'),'data' => $session->get('filtroPagado')))            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }    
    
    private function filtrar ($form) {
        $session = new session; 
        $codigoCentroCosto = '';
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }         
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroGenerado', $form->get('estadoGenerado')->getData());
        $session->set('filtroPagado', $form->get('estadoPagado')->getData());
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
        for($col = 'A'; $col !== 'AD'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for($col = 'J'; $col !== 'S'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        for($col = 'Z'; $col !== 'AD'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NUMERO')
                    ->setCellValue('B1', 'CODIGO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CENTRO COSTO')
                    ->setCellValue('F1', 'CONTRATO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'AUX.TTE')
                    ->setCellValue('J1', 'SALARIO')               
                    ->setCellValue('K1', 'CESANTIAS')
                    ->setCellValue('L1', 'INTERESES')
                    ->setCellValue('M1', 'SALARIO')
                    ->setCellValue('N1', 'PRIMA')
                    ->setCellValue('O1', 'DED.PRIMA')
                    ->setCellValue('P1', 'SALARIO')
                    ->setCellValue('Q1', 'VACACIONES')
                    ->setCellValue('R1', 'INDEMNIZACION')
                    ->setCellValue('S1', 'D.CES')
                    ->setCellValue('T1', 'D.VAC')
                    ->setCellValue('U1', 'D.PRI')
                    ->setCellValue('V1', 'F.ULT.PAGO')
                    ->setCellValue('W1', 'F.ULT.PAGO.PRI')
                    ->setCellValue('X1', 'F.ULT.PAGO.VAC')
                    ->setCellValue('Y1', 'F.ULT.PAGO.CES')
                    ->setCellValue('Z1', 'DEDUCCIONES')
                    ->setCellValue('AA1', 'BONIFICACIONES')                
                    ->setCellValue('AB1', 'TOTAL')
                    ->setCellValue('AC1', '%IBP');    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arLiquidaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidaciones = $query->getResult();
        foreach ($arLiquidaciones as $arLiquidacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLiquidacion->getCodigoLiquidacionPk())
                    ->setCellValue('B' . $i, $arLiquidacion->getCodigoEmpleadoFk())
                    ->setCellValue('C' . $i, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arLiquidacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arLiquidacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arLiquidacion->getCodigoContratoFk())
                    ->setCellValue('G' . $i, $arLiquidacion->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arLiquidacion->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arLiquidacion->getVrAuxilioTransporte())
                    ->setCellValue('J' . $i, $arLiquidacion->getVrSalarioPromedioCesantias())
                    ->setCellValue('K' . $i, $arLiquidacion->getVrCesantias())
                    ->setCellValue('L' . $i, $arLiquidacion->getVrInteresesCesantias())
                    ->setCellValue('M' . $i, $arLiquidacion->getVrSalarioPromedioPrimas())
                    ->setCellValue('N' . $i, $arLiquidacion->getVrPrima())
                    ->setCellValue('O' . $i, $arLiquidacion->getVrDeduccionPrima())
                    ->setCellValue('P' . $i, $arLiquidacion->getVrSalarioVacaciones())
                    ->setCellValue('Q' . $i, $arLiquidacion->getVrVacaciones())
                    ->setCellValue('R' . $i, $arLiquidacion->getVrIndemnizacion())
                    ->setCellValue('S' . $i, $arLiquidacion->getDiasCesantias())
                    ->setCellValue('T' . $i, $arLiquidacion->getDiasVacaciones())
                    ->setCellValue('U' . $i, $arLiquidacion->getDiasPrimas())                                        
                    ->setCellValue('Z' . $i, $arLiquidacion->getVrDeducciones())
                    ->setCellValue('AA' . $i, $arLiquidacion->getVrBonificaciones())
                    ->setCellValue('AB' . $i, $arLiquidacion->getVrTotal())
                    ->setCellValue('AC' . $i, $arLiquidacion->getPorcentajeIbp());
            if($arLiquidacion->getFechaUltimoPago()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $i, $arLiquidacion->getFechaUltimoPago()->format('Y-m-d'));
            }
            if($arLiquidacion->getFechaUltimoPagoPrimas()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $i, $arLiquidacion->getFechaUltimoPagoPrimas()->format('Y-m-d'));
            }
            if($arLiquidacion->getFechaUltimoPagoVacaciones()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X' . $i, $arLiquidacion->getFechaUltimoPagoVacaciones()->format('Y-m-d'));
            }
            if($arLiquidacion->getFechaUltimoPagoCesantias()) {
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y' . $i, $arLiquidacion->getFechaUltimoPagoCesantias()->format('Y-m-d'));
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Liquidaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Liquidaciones.xlsx"');
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
