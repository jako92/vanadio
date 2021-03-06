<?php

namespace Brasa\TurnoBundle\Controller\Consulta\Pedido;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class pendientesFacturarController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/tur/consulta/pedidos/pendiente/facturar", name="brs_tur_consulta_pedidos_pendientes_facturar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 46)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                    $this->lista();
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                    $this->lista();
                    $this->generarExcel();
                }
            }
        }
        $arPedidosDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Pedido:pendienteFacturar.html.twig', array(
                    'arPedidosDetalles' => $arPedidosDetalles,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroPedidoFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }
        $this->strListaDql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaConsultaPendienteFacturarDql(
                $session->get('filtroPedidoNumero'), $session->get('filtroCodigoCliente'), $session->get('filtroPedidoEstadoAutorizado'), $session->get('filtroPedidoEstadoProgramado'), $session->get('filtroPedidoEstadoFacturado'), $session->get('filtroPedidoEstadoAnulado'), $strFechaDesde, $strFechaHasta);
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroPedidoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPedidoEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroPedidoEstadoProgramado', $form->get('estadoProgramado')->getData());
        $session->set('filtroPedidoEstadoFacturado', $form->get('estadoFacturado')->getData());
        $session->set('filtroPedidoEstadoAnulado', $form->get('estadoAnulado')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroPedidoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroPedidoFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroPedidoFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroPedidoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
        }
        if ($session->get('filtroPedidoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroPedidoNumero')))
                ->add('estadoAutorizado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroPedidoEstadoAutorizado')))
                ->add('estadoProgramado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'PROGRAMADO' => '1', 'SIN PROGRAMAR' => '0'), 'data' => $session->get('filtroPedidoEstadoProgramado')))
                ->add('estadoFacturado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'FACTURADO' => '1', 'SIN FACTURAR' => '0'), 'data' => $session->get('filtroPedidoEstadoFacturado')))
                ->add('estadoAnulado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0'), 'data' => $session->get('filtroPedidoEstadoAnulado')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroPedidoFiltrarFecha')))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for ($col = 'A'; $col !== 'AK'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'AI'; $col !== 'AK'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'TIPO PEDIDO')
                ->setCellValue('C1', 'NUMERO PEDIDO')
                ->setCellValue('D1', 'FECHA PEDIDO')
                ->setCellValue('E1', 'FECHA PROG')
                ->setCellValue('F1', 'NIT')
                ->setCellValue('G1', 'CLIENTE')
                ->setCellValue('H1', 'SECTOR')
                ->setCellValue('I1', 'AUT')
                ->setCellValue('J1', 'PRO')
                ->setCellValue('K1', 'FAC')
                ->setCellValue('L1', 'ANU')
                ->setCellValue('M1', 'C_COSTO')
                ->setCellValue('N1', 'PUESTO')
                ->setCellValue('O1', 'SERVICIO')
                ->setCellValue('P1', 'MODALIDAD')
                ->setCellValue('Q1', 'PERIODO')
                ->setCellValue('R1', 'PLANTILLA')
                ->setCellValue('S1', 'DESDE')
                ->setCellValue('T1', 'HASTA')
                ->setCellValue('U1', 'CANT')
                ->setCellValue('V1', 'LU')
                ->setCellValue('W1', 'MA')
                ->setCellValue('X1', 'MI')
                ->setCellValue('Y1', 'JU')
                ->setCellValue('Z1', 'VI')
                ->setCellValue('AA1', 'SA')
                ->setCellValue('AB1', 'DO')
                ->setCellValue('AC1', 'FE')
                ->setCellValue('AD1', 'H')
                ->setCellValue('AE1', 'HD')
                ->setCellValue('AF1', 'HN')
                ->setCellValue('AG1', 'HP')
                ->setCellValue('AH1', 'HDP')
                ->setCellValue('AI1', 'HNP')
                ->setCellValue('AJ1', 'DIAS')
                ->setCellValue('AK1', 'VALOR')
                ->setCellValue('AL1', 'VR.PEND');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalles = $query->getResult();

        foreach ($arPedidosDetalles as $arPedidoDetalle) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoDetalle->getCodigoPedidoDetallePk())
                    ->setCellValue('B' . $i, $arPedidoDetalle->getPedidoRel()->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedidoDetalle->getPedidoRel()->getNumero())
                    ->setCellValue('D' . $i, $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNit().'-'.$arPedidoDetalle->getPedidoRel()->getClienteRel()->getDigitoVerificacion())
                    ->setCellValue('G' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arPedidoDetalle->getPedidoRel()->getSectorRel()->getNombre())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoProgramado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoFacturado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoAnulado()))
                    ->setCellValue('O' . $i, $arPedidoDetalle->getConceptoServicioRel()->getNombre())
                    ->setCellValue('P' . $i, $arPedidoDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('Q' . $i, $arPedidoDetalle->getPeriodoRel()->getNombre())
                    ->setCellValue('S' . $i, $arPedidoDetalle->getDiaDesde())
                    ->setCellValue('T' . $i, $arPedidoDetalle->getDiaHasta())
                    ->setCellValue('U' . $i, $arPedidoDetalle->getCantidad())
                    ->setCellValue('V' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getLunes()))
                    ->setCellValue('W' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getMartes()))
                    ->setCellValue('X' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getMiercoles()))
                    ->setCellValue('Y' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getJueves()))
                    ->setCellValue('Z' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getViernes()))
                    ->setCellValue('AA' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getSabado()))
                    ->setCellValue('AB' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getDomingo()))
                    ->setCellValue('AC' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getFestivo()))
                    ->setCellValue('AD' . $i, $arPedidoDetalle->getHoras())
                    ->setCellValue('AE' . $i, $arPedidoDetalle->getHorasDiurnas())
                    ->setCellValue('AF' . $i, $arPedidoDetalle->getHorasNocturnas())
                    ->setCellValue('AG' . $i, $arPedidoDetalle->getHorasProgramadas())
                    ->setCellValue('AH' . $i, $arPedidoDetalle->getHorasDiurnasProgramadas())
                    ->setCellValue('AI' . $i, $arPedidoDetalle->getHorasNocturnasProgramadas())
                    ->setCellValue('AJ' . $i, $arPedidoDetalle->getDias())
                    ->setCellValue('AK' . $i, $arPedidoDetalle->getVrTotalDetalle())
                    ->setCellValue('AL' . $i, $arPedidoDetalle->getVrTotalDetallePendiente());
            if ($arPedidoDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('M' . $i, $arPedidoDetalle->getPuestoRel()->getCodigoCentroCostoContabilidadFk())
                        ->setCellValue('N' . $i, $arPedidoDetalle->getPuestoRel()->getNombre());
            }
            if ($arPedidoDetalle->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('R' . $i, $arPedidoDetalle->getPlantillaRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidosPendientesFacturar');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosPendientesFacturar.xlsx"');
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
