<?php

namespace Brasa\InventarioBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvOrdenCompraType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrdenCompraController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNumero = "";
    var $strTercero= "";

    /**
     * @Route("/inv/movimiento/orden/compra/lista", name="brs_inv_movimiento_orden_compra_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->lista();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnEliminarDocumento')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados > 0) {
                    foreach ($arrSeleccionados as $codigoOrdenCompra) {
                        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
                        $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
                        $em->remove($arOrdenCompra);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_lista'));
                    }
                }
            }
            if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form, $arrControles);
                    $this->lista();
                    $this->generarExcel();
                }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $arrControles);
            }
        }
        $arOrdenesCompra = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Movimiento/OrdenCompra:lista.html.twig', array(
                    'arOrdenesCompra' => $arOrdenesCompra,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/orden/compra/nuevo/{codigoOrdenCompra}", name="brs_inv_movimiento_orden_compra_nuevo")
     */
    public function nuevoAction(Request $request, $codigoOrdenCompra) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra->setFechaEntrega(new \DateTime('now'));
        if ($codigoOrdenCompra != 0) {
            $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
        }
        $form = $this->createForm(InvOrdenCompraType::class, $arOrdenCompra);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            $arTercero = new \Brasa\InventarioBundle\Entity\InvTercero();
            $arTercero = $em->getRepository('BrasaInventarioBundle:InvTercero')->findOneBy(array('nit' => $arrControles['txtNit']));
            if (count($arTercero) > 0) {
                $arOrdenCompra->setTerceroRel($arTercero);
                $arOrdenCompra->setFecha(new \DateTime('now'));
                $arOrdenCompra = $form->getData();
                $em->persist($arOrdenCompra);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_nuevo', array('codigoOrdenCompra' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_lista'));
                }
            } else {
                $objMensaje->Mensaje("error", "El tercero no existe");
            }
        }
        return $this->render('BrasaInventarioBundle:Movimiento/OrdenCompra:nuevo.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/orden/compra/detalle/{codigoOrdenCompra}", name="brs_inv_movimiento_orden_compra_detalle")
     */
    public function detalleAction(Request $request, $codigoOrdenCompra) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
        $form = $this->formularioDetalle($arOrdenCompra);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnEliminarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarDetalle');
                if ($arrSeleccionados > 0) {
                    foreach ($arrSeleccionados as $codigoOrdenCompraDetalle) {
                        $arOrdenCompraDetalle = new \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle();
                        $arOrdenCompraDetalle = $em->getRepository('BrasaInventarioBundle:InvOrdenCompraDetalle')->find($codigoOrdenCompraDetalle);
                        $em->remove($arOrdenCompraDetalle);
                        $em->flush();
                    }
                }
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoOrdenCompra);
                return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_detalle', array('codigoOrdenCompra' => $codigoOrdenCompra)));
            }
            if ($form->get('BtnAutorizar')->isClicked()) {
                    $respuesta = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->autorizar($codigoOrdenCompra);
                    if ($respuesta != "") {
                        $objMensaje->Mensaje("error", $respuesta);
                    }
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_detalle', array('codigoOrdenCompra' => $codigoOrdenCompra)));
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                    $respuesta = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->desAutorizar($codigoOrdenCompra);
                    if ($respuesta != "") {
                        $objMensaje->Mensaje("error", $respuesta);
                    }
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_detalle', array('codigoOrdenCompra' => $codigoOrdenCompra)));
            }
            if ($form->get('BtnImprimir')->isClicked()) {
                    $respuesta = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->imprimir($codigoOrdenCompra);
                    if ($respuesta != "") {
                        $objMensaje->Mensaje("error", $respuesta);
                    }
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_detalle', array('codigoOrdenCompra' => $codigoOrdenCompra)));
            }
        }

        $arOrdenesCompraDetalles = new \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle();
        $arOrdenesCompraDetalles = $em->getRepository('BrasaInventarioBundle:InvOrdenCompraDetalle')->FindBy(array('codigoOrdenCompraFk' => $codigoOrdenCompra));
        return $this->render('BrasaInventarioBundle:Movimiento/OrdenCompra:detalle.html.twig', array(
                    'arOrdenCompra' => $arOrdenCompra,
                    'arOrdenCompraDetalle' => $arOrdenesCompraDetalles,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/inv/movimiento/orden/compra/detalle/nuevo/{codigoOrdenCompra}", name="brs_inv_movimiento_orden_compra_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoOrdenCompra) {
        $em = $this->getDoctrine()->getManager();
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
        $form = $this->createFormBuilder()
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnGuardar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $arrControles = $request->request->All();
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
                            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigo);
                            $arOrdenesCompraDetalles = new \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle();
                            $arOrdenesCompraDetalles->setOrdenCompraRel($arOrdenCompra);
                            $arOrdenesCompraDetalles->setItemRel($arItem);
                            $arOrdenesCompraDetalles->setPorcentajeIva($arItem->getPorcentajeIva());
                            $em->persist($arOrdenesCompraDetalles);
                        }
                        $em->persist($arOrdenesCompraDetalles);
                        $em->flush();
                    }
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        return $this->render('BrasaInventarioBundle:Movimiento/OrdenCompra:detalleNuevo.html.twig', array(
                    'arItem' => $arItem,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->listaDql(
                $this->strCodigo,
                $this->strNumero,
                $this->strTercero
        );
    }

    private function filtrar($form, $arrControles) {
        $em = $this->getDoctrine()->getManager();
        $arTercero = $em->getRepository('BrasaInventarioBundle:InvTercero')->findOneBy(array('nit' => $arrControles['txtNit']));
        if($arTercero){
            $this->strTercero = $arTercero->getCodigoTerceroPk();
        }
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNumero = $form->get('TxtNumero')->getData();
        $this->lista();
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Numero', 'data' => $this->strNumero))
                ->add('TxtCodigo', TextType::class, array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnEliminarDocumento', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($arOrdenCompra) { 
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Desautorizar', 'disabled' => true);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' =>true);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        if($arOrdenCompra->getEstadoAutorizado() == 1){
            $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => true);
            $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => true);
            $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => true);
            $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' =>false);
            $arrBotonDesAutorizar = array('label' => 'Desautorizar', 'disabled' => false);
        }
        $form = $this->createFormBuilder()
                ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                ->add('BtnEliminarDetalle', SubmitType::class, $arrBotonDetalleEliminar)
                ->getForm();
        return $form;
    }

    private function actualizarDetalle($arrControles, $codigoOrdenCompra) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $codigo) {
                $arOrdenCompraDetalle = new \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle();
                $arOrdenCompraDetalle = $em->getRepository('BrasaInventarioBundle:InvOrdenCompraDetalle')->find($codigo);
                $cantidad = $arrControles['TxtCantidad'][$codigo];
                $valor = $arrControles['TxtValor'][$codigo];
                $arOrdenCompraDetalle->setCantidad($cantidad);
                $arOrdenCompraDetalle->setValor($valor);
                $em->persist($arOrdenCompraDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->liquidar($codigoOrdenCompra);
        }
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
        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        /* for($col = 'I'; $col !== 'O'; $col++) {
          $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
          } */
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'PROVEEDOR')
                ->setCellValue('C1', 'TIPO ORDEN')
                ->setCellValue('D1', 'NUMERO')
                ->setCellValue('E1', 'FECHA')
                ->setCellValue('F1', 'SUBTOTAL')
                ->setCellValue('G1', 'IVA')
                ->setCellValue('H1', 'NETO');

        $i = 2;

        
        $query = $em->createQuery($this->strDqlLista);
        $arOrdenesCompra = $query->getResult();
        
        foreach ($arOrdenesCompra as $arOrdenCompra) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arOrdenCompra->getCodigoOrdenCompraPk())
                    ->setCellValue('B' . $i, $arOrdenCompra->getTerceroRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arOrdenCompra->getOrdenCompraDocumentoRel()->getNombre())
                    ->setCellValue('D' . $i, $arOrdenCompra->getNumero())
                    ->setCellValue('E' . $i, $arOrdenCompra->getFecha()->format('y/m/d'))
                    ->setCellValue('F' . $i, $arOrdenCompra->getVrSubtotal())
                    ->setCellValue('G' . $i, $arOrdenCompra->getVrIva())
                    ->setCellValue('H' . $i, $arOrdenCompra->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Movimientos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Movimientos.xlsx"');
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
