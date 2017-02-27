<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContabilizarPagoProvisionController extends Controller
{
    var $strDqlLista = "";

    /**
     * @Route("/rhu/proceso/contabilizar/provision", name="brs_rhu_proceso_contabilizar_provision")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 67)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if ($form->get('BtnContabilizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arConfiguracionAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionAporte();
                    $arConfiguracionAporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionAporte')->find(1);                    
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteProvision());                    
                    $errorDatos = false;
                    foreach ($arrSeleccionados AS $codigo) {
                        $arProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuProvision();
                        $arProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->find($codigo);
                        $tipoEmpleado = $arProvision->getEmpleadoRel()->getCodigoEmpleadoTipoFk();
                        $arCentroCosto = $arProvision->getEmpleadoRel()->getCentroCostoContabilidadRel();
                        //$arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
                        //$arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find(1);
                        if($arProvision->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getEmpleadoRel()->getNumeroIdentificacion()));
                            if(count($arTercero) <= 0) {
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
                            if($arProvision->getVrCesantias() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 1, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrCesantias());
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 2, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrCesantias());
                                    $arRegistro->setDescripcionContable('PROVISION CESANTIAS');
                                    $em->persist($arRegistro);
                                }
                            }

                            //Cesantias Intereses
                            if($arProvision->getVrInteresesCesantias() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 3, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrInteresesCesantias());
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 4, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrInteresesCesantias());
                                    $arRegistro->setDescripcionContable('PROVISION INTERESES CESANTIAS');
                                    $em->persist($arRegistro);
                                }
                            }

                            //Prima
                            if($arProvision->getVrPrimas() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 5, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrPrimas());
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 6, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrPrimas());
                                    $arRegistro->setDescripcionContable('PROVISION PRIMAS');
                                    $em->persist($arRegistro);
                                }
                            }

                            //Vacaciones
                            if($arProvision->getVrVacaciones() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 7, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrVacaciones());
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 8, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrVacaciones());
                                    $arRegistro->setDescripcionContable('PROVISION VACACIONES');
                                    $em->persist($arRegistro);
                                }
                            }

                            //Indemnizaciones
                            if($arProvision->getVrIndemnizacion() > 0) {
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 9, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setDebito($arProvision->getVrIndemnizacion());
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                }
                                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 10, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                    $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                    $arRegistro->setCredito($arProvision->getVrIndemnizacion());
                                    $arRegistro->setDescripcionContable('PROVISION INDEMNIZACION');
                                    $em->persist($arRegistro);
                                }
                            }

                            //Pension
                            if($arProvision->getVrPension() > 0) {
                                $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadPensionRel()->getNit()));
                                if($arTerceroPension) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 15, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrPension());
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 16, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroPension);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrPension());
                                        $arRegistro->setDescripcionContable('PROVISION PENSION');
                                        $em->persist($arRegistro);
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de pension " . $arProvision->getContratoRel()->getEntidadPensionRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadPensionRel()->getNit());
                                    break 1;
                                }
                            }

                            //Salud
                            if($arProvision->getVrSalud() > 0) {
                                $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadSaludRel()->getNit()));
                                if($arTerceroSalud) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 13, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrSalud());
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 14, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSalud);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrSalud());
                                        $arRegistro->setDescripcionContable('PROVISION SALUD');
                                        $em->persist($arRegistro);
                                    }
                                }  else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de salud " . $arProvision->getContratoRel()->getEntidadSaludRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadSaludRel()->getNit());
                                    break 1;
                                }
                            }

                            //Riesgos
                            if($arProvision->getVrRiesgos() > 0) {

                                $arEntidadRiesgos = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                                if($arConfiguracionAporte->getCodigoEntidadRiesgosProfesionales()) {
                                    $arEntidadRiesgos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->findOneBy(array('codigoInterface' => $arConfiguracionAporte->getCodigoEntidadRiesgosProfesionales()));
                                }                                
                                $arTerceroRiesgos = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arEntidadRiesgos->getNit()));
                                if($arTerceroRiesgos) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 11, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrRiesgos());
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 12, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroRiesgos);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrRiesgos());
                                        $arRegistro->setDescripcionContable('PROVISION RIESGOS');
                                        $em->persist($arRegistro);
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de riesgos " . $arEntidadRiesgos->getNombre() . " Nit: " . $arEntidadRiesgos->getNit());
                                    break 1;
                                }
                            }

                            //Caja
                            if($arProvision->getVrCaja() > 0) {
                                $arTerceroCaja = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arProvision->getContratoRel()->getEntidadCajaRel()->getNit()));
                                if($arTerceroCaja) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 17, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrCaja());
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 18, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroCaja);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrCaja());
                                        $arRegistro->setDescripcionContable('PROVISION CAJA');
                                        $em->persist($arRegistro);
                                    }
                                }  else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad de caja " . $arProvision->getContratoRel()->getEntidadCajaRel()->getNombre() . " Nit: " . $arProvision->getContratoRel()->getEntidadCajaRel()->getNit());
                                    break 1;
                                }
                            }

                            //Sena
                            if($arProvision->getVrSena() > 0) {
                                $arTerceroSena = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arConfiguracion->getNitSena()));
                                if($arTerceroSena) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 19, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSena);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrSena());
                                        $arRegistro->setDescripcionContable('PROVISION SENA');
                                        $em->persist($arRegistro);
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 20, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroSena);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrSena());
                                        $arRegistro->setDescripcionContable('PROVISION SENA');
                                        $em->persist($arRegistro);
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad sena ");
                                    break 1;
                                }
                            }

                            //Icbf
                            if($arProvision->getVrIcbf() > 0) {
                                $arTerceroIcbf = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arConfiguracion->getNitIcbf()));
                                if($arTerceroIcbf) {
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 21, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroIcbf);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setDebito($arProvision->getVrIcbf());
                                        $arRegistro->setDescripcionContable('PROVISION ICBF');
                                        $em->persist($arRegistro);
                                    }
                                    $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findOneBy(array('tipo' => 22, 'codigoEmpleadoTipoFk' => $tipoEmpleado));
                                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arConfiguracionProvision->getCodigoCuentaFk());
                                    if($arCuenta) {
                                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                                        $arRegistro->setComprobanteRel($arComprobanteContable);
                                        $arRegistro->setCentroCostoRel($arCentroCosto);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setTerceroRel($arTerceroIcbf);
                                        $arRegistro->setNumero($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setNumeroReferencia($arProvision->getCodigoProvisionPeriodoFk());
                                        $arRegistro->setFecha($arProvision->getProvisionPeriodoRel()->getFechaHasta());
                                        $arRegistro->setCredito($arProvision->getVrIcbf());
                                        $arRegistro->setDescripcionContable('PROVISION ICBF');
                                        $em->persist($arRegistro);
                                    }
                                } else {
                                    $errorDatos = true;
                                    $objMensaje->Mensaje("error", "El empleado (" . $arProvision->getEmpleadoRel()->getNombreCorto() . ") con identificacion: " . $arProvision->getEmpleadoRel()->getNumeroIdentificacion() . ", en terceros de contabilidad no existe la entidad icbf ");
                                    break 1;
                                }
                            }

                            $arProvision->setEstadoContabilizado(1);
                            if($errorDatos == false) {
                                $em->persist($arProvision);
                            }
                        }
                    }
                    if($errorDatos == false) {
                        $em->flush();
                    }

                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_provision'));
            }

        }
        $arProvisiones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:provision.html.twig', array(
            'arProvisiones' => $arProvisiones,
            'form' => $form->createView()));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->add('BtnContabilizar', SubmitType::class, array('label'  => 'Contabilizar',))
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->pendientesContabilizarDql();
    }    

}
