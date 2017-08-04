<?php

namespace Brasa\TurnoBundle\Controller\Consulta\Pedido;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IngresoPendienteController extends Controller {

    var $strListaDql = "";
    var $strListaDetalleDql = "";

    /**
     * @Route("/tur/consulta/pedidos/ingreso/pendiente", name="brs_tur_consulta_pedidos_ingreso_pendiente")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 49)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $this->filtrarFecha = TRUE;
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
        $arIngresoPendiente = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Pedido:ingresoPendiente.html.twig', array(
                    'arIngresoPendiente' => $arIngresoPendiente,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql = $em->getRepository('BrasaTurnoBundle:TurIngresoPendiente')->listaDql(
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroTurAnio'), 
                $session->get('filtroTurMes')
        );
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroCodigoCliente', $form->get('TxtNit')->getData());        
        $session->set('filtroTurMes', $form->get('TxtMes')->getData());
        $session->set('filtroTurAnio', $form->get('TxtAnio')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroCodigoCliente')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($session->get('filtroCodigoCliente'));
            if ($arCliente) {
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
            }
        }
        $form = $this->createFormBuilder()
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroCodigoCliente')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('TxtAnio', TextType::class, array('data' => $session->get('filtroTurAnio')))
                ->add('TxtMes', TextType::class, array('data' => $session->get('filtroTurMes')))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for ($col = 'A'; $col !== 'J'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        for ($col = 'H'; $col !== 'I'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'AÑO')
                ->setCellValue('B1', 'MES')
                ->setCellValue('C1', 'PED')
                ->setCellValue('D1', 'PED_DET')
                ->setCellValue('E1', 'NIT')
                ->setCellValue('F1', 'CLIENTE')
                ->setCellValue('G1', 'C.COSTO')
                ->setCellValue('H1', 'SERVICIO')
                ->setCellValue('I1', 'PUESTO')
                ->setCellValue('J1', 'SUBTOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arIngresosPendientes = new \Brasa\TurnoBundle\Entity\TurIngresoPendiente();
        $arIngresosPendientes = $query->getResult();
        foreach ($arIngresosPendientes as $arIngresoPendiente) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIngresoPendiente->getAnio())
                    ->setCellValue('B' . $i, $arIngresoPendiente->getMes())
                    ->setCellValue('C' . $i, $arIngresoPendiente->getPedidoDetalleRel()->getPedidoRel()->getNumero())
                    ->setCellValue('D' . $i, $arIngresoPendiente->getCodigoPedidoDetalleFk())
                    ->setCellValue('E' . $i, $arIngresoPendiente->getClienteRel()->getNit().'-'.$arIngresoPendiente->getClienteRel()->getDigitoVerificacion())
                    ->setCellValue('F' . $i, $arIngresoPendiente->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arIngresoPendiente->getPedidoDetalleRel()->getPuestoRel()->getCodigoCentroCostoContabilidadFk())
                    ->setCellValue('H' . $i, $arIngresoPendiente->getPedidoDetalleRel()->getConceptoServicioRel()->getNombre())
                    ->setCellValue('I' . $i, $arIngresoPendiente->getPedidoDetalleRel()->getPuestoRel()->getNombre())
                    ->setCellValue('J' . $i, $arIngresoPendiente->getVrSubtotal());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IngresoPendiente');       

        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="IngresoPendiente.xlsx"');
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
