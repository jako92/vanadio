<?php

namespace Brasa\RecursoHumanoBundle\Controller\General;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller {

    /**
     * @Route("/rhu/configuracion/{codigoConfiguracionPk}", name="brs_rhu_configuracion_nomina")
     */
    public function configuracionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 92)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->findAll();
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findAll();
        $arConfiguracionProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision();
        $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->findAll();
        $arrayPropiedadesConceptoAuxilioTransporte = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoAuxilioTransporte['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoAuxilioTransporte());

        $arrayPropiedadesConceptoCredito = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoCredito['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoCredito());

        $arrayPropiedadesConceptoSeguro = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoSeguro['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoSeguro());

        $arrayPropiedadesConceptoHoraDiurnaTrabajada = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoHoraDiurnaTrabajada['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoHoraDiurnaTrabajada());

        /* $arrayPropiedadesConceptoRiesgoProfesional = array(
          'class' => 'BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional',
          'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('rp')
          ->orderBy('rp.codigoEntidadRiesgoPk', 'ASC');},
          'choice_label' => 'nombre',
          'required' => false);
          $arrayPropiedadesConceptoRiesgoProfesional['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional", $arConfiguracion->getCodigoEntidadRiesgoFk());
         */
        $arrayPropiedadesConceptoIncapacidad = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoIncapacidad['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoIncapacidad());

        $arrayPropiedadesConceptoRetencionFuente = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoRetencionFuente['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoRetencionFuente());

        $arrayPropiedadesConceptoEntidadExamenIngreso = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ee')
                                ->orderBy('ee.codigoEntidadExamenPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoEntidadExamenIngreso['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadExamen", $arConfiguracion->getCodigoEntidadExamenIngreso());

        $arrayPropiedadesConceptoEntidadComprobanteNomina = array(
            'class' => 'BrasaContabilidadBundle:CtbComprobante',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.codigoComprobantePk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoEntidadComprobanteNomina['data'] = $em->getReference("BrasaContabilidadBundle:CtbComprobante", $arConfiguracion->getCodigoComprobantePagoNomina());

        $arrayPropiedadesConceptoEntidadComprobanteBanco = array(
            'class' => 'BrasaContabilidadBundle:CtbComprobante',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.codigoComprobantePk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoEntidadComprobanteBanco['data'] = $em->getReference("BrasaContabilidadBundle:CtbComprobante", $arConfiguracion->getCodigoComprobantePagoBanco());
        if ($arConfiguracion->getControlPago() == 1) {
            $srtControlPago = "SI";
        } else {
            $srtControlPago = "NO";
        }
        $arrayPropiedadesConceptoVacacion = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.codigoPagoConceptoPk', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false);
        $arrayPropiedadesConceptoVacacion['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $arConfiguracion->getCodigoVacacion());

        $formConfiguracion = $this->createFormBuilder()
                ->add('conceptoAuxilioTransporte', EntityType::class, $arrayPropiedadesConceptoAuxilioTransporte)
                ->add('vrAuxilioTransporte', NumberType::class, array('data' => $arConfiguracion->getVrAuxilioTransporte(), 'required' => true))
                ->add('vrSalario', NumberType::class, array('data' => $arConfiguracion->getVrSalario(), 'required' => true))
                ->add('conceptoCredito', EntityType::class, $arrayPropiedadesConceptoCredito, array('required' => true))
                ->add('conceptoSeguro', EntityType::class, $arrayPropiedadesConceptoSeguro, array('required' => true))
                ->add('conceptoHoraDiurnaTrabajada', EntityType::class, $arrayPropiedadesConceptoHoraDiurnaTrabajada, array('required' => true))
                //->add('conceptoRiesgoProfesional', EntityType::class, $arrayPropiedadesConceptoRiesgoProfesional, array('required' => true))
                ->add('porcentajePensionExtra', NumberType::class, array('data' => $arConfiguracion->getPorcentajePensionExtra(), 'required' => true))
                ->add('conceptoIncapacidad', EntityType::class, $arrayPropiedadesConceptoIncapacidad, array('required' => true))
                ->add('porcentajeIva', NumberType::class, array('data' => $arConfiguracion->getPorcentajeIva(), 'required' => true))
                ->add('conceptoRetencionFuente', EntityType::class, $arrayPropiedadesConceptoRetencionFuente, array('required' => true))
                ->add('porcentajeBonificacionNoPrestacional', NumberType::class, array('data' => $arConfiguracion->getPorcentajeBonificacionNoPrestacional(), 'required' => true))
                ->add('edadMinimaEmpleado', NumberType::class, array('data' => $arConfiguracion->getEdadMinimaEmpleado(), 'required' => true))
                ->add('entidadExamenIngreso', EntityType::class, $arrayPropiedadesConceptoEntidadExamenIngreso, array('required' => true))
                ->add('comprobantePagoNomina', EntityType::class, $arrayPropiedadesConceptoEntidadComprobanteNomina, array('required' => true))
                ->add('comprobantePagoBanco', EntityType::class, $arrayPropiedadesConceptoEntidadComprobanteBanco, array('required' => true))
                ->add('controlPago', ChoiceType::class, array('choices' => array($srtControlPago => $arConfiguracion->getControlPago(), 'SI' => '1', 'NO' => '0')))
                ->add('prestacionesPorcentajeCesantias', NumberType::class, array('data' => $arConfiguracion->getPrestacionesPorcentajeCesantias(), 'required' => true))
                ->add('prestacionesPorcentajeInteresesCesantias', NumberType::class, array('data' => $arConfiguracion->getPrestacionesPorcentajeInteresesCesantias(), 'required' => true))
                ->add('prestacionesPorcentajeVacaciones', NumberType::class, array('data' => $arConfiguracion->getPrestacionesPorcentajeVacaciones(), 'required' => true))
                ->add('prestacionesPorcentajePrimas', NumberType::class, array('data' => $arConfiguracion->getPrestacionesPorcentajePrimas(), 'required' => true))
                ->add('aportesPorcentajeCaja', NumberType::class, array('data' => $arConfiguracion->getAportesPorcentajeCaja(), 'required' => true))
                ->add('aportesPorcentajeVacaciones', NumberType::class, array('data' => $arConfiguracion->getAportesPorcentajeVacaciones(), 'required' => true))
                ->add('tipoBasePagoVacaciones', ChoiceType::class, array('choices' => array('SALARIO' => '1', 'SALARIO PRESTACIONAL' => '1', 'SALARIO+RECAROS NOCTURNOS' => '2', 'SIN ASIGNAR' => '0'), 'data' => $arConfiguracion->getTipoBasePagoVacaciones()))
                ->add('cuentaPago', NumberType::class, array('data' => $arConfiguracion->getCuentaPago(), 'required' => true))
                ->add('conceptoVacacion', EntityType::class, $arrayPropiedadesConceptoVacacion, array('required' => true))
                ->add('afectaVacacionesParafiscales', CheckboxType::class, array('data' => $arConfiguracion->getAfectaVacacionesParafiscales(), 'required' => false))
                ->add('guardar', SubmitType::class, array('label' => 'Actualizar'))
                //->add('guardarProvision', 'submit', array('label' => 'Actualizar'))
                ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');
            $codigoConceptoAuxilioTransporte = $controles['conceptoAuxilioTransporte'];
            $ValorAuxilioTransporte = $controles['vrAuxilioTransporte'];
            $porcentajePensionExtra = $controles['porcentajePensionExtra'];
            $cuentaPago = $controles['cuentaPago'];
            $ValorSalario = $controles['vrSalario'];
            $codigoConceptoCredito = $controles['conceptoCredito'];
            $codigoConceptoIncapacidad = $controles['conceptoIncapacidad'];
            $codigoConceptoSeguro = $controles['conceptoSeguro'];
            $codigoConceptoHoraDiurnaTrabajada = $controles['conceptoHoraDiurnaTrabajada'];
            //$codigoConceptoRiesgoProfesional = $controles['conceptoRiesgoProfesional'];
            $codigoConceptoRetencionFuente = $controles['conceptoRetencionFuente'];
            $porcentajeIva = $controles['porcentajeIva'];
            $porcentajeBonificacionNoPrestacional = $controles['porcentajeBonificacionNoPrestacional'];
            $edadMinimaEmpleado = $controles['edadMinimaEmpleado'];
            $entidadExamenIngreso = $controles['entidadExamenIngreso'];
            $comprobantePagoNomina = $controles['comprobantePagoNomina'];
            $comprobantePagoBanco = $controles['comprobantePagoBanco'];
            $controlPago = $controles['controlPago'];
            $prestacionesPorcentajeCesantias = $controles['prestacionesPorcentajeCesantias'];
            $prestacionesPorcentajeInteresesCesantias = $controles['prestacionesPorcentajeInteresesCesantias'];
            $prestacionesPorcentajeVacaciones = $controles['prestacionesPorcentajeVacaciones'];
            $prestacionesPorcentajePrimas = $controles['prestacionesPorcentajePrimas'];
            $aportesPorcentajeCaja = $controles['aportesPorcentajeCaja'];
            $aportesPorcentajeVacaciones = $controles['aportesPorcentajeVacaciones'];
            $tipoBasePagoVacaciones = $controles['tipoBasePagoVacaciones'];
            $codigoConceptoVacacion = $controles['conceptoVacacion'];
            // guardar la tarea en la base de datos
            $arConfiguracion->setCodigoAuxilioTransporte($codigoConceptoAuxilioTransporte);
            $arConfiguracion->setVrAuxilioTransporte($ValorAuxilioTransporte);
            $arConfiguracion->setPorcentajePensionExtra($porcentajePensionExtra);
            $arConfiguracion->setPorcentajeIva($porcentajeIva);
            $arConfiguracion->setVrSalario($ValorSalario);
            $arConfiguracion->setCodigoCredito($codigoConceptoCredito);
            $arConfiguracion->setCodigoIncapacidad($codigoConceptoIncapacidad);
            $arConfiguracion->setCodigoSeguro($codigoConceptoSeguro);
            $arConfiguracion->setCodigoHoraDiurnaTrabajada($codigoConceptoHoraDiurnaTrabajada);
            //$arConfiguracion->setCodigoEntidadRiesgoFk($codigoConceptoRiesgoProfesional);
            $arConfiguracion->setCodigoRetencionFuente($codigoConceptoRetencionFuente);
            $arConfiguracion->setPorcentajeBonificacionNoPrestacional($porcentajeBonificacionNoPrestacional);
            $arConfiguracion->setEdadMinimaEmpleado($edadMinimaEmpleado);
            $arConfiguracion->setCodigoEntidadExamenIngreso($entidadExamenIngreso);
            $arConfiguracion->setCodigoComprobantePagoNomina($comprobantePagoNomina);
            $arConfiguracion->setCodigoComprobantepagoBanco($comprobantePagoBanco);
            $arConfiguracion->setControlPago($controlPago);
            $arConfiguracion->setPrestacionesPorcentajeCesantias($prestacionesPorcentajeCesantias);
            $arConfiguracion->setPrestacionesPorcentajeInteresesCesantias($prestacionesPorcentajeInteresesCesantias);
            $arConfiguracion->setPrestacionesPorcentajeVacaciones($prestacionesPorcentajeVacaciones);
            $arConfiguracion->setPrestacionesPorcentajePrimas($prestacionesPorcentajePrimas);
            $arConfiguracion->setAportesPorcentajeCaja($aportesPorcentajeCaja);
            $arConfiguracion->setAportesPorcentajeVacaciones($aportesPorcentajeVacaciones);
            $arConfiguracion->setTipoBasePagoVacaciones($tipoBasePagoVacaciones);
            $arrControles = $request->request->All();
            $intIndiceConsecutivo = 0;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
                $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->find($intCodigo);
                if (count($arConsecutivo) > 0) {
                    $intConsecutivo = $arrControles['TxtConsecutivo' . $intCodigo];
                    $arConsecutivo->setConsecutivo($intConsecutivo);
                    $em->persist($arConsecutivo);
                }
                $intIndiceConsecutivo++;
            }

            //provision
            $intCodigoProvision = 0;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arConfiguracionProvision = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision();
                $arConfiguracionProvision = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionProvision')->find($intCodigo);
                if (count($arConfiguracionProvision) > 0) {
                    $intCuenta = $arrControles['TxtCuenta' . $intCodigo];
                    $arConfiguracionProvision->setCodigoCuentaFk($intCuenta);
                    $intTipoCuenta = $arrControles['TxtTipoCuenta' . $intCodigo];
                    $arConfiguracionProvision->setTipoCuenta($intTipoCuenta);
                    $intCuentaOperacion = $arrControles['TxtCuentaOperacion' . $intCodigo];
                    $arConfiguracionProvision->setCodigoCuentaOperacionFk($intCuentaOperacion);
                    $intCuentaComercial = $arrControles['TxtCuentaComercial' . $intCodigo];
                    $arConfiguracionProvision->setCodigoCuentaComercialFk($intCuentaComercial);
                    $em->persist($arConfiguracionProvision);
                }
                $intCodigoProvision++;
            }
            //fin provision
            $em->persist($arConfiguracion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_configuracion_nomina', array('codigoConfiguracionPk' => 1)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:Configuracion.html.twig', array(
                    'formConfiguracion' => $formConfiguracion->createView(),
                    'arConsecutivo' => $arConsecutivo,
                    'arPagoConcepto' => $arPagoConcepto,
                    'arConfiguracionProvision' => $arConfiguracionProvision
        ));
    }

    /**
     * @Route("/rhu/configuracion/nomina/parametros/prestaciones/", name="brs_rhu_configuracion_nomina_parametros_prestaciones")
     */
    public function configuracionParametrosPrestacionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 115)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $form = $this->createFormBuilder()
                ->add('promedioPrimasLaborado', CheckboxType::class, array('data' => $arConfiguracion->getPromedioPrimasLaborado(), 'required' => false))
                ->add('promedioPrimasLaboradoDias', NumberType::class, array('data' => $arConfiguracion->getPromedioPrimasLaboradoDias(), 'required' => true))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->add('BtnNuevo', SubmitType::class, array('label' => 'Nuevo'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $controles = $request->request->get('form');
                foreach ($arrControles['LblCodigo'] as $codigo) {
                    $arParametroPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                    $arParametroPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->find($codigo);
                    if ($arParametroPrestacion) {
                        if ($arrControles['TxtTipo' . $codigo] != "") {
                            $arParametroPrestacion->setTipo($arrControles['TxtTipo' . $codigo]);
                        }
                        if ($arrControles['TxtOrden' . $codigo] != "") {
                            $arParametroPrestacion->setOrden($arrControles['TxtOrden' . $codigo]);
                        }
                        if ($arrControles['TxtDesde' . $codigo] != "") {
                            $arParametroPrestacion->setDiaDesde($arrControles['TxtDesde' . $codigo]);
                        }
                        if ($arrControles['TxtHasta' . $codigo] != "") {
                            $arParametroPrestacion->setDiaHasta($arrControles['TxtHasta' . $codigo]);
                        }
                        if ($arrControles['TxtPorcentaje' . $codigo] != "") {
                            $arParametroPrestacion->setPorcentaje($arrControles['TxtPorcentaje' . $codigo]);
                        }
                        if ($arrControles['TxtOrigen' . $codigo] != "") {
                            $arParametroPrestacion->setOrigen($arrControles['TxtOrigen' . $codigo]);
                        }
                        $em->persist($arParametroPrestacion);
                    }
                }
                if (isset($controles['promedioPrimasLaborado'])) {
                    $arConfiguracion->setPromedioPrimasLaborado(1);
                } else {
                    $arConfiguracion->setPromedioPrimasLaborado(0);
                }
                $arConfiguracion->setPromedioPrimasLaboradoDias($controles['promedioPrimasLaboradoDias']);
                $em->flush();
            }

            if ($form->get('BtnNuevo')->isClicked()) {
                $arParametroPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                $em->persist($arParametroPrestacion);
                $em->flush();
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arParametroPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
                        $arParametroPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->find($codigo);
                        $em->remove($arParametroPrestacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_configuracion_nomina_parametros_prestaciones'));
                }
            }
        }
        $arParametrosPrestacion = new \Brasa\RecursoHumanoBundle\Entity\RhuParametroPrestacion();
        $arParametrosPrestacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuParametroPrestacion')->findBy(array(), array('tipo' => 'ASC', 'orden' => 'ASC'));
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:ConfiguracionParametrosPrestacion.html.twig', array(
                    'form' => $form->createView(),
                    'arParametrosPrestacion' => $arParametrosPrestacion
        ));
    }

}
