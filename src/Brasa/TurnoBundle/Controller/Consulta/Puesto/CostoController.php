<?php

namespace Brasa\TurnoBundle\Controller\Consulta\Puesto;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CostoController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/tur/consulta/puesto/costo", name="brs_tur_consulta_puesto_costo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $this->filtrarFecha = TRUE;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $ob = new \Ob\HighchartsBundle\Highcharts\Highchart();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnGenerar')->isClicked()) {
                    $anio = $form->get('TxtAnio')->getData();
                    $codigoPuesto = $form->get('TxtCodigoPuesto')->getData();
                    $arCostoServicio = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
                    $arCostoServicio = $em->getRepository('BrasaTurnoBundle:TurCostoServicio')->findBy(array('codigoPuestoFk' => 2773));
                    $dql = "SELECT cs.mes, SUM(cs.vrCostoRecurso) as vrCostoRecurso, SUM(cs.vrTotal) as vrTotal FROM BrasaTurnoBundle:TurCostoServicio cs "
                            . "WHERE cs.codigoPuestoFk = $codigoPuesto AND cs.anio = $anio GROUP BY cs.mes ORDER BY cs.mes ASC";
                    $query = $em->createQuery($dql);
                    $arResultados = $query->getResult();
                    $contador = 0;
                    $pocisionInicial = 0;
                    $arrCosto = array();
                    $arrPrecio = array();
                    foreach ($arResultados as $arResultado) {
                        if ($contador == 0) {
                            $pocisionInicial = $arResultado['mes'] - 1;
                        }
                        $arrCosto[] = round($arResultado['vrCostoRecurso']);
                        $arrPrecio[] = round($arResultado['vrTotal']);
                        $contador++;
                    }
                    $series = array(
                        array("name" => "Costo", "data" => $arrCosto, "pointStart" => $pocisionInicial),
                        array("name" => "Precio", "data" => $arrPrecio, "pointStart" => $pocisionInicial)
                    );
                    //array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8))

                    $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
                    $ob->title->text('Costos');
                    $ob->xAxis->title(array('text' => "Horizontal axis title"));
                    $ob->xAxis->categories(array('ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nom', 'dic'));
                    //$ob->xAxis->allowDecimals(false);
                    $ob->yAxis->title(array('text' => "Vertical axis title"));
                    $ob->series($series);
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Consultas/Puesto:costo.html.twig', array(
                    'chart' => $ob,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql = $em->getRepository('BrasaTurnoBundle:TurCosto')->listaDql(
                $session->get('filtroCodigoRecurso'), $session->get('filtroTurAnio'), $session->get('filtroTurMes'));
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
        $session->set('filtroTurMes', $form->get('TxtMes')->getData());
        $session->set('filtroTurAnio', $form->get('TxtAnio')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombrePuesto = "";
        if ($session->get('filtroCodigoPuesto')) {
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($session->get('filtroCodigoPuesto'));
            if ($arPuesto) {
                $strNombrePuesto = $arPuesto->getNombre();
            } else {
                $session->set('filtroCodigoPuesto', null);
            }
        }

        $form = $this->createFormBuilder()
                ->add('TxtCodigoPuesto', TextType::class, array('data' => $session->get('filtroCodigoPuesto')), 'required')
                ->add('TxtNombrePuesto', TextType::class, array('data' => $strNombrePuesto))
                ->add('TxtAnio', TextType::class, array('data' => $session->get('filtroTurAnio')), 'required')
                ->add('BtnGenerar', SubmitType::class, array('label' => 'Generar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        for ($col = 'F'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'AÑO')
                ->setCellValue('B1', 'MES')
                ->setCellValue('C1', 'CODIGO')
                ->setCellValue('D1', 'IDENTIFICACION')
                ->setCellValue('E1', 'NOMBRE')
                ->setCellValue('F1', 'C.COSTO')
                ->setCellValue('G1', 'C.NOMINA')
                ->setCellValue('H1', 'C.PRESTACIONES')
                ->setCellValue('I1', 'C.APORTES')
                ->setCellValue('J1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCostos = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
        $arCostos = $query->getResult();
        foreach ($arCostos as $arCosto) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCosto->getAnio())
                    ->setCellValue('B' . $i, $arCosto->getMes())
                    ->setCellValue('C' . $i, $arCosto->getCodigoEmpleadoFk())
                    ->setCellValue('D' . $i, $arCosto->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arCosto->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arCosto->getCodigoCentroCostoFk())
                    ->setCellValue('G' . $i, $arCosto->getVrNomina())
                    ->setCellValue('H' . $i, $arCosto->getVrPrestaciones())
                    ->setCellValue('I' . $i, $arCosto->getVrAportesSociales())
                    ->setCellValue('J' . $i, $arCosto->getVrCostoTotal());
            $i++;
        }
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->setTitle('Costo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostoRecurso.xlsx"');
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
