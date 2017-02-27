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

class PagoBancoDetalleController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/consulta/pago/banco/detalle", name="brs_rhu_consultas_pago_banco_detalle")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 21)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
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
        }
        $arPagoBancoDetalle = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagoBanco:PagoBancoDetalle.html.twig', array(
                    'arPagoBancoDetalle' => $arPagoBancoDetalle,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->listaPagoBancoDetalleDql(
                $session->get('filtroIdentificacion'), $strFechaDesde = $session->get('filtroDesde'), $strFechaHasta = $session->get('filtroHasta')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            } else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }
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
        $form = $this->createFormBuilder()
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->get('session');
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
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
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'PAG')
                ->setCellValue('D1', 'VAC')
                ->setCellValue('E1', 'LIQ')
                ->setCellValue('F1', 'S.S')
                ->setCellValue('G1', 'F. APLICACION')
                ->setCellValue('H1', 'IDENTIFICACION')
                ->setCellValue('I1', 'EMPLEADO')
                ->setCellValue('J1', 'BANCO')
                ->setCellValue('K1', 'CUENTA')
                ->setCellValue('L1', 'VR.PAGO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagosBancoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
        $arPagosBancoDetalle = $query->getResult();

        foreach ($arPagosBancoDetalle as $arPagoBancoDetalle) {
            $banco = "";
            if ($arPagoBancoDetalle->getCodigoBancoFk() != NULL) {
                $banco = $arPagoBancoDetalle->getBancoRel()->getNombre();
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoBancoDetalle->getCodigoPagoBancoDetallePk())
                    ->setCellValue('B' . $i, $arPagoBancoDetalle->getPagoBancoRel()->getCodigoPagoBancoPk())
                    ->setCellValue('C' . $i, $arPagoBancoDetalle->getCodigoPagoFk())
                    ->setCellValue('D' . $i, $arPagoBancoDetalle->getCodigoVacacionFk())
                    ->setCellValue('E' . $i, $arPagoBancoDetalle->getCodigoLiquidacionFk())
                    ->setCellValue('F' . $i, $arPagoBancoDetalle->getCodigoPeriodoDetalleFk())
                    ->setCellValue('G' . $i, $arPagoBancoDetalle->getPagoBancoRel()->getFechaAplicacion())
                    ->setCellValue('H' . $i, $arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('I' . $i, $arPagoBancoDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('J' . $i, $banco)
                    ->setCellValue('K' . $i, $arPagoBancoDetalle->getCuenta())
                    ->setCellValue('L' . $i, $arPagoBancoDetalle->getVrPago());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('$arPagosBancoDetalle');
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
