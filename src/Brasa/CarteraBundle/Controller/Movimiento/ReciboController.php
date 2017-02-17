<?php
namespace Brasa\CarteraBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\CarteraBundle\Form\Type\CarReciboType;
use Brasa\CarteraBundle\Form\Type\CarReciboDetalleType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReciboController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/movimiento/recibo/lista", name="brs_cartera_movimiento_recibo_listar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $session = new session;
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarRecibo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));                
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

        $arRecibos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:lista.html.twig', array(
            'arRecibos' => $arRecibos,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/recibo/nuevo/{codigoRecibo}", name="brs_cartera_movimiento_recibo_nuevo")
     */
    public function nuevoAction(Request $request,$codigoRecibo) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        if($codigoRecibo != 0) {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        }else{
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arRecibo->setFecha(new \DateTime('now'));
            $arRecibo->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(CarReciboType::class, $arRecibo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRecibo = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arRecibo->setClienteRel($arCliente);
                    $arRecibo->setAsesorRel($arCliente->getAsesorRel());
                }
            }
            if ($codigoRecibo != 0 && $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigoRecibo) > 0) {
                if ($arRecibo->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                    $arUsuario = $this->getUser();
                    $arRecibo->setUsuario($arUsuario->getUserName());            
                    $em->persist($arRecibo);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_nuevo', array('codigoRecibo' => 0 )));
                    } else {
                        if ($codigoRecibo != 0){
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $arRecibo->getCodigoReciboPk())));
                        }

                    }
                } else {
                    $objMensaje->Mensaje("error", "Para modificar el cliente debe eliminar los detalles asociados a este registro");
                }
            } else {
                $arUsuario = $this->getUser();
                $arRecibo->setUsuario($arUsuario->getUserName());            
                $em->persist($arRecibo);
                $em->flush();
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_nuevo', array('codigoRecibo' => 0 )));
                } else {
                    if ($codigoRecibo != 0){
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_listar'));
                    } else {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $arRecibo->getCodigoReciboPk())));
                    }
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:nuevo.html.twig', array(
            'arRecibo' => $arRecibo,
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/recibo/detalle/{codigoRecibo}", name="brs_cartera_movimiento_recibo_detalle")
     */
    public function detalleAction(Request $request,$codigoRecibo) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = $this->get('mensajes_brasa');
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        $form = $this->formularioDetalle($arRecibo);
        $form->handleRequest($request);
        $arUsuario = $this->getUser();
        $rol = $arUsuario->getRoles();
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 5)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrControles = $request->request->All();
                if ($arRecibo->getEstadoAutorizado() == 0){
                    $this->actualizarDetalle($arrControles, $codigoRecibo);                    
                    if($arRecibo->getEstadoAutorizado() == 0) {
                        if($em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigoRecibo) > 0) {                            
                            $error = false;
                            $arReciboDetalles = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
                            foreach ($arReciboDetalles AS $arReciboDetalle) {
                                $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();                                                                
                                $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarFk());                                 
                                if($arCuentaCobrar->getSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                                    if($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                                        $arCuentaCobrarAplicacion = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();                                                                
                                        $arCuentaCobrarAplicacion = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());                                                                             
                                        if($arCuentaCobrarAplicacion->getSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                                            //Cuenta por cobrar aplicacion
                                            $saldo = $arCuentaCobrarAplicacion->getSaldo() - $arReciboDetalle->getVrPagoAfectar();
                                            $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();                                            
                                            $arCuentaCobrarAplicacion->setSaldo($saldo);
                                            $arCuentaCobrarAplicacion->setSaldoOperado($saldoOperado);
                                            $arCuentaCobrarAplicacion->setAbono($arCuentaCobrarAplicacion->getAbono() + $arReciboDetalle->getVrPagoAfectar());                                                                                                                                
                                            $em->persist($arCuentaCobrarAplicacion);
                                            //Cuenta por cobrar
                                            $saldo = $arCuentaCobrar->getSaldo() - $arReciboDetalle->getVrPagoAfectar();
                                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                                            $arCuentaCobrar->setSaldo($saldo);
                                            $arCuentaCobrar->setSaldoOperado($saldoOperado);
                                            $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arReciboDetalle->getVrPagoAfectar());                                                                                    
                                            $em->persist($arCuentaCobrar);                                            
                                        } else {
                                            $objMensaje->Mensaje('error', 'El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getSaldo()); 
                                            $error = true;
                                            break;                                            
                                        }
                                    } else {
                                            $saldo = $arCuentaCobrar->getSaldo() - $arReciboDetalle->getVrPagoAfectar();
                                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                                            $arCuentaCobrar->setSaldo($saldo);
                                            $arCuentaCobrar->setSaldoOperado($saldoOperado);
                                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arReciboDetalle->getVrPagoAfectar());                                        
                                        $em->persist($arCuentaCobrar);
                                    }
                                                                        
                                } else {
                                    $objMensaje->Mensaje('error', 'El pago de la factura ' . $arCuentaCobrar->getNumeroDocumento() . " por " . $arReciboDetalle->getVrPagoAfectar() . " supera el saldo desponible para afectar " . $arCuentaCobrar->getSaldo()); 
                                    $error = true;
                                    break;
                                }
                            }
                            if($error == false) {
                                $arRecibo->setEstadoAutorizado(1);
                                $em->persist($arRecibo);
                                $em->flush();                                                        
                            }
                        } else {
                            $objMensaje->Mensaje('error', 'Debe adicionar detalles al recibo de caja');
                        }                    
                    }
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                
                } else {
                   $objMensaje->Mensaje('error', 'No se puede autorizar, el documento ya esta autorizado'); 
                }
                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 6)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arRecibo->getEstadoAutorizado() == 1 && $arRecibo->getEstadoImpreso() == 0) {                    
                    $arReciboDetalles = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
                    foreach ($arReciboDetalles AS $arReciboDetalle) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                        $saldo = $arCuentaCobrar->getSaldo() + $arReciboDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();                                                                    
                        $arCuentaCobrar->setSaldo($saldo);
                        $arCuentaCobrar->setSaldoOperado($saldoOperado);
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arReciboDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaCobrar);
                        if($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {                            
                            $arCuentaCobrarAplicacion = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                            $arCuentaCobrarAplicacion = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                            $saldo = $arCuentaCobrarAplicacion->getSaldo() + $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();                                                                        
                            $arCuentaCobrarAplicacion->setSaldo($saldo);
                            $arCuentaCobrarAplicacion->setSaldoOperado($saldoOperado);
                            $arCuentaCobrarAplicacion->setAbono($arCuentaCobrarAplicacion->getAbono() - $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrarAplicacion);                            
                        }
                    }
                    $arRecibo->setEstadoAutorizado(0);
                    $em->persist($arRecibo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                
                } else {
                    $objMensaje->Mensaje('error', "El recibo debe estar autorizado y no puede estar impreso");
                }
            }
            if($form->get('BtnAnular')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 9)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arRecibo->getEstadoImpreso() == 1) {
                    $arReciboDetalles = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
                    foreach ($arReciboDetalles AS $arReciboDetalle) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                        $arCuentaCobrar->setSaldo($arCuentaCobrar->getSaldo() + $arReciboDetalle->getVrPagoAfectar());
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() - $arReciboDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaCobrar);
                        $arReciboDetalleAnulado = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                        $arReciboDetalleAnulado = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($arReciboDetalle->getCodigoReciboDetallePk());
                        $arReciboDetalleAnulado->setVrDescuento(0);
                        $arReciboDetalleAnulado->setVrAjustePeso(0);
                        $arReciboDetalleAnulado->setVrRetencionIca(0);
                        $arReciboDetalleAnulado->setVrRetencionIva(0);
                        $arReciboDetalleAnulado->setVrRetencionFuente(0);                        
                        $arReciboDetalleAnulado->setVrPago(0);
                        $arReciboDetalleAnulado->setVrPagoTotal(0); 
                        $arReciboDetalleAnulado->setVrPagoAfectar(0);
                        $em->persist($arReciboDetalleAnulado);
                    }
                    $arRecibo->setEstadoAnulado(1);
                    $arRecibo->setVrTotalAjustePeso(0);
                    $arRecibo->setVrTotalDescuento(0);
                    $arRecibo->setVrTotalRetencionIca(0);
                    $arRecibo->setVrTotalRetencionIva(0);
                    $arRecibo->setVrTotalRetencionFuente(0);
                    $arRecibo->setVrPago(0);
                    $arRecibo->setVrPagoTotal(0);                    
                    $em->persist($arRecibo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                
                }
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                if($arRecibo->getEstadoAutorizado() == 0 ) {
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles, $codigoRecibo);                
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
                } else {
                    $objMensaje->Mensaje("error", "No se puede actualizar el registro, esta autorizado");
                }    
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {  
                if($arRecibo->getEstadoAutorizado() == 0 ) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);                 
                } else {
                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado");
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                    
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                if($arRecibo->getEstadoAutorizado() == 1 ) {
                    $strResultado = $em->getRepository('BrasaCarteraBundle:CarRecibo')->imprimir($codigoRecibo);
                    if($strResultado != "") {
                        $objMensaje->Mensaje("error", $strResultado);
                    } else {
                        $objRecibo = new \Brasa\CarteraBundle\Formatos\FormatoRecibo();
                        $objRecibo->Generar($em, $codigoRecibo);
                    }
                } else {
                    $objMensaje->Mensaje("error", "No se puede imprimir el registro, no esta autorizado");
                }
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                        
            }
            if($form->get('BtnVistaPrevia')->isClicked()) {
                /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }*/
                
                    //$strResultado = $em->getRepository('BrasaCarteraBundle:CarRecibo')->imprimir($codigoRecibo);
                    
                    $objRecibo = new \Brasa\CarteraBundle\Formatos\FormatoRecibo();
                        $objRecibo->Generar($em, $codigoRecibo);
                 
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));                        
            }
        }
        $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array ('codigoReciboFk' => $codigoRecibo));
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalle.html.twig', array(
                    'arRecibo' => $arRecibo,
                    'arReciboDetalle' => $arReciboDetalle,
                    'form' => $form->createView(),
                    'rol' => $rol 
                    ));
    }
    
    /**
     * @Route("/cartera/movimiento/recibo/detalle/nuevo/{codigoRecibo}", name="brs_cartera_movimiento_recibo_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoRecibo) {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {            
            if ($form->get('BtnGuardar')->isClicked()) {
                $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                $intIndice = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
                        $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                        $arReciboDetalle->setReciboRel($arRecibo);
                        $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arReciboDetalle->setValor($arrControles['TxtSaldo'.$codigoCuentaCobrar]);
                        $arReciboDetalle->setUsuario($arUsuario->getUserName());
                        $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                        $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $em->persist($arReciboDetalle);                            
                    }
                    $em->flush();
                } 
                $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrar($arRecibo->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);        
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arRecibo' => $arRecibo,
            'form' => $form->createView()));
    } 

    /**
     * @Route("/cartera/movimiento/recibo/detalle/aplicar/{codigoReciboDetalle}", name="brs_cartera_movimiento_recibo_detalle_aplicar")
     */
    public function detalleAplicarAction(Request $request, $codigoReciboDetalle) {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($codigoReciboDetalle);
        $form = $this->createFormBuilder()            
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {            
            if($request->request->get('OpAplicar')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $codigoCuentaCobrar = $request->request->get('OpAplicar');
                $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();                
                $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);                                
                $arReciboDetalle->setCuentaCobrarAplicacionRel($arCuentaCobrar);
                $arReciboDetalle->setNumeroDocumentoAplicacion($arCuentaCobrar->getNumeroDocumento());
                $em->persist($arReciboDetalle);
                $em->flush();
            }           
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->cuentasCobrarAplicar($arReciboDetalle->getReciboRel()->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);        
        return $this->render('BrasaCarteraBundle:Movimientos/Recibo:detalleAplicar.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,        
            'form' => $form->createView()));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaDQL(
                $session->get('filtroReciboNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroReciboEstadoImpreso'));
    }

    private function filtrar ($form) {       
        $session = new session;        
        $session->set('filtroReciboNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroReciboEstadoImpreso', $form->get('estadoImpreso')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());   
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        
        $form = $this->createFormBuilder()
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroCotizacionNumero')))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoImpreso', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'IMPRESO' => '1', 'SIN IMPRIMIR' => '0'), 'data' => $session->get('filtroReciboEstadoImpreso')))                
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonVistaPrevia = array('label' => 'Vista previa', 'disabled' => false);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
            $arrBotonAnular['disabled'] = true;
        }
        if($ar->getEstadoImpreso() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAnular['disabled'] = false;
        }
        if($ar->getEstadoAnulado() == 1) {
            $arrBotonAnular['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                 
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnVistaPrevia', SubmitType::class, $arrBotonVistaPrevia)
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
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'H'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'NIT')                
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'CUENTA')
                    ->setCellValue('F1', 'TIPO RECIBO')
                    ->setCellValue('G1', 'FECHA PAGO')
                    ->setCellValue('H1', 'TOTAL')
                    ->setCellValue('I1', 'ANULADO')
                    ->setCellValue('J1', 'AUTORIZADO')
                    ->setCellValue('K1', 'IMPRESO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arRecibos = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibos = $query->getResult();

        foreach ($arRecibos as $arRecibo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecibo->getCodigoReciboPk())
                    ->setCellValue('B' . $i, $arRecibo->getNumero())
                    ->setCellValue('E' . $i, $arRecibo->getCuentaRel()->getNombre())
                    ->setCellValue('F' . $i, $arRecibo->getReciboTipoRel()->getNombre())
                    ->setCellValue('G' . $i, $arRecibo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arRecibo->getVrTotal())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAnulado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoAutorizado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arRecibo->getEstadoImpreso()));
            if($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arRecibo->getClienteRel()->getNit());
            }
            if($arRecibo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arRecibo->getClienteRel()->getNombreCorto());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Recibos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recibos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function actualizarDetalle($arrControles, $codigoRecibo) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arReciboDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($intCodigo);                                
                //Valor que debia pagar                                
                $valorPagoAfectar = $arrControles['TxtValorPago'.$intCodigo] - $arrControles['TxtVrAjustePeso'.$intCodigo] + $arrControles['TxtVrDescuento'.$intCodigo];                
                $arReciboDetalle->setVrDescuento($arrControles['TxtVrDescuento'.$intCodigo]);
                $arReciboDetalle->setVrAjustePeso($arrControles['TxtVrAjustePeso'.$intCodigo]);
                $arReciboDetalle->setVrRetencionIca($arrControles['TxtVrRetencionIca'.$intCodigo]);
                $arReciboDetalle->setVrRetencionIva($arrControles['TxtVrRetencionIva'.$intCodigo]);
                $arReciboDetalle->setVrRetencionFuente($arrControles['TxtVrRetencionFuente'.$intCodigo]);                
                $arReciboDetalle->setVrPago($arrControles['TxtValorPago'.$intCodigo]);                                                
                $arReciboDetalle->setVrPagoAfectar($valorPagoAfectar);
                $em->persist($arReciboDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->liquidar($codigoRecibo);                   
        }
    }
    
}