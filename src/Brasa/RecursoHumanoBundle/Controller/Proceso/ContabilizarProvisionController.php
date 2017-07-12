<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ContabilizarProvisionController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/proceso/contabilizar/provision", name="brs_rhu_proceso_contabilizar_provision")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 67)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();        
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
        $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();
        $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteProvision());        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            if ($form->get('BtnContabilizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    $arConfiguracionAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionAporte();
                    $arConfiguracionAporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionAporte')->find(1);
                    $errorDatos = false;
                    foreach ($arrSeleccionados AS $codigo) {
                        $arProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();                        
                        $arProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->find($codigo);
                        $tipoEmpleado = $arProvision->getEmpleadoRel()->getCodigoEmpleadoTipoFk();
                        $arSucursal = $arProvision->getEmpleadoRel()->getSucursalRel();
                        $area = $arProvision->getEmpleadoRel()->getEmpleadoTipoRel()->getInterfaz();
                        $arCentroCosto = $arProvision->getEmpleadoRel()->getCentroCostoContabilidadRel();
                        //$arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
                        //$arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);
                        if ($arProvision->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getEmpleadoRel()->getNumeroIdentificacion()));
                            if (count($arTercero) <= 0) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arProvision->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arProvision->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arProvision->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arProvision->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arProvision->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arProvision->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arProvision->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arProvision->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arProvision->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arProvision->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arProvision->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arProvision->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);
                            }
                            //Cesantias
                            if ($arProvision->getVrCesantias() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 1, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);                                    
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrCesantias());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 2, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrCesantias());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                            }

                            //Cesantias Intereses
                            if ($arProvision->getVrInteresesCesantias() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 3, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrInteresesCesantias());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 4, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrInteresesCesantias());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                            }

                            //Prima
                            if ($arProvision->getVrPrimas() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 5, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrPrimas());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 6, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrPrimas());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                            }

                            //Vacaciones
                            if ($arProvision->getVrVacaciones() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 7, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrVacaciones());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 8, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrVacaciones());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                            }

                            //Indemnizaciones
                            if ($arProvision->getVrIndemnizacion() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 9, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrIndemnizacion());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 10, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if ($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {                                                                            
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                    }                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getNumero());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrIndemnizacion());
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                    break 1;
                                }
                            }

                            //Pension
                            if ($arProvision->getVrPension() > 0) {
                                $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadPensionRel()->getNit()));
                                if ($arTerceroPension) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 15, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrPension());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 16, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrPension());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de pension " . $arProvision->getContratoRel()->getEntidadPensionRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadPensionRel()->getNit());
                                    break 1;
                                }
                            }

                            //Salud
                            if ($arProvision->getVrSalud() > 0) {
                                $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadSaludRel()->getNit()));
                                if ($arTerceroSalud) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 13, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrSalud());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 14, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrSalud());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de salud " . $arProvision->getContratoRel()->getEntidadSaludRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadSaludRel()->getNit());
                                    break 1;
                                }
                            }

                            //Riesgos
                            if ($arProvision->getVrRiesgos() > 0) {
                                $arEntidadRiesgos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                                if ($arConfiguracionAporte->getCodigoEntidadRiesgosProfesionales()) {
                                    $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->findOneBy(array('codigoInterface' => $arConfiguracionAporte->getCodigoEntidadRiesgosProfesionales()));
                                }
                                $arTerceroRiesgos = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadRiesgos->getNit()));
                                if ($arTerceroRiesgos) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 11, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrRiesgos());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 12, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrRiesgos());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de riesgos " . $arEntidadRiesgos->getNombre() . " Nit: " . $arEntidadRiesgos->getNit());
                                    break 1;
                                }
                            }

                            //Caja
                            if ($arProvision->getVrCaja() > 0) {
                                $arTerceroCaja = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadCajaRel()->getNit()));
                                if ($arTerceroCaja) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 17, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrCaja());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 18, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrCaja());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de caja " . $arProvision->getContratoRel()->getEntidadCajaRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadCajaRel()->getNit());
                                    break 1;
                                }
                            }

                            //Sena
                            if ($arProvision->getVrSena() > 0) {
                                $arTerceroSena = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arConfiguracion->getNitSena()));
                                if ($arTerceroSena) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 19, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSena);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrSena());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION SENA');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 20, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }                                        
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSena);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrSena());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION SENA');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad sena ");
                                    break 1;
                                }
                            }

                            //Icbf
                            if ($arProvision->getVrIcbf() > 0) {
                                $arTerceroIcbf = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arConfiguracion->getNitIcbf()));
                                if ($arTerceroIcbf) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 21, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroIcbf);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrIcbf());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION ICBF');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 22, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if ($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        if($arCuenta->getExigeCentroCostos()) {                                                                            
                                            $arRegistro->setCentroCostoRel($arCentroCosto);                                    
                                        }
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroIcbf);
                                        $arRegistro->setNumero($arProvision->getNumero());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrIcbf());
                                        $arRegistro->setSucursalRel($arSucursal);
                                        $arRegistro->setCodigoAreaFk($area);                                        
                                        $arRegistro->setDescripcionContable('PROVISION ICBF');
                                        $em->persist($arRegistro);
                                    } else {
                                        $errorDatos = true;
                                        $objMensaje->Mensaje("error", "La cuenta " . $arConfiguracionProvision->getCodigoCuentaFk() . " no existe en el plan de cuentas");
                                        break 1;
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad icbf ");
                                    break 1;
                                }
                            }

                            $arProvision->setEstadoContabilizado(1);
                            if ($errorDatos == false) {
                                $em->persist($arProvision);
                            }
                        }
                    }
                    if ($errorDatos == false) {
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_provision'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->formularioLista();
                $this->listar();
            }            
        }        
        $arProvisiones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:provision.html.twig', array(
                    'arProvisiones' => $arProvisiones,
                    'arComprobante' => $arComprobanteContable,
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
                $session->set('filtroRhuIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }        
        $form = $this->createFormBuilder()
                ->add('TxtAnio', TextType::class, array('data' => $session->get('filtroRhuAnio')))
                ->add('TxtMes', TextType::class, array('data' => $session->get('filtroRhuMes')))
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroRhuIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))                
                ->add('BtnContabilizar', SubmitType::class, array('label' => 'Contabilizar',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->pendientesContabilizarDql(
                $session->get('filtroRhuAnio'),
                $session->get('filtroRhuMes'),
                $session->get('filtroRhuCodigoEmpleado'));
    }



    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');        
        $session->set('filtroRhuIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuAnio', $form->get('TxtAnio')->getData());
        $session->set('filtroRhuMes', $form->get('TxtMes')->getData());
    }
 
    
    /**
     * @Route("/rhu/proceso/descontabilizar/provision/", name="brs_rhu_proceso_descontabilizar_provision")
     */
    public function descontabilizarProvisionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()                
                ->add('numeroDesde', NumberType::class, array('label' => 'Numero desde'))
                ->add('numeroHasta', NumberType::class, array('label' => 'Numero hasta'))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('BtnDescontabilizar', SubmitType::class, array('label' => 'Descontabilizar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intNumeroDesde = $form->get('numeroDesde')->getData();
                $intNumeroHasta = $form->get('numeroHasta')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                if ($intNumeroDesde != "" || $intNumeroHasta != "" || $dateFechaDesde != "" || $dateFechaHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->contabilizadosDql($intNumeroDesde, $intNumeroHasta, $dateFechaDesde, $dateFechaHasta);
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->find($codigoRegistro);
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
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarProvision.html.twig', array(
                    'form' => $form->createView()));
    }    
    
}
