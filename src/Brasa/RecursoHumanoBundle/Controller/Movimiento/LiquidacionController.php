<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLiquidacionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LiquidacionController extends Controller {

    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/liquidacion/", name="brs_rhu_movimiento_liquidacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 9, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnLiquidar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigo);
                        if ($arLiquidacion->getEstadoPagoGenerado() == 0) {
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigo);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion'));
                }
            }

            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioLista();
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }
        }

        $arLiquidaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:lista.html.twig', array('arLiquidaciones' => $arLiquidaciones, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/nuevo/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoLiquidacion = 0) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        if ($codigoLiquidacion != 0) {
            $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        }
        $form = $this->createForm(RhuLiquidacionType::class, $arLiquidacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();

            if ($form->get('guardar')->isClicked()) {
                $arLiquidacion = $form->getData();
                $em->persist($arLiquidacion);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:nuevo.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle")
     */
    public function detalleAction(Request $request, $codigoLiquidacion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $form = $this->formularioDetalle($arLiquidacion);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('BtnImprimir')->isClicked()) {
                if ($arLiquidacion->getEstadoGenerado() == 1) {
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $codigoFormato = $arConfiguracion->getCodigoFormatoLiquidacion();
                    if ($codigoFormato <= 1) {
                        $objFormatoLiquidacion = new \Brasa\RecursoHumanoBundle\Formatos\Liquidacion1();
                        $objFormatoLiquidacion->Generar($em, $codigoLiquidacion);
                    }
                    if ($codigoFormato == 2) {
                        $objFormatoLiquidacion = new \Brasa\RecursoHumanoBundle\Formatos\Liquidacion2();
                        $objFormatoLiquidacion->Generar($em, $codigoLiquidacion);
                    }
                }
            }
            if ($form->get('BtnImprimirCartaRetiro')->isClicked()) {
                $objFormatoCartaRetiro = new \Brasa\RecursoHumanoBundle\Formatos\CartaRetiro();
                $objFormatoCartaRetiro->Generar($em, $arLiquidacion->getCodigoContratoFk());
            }
            if ($form->get('BtnImprimirCartaExamenEgreso')->isClicked()) {
                $objFormatoCartaExamenEgreso = new \Brasa\RecursoHumanoBundle\Formatos\CartaExamenEgreso();
                $objFormatoCartaExamenEgreso->Generar($em, $arLiquidacion->getCodigoContratoFk());
            }
            if ($form->get('BtnAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0) {
                    if ($arLiquidacion->getVrTotal() >= 0) {
                        $arDotacionPendiente = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->dotacionDevolucion($arLiquidacion->getCodigoEmpleadoFk());
                        $registrosDotacionesPendientes = count($arDotacionPendiente);
                        if ($registrosDotacionesPendientes > 0) {
                            $objMensaje->Mensaje("error", "El empleado tiene dotaciones pendientes por entregar, no se puede autorizar la liquidación");
                        } else {
                            if ($arLiquidacion->getEstadoGenerado() == 0) {
                                $objMensaje->Mensaje("error", "La liquidacion debe ser liquidada antes de autorizar");
                            } else {
                                $arLiquidacion->setEstadoAutorizado(1);
                                $em->persist($arLiquidacion);
                                $em->flush();
                                return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                            }
                        }
                    } else {
                        $objMensaje->Mensaje('error', "El total de la liquidacion no puede ser negativo");
                    }
                }
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 1) {
                    $arLiquidacion->setEstadoAutorizado(0);
                    $em->persist($arLiquidacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                }
            }

            if ($form->get('BtnAnular')->isclicked()) {
                $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->anular($codigoLiquidacion);
                if ($strRespuesta != "") {
                    $objMensaje->Mensaje('error', $strRespuesta);
                }
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
            }

            if ($form->get('BtnLiquidar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0) {
                    $respuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                    if ($respuesta == "") {
                        $em->flush();
                    } else {
                        $objMensaje->Mensaje("error", $respuesta);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                } else {
                    $objMensaje->Mensaje("error", "No puede reliquidar una liquidacion autorizada");
                }
            }

            if ($form->get('BtnGenerarPago')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 1) {
                    $respuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->pagar($codigoLiquidacion);
                    if ($respuesta == '') {
                        $numero = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->consecutivo(3);
                        $arLiquidacion->setNumero($numero);
                        $arLiquidacion->setFecha(new \DateTime('now'));
                        $arLiquidacion->setEstadoPagoGenerado(1);
                        $em->persist($arLiquidacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                    } else {
                        $objMensaje->Mensaje("error", $respuesta);
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                    }
                } else {
                    $objMensaje->Mensaje("error", "No esta autorizado, no se puede generar pago");
                }
            }

            if ($form->get('BtnEliminarAdicional')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoLiquidacionAdicional) {
                            $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                            $arLiquidacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->find($codigoLiquidacionAdicional);
                            $em->remove($arLiquidacionAdicional);
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
                }
            }
        }
        $arLiquidacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $arLiquidacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->FindBy(array('codigoLiquidacionFk' => $codigoLiquidacion));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalle.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'arLiquidacionAdicionales' => $arLiquidacionAdicionales,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/adicional/{codigoLiquidacion}/{codigoLiquidacionAdicional}/{tipo}/", name="brs_rhu_movimiento_liquidacion_adicional")
     */
    public function detalleAdicionalAction(Request $request, $codigoLiquidacion, $codigoLiquidacionAdicional, $tipo) {

        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
        $valor = 0;
        $arrayPropiedadesPagoConcepto = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) use($tipo) {
                return $er->createQueryBuilder('pc')
                                ->where('pc.tipoAdicional = :tipoAdicional')
                                ->setParameter('tipoAdicional', $tipo)
                                ->orderBy('pc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => true,
            'data' => ""
        );
        if ($codigoLiquidacionAdicional != 0) {
            $arLiquidacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionales')->find($codigoLiquidacionAdicional);
            if ($tipo == 1) {
                $valor = $arLiquidacionAdicional->getVrBonificacion();
            }
            if ($tipo == 2) {
                $valor = $arLiquidacionAdicional->getVrDeduccion();
            }
            $arrayPropiedadesPagoConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arLiquidacionAdicional->getCodigoPagoConceptoFk());
        }

        $form = $this->createFormBuilder()
                ->add('pagoConceptoRel', EntityType::class, $arrayPropiedadesPagoConcepto)
                ->add('TxtValor', NumberType::class, array('required' => true, 'data' => $valor))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                $arLiquidacionAdicional->setPagoConceptoRel($arPagoConcepto);
                if ($tipo == 1) {
                    $arLiquidacionAdicional->setVrBonificacion($form->get('TxtValor')->getData());
                }
                if ($tipo == 2) {
                    $arLiquidacionAdicional->setVrDeduccion($form->get('TxtValor')->getData());
                }
                $em->persist($arLiquidacionAdicional);
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($codigoLiquidacion);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleAdicionalNuevo.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/detalle/credito/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_detalle_credito")
     */
    public function detalleCreditoAction(Request $request, $codigoLiquidacion) {

        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arLiquidacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $valor = 0;
                        if ($arrControles['TxtValor' . $codigoCredito] != '') {
                            $valor = $arrControles['TxtValor' . $codigoCredito];
                        }
                        $arLiquidacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales();
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setVrDeduccion($valor);
                        $arLiquidacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());
                        $arLiquidacionAdicional->setCreditoRel($arCredito);
                        $em->persist($arLiquidacionAdicional);
                        $floVrDeducciones += $valor;
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->liquidar($arLiquidacion->getCodigoLiquidacionPk());
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:detalleCreditoNuevo.html.twig', array(
                    'arCreditos' => $arCreditos,
                    'arLiquidacion' => $arLiquidacion,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/liquidacion/parametros/{codigoLiquidacion}", name="brs_rhu_movimiento_liquidacion_parametros")
     */
    public function parametrosAction(Request $request, $codigoLiquidacion) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arLiquidacion = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->find($codigoLiquidacion);
        $form = $this->createFormBuilder()
                ->setAction($this->generateUrl('brs_rhu_movimiento_liquidacion_parametros', array('codigoLiquidacion' => $codigoLiquidacion)))
                ->add('porcentajeIbp', NumberType::class, array('data' => $arLiquidacion->getPorcentajeIbp(), 'required' => false))
                ->add('liquidarSalario', CheckboxType::class, array('required' => false, 'data' => $arLiquidacion->getLiquidarSalario()))
                ->add('vrIndemnizacion', NumberType::class, array('data' => $arLiquidacion->getVrIndemnizacion(), 'required' => false))
                ->add('diasAusentismoAdicional', NumberType::class, array('data' => $arLiquidacion->getDiasAusentismoAdicional(), 'required' => false))
                ->add('diasAusentismoPropuesto', NumberType::class, array('data' => $arLiquidacion->getDiasAusentismoPropuesto(), 'required' => false))
                ->add('vrSalarioVacacionPropuesto', NumberType::class, array('data' => $arLiquidacion->getVrSalarioVacacionPropuesto(), 'required' => false))
                ->add('vrSalarioPrimaPropuesto', NumberType::class, array('data' => $arLiquidacion->getVrSalarioPrimaPropuesto(), 'required' => false))
                ->add('vrSalarioCesantiasPropuesto', NumberType::class, array('data' => $arLiquidacion->getVrSalarioCesantiasPropuesto(), 'required' => false))
                ->add('eliminarAusentismo', CheckboxType::class, array('required' => false, 'data' => $arLiquidacion->getEliminarAusentismo()))
                ->add('omitirCesantiasAnterior', CheckboxType::class, array('required' => false, 'data' => $arLiquidacion->getOmitirCesantiasAnterior()))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $porcentajeIbp = $form->get('porcentajeIbp')->getData();
            $vrIndemnizacion = $form->get('vrIndemnizacion')->getData();
            $diasAusentismoAdicional = $form->get('diasAusentismoAdicional')->getData();
            $diasAusentismoPropuesto = $form->get('diasAusentismoPropuesto')->getData();
            $liquidarSalario = $form->get('liquidarSalario')->getData();
            $vrSalarioVacacionPropuesto = $form->get('vrSalarioVacacionPropuesto')->getData();
            $vrSalarioPrimaPropuesto = $form->get('vrSalarioPrimaPropuesto')->getData();
            $vrSalarioCesantiasPropuesto = $form->get('vrSalarioCesantiasPropuesto')->getData();
            $eliminarAusentismo = $form->get('eliminarAusentismo')->getData();
            $omitirCesantiasAnterior = $form->get('omitirCesantiasAnterior')->getData();
            $arLiquidacion->setPorcentajeIbp($porcentajeIbp);
            $arLiquidacion->setLiquidarSalario($liquidarSalario);
            $arLiquidacion->setVrIndemnizacion($vrIndemnizacion);
            $arLiquidacion->setDiasAusentismoAdicional($diasAusentismoAdicional);
            $arLiquidacion->setDiasAusentismoPropuesto($diasAusentismoPropuesto);
            $arLiquidacion->setVrSalarioVacacionPropuesto($vrSalarioVacacionPropuesto);
            $arLiquidacion->setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto);
            $arLiquidacion->setVrSalarioCesantiasPropuesto($vrSalarioCesantiasPropuesto);
            $arLiquidacion->setEliminarAusentismo($eliminarAusentismo);
            $arLiquidacion->setOmitirCesantiasAnterior($omitirCesantiasAnterior);
            $em->persist($arLiquidacion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_movimiento_liquidacion_detalle', array('codigoLiquidacion' => $codigoLiquidacion)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Liquidaciones:parametros.html.twig', array(
                    'arLiquidacion' => $arLiquidacion,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroRhuLiquidacionFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroRhuLiquidacionFechaDesde');
            $strFechaHasta = $session->get('filtroRhuLiquidacionFechaHasta');
        }
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->listaDql(
                $session->get('filtroIdentificacion'), $session->get('filtroGenerado'), $session->get('filtroCodigoCentroCosto'), $session->get('filtroPagado'), $strFechaDesde, $strFechaHasta,$session->get('filtroNumero'));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroRhuLiquidacionFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroRhuLiquidacionFechaDesde');
        }
        if ($session->get('filtroRhuLiquidacionFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroRhuLiquidacionFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('numero', NumberType::class, array('data' => $session->get('filtroNumero')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroRhuLiquidacionFiltrarFecha')))
                ->add('centroCostoRel', EntityType::class, $arrayPropiedadesCentroCosto)
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('estadoGenerado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'), 'data' => $session->get('filtroGenerado')))
                ->add('estadoPagado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'), 'data' => $session->get('filtroPagado')))
                ->add('BtnLiquidar', SubmitType::class, array('label' => 'Liquidar'))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonEliminarAdicional = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonImprimirCartaRetiro = array('label' => 'Carta retiro', 'disabled' => false);
        $arrBotonImprimirCartaExamenEgreso = array('label' => 'Carta examen egreso', 'disabled' => false);
        $arrBotonImprimirCartaPazysalvo = array('label' => 'Carta paz y salvo', 'disabled' => false);
        $arrBotonLiquidar = array('label' => 'Liquidar', 'disabled' => false);
        $arrBotonGenerarPago = array('label' => 'Generar pago', 'disabled' => false);
        if ($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarAdicional['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
            if ($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
                $arrBotonGenerarPago['disabled'] = true;
            }
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
        }
        if ($ar->getEstadoPagoGenerado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                ->add('BtnAnular', SubmitType::class, $arrBotonAnular)
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnImprimirCartaRetiro', SubmitType::class, $arrBotonImprimirCartaRetiro)
                ->add('BtnImprimirCartaExamenEgreso', SubmitType::class, $arrBotonImprimirCartaExamenEgreso)
                ->add('BtnImprimirCartaPazySalvo', SubmitType::class, $arrBotonImprimirCartaPazysalvo)
                ->add('BtnLiquidar', SubmitType::class, $arrBotonLiquidar)
                ->add('BtnEliminarAdicional', SubmitType::class, $arrBotonEliminarAdicional)
                ->add('BtnGenerarPago', SubmitType::class, $arrBotonGenerarPago)
                ->getForm();
        return $form;
    }

    private function filtrar($form) {
        $session = new session;
        $codigoCentroCosto = '';
        if ($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroGenerado', $form->get('estadoGenerado')->getData());
        $session->set('filtroPagado', $form->get('estadoPagado')->getData());
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroRhuLiquidacionFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroRhuLiquidacionFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroRhuLiquidacionFiltrarFecha', $form->get('filtrarFecha')->getData());
        $session->set('filtroNumero', $form->get('numero')->getData());
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        for ($col = 'J'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        for ($col = 'W'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'COD EMPLEADO')
                ->setCellValue('D1', 'DOCUMENTO')
                ->setCellValue('E1', 'EMPLEADO')
                ->setCellValue('F1', 'CENTRO COSTO')
                ->setCellValue('G1', 'CONTRATO')
                ->setCellValue('H1', 'DESDE')
                ->setCellValue('I1', 'HASTA')
                ->setCellValue('J1', 'AUX.TTE')
                ->setCellValue('K1', 'CESANTIAS')
                ->setCellValue('L1', 'INTERESES')
                ->setCellValue('M1', 'PRIMA')
                ->setCellValue('N1', 'DED.PRIMA')
                ->setCellValue('O1', 'VACACIONES')
                ->setCellValue('P1', 'INDEMNIZACION')
                ->setCellValue('Q1', 'D.CES')
                ->setCellValue('R1', 'D.VAC')
                ->setCellValue('S1', 'D.PRI')
                ->setCellValue('T1', 'F.ULT.PAGO')
                ->setCellValue('U1', 'F.ULT.PAGO.PRI')
                ->setCellValue('V1', 'F.ULT.PAGO.VAC')
                ->setCellValue('W1', 'F.ULT.PAGO.CES')
                ->setCellValue('X1', 'DEDUCCIONES')
                ->setCellValue('Y1', 'BONIFICACIONES')
                ->setCellValue('Z1', 'TOTAL');
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arLiquidaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion();
        $arLiquidaciones = $query->getResult();
        foreach ($arLiquidaciones as $arLiquidacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLiquidacion->getCodigoLiquidacionPk())
                    ->setCellValue('B' . $i, $arLiquidacion->getNumero())
                    ->setCellValue('C' . $i, $arLiquidacion->getCodigoEmpleadoFk())
                    ->setCellValue('D' . $i, $arLiquidacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arLiquidacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arLiquidacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arLiquidacion->getCodigoContratoFk())
                    ->setCellValue('H' . $i, $arLiquidacion->getFechaDesde()->format('d/m/Y'))
                    ->setCellValue('I' . $i, $arLiquidacion->getFechaHasta()->format('d/m/Y'))
                    ->setCellValue('J' . $i, $arLiquidacion->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arLiquidacion->getVrCesantias())
                    ->setCellValue('L' . $i, $arLiquidacion->getVrInteresesCesantias())
                    ->setCellValue('M' . $i, $arLiquidacion->getVrPrima())
                    ->setCellValue('N' . $i, $arLiquidacion->getVrDeduccionPrima())
                    ->setCellValue('O' . $i, $arLiquidacion->getVrVacaciones())
                    ->setCellValue('P' . $i, $arLiquidacion->getVrIndemnizacion())
                    ->setCellValue('Q' . $i, $arLiquidacion->getDiasCesantias())
                    ->setCellValue('R' . $i, $arLiquidacion->getDiasVacaciones())
                    ->setCellValue('S' . $i, $arLiquidacion->getDiasPrimas())
                    ->setCellValue('X' . $i, $arLiquidacion->getVrDeducciones())
                    ->setCellValue('Y' . $i, $arLiquidacion->getVrBonificaciones())
                    ->setCellValue('Z' . $i, $arLiquidacion->getVrTotal());
            if ($arLiquidacion->getFechaUltimoPago()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $i, $arLiquidacion->getFechaUltimoPago()->format('d/m/Y'));
            }
            if ($arLiquidacion->getFechaUltimoPagoPrimas()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $i, $arLiquidacion->getFechaUltimoPagoPrimas()->format('d/m/Y'));
            }
            if ($arLiquidacion->getFechaUltimoPagoVacaciones()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $i, $arLiquidacion->getFechaUltimoPagoVacaciones()->format('d/m/Y'));
            }
            if ($arLiquidacion->getFechaUltimoPagoCesantias()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $i, $arLiquidacion->getFechaUltimoPagoCesantias()->format('d/m/Y'));
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Liquidaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Liquidaciones.xlsx"');
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
