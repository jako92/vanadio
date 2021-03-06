<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContabilizarLiquidacionController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/proceso/contabilizar/liquidacion/", name="brs_rhu_proceso_contabilizar_liquidacion")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 68)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();  
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
        $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
        $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteLiquidacion());        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) { 
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $respuesta = '';
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(1);
                    $codigoCuentaCesantias = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(2);                    
                    $codigoCuentaInteresesCesantias = $arCuenta->getCodigoCuentaFk();
                    //Cesantias año anterior
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(17);
                    $codigoCuentaCesantiasAnterior = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(18);
                    $codigoCuentaInteresesCesantiasAnterior = $arCuenta->getCodigoCuentaFk();
                    
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(3);
                    $codigoCuentaPrimas = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(4);
                    $codigoCuentaVacaciones = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(6);
                    $codigoCuentaIndemnizacion = $arCuenta->getCodigoCuentaFk();
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(5);
                    $codigoCuentaLiquidacion = $arCuenta->getCodigoCuentaFk();
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigo);
                        $arSucursal = $arLiquidacion->getEmpleadoRel()->getSucursalRel();
                        $area = $arLiquidacion->getEmpleadoRel()->getEmpleadoTipoRel()->getInterfaz();                        
                        if($arLiquidacion->getEstadoContabilizado() == 0) {                            
                            $arCentroCosto = $arLiquidacion->getEmpleadoRel()->getCentroCostoContabilidadRel();                                                       
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(!$arTercero) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arLiquidacion->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arLiquidacion->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arLiquidacion->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arLiquidacion->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arLiquidacion->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arLiquidacion->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arLiquidacion->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arLiquidacion->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arLiquidacion->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arLiquidacion->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arLiquidacion->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }                            

                            //Cesantias
                            if($arLiquidacion->getVrCesantias() > 0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaCesantias); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrCesantias()); 
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);             
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('CESANTIAS');
                                    $em->persist($arRegistro);
                                }             
                            }

                            //Intereses cesantias
                            if($arLiquidacion->getVrInteresesCesantias() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaInteresesCesantias); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrInteresesCesantias()); 
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);   
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }             
                            }  

                            //Cesantias anteriores
                            if($arLiquidacion->getVrCesantiasAnterior() > 0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaCesantiasAnterior); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrCesantiasAnterior()); 
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);   
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('CESANTIAS AÑO ANTERIOR');
                                    $em->persist($arRegistro);
                                }             
                            }

                            //Intereses cesantias anteriores
                            if($arLiquidacion->getVrInteresesCesantiasAnterior() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaInteresesCesantiasAnterior); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrInteresesCesantiasAnterior());      
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);       
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('INTERESES CESANTIAS AÑO ANTERIOR');
                                    $em->persist($arRegistro);
                                }             
                            }                            
                            
                            //Primas
                            if($arLiquidacion->getVrPrima() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaPrimas); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrPrima()); 
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);  
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('PRIMAS');
                                    $em->persist($arRegistro);
                                }             
                            } 

                            //Vacaciones
                            if($arLiquidacion->getVrVacaciones() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaVacaciones); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrVacaciones());  
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);   
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('VACACIONES');
                                    $em->persist($arRegistro);
                                }             
                            }        

                            //Indemnizacion
                            if($arLiquidacion->getVrIndemnizacion() > 0) {                                    
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaIndemnizacion); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setDebito($arLiquidacion->getVrIndemnizacion());   
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);  
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('VACACIONES');
                                    $em->persist($arRegistro);
                                }             
                            }                                 

                            //Adicionales
                            $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                            $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->findBy(array('codigoLiquidacionFk' => $codigo));
                            foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
                                $arPagoConceptoCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConceptoCuenta();
                                $arPagoConceptoCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConceptoCuenta')->findOneBy(array('codigoPagoConceptoFk' => $arLiquidacionAdicional->getCodigoPagoConceptoFk(), 'codigoEmpleadoTipoFk' => $arLiquidacion->getEmpleadoRel()->getCodigoEmpleadoTipoFk()));                                                                
                                if($arPagoConceptoCuenta) {
                                    $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoConceptoCuenta->getCodigoCuentaFk());                                    
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                        
                                        }                                    
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setNumero($arLiquidacion->getNumero());
                                        $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                        $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                        if($arLiquidacionAdicional->getVrBonificacion() > 0) {
                                            $arRegistro->setDebito($arLiquidacionAdicional->getVrBonificacion());    
                                        } else {
                                            $arRegistro->setCredito($arLiquidacionAdicional->getVrDeduccion());
                                        }                       
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                    
                                        $arRegistro->setDescripcionContable($arLiquidacionAdicional->getPagoConceptoRel()->getNombre());
                                        $em->persist($arRegistro);
                                    } else {
                                        $respuesta = "La cuenta " . $arPagoConceptoCuenta->getCodigoCuentaFk() . " no existe en el plan de cuentas";
                                        break;                                         
                                    }                                  
                                } else {
                                    $respuesta = "El concepto adicional de la liquidacion " . $arLiquidacionAdicional->getPagoConceptoRel()->getNombre() . " no tiene cuenta configurada";
                                    break;
                                }                                    
                            }                                

                            //Liquidacion
                            if($arLiquidacion->getVrTotal() > 0) {                                   
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaLiquidacion); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arLiquidacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arLiquidacion->getCodigoLiquidacionPk());
                                    $arRegistro->setFecha($arLiquidacion->getFechaHasta());
                                    $arRegistro->setCredito($arLiquidacion->getVrTotal());  
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);
                                    if ($arCuenta->getExigeCentroCostos() == 1) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                    }                                    
                                    $arRegistro->setDescripcionContable('LIQUIDACION POR PAGAR');
                                    $em->persist($arRegistro);
                                }             
                            }
                            
                            if ($respuesta != '') {                                                            
                                break;
                            }
                            $arLiquidacion->setEstadoContabilizado(1);                                                            
                            $em->persist($arLiquidacion);                                                                                          
                        }
                    }
                    if ($respuesta == '') {
                        $em->flush();
                    } else {
                        $objMensaje->Mensaje('error', $respuesta);
                    }                                        
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_liquidacion'));
            }    
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->formularioLista();
                $this->listar();
            }            
        }       
                
        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:liquidacion.html.twig', array(
            'arLiquidaciones' => $arLiquidaciones,
            'arComprobante' => $arComprobanteContable,            
            'form' => $form->createView()));
    }          
    
    /**
     * @Route("/rhu/proceso/contabilizar/liquidacion/configurar/", name="brs_rhu_proceso_contabilizar_liquidacion_configurar")
     */     
    public function configurarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();       
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
        $form = $this->createFormBuilder()          
            ->add('TxtCodigoComprobante', TextType::class, array('data' => $arConfiguracion->getCodigoComprobanteLiquidacion()))    
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm(); 
        $form->handleRequest($request);
        if($form->isValid()) {            
            if ($form->get('BtnGuardar')->isClicked()) { 
                $arrControles = $request->request->All();
                $codigoComprobante = $form->get('TxtCodigoComprobante')->getData();
                $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($codigoComprobante);  
                if($arComprobanteContable) {
                    $intCodigoCuenta = 0;
                    foreach ($arrControles['LblCodigoCuenta'] as $intCodigoCuenta) {
                        $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
                        $arConfiguracionCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find($intCodigoCuenta);
                        if (count($arConfiguracionCuenta) > 0) {
                            $intConfiguracionCuenta = $arrControles['TxtCodigoCuenta' . $intCodigoCuenta];
                            $arConfiguracionCuenta->setCodigoCuentaFk($intConfiguracionCuenta);
                            $em->persist($arConfiguracionCuenta);
                        }
                        $intCodigoCuenta++;
                    }
                    $em->flush();                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje("error", "El comprobante no existe");
                }                
            }    
        }       
        $arConfiguracionCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->findBy(array('tipo' => 'LIQUIDACION'));        
        //$arLiquidaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:liquidacionConfigurar.html.twig', array(
            'arConfiguracionCuenta' => $arConfiguracionCuenta,
            'form' => $form->createView()));
    }     
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');        
        $strNombreEmpleado = "";
        if ($session->get('filtroRhuIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroRhuIdentificacion')));
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
        $form = $this->createFormBuilder()   
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('TxtNumero', TextType::class, array('label' => 'Numero', 'data' => $session->get('filtroRhuLiquidacionNumero')))
            ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroRhuIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))                
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))                
            ->add('BtnContabilizar', SubmitType::class, array('label'  => 'Contabilizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();     
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->pendientesContabilizarDql(
                $session->get('filtroRhuLiquidacionNumero'),  
                $session->get('filtroRhuCodigoEmpleado'), 
                $session->get('filtroDesde'), 
                $session->get('filtroHasta')                
                );  
    }                 
    
    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');
        $session->set('filtroRhuIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuLiquidacionNumero', $form->get('TxtNumero')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaHasta')->getData() == null) {
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
    }    
    
    /**
     * @Route("/rhu/proceso/descontabilizar/liquidacion/", name="brs_rhu_proceso_descontabilizar_liquidacion")
     */    
    public function descontabilizarLiquidacionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $session = new Session; 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('numeroDesde', NumberType::class, array('label'  => 'Numero desde'))
            ->add('numeroHasta', NumberType::class, array('label'  => 'Numero hasta'))
            ->add('fechaDesde',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                            
            ->add('BtnDescontabilizar', SubmitType::class, array('label'  => 'Descontabilizar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intNumeroDesde = $form->get('numeroDesde')->getData();
                $intNumeroHasta = $form->get('numeroHasta')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                if($intNumeroDesde != "" || $intNumeroHasta != "" || $dateFechaDesde != "" || $dateFechaHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->contabilizadosDql($intNumeroDesde,$intNumeroHasta,$dateFechaDesde,$dateFechaHasta);  
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoRegistro);
                        $arRegistro->setEstadoContabilizado(0);                                                    
                        $em->persist($arRegistro);    
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', 'Debe seleccionar un filtro', $this);
                }                               
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarLiquidacion.html.twig', array(
            'form' => $form->createView()));
    }    
}
