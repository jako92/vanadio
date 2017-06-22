<?php

namespace Brasa\RecursoHumanoBundle\Controller\General;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuConfiguracionType;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller {

    /**
     * @Route("/rhu/configuracion/{codigoConfiguracionPk}", name="brs_rhu_configuracion_nomina")
     */
    public function configuracionAction(Request $request, $codigoConfiguracionPk) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 92)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find($codigoConfiguracionPk);
        if ($arConfiguracion == NULL) {
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        }
        $form = $this->createForm(RhuConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                //Recurso humano
                $arConfiguracion = $form->getData();
                $em->persist($arConfiguracion);
                //Consecutivo de recurso humano
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
                //fin recurrso humano
                //provision
                /*$intCodigoProvision = 0;
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
                }*/
                //fin provision
                $em->flush();
            } else {
                $objMensaje->Mensaje("error", "ocurrio un error, verifica que los campos obligatorios se encuentren diligenciados.");
            }
        }
        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:configuracion.html.twig', array(
                    'form' => $form->createView(),
                    'arConsecutivo' => $arConsecutivo));
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
