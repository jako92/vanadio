<?php

namespace Brasa\ContabilidadBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnalizarInconsistenciasController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/ctb/utilidades/analizar/inconsistencias", name="brs_ctb_utilidades_analizar_inconsistencias")
     */
    public function listarAction(Request $request) {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formulario();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnGenerar')->isClicked()) {
                $arRegistros = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->generarInconsistencias($session->get('filtroCtbRegistroFechaDesde'), $session->get('filtroCtbRegistroFechaHasta'));
                foreach ($arRegistros as $arRegistro) {
                    if ($arRegistro['debito'] != $arRegistro['credito']) {
                        $descripcionInconsistencia = "El registro contable con numero " . $arRegistro['numero'] . " y comprobante" . $arRegistro['codigoComprobanteFk'] . " contiene inconsistencias en los valores";
                        $arInconsistencia = new \Brasa\ContabilidadBundle\Entity\CtbInconsistencia();
                        $arInconsistencia->setDescripcion($descripcionInconsistencia);
                        $em->persist($arInconsistencia);
                    }
                    $em->flush();
                }
                $this->filtrar($form);
                $this->listar();
            }
        }
        $arInconsistencias = $em->getRepository('BrasaContabilidadBundle:CtbInconsistencia')->findAll();
        return $this->render('BrasaContabilidadBundle:Utilidad/AnalizarInconsistencias:lista.html.twig', array(
                    'arInconsistencias' => $arInconsistencias,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbInconsistencia')->listaDQL();
    }

    private function filtrar($form) {
        $session = $this->get('session');
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroCtbRegistroFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroCtbRegistroFechaHasta', $dateFechaHasta->format('Y/m/d'));
    }

    private function formulario() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroCtbRegistroFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroCtbRegistroFechaDesde');
        }
        if ($session->get('filtroCtbRegistroFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroCtbRegistroFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);

        $form = $this->createFormBuilder()
                ->add('fechaDesde', DateType::class, array('data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('data' => $dateFechaHasta))
                ->add('BtnGenerar', SubmitType::class, array('label' => 'Generar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
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
                ->setCellValue('B1', 'DESDE')
                ->setCellValue('C1', 'HASTA')
                ->setCellValue('D1', 'IDENTIFICACION')
                ->setCellValue('E1', 'NOMBRE')
                ->setCellValue('F1', 'CENTRO COSTOS')
                ->setCellValue('G1', 'BASICO')
                ->setCellValue('H1', 'TIEMPO EXTRA')
                ->setCellValue('I1', 'VALORES ADICIONALES')
                ->setCellValue('J1', 'AUX. TRANSPORTE')
                ->setCellValue('K1', 'ARP')
                ->setCellValue('L1', 'EPS')
                ->setCellValue('M1', 'PENSION')
                ->setCellValue('N1', 'CAJA')
                ->setCellValue('O1', 'ICBF')
                ->setCellValue('P1', 'SENA')
                ->setCellValue('Q1', 'CESANTIAS')
                ->setCellValue('R1', 'VACACIONES')
                ->setCellValue('S1', 'ADMON')
                ->setCellValue('T1', 'COSTO')
                ->setCellValue('U1', 'TOTAL')
                ->setCellValue('W1', 'NETO')
                ->setCellValue('X1', 'IBC')
                ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                ->setCellValue('Z1', 'DIAS PERIODO')
                ->setCellValue('AA1', 'SALARIO PERIODO')
                ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
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
