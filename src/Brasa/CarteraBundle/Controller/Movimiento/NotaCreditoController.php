<?php

namespace Brasa\CarteraBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarNotaCreditoType;
use Brasa\CarteraBundle\Form\Type\CarNotaCreditoDetalleType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NotaCreditoController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/cartera/movimiento/notacredito/lista", name="brs_car_movimiento_notacredito_listar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 117, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $session = new session;
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnEliminar')->isClicked()) {
                    if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 4)) {
                        return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                    }
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_listar'));
                }
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
        $arNotasCreditos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/NotaCredito:lista.html.twig', array(
                    'arNotasCreditos' => $arNotasCreditos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/notacredito/nuevo/{codigoNotaCredito}", name="brs_car_movimiento_notacredito_nuevo")
     */
    public function nuevoAction(Request $request, $codigoNotaCredito) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arNotaCredito = new \Brasa\CarteraBundle\Entity\CarNotaCredito();
        if ($codigoNotaCredito != 0) {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
            $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigoNotaCredito);
        } else {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
            $arNotaCredito->setFecha(new \DateTime('now'));
            $arNotaCredito->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(CarNotaCreditoType::class, $arNotaCredito);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arNotaCredito = $form->getData();
                $arrControles = $request->request->All();
                $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
                if ($arrControles['txtNit'] != '') {
                    $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                    if (count($arCliente) > 0) {
                        $arNotaCredito->setClienteRel($arCliente);
                    }
                }
                if ($codigoNotaCredito != 0 && $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->numeroRegistros($codigoNotaCredito) > 0) {
                    if ($arNotaCredito->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                        $arUsuario = $this->getUser();
                        $arNotaCredito->setUsuario($arUsuario->getUserName());
                        $em->persist($arNotaCredito);
                        $em->flush();
                        if ($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_nuevo', array('codigoNotaCredito' => 0)));
                        } else {
                            if ($codigoNotaCredito != 0) {
                                return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_listar'));
                            } else {
                                return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $arNotaCredito->getCodigoNotaCreditoPk())));
                            }
                        }
                    } else {
                        $objMensaje->Mensaje("error", "Para modificar el cliente debe eliminar los detalles asociados a este registro");
                    }
                } else {
                    $arUsuario = $this->getUser();
                    $arNotaCredito->setUsuario($arUsuario->getUserName());
                    $em->persist($arNotaCredito);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_nuevo', array('codigoNotaCredito' => 0)));
                    } else {
                        if ($codigoNotaCredito != 0) {
                            return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $arNotaCredito->getCodigoNotaCreditoPk())));
                        }
                    }
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/NotaCredito:nuevo.html.twig', array(
                    'arNotaCredito' => $arNotaCredito,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/notacredito/detalle/{codigoNotaCredito}", name="brs_car_movimiento_notacredito_detalle")
     */
    public function detalleAction(Request $request, $codigoNotaCredito) {
        $em = $this->getDoctrine()->getManager();

        $objMensaje = $this->get('mensajes_brasa');
        $arNotaCredito = new \Brasa\CarteraBundle\Entity\CarNotaCredito();
        $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigoNotaCredito);
        $form = $this->formularioDetalle($arNotaCredito);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnAutorizar')->isClicked()) {
                    if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 5)) {
                        return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                    }
                    $arrControles = $request->request->All();
                    if ($arNotaCredito->getEstadoAutorizado() == 0) {
                        $this->actualizarDetalle($arrControles, $codigoNotaCredito);
                        $arInconsistencias = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->findBy(array('codigoNotaCreditoFk' => $codigoNotaCredito, 'estadoInconsistencia' => 1));
                        if ($arInconsistencias == null) {
                            if ($arNotaCredito->getEstadoAutorizado() == 0) {
                                if ($em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->numeroRegistros($codigoNotaCredito) > 0) {
                                    $arNotaCredito->setEstadoAutorizado(1);
                                    $arDetallesNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->findBy(array('codigoNotaCreditoFk' => $codigoNotaCredito));
                                    foreach ($arDetallesNotaCredito AS $arDetalleNotaCredito) {
                                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleNotaCredito->getCodigoCuentaCobrarFk());
                                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() - $arDetalleNotaCredito->getVrPago());
                                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arDetalleNotaCredito->getVrPago());
                                        $em->persist($arCuentaCobrar);
                                    }
                                    $em->persist($arNotaCredito);
                                    $em->flush();
                                } else {
                                    $objMensaje->Mensaje('error', 'Debe adicionar detalles al recibo de caja');
                                }
                            }
                        } else {
                            $objMensaje->Mensaje('error', 'No se puede autorizar, hay inconsistencias');
                        }
                        return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $codigoNotaCredito)));
                    } else {
                        $objMensaje->Mensaje('error', 'No se puede autorizar, ya esta autorizado');
                    }
                }
                if ($form->get('BtnDesAutorizar')->isClicked()) {
                    if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 6)) {
                        return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                    }
                    if ($arNotaCredito->getEstadoAutorizado() == 1 && $arNotaCredito->getEstadoImpreso() == 0) {
                        $arNotaCredito->setEstadoAutorizado(0);
                        $arDetallesNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->findBy(array('codigoNotaCreditoFk' => $codigoNotaCredito));
                        foreach ($arDetallesNotaCredito AS $arDetalleNotaCredito) {
                            $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleNotaCredito->getCodigoCuentaCobrarFk());
                            $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleNotaCredito->getVrPago());
                            $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleNotaCredito->getVrPago());
                            $em->persist($arCuentaCobrar);
                        }
                        $em->persist($arNotaCredito);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $codigoNotaCredito)));
                    } else {
                        $objMensaje->Mensaje('error', "La nota credito debe estar autorizado y no puede estar impreso");
                    }
                }
                if ($form->get('BtnAnular')->isClicked()) {
                    if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 9)) {
                        return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                    }
                    if ($arNotaCredito->getEstadoImpreso() == 1) {
                        $arNotaCredito->setEstadoAnulado(1);
                        $arNotaCredito->setValor(0);
                        $arDetallesNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->findBy(array('codigoNotaCreditoFk' => $codigoNotaCredito));
                        foreach ($arDetallesNotaCredito AS $arDetalleNotaCredito) {
                            $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arDetalleNotaCredito->getCodigoCuentaCobrarFk());
                            $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arDetalleNotaCredito->getVrPago());
                            $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arDetalleNotaCredito->getVrPago());
                            $arDetalleNotaCreditoAnulado = new \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle();
                            $arDetalleNotaCreditoAnulado = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->find($arDetalleNotaCredito->getCodigoNotaCreditoDetallePk());
                            $arDetalleNotaCreditoAnulado->setValor(0);
                            $em->persist($arCuentaCobrar);
                            $em->persist($arDetalleNotaCreditoAnulado);
                        }
                        $em->persist($arNotaCredito);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $codigoNotaCredito)));
                    }
                }
                if ($form->get('BtnDetalleActualizar')->isClicked()) {
                    if ($arNotaCredito->getEstadoAutorizado() == 0) {
                        $arrControles = $request->request->All();
                        $this->actualizarDetalle($arrControles, $codigoNotaCredito);
                        return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $codigoNotaCredito)));
                    } else {
                        $objMensaje->Mensaje("error", "No se puede actualizar el registro, esta autorizado");
                    }
                }
                if ($form->get('BtnDetalleEliminar')->isClicked()) {
                    if ($arNotaCredito->getEstadoAutorizado() == 0) {
                        $arrSeleccionados = $request->request->get('ChkSeleccionar');
                        $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->eliminarSeleccionados($arrSeleccionados);
                        $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->liquidar($codigoNotaCredito);
                        return $this->redirect($this->generateUrl('brs_car_movimiento_notacredito_detalle', array('codigoNotaCredito' => $codigoNotaCredito)));
                    } else {
                        $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado");
                    }
                }
                if ($form->get('BtnImprimir')->isClicked()) {
                    if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 118, 10)) {
                        return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                    }
                    if ($arNotaCredito->getEstadoAutorizado() == 1) {
                        $strResultado = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->imprimir($codigoNotaCredito);
                        if ($strResultado != "") {
                            $objMensaje->Mensaje("error", $strResultado);
                        } else {
                            $objNotaCredito = new \Brasa\CarteraBundle\Formatos\FormatoNotaCredito();
                            $objNotaCredito->Generar($em, $codigoNotaCredito);
                        }
                    } else {
                        $objMensaje->Mensaje("error", "No se puede imprimir el registro, no esta autorizado");
                    }
                }
            }
        }
        $arNotaCreditoDetalle = new \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle();
        $arNotaCreditoDetalle = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->findBy(array('codigoNotaCreditoFk' => $codigoNotaCredito));
        return $this->render('BrasaCarteraBundle:Movimientos/NotaCredito:detalle.html.twig', array(
                    'arNotaCredito' => $arNotaCredito,
                    'arNotaCreditoDetalle' => $arNotaCreditoDetalle,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/cartera/movimiento/notacredito/detalle/nuevo/{codigoNotaCredito}/{codigoNotaCreditoDetalle}", name="brs_car_movimiento_notacredito_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoNotaCredito, $codigoNotaCreditoDetalle = 0) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $arNotaCredito = new \Brasa\CarteraBundle\Entity\CarNotaCredito();
        $arNotaCredito = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->find($codigoNotaCredito);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrar($arNotaCredito->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        $form = $this->createFormBuilder()
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                if ($form->get('BtnGuardar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $arrControles = $request->request->All();
                    $intIndice = 0;
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                            if ($em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->validarCuenta($codigoCuentaCobrar, $codigoNotaCredito)) {
                                $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
                                $arNotaCreditoDetalle = new \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle();
                                $arNotaCreditoDetalle->setNotaCreditoRel($arNotaCredito);
                                $arNotaCreditoDetalle->setCuentaCobrarRel($arCuentaCobrar);
                                $arNotaCreditoDetalle->setValor($arrControles['TxtSaldo' . $codigoCuentaCobrar]);
                                $arNotaCreditoDetalle->setUsuario($arUsuario->getUserName());
                                $arNotaCreditoDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                                $arNotaCreditoDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                                $em->persist($arNotaCreditoDetalle);
                            }
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->liquidar($codigoNotaCredito);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/NotaCredito:detalleNuevo.html.twig', array(
                    'arCuentasCobrar' => $arCuentasCobrar,
                    'arNotaCredito' => $arNotaCredito,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaCarteraBundle:CarNotaCredito')->listaDQL(
                $session->get('filtroNotaCreditoNumero'), $session->get('filtroCodigoCliente'), $session->get('filtroNotaCreditoEstadoImpreso'));
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroNotaCreditoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroNotaCreditoEstadoImpreso', $form->get('estadoImpreso')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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

        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroCotizacionNumero')))
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('estadoImpreso', ChoiceType::class, array('choices' => array('TODOS' => '2', 'IMPRESO' => '1', 'SIN IMPRIMIR' => '0'), 'data' => $session->get('filtroNotaCreditoEstadoImpreso')))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if ($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        if ($ar->getEstadoImpreso() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
        }
        if ($ar->getEstadoAnulado() == 1) {
            $arrBotonAnular['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnAnular', SubmitType::class, $arrBotonAnular)
                ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'H'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'NIT')
                ->setCellValue('D1', 'CLIENTE')
                ->setCellValue('E1', 'CUENTA')
                ->setCellValue('F1', 'CONCEPTO')
                ->setCellValue('G1', 'FECHA PAGO')
                ->setCellValue('H1', 'TOTAL')
                ->setCellValue('I1', 'ANULADO')
                ->setCellValue('J1', 'AUTORIZADO')
                ->setCellValue('k1', 'IMPRESO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arNotaCreditos = new \Brasa\CarteraBundle\Entity\CarNotaCredito();
        $arNotaCreditos = $query->getResult();

        foreach ($arNotaCreditos as $arNotaCredito) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNotaCredito->getCodigoNotaCreditoPk())
                    ->setCellValue('B' . $i, $arNotaCredito->getNumero())
                    ->setCellValue('E' . $i, $arNotaCredito->getCuentaRel()->getNombre())
                    ->setCellValue('F' . $i, $arNotaCredito->getNotaCreditoConceptoRel()->getNombre())
                    ->setCellValue('G' . $i, $arNotaCredito->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arNotaCredito->getValor())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arNotaCredito->getEstadoAnulado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arNotaCredito->getEstadoAutorizado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arNotaCredito->getEstadoImpreso()));
            if ($arNotaCredito->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C' . $i, $arNotaCredito->getClienteRel()->getNit());
            }
            if ($arNotaCredito->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $i, $arNotaCredito->getClienteRel()->getNombreCorto());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('NotaCreditos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="NotaCreditos.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoNotaCredito) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        $floTotal = 0;
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arNotaCreditoDetalle = new \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle();
                $arNotaCreditoDetalle = $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->find($intCodigo);
                $floSaldo = $arNotaCreditoDetalle->getCuentaCobrarRel()->getSaldo();
                $floSaldoAfectar = $arrControles['TxtValor' . $intCodigo];
                if ($floSaldo < $floSaldoAfectar) {
                    $arNotaCreditoDetalle->setEstadoInconsistencia(1);
                } else {
                    $arNotaCreditoDetalle->setEstadoInconsistencia(0);
                }
                $arNotaCreditoDetalle->setValor($arrControles['TxtValor' . $intCodigo]);
                $arNotaCreditoDetalle->setVrPago($floSaldoAfectar);
                $em->persist($arNotaCreditoDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaCarteraBundle:CarNotaCreditoDetalle')->liquidar($codigoNotaCredito);
        }
    }

}
