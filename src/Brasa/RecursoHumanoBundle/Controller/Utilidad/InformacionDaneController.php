<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class InformacionDaneController extends Controller
{
    /**
     * @Route("/rhu/utilidades/informaciondane/informe", name="brs_rhu_utilidades_informacion_dane_informe")
     */
    public function InformeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();  
        $objMensaje = $this->get('mensajes_brasa'); 
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 79)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $form = $this->createFormBuilder()
            ->add('BtnGenerarArchivo', SubmitType::class, array('label'  => 'Generar archivo',))            
            ->add('formatos', ChoiceType::class, array('choices' => array('Muestra trimestral de servicios MTS' => 'mts')))
            ->add('fechaDesde',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $controles = $request->request->get('form');
            if($form->get('BtnGenerarArchivo')->isClicked()) {
                ob_clean();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $objPHPExcel = new \PHPExcel();
                // Set document properties
                $intContratoObraoLabor = 0;
                $intContratoFijo = 0;
                $intContratoIndefinido = 0;
                $intContratoAprendiz = 0;
                $intContratoPracticante = 0; 
                $proceso = false;                
                if ($controles['fechaDesde'] != null && $controles['fechaHasta'] != null){                    
                    //empleados por trimestre o por año
                    $proceso = true;
                    $empleadosContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->contratosPeriodo($controles['fechaDesde'],$controles['fechaHasta']);                    
                    //devengado y primas pagadas
                    $salariosEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosDane($controles['fechaDesde'],$controles['fechaHasta'],"");
                    //parafiscales
                    $parafiscalesSsoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->devuelveCostosParafiscales($controles['fechaDesde'],$controles['fechaHasta'],"");
                    //prestaciones liquidadas
                    $prestacionesLiquidadasEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelveCostosPrestacionesDane($controles['fechaDesde'],$controles['fechaHasta'],"");
                } else {                    
                    $proceso = false; 
                    $objMensaje->Mensaje('error', "Las fechas del periodo deben estar diligencidas");
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_informacion_dane_informe'));
                }
                if ($proceso == true){
                    
                    foreach ($empleadosContratos as $empleadosContrato) {                        
                        if ($empleadosContrato->getCodigoContratoTipoFk() == 1){
                            $intContratoObraoLabor++;                                
                        }
                        if ($empleadosContrato->getCodigoContratoTipoFk() == 2){
                            $intContratoFijo++;                                
                        }
                        if ($empleadosContrato->getCodigoContratoTipoFk() == 3){
                            $intContratoIndefinido++;                                
                        }
                        if ($empleadosContrato->getCodigoContratoTipoFk() == 4){
                            $intContratoAprendiz++;                                
                        }
                        if ($empleadosContrato->getCodigoContratoTipoFk() == 5){
                            $intContratoPracticante++;                                
                        }
                    }
                    //COSTOS Y GASTOS CAUSADOS
                    //SALARIOS Y DEVENGADO
                    $salarioEmpleadoObraLabor = 0;
                    $salarioEmpleadoFijo = 0;
                    $salarioEmpleadoIndefinido = 0;
                    $salarioEmpleadoAprendiz = 0;
                    $salarioEmpleadoPracticante = 0;                                     
                    foreach ($salariosEmpleados as $salariosEmpleado) {
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 1){
                            $salarioEmpleadoObraLabor = $salarioEmpleadoObraLabor +  $salariosEmpleado->getVrDevengado();                                                        
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 2){
                            $salarioEmpleadoFijo = $salarioEmpleadoFijo +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 3){
                            $salarioEmpleadoIndefinido = $salarioEmpleadoIndefinido +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 4){
                            $salarioEmpleadoAprendiz = $salarioEmpleadoAprendiz +  $salariosEmpleado->getVrDevengado();
                        }
                        if ($salariosEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 5){
                            $salarioEmpleadoPracticante = $salarioEmpleadoPracticante +  $salariosEmpleado->getVrDevengado();
                        }
                    }
                    //PARAFISCALES Y SEGURIDAD SOCIAL
                    $parafiscalesSsoEmpleadoObraLabor = 0;
                    $parafiscalesSsoEmpleadoFijo = 0;
                    $parafiscalesSsoEmpleadoIndefinido = 0;
                    $parafiscalesSsoEmpleadoAprendiz = 0;
                    $parafiscalesSsoEmpleadoPracticante = 0;
                    foreach ($parafiscalesSsoEmpleados as $parafiscalesSsoEmpleado) {
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 1){
                            $parafiscalesSsoEmpleadoObraLabor = $parafiscalesSsoEmpleadoObraLabor + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 2){
                            $parafiscalesSsoEmpleadoFijo = $parafiscalesSsoEmpleadoFijo + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 3){
                            $parafiscalesSsoEmpleadoIndefinido = $parafiscalesSsoEmpleadoIndefinido + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 4){
                            $parafiscalesSsoEmpleadoAprendiz = $parafiscalesSsoEmpleadoAprendiz + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                        if ($parafiscalesSsoEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 5){
                            $parafiscalesSsoEmpleadoPracticante = $parafiscalesSsoEmpleadoPracticante + $parafiscalesSsoEmpleado->getCotizacionCaja() + $parafiscalesSsoEmpleado->getCotizacionSena() + $parafiscalesSsoEmpleado->getCotizacionIcbf() + $parafiscalesSsoEmpleado->getCotizacionPension() + $parafiscalesSsoEmpleado->getCotizacionSalud();
                        }
                    }
                    $prestacionesLiquidadasEmpleadosObraLabor = 0;
                    $prestacionesLiquidadasEmpleadosFijo = 0;
                    $prestacionesLiquidadasEmpleadosIndefinido = 0;
                    foreach ($prestacionesLiquidadasEmpleados as $prestacionesLiquidadasEmpleado) {
                        if ($prestacionesLiquidadasEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 1){
                            $prestacionesLiquidadasEmpleadosObraLabor = $prestacionesLiquidadasEmpleadosObraLabor + $prestacionesLiquidadasEmpleado->getVrCesantias() + $prestacionesLiquidadasEmpleado->getVrCesantiasAnterior() + $prestacionesLiquidadasEmpleado->getVrInteresesCesantias() + $prestacionesLiquidadasEmpleado->getVrPrima() + $prestacionesLiquidadasEmpleado->getVrVacaciones();
                        }
                        if ($prestacionesLiquidadasEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 2){
                            $prestacionesLiquidadasEmpleadosFijo = $prestacionesLiquidadasEmpleadosFijo + $prestacionesLiquidadasEmpleado->getVrCesantias() + $prestacionesLiquidadasEmpleado->getVrCesantiasAnterior() + $prestacionesLiquidadasEmpleado->getVrInteresesCesantias() + $prestacionesLiquidadasEmpleado->getVrPrima() + $prestacionesLiquidadasEmpleado->getVrVacaciones();
                        }
                        if ($prestacionesLiquidadasEmpleado->getContratoRel()->getCodigoContratoTipoFk() == 3){
                            $prestacionesLiquidadasEmpleadosIndefinido = $prestacionesLiquidadasEmpleadosIndefinido + $prestacionesLiquidadasEmpleado->getVrCesantias() + $prestacionesLiquidadasEmpleado->getVrCesantiasAnterior() + $prestacionesLiquidadasEmpleado->getVrInteresesCesantias() + $prestacionesLiquidadasEmpleado->getVrPrima() + $prestacionesLiquidadasEmpleado->getVrVacaciones();
                        }
                    }
                    $objPHPExcel->getProperties()->setCreator("EMPRESA")
                    ->setLastModifiedBy("EMPRESA")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
                    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                    $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('8')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle(8)->getAlignment()->setHorizontal('right');
                    for($col = 'A'; $col !== 'C'; $col++) {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                        
                    }     
                     
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'TIPO CONTRATACION')
                    ->setCellValue('A2', 'Propietarios, socios y familiares (sin remuneracion fija)')
                    ->setCellValue('A3', 'Personal permanente (contrato a termino indefinido)')
                    ->setCellValue('A4', 'Temporal Contratado directamente por la Empresa')
                    ->setCellValue('A5', 'Temporal en Mision en otras empresas (solo para empresas especializadas en suministro de personal')
                    ->setCellValue('A6', 'Temporal suministrado por otras empresas')
                    ->setCellValue('A7', 'Personal Aprendiz o estudiantes por convenio ( Universitario, tecnologo o tecnico)')
                    ->setCellValue('A8', 'TOTAL')
                    ->setCellValue('B1', 'Número de personas promedio trimestre TOTAL NACIONAL')
                    ->setCellValue('B2', 0)
                    ->setCellValue('B3', $intContratoIndefinido)
                    ->setCellValue('B4', $intContratoFijo)
                    ->setCellValue('B5', $intContratoObraoLabor)
                    ->setCellValue('B6', 0)
                    ->setCellValue('B7', $intContratoAprendiz + $intContratoPracticante)
                    ->setCellValue('B8', $intContratoIndefinido + $intContratoObraoLabor + $intContratoAprendiz + $intContratoPracticante)
                    /*->setCellValue('L1', 'Número de personas promedio trimestre TOTAL BOGOTA')
                    ->setCellValue('L2', $intContratoFijoBogota)
                    ->setCellValue('L3', $intContratoIndefinidoBogota)
                    ->setCellValue('L4', 0)
                    ->setCellValue('L5', $intContratoObraoLaborBogota)
                    ->setCellValue('L6', 0)
                    ->setCellValue('L7', $intContratoAprendizBogota + $intContratoPracticanteBogota)
                    ->setCellValue('L8', $intContratoIndefinidoBogota + $intContratoObraoLaborBogota + $intContratoFijoBogota + $intContratoAprendizBogota + $intContratoPracticanteBogota)*/;                    
                    
                    $objPHPExcel->getActiveSheet()->setTitle('1. PERSONAL OCUPADO');

                    
                    $objPHPExcel->createSheet(1)->setTitle('2. COSTOS Y GASTOS CAUSADOS')
                            ->setCellValue('A1', 'CONCEPTO')
                    ->setCellValue('A2', 'Sueldo y salarios del personal permanente (en dinero y en especie, horas extras, dominicales, comisiones por ventas, viaticos permanentes)')
                    ->setCellValue('A3', 'Prestaciones sociales, cotizaciones y aportes personal permanente')
                    ->setCellValue('A4', 'Salarios y prestaciones, cotizaciones  y Aportes del personal temporal contratado directamente por la empresa')
                    ->setCellValue('A5', 'Sueldos y prestaciones del personal temporal en mision (solo para empresas especializadas en suministro de personal)')
                    ->setCellValue('A6', 'Valor causado por el personal contratado a traves de empresas de servicios temporales')
                    ->setCellValue('A7', 'Gastos causados por el personal aprendiz o estudiante por convenio ( universitario, tecnologo o tecnico)')
                    ->setCellValue('A8', 'TOTAL')
                    ->setCellValue('B1', 'TOTAL NACIONAL')
                    ->setCellValue('B2', $salarioEmpleadoIndefinido)
                    ->setCellValue('B3', $prestacionesLiquidadasEmpleadosIndefinido + $parafiscalesSsoEmpleadoIndefinido)
                    ->setCellValue('B4', $salarioEmpleadoFijo + $parafiscalesSsoEmpleadoFijo + $prestacionesLiquidadasEmpleadosFijo)
                    ->setCellValue('B5', $salarioEmpleadoObraLabor + $parafiscalesSsoEmpleadoObraLabor + $prestacionesLiquidadasEmpleadosObraLabor)
                    ->setCellValue('B6', 0)
                    ->setCellValue('B7', $salarioEmpleadoAprendiz + $salarioEmpleadoPracticante + $parafiscalesSsoEmpleadoAprendiz + $parafiscalesSsoEmpleadoPracticante)
                    ->setCellValue('B8', $salarioEmpleadoIndefinido + $prestacionesLiquidadasEmpleadosIndefinido + $parafiscalesSsoEmpleadoIndefinido + $salarioEmpleadoFijo + $parafiscalesSsoEmpleadoFijo + $prestacionesLiquidadasEmpleadosFijo + $salarioEmpleadoObraLabor + $parafiscalesSsoEmpleadoObraLabor + $prestacionesLiquidadasEmpleadosObraLabor + $salarioEmpleadoAprendiz + $salarioEmpleadoPracticante + $parafiscalesSsoEmpleadoAprendiz + $parafiscalesSsoEmpleadoPracticante)
                    /*->setCellValue('L1', 'TOTAL BOGOTA')
                    ->setCellValue('L2', 0)
                    ->setCellValue('L3', 0)
                    ->setCellValue('L4', 0)
                    ->setCellValue('L5', 0)
                    ->setCellValue('L6', 0)
                    ->setCellValue('L7', 0)
                    ->setCellValue('L8', 0)*/;

                    $objPHPExcel->setActiveSheetIndex(1); 
                    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                    $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('8')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle(8)->getAlignment()->setHorizontal('right');
                    $objPHPExcel->getActiveSheet()->getStyle(1)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(2)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(3)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(4)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(5)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(6)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(7)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle(8)->getNumberFormat()->setFormatCode('#,##0');
                    for($col = 'A'; $col !== 'C'; $col++) {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                        
                    }             
                    for($col = 'A'; $col !== 'C'; $col++) {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                        //$objPHPExcel->getActiveSheet()->getStyle(2)->getNumberFormat()->setFormatCode('#,##0');
                        $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
                    }
                    
                                                                   
                    $objPHPExcel->setActiveSheetIndex(0);
                    // Redirect output to a client’s web browser (Excel2007)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="InformeDane.xlsx"');
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


        }

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/InformacionDane:Informe.html.twig', array(
                'form' => $form->createView()
                ));
    }

}
