<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContabilizarPagoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/proceso/contabilizar/pago/", name="brs_rhu_proceso_contabilizar_pago")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 66)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            if ($form->get('BtnContabilizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    $respuesta = '';
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion;
                    $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobantePagoNomina());
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigo);
                        if ($arPago->getEstadoContabilizado() == 0) {
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                            if (count($arTercero) <= 0) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arPago->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arPago->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arPago->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arPago->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arPago->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arPago->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arPago->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arPago->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arPago->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arPago->getEmpleadoRel()->getCorreo());
                                $arTercero->setArea($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getInterfaz());
                                $arTercero->setSucursalRel($arPago->getEmpleadoRel()->getSucursalRel());
                                $em->persist($arTercero);
                            }
                            $respuesta = $this->contabilizarPagoNomina($codigo, $arComprobanteContable, $arPago->getEmpleadoRel()->getCentroCostoContabilidadRel(), $arTercero, $arPago, $arConfiguracionNomina);
                            if ($respuesta != '') {
                                break;
                            }
                            $arPago->setEstadoContabilizado(1);
                            $em->persist($arPago);
                        }
                    }
                    if ($respuesta == '') {
                        $em->flush();
                    } else {
                        $objMensaje->Mensaje('error', $respuesta);
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_pago'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }
        }

        $arPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:pago.html.twig', array(
                    'arPagos' => $arPagos,
                    'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->pendientesContabilizarDql(
                $session->get('filtroPagoNumero'), 
                $session->get('filtroCodigoCentroCosto'), 
                $session->get('filtroIdentificacion'), 
                $session->get('filtroDesde'), 
                $session->get('filtroHasta')
        );
    }

    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');
        $codigoCentroCosto = '';
        if ($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroPagoNumero', $form->get('TxtNumero')->getData());
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

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $arrayPropiedadesCentroCosto = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
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
                ->add('centroCostoRel', EntityType::class, $arrayPropiedadesCentroCosto)
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('TxtNumero', TextType::class, array('label' => 'Numero', 'data' => $session->get('filtroPagoNumero')))
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('BtnContabilizar', SubmitType::class, array('label' => 'Contabilizar',))
                ->getForm();
        return $form;
    }

    private function contabilizarPagoNomina($codigo, $arComprobanteContable, $arCentroCosto, $arTercero, $arPago, $arConfiguracionNomina) {
        $em = $this->getDoctrine()->getManager();
        $respuesta = "";
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigo));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            //if ($arPagoDetalle->getVrPago() > 0) {
                $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                $arPagoConceptoCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConceptoCuenta();
                $arPagoConceptoCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConceptoCuenta')->findOneBy(array('codigoPagoConceptoFk' => $arPagoDetalle->getCodigoPagoConceptoFk(), 'codigoEmpleadoTipoFk' => $arPago->getEmpleadoRel()->getCodigoEmpleadoTipoFk()));
                if ($arPagoConceptoCuenta) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPagoConceptoCuenta->getCodigoCuentaFk());
                    if ($arCuenta) {
                        $arRegistro->setComprobanteRel($arComprobanteContable);
                        if ($arCuenta->getExigeCentroCostos() == 1) {
                            $arRegistro->setCentroCostoRel($arCentroCosto);
                        }
                        $arRegistro->setCuentaRel($arCuenta);
                        $arRegistro->setTerceroRel($arTercero);
                        // Cuando el detalle es de salud y pension se lleva al nit de la entidad
                        if ($arCuenta->getExigeNit() == 1) {
                            if ($arPagoDetalle->getSalud()) {
                                $arTerceroSalud = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadSaludRel()->getNit()));
                                if ($arTerceroSalud) {
                                    $arRegistro->setTerceroRel($arTerceroSalud);
                                } else {
                                    $respuesta = "La entidad de salud " . $arPago->getEntidadSaludRel()->getNit() . "-" . $arPago->getEntidadSaludRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                    break;
                                }
                            }
                            if ($arPagoDetalle->getPension()) {
                                $arTerceroPension = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arPago->getEntidadPensionRel()->getNit()));
                                if ($arTerceroPension) {
                                    $arRegistro->setTerceroRel($arTerceroPension);
                                } else {
                                    $respuesta = "La entidad de pension " . $arPago->getEntidadPensionRel()->getNombre() . " del pago " . $arPago->getNumero() . " no existe en contabilidad";
                                    break;
                                }
                            }
                        }

                        $arRegistro->setNumero($arPago->getNumero());
                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                        $arRegistro->setFecha($arPago->getFechaHasta());
                        if ($arPagoConceptoCuenta->getTipoCuenta() == 1) {
                            $arRegistro->setDebito($arPagoDetalle->getVrPago());
                        } else {
                            $arRegistro->setCredito($arPagoDetalle->getVrPago());
                        }
                        $arRegistro->setDescripcionContable($arPagoDetalle->getPagoConceptoRel()->getNombre());                        
                        $arRegistro->setSucursalRel($arPago->getEmpleadoRel()->getSucursalRel());
                        $arRegistro->setCodigoAreaFk($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getInterfaz());
                        $em->persist($arRegistro);
                    } else {
                        $respuesta = "La cuenta " . $arPagoConceptoCuenta->getCodigoCuentaFk() . " no existe en el plan de cuentas";
                        break;
                    }
                } else {
                    $respuesta = "El concepto de pago " . $arPagoDetalle->getCodigoPagoConceptoFk() . " no tiene cuenta para el tipo de empleado " . $arPago->getEmpleadoRel()->getCodigoEmpleadoTipoFk();
                    break;
                }
            //}
        }
        if ($respuesta == '') {
            //Nomina por pagar                       
            if ($arPago->getVrNeto() > 0) {
                if ($arPago->getPagoTipoRel()->getCodigoCuentaFk()) {
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arPago->getPagoTipoRel()->getCodigoCuentaFk()); //estaba 250501                           
                    if ($arCuenta) {
                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                        $arRegistro->setComprobanteRel($arComprobanteContable);
                        //$arRegistro->setCentroCostoRel($arCentroCosto);
                        $arRegistro->setCuentaRel($arCuenta);
                        $arRegistro->setTerceroRel($arTercero);
                        $arRegistro->setNumero($arPago->getNumero());
                        $arRegistro->setNumeroReferencia($arPago->getNumero());
                        $arRegistro->setFecha($arPago->getFechaHasta());
                        $arRegistro->setCredito($arPago->getVrNeto());
                        $arRegistro->setDescripcionContable('NOMINA POR PAGAR');
                        $arRegistro->setSucursalRel($arPago->getEmpleadoRel()->getSucursalRel());
                        $arRegistro->setCodigoAreaFk($arPago->getEmpleadoRel()->getEmpleadoTipoRel()->getInterfaz());                        
                        $em->persist($arRegistro);
                    }
                }
            }
        }
        return $respuesta;
    }

    /**
     * @Route("/rhu/proceso/descontabilizar/pago/", name="brs_rhu_proceso_descontabilizar_pago")
     */
    public function descontabilizarPagoNominaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arrayPropiedadesTipo = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoPagoTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoTipo", $session->get('filtroCodigoPagoTipo'));
        }
        $form = $this->createFormBuilder()
                ->add('pagoTipoRel', EntityType::class, $arrayPropiedadesTipo)
                ->add('numeroDesde', NumberType::class, array('label' => 'Numero desde'))
                ->add('numeroHasta', NumberType::class, array('label' => 'Numero hasta'))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('BtnDescontabilizar', SubmitType::class, array('label' => 'Descontabilizar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $codigoPagoTipo = '';
                if ($form->get('pagoTipoRel')->getData()) {
                    $codigoPagoTipo = $form->get('pagoTipoRel')->getData()->getCodigoPagoTipoPk();
                }
                $intNumeroDesde = $form->get('numeroDesde')->getData();
                $intNumeroHasta = $form->get('numeroHasta')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                if ($intNumeroDesde != "" || $intNumeroHasta != "" || $dateFechaDesde != "" || $dateFechaHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->contabilizadosPagoNominaDql($intNumeroDesde, $intNumeroHasta, $dateFechaDesde, $dateFechaHasta, $codigoPagoTipo);
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoRegistro);
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
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarPagoNomina.html.twig', array(
                    'form' => $form->createView()));
    }

}
