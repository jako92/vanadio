<?php

namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurPedidoTipoType;

class PedidoTipoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/tur/base/pedido/tipo", name="brs_tur_base_general_pedido_tipo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($form->get('BtnEliminar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_base_general_pedido_tipo'));
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    //$this->filtrar($form);
                    //$this->generarExcel();
                }
            }
        }
        $arPedidoTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/PedidoTipo:lista.html.twig', array(
                    'arPedidoTipos' => $arPedidoTipos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/pedido/tipo/nuevo/{codigoPedidoTipo}", name="brs_tur_base_general_pedido_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoPedidoTipo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPedidoTipo = new \Brasa\TurnoBundle\Entity\TurPedidoTipo();
        if ($codigoPedidoTipo != '' && $codigoPedidoTipo != '0') {
            $arPedidoTipo = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find($codigoPedidoTipo);
        }
        $form = $this->createForm(TurPedidoTipoType::class, $arPedidoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arPedidoTipo = $form->getData();
                $em->persist($arPedidoTipo);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_base_pedido_conceto_nuevo', array('codigoPedidoTipo' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_tur_base_general_pedido_tipo'));
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/PedidoTipo:nuevo.html.twig', array(
                    'arPedidoTipo' => $arPedidoTipo,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->listaDQL(
                $session->get('filtroPedidoTipoNombre')
        );
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroPedidoTipoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }

    private function formularioFiltro() {
        $session = new session;
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $session->get('filtroPedidoTipoNombre')))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arPedidoTipos = new \Brasa\TurnoBundle\Entity\TurPedidoTipo();
        $arPedidoTipos = $query->getResult();

        foreach ($arPedidoTipos as $arPedidoTipo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoTipo->getCodigoPedidoTipoPk())
                    ->setCellValue('B' . $i, $arPedidoTipo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidoTipo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidoTipos.xlsx"');
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
