<?php

namespace Brasa\InventarioBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvSolicitudType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SolicitudController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNumero = "";

    /**
     * @Route("/inv/movimiento/solicitud/lista", name="brs_inv_movimiento_solicitud_lista")
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
                $em->getRepository('BrasaInventarioBundle:InvSolicitud')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_inv_movimiento_solicitud_lista'));
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
        }
        $arSolicitudes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Movimiento/Solicitud:lista.html.twig', array(
                    'arSolicitudes' => $arSolicitudes,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/orden/solicitud/{codigoSolicitud}", name="brs_inv_movimiento_solicitud_nuevo")
     */
    public function nuevoAction(Request $request, $codigoSolicitud) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud->setFechaEntrega(new \DateTime('now'));
        if ($codigoSolicitud != 0) {
            $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
        }
        $form = $this->createForm(InvSolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                $arSolicitud->setFecha(new \DateTime('now'));
                $arSolicitud = $form->getData();
                $em->persist($arSolicitud);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_solicitud_nuevo', array('codigoSolicitud' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_inv_movimiento_solicitud_lista'));
                }
        }
        return $this->render('BrasaInventarioBundle:Movimiento/Solicitud:nuevo.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/solicitud/{codigoSolicitud}", name="brs_inv_movimiento_solicitud_detalle")
     */
    public function detalleAction(Request $request, $codigoSolicitud) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
        $form = $this->formularioDetalle($arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnEliminarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarDetalle');
                if ($arrSeleccionados > 0) {
                    foreach ($arrSeleccionados as $codigoSolicitudDetalle) {
                        $arSolicitudDetalle = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
                        $arSolicitudDetalle = $em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->find($codigoSolicitudDetalle);
                        $em->remove($arSolicitudDetalle);
                        $em->flush();
                    }
                }
            }
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoSolicitud);
                return $this->redirect($this->generateUrl('brs_inv_movimiento_solicitud_detalle', array('codigoSolicitud' => $codigoSolicitud)));
            }
            if ($form->get('BtnAutorizar')->isClicked()) {
                $respuesta = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->autorizar($codigoSolicitud);
                if ($respuesta != "") {
                    $objMensaje->Mensaje("error", $respuesta);
                }
                return $this->redirect($this->generateUrl('brs_inv_movimiento_solicitud_detalle', array('codigoSolicitud' => $codigoSolicitud)));
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                $respuesta = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->desAutorizar($codigoSolicitud);
                if ($respuesta != "") {
                    $objMensaje->Mensaje("error", $respuesta);
                }
                return $this->redirect($this->generateUrl('brs_inv_movimiento_solicitud_detalle', array('codigoSolicitud' => $codigoSolicitud)));
            }
            if ($form->get('BtnImprimir')->isClicked()) {
                $respuesta = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->imprimir($codigoSolicitud);
                if ($respuesta != "") {
                    $objMensaje->Mensaje("error", $respuesta);
                } else {
                    $objFormatoSolicitud = new \Brasa\InventarioBundle\Formatos\FormatoSolicitud();
                    $objFormatoSolicitud->Generar($em, $codigoSolicitud);
                }
            }
        }

        $arSolicitudDetalles = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
        $arSolicitudDetalles = $em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->FindBy(array('codigoSolicitudFk' => $codigoSolicitud));
        return $this->render('BrasaInventarioBundle:Movimiento/Solicitud:detalle.html.twig', array(
                    'arSolicitud' => $arSolicitud,
                    'arSolicitudDetalles' => $arSolicitudDetalles,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/inv/movimiento/solicitud/detalle/nuevo/{codigoSolicitud}", name="brs_inv_movimiento_solicitud_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoSolicitud) {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
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
                            $arSolicitudesDetalles = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
                            $arSolicitudesDetalles->setSolicitudRel($arSolicitud);
                            $arSolicitudesDetalles->setItemRel($arItem);
                            $arSolicitudesDetalles->setPorcentajeIva($arItem->getPorcentajeIva());
                            $em->persist($arSolicitudesDetalles);
                        }
                        $em->persist($arSolicitudesDetalles);
                        $em->flush();
                    }
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        return $this->render('BrasaInventarioBundle:Movimiento/Solicitud:detalleNuevo.html.twig', array(
                    'arItem' => $arItem,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->listaDql(
                $this->strCodigo, $this->strNumero
        );
    }

    private function filtrar($form) {
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

    private function formularioDetalle($arSolicitud) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Desautorizar', 'disabled' => true);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => true);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        if ($arSolicitud->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => true);
            $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => true);
            $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => true);
            $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
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

    private function actualizarDetalle($arrControles, $codigoSolicitud) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $codigo) {
                $arSolicitudDetalle = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
                $arSolicitudDetalle = $em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->find($codigo);
                $cantidad = $arrControles['TxtCantidad'][$codigo];
                $valor = $arrControles['TxtValor'][$codigo];
                $arSolicitudDetalle->setCantidad($cantidad);
                $arSolicitudDetalle->setValor($valor);
                $em->persist($arSolicitudDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaInventarioBundle:InvSolicitud')->liquidar($codigoSolicitud);
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
         for($col = 'I'; $col !== 'O'; $col++) {
          $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
          } 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'TIPO SOLICITUD')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'SUBTOTAL')
                ->setCellValue('F1', 'IVA')
                ->setCellValue('G1', 'NETO');

        $i = 2;


        $query = $em->createQuery($this->strDqlLista);
        $arSolicitudes = $query->getResult();

        foreach ($arSolicitudes as $arSolicitud) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSolicitud->getCodigoSolicitudPk())
                    ->setCellValue('B' . $i, $arSolicitud->getNumero())
                    ->setCellValue('C' . $i, $arSolicitud->getSolicitudDocumentoRel()->getNombre())
                    ->setCellValue('D' . $i, $arSolicitud->getFecha()->format('y/m/d'))
                    ->setCellValue('E' . $i, $arSolicitud->getVrSubtotal())
                    ->setCellValue('F' . $i, $arSolicitud->getVrIva())
                    ->setCellValue('G' . $i, $arSolicitud->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Solicitudes');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Solicitudes.xlsx"');
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
