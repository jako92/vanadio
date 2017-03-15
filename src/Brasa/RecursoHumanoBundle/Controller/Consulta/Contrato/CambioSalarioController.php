<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta\Contrato;

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

class CambioSalarioController extends Controller {

    var $strDqlLista = "";
    var $intNumero = 0;

    /**
     * @Route("/rhu/consulta/contrato/cambio/salario", name="brs_rhu_consulta_contrato_cambio_salario")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrarLista($form, $request);
                    $this->listar();
                    $this->generarExcel();
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrarLista($form, $request);
                    $this->listar();
                }
                if ($form->get('BtnImprimirNotificacionMasiva')->isClicked()) {
                    $arrCodigosCambioSalario = array();
                    $query = $em->createQuery($this->strDqlLista);
                    $arCambiosSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
                    $arCambiosSalario = $query->getResult();                    
                    foreach ($arCambiosSalario as $arCambioSalario) {                        
                        $arrCodigosCambioSalario[] = $arCambioSalario->getCodigoCambioSalarioPk();
                    }
                    if(count($arrCodigosCambioSalario) > 0) {
                        $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                        $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\CambioSalarioNotificacion();
                        $objFormato->Generar($em, $arrCodigosCambioSalario, $arUsuario);                         
                    }
                }                
            }            
        }
        
        $arCambiosSalario = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Contrato:cambioSalario.html.twig', array(
                    'arCambiosSalario' => $arCambiosSalario,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->listaConsultaDql(
                $session->get('filtroDesde'), 
                $session->get('filtroHasta')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y-m-') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y-m-') . $intUltimoDia;
        if ($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if ($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);

        $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            }
        }

        $form = $this->createFormBuilder()
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))                
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnImprimirNotificacionMasiva', SubmitType::class, array('label' => 'Imprimir notificacion masiva',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->get('session');
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
        $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
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
        for ($col = 'A'; $col !== 'X'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'CODIGO')
                ->setCellValue('D1', 'IDENTIFICACIÓN')
                ->setCellValue('E1', 'EMPLEADO')
                ->setCellValue('F1', 'CODIGO')
                ->setCellValue('G1', 'CONCEPTO')
                ->setCellValue('H1', 'GRUPO PAGO')
                ->setCellValue('I1', 'DESDE')
                ->setCellValue('J1', 'HASTA')
                ->setCellValue('K1', 'DEVENGADO')
                ->setCellValue('L1', 'DEDUCCION')
                ->setCellValue('M1', 'HORAS')
                ->setCellValue('N1', 'DÍAS')
                ->setCellValue('O1', '%')
                ->setCellValue('P1', 'VR IBC')
                ->setCellValue('Q1', 'VR IBP')
                ->setCellValue('R1', 'N. CRED')
                ->setCellValue('S1', 'PEN')
                ->setCellValue('T1', 'SAL')
                ->setCellValue('U1', 'ZONA')
                ->setCellValue('V1', 'SUBZONA')
                ->setCellValue('W1', 'TIPO EMPLEADO')
                ->setCellValue('X1', 'C_COSTO')
                ->setCellValue('Y1', 'VR_EXTRA')
                ->setCellValue('Z1', 'C_INC')
                ->setCellValue('AA1', 'C_LIC')
                ->setCellValue('AB1', 'C_VAC')
                ->setCellValue('AC1', 'F_DESDE_NOV')
                ->setCellValue('AD1', 'F_HASTA_NOV');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagosDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagosDetalle = $query->getResult();

        foreach ($arPagosDetalle as $arPagoDetalle) {
            $devengado = 0;
            $deduccion = 0;
            if ($arPagoDetalle->getOperacion() == 1) {
                $devengado = $arPagoDetalle->getVrPago();
            }
            if ($arPagoDetalle->getOperacion() == -1) {
                $deduccion = $arPagoDetalle->getVrPago();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                    ->setCellValue('B' . $i, $arPagoDetalle->getPagoRel()->getNumero())
                    ->setCellValue('C' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoEmpleadoPk())
                    ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPagoDetalle->getCodigoPagoConceptoFk())
                    ->setCellValue('G' . $i, $arPagoDetalle->getPagoConceptoRel()->getNombre())
                    ->setCellValue('H' . $i, $arPagoDetalle->getPagoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('I' . $i, $arPagoDetalle->getPagoRel()->getFechaDesdePago()->format('Y-m-d'))
                    ->setCellValue('J' . $i, $arPagoDetalle->getPagoRel()->getFechaHastaPago()->format('Y-m-d'))
                    ->setCellValue('K' . $i, $devengado)
                    ->setCellValue('L' . $i, $deduccion)
                    ->setCellValue('M' . $i, $arPagoDetalle->getNumeroHoras())
                    ->setCellValue('N' . $i, $arPagoDetalle->getNumeroDias())
                    ->setCellValue('O' . $i, $arPagoDetalle->getPorcentajeAplicado())
                    ->setCellValue('P' . $i, round($arPagoDetalle->getVrIngresoBaseCotizacion()))
                    ->setCellValue('Q' . $i, round($arPagoDetalle->getVrIngresoBasePrestacion()))
                    ->setCellValue('R' . $i, $arPagoDetalle->getCodigoCreditoFk())
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getPension()))
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arPagoDetalle->getSalud()))
                    ->setCellValue('Y' . $i, $arPagoDetalle->getVrExtra())
                    ->setCellValue('Z' . $i, $arPagoDetalle->getCodigoIncapacidadFk())
                    ->setCellValue('AA' . $i, $arPagoDetalle->getCodigoLicenciaFk())
                    ->setCellValue('AB' . $i, $arPagoDetalle->getCodigoVacacionFk());
            if ($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoZonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getZonaRel()->getNombre());
            }
            if ($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoSubzonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getSubzonaRel()->getNombre());
            }
            if ($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoEmpleadoTipoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getEmpleadoTipoRel()->getNombre());
            }
            if ($arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCentroCostoContabilidadRel()->getNombre());
            }
            if ($arPagoDetalle->getFechaDesdeNovedad()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC' . $i, $arPagoDetalle->getFechaDesdeNovedad()->format('Y-m-d'));
            }
            if ($arPagoDetalle->getFechaHastaNovedad()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD' . $i, $arPagoDetalle->getFechaHastaNovedad()->format('Y-m-d'));
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('pagosDetalle');
        $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosDetalle.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }


}
