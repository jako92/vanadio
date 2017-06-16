<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPoligrafiaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPoligrafiaEmpleadoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PoligrafiaController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/rhu/movimiento/poligrafia", name="brs_rhu_movimiento_poligrafia")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        /* if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 120, 1)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->listar();
        if ($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if (count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoPoligrafia) {
                        $arPoligrafia = new \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia();
                        $arPoligrafia = $em->getRepository('BrasaRecursoHumanoBundle:RhuPoligrafia')->find($codigoPoligrafia);
                        if ($arPoligrafia->getCodigoCobroFk() == null) {
                            if ($arPoligrafia->getEstadoAutorizado() == 1 || $arPoligrafia->getEstadoCerrado() == 1) {
                                $objMensaje->Mensaje("error", "La poligrafia " . $codigoPoligrafia . " ya fue autorizada y/o cerrada, no se pude eliminar");
                            } else {
                                $em->remove($arPoligrafia);
                            }
                        } else {
                            $objMensaje->Mensaje("error", "La poligrafia " . $codigoPoligrafia . " ya tiene una relacion de cobro generada");
                        }
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia'));
            }

            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->listar();
            }
        }

        $arPoligrafias = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Poligrafia:lista.html.twig', array('arPoligrafias' => $arPoligrafias, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/poligrafia/nuevo/{codigoPoligrafia}", name="brs_rhu_movimiento_poligrafia_nuevo")
     */
    public function nuevoAction(Request $request, $codigoPoligrafia) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPoligrafia = new \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia();
        if ($codigoPoligrafia != 0) {
            $arPoligrafia = $em->getRepository('BrasaRecursoHumanoBundle:RhuPoligrafia')->find($codigoPoligrafia);
        } else {
            $arPoligrafia->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RhuPoligrafiaType::class, $arPoligrafia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arPoligrafia = $form->getData();
            if ($codigoPoligrafia == 0) {
                $arPoligrafia->setCodigoUsuario($arUsuario->getUserName());
                $arPoligrafia->setFechaCreacion(new \DateTime('now'));
            }
            $em->persist($arPoligrafia);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia_nuevo', array('codigoPoligrafia' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia_detalle', array('codigoPoligrafia' => $arPoligrafia->getCodigoPoligrafiaPk())));
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Poligrafia:nuevo.html.twig', array(
                    'arPoligrafia' => $arPoligrafia,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/poligrafia/nuevo/empleado/{codigoPoligrafia}", name="brs_rhu_movimiento_poligrafia_empleado_nuevo")
     */
    public function nuevoEmpleadoAction(Request $request, $codigoPoligrafia) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPoligrafia = new \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia();

        if ($codigoPoligrafia != 0) {
            $arPoligrafia = $em->getRepository('BrasaRecursoHumanoBundle:RhuPoligrafia')->find($codigoPoligrafia);
        } else {
            $arPoligrafia->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RhuPoligrafiaEmpleadoType::class, $arPoligrafia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arPoligrafia = $form->getData();
            if ($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if (count($arEmpleado) > 0) {
                    $arPoligrafia->setEmpleadoRel($arEmpleado);
                    if ($arEmpleado->getCodigoContratoActivoFk() != '') {
                        if ($codigoPoligrafia == 0) {
                            $arPoligrafia->setNombreCorto($arEmpleado->getNombreCorto());
                            $arPoligrafia->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                            $arPoligrafia->setTipoIdentificacionRel($arEmpleado->getTipoIdentificacionRel());
                            $arPoligrafia->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                            $arPoligrafia->setClienteRel($arPoligrafia->getCentroCostoRel()->getClienteRel());
                            $arPoligrafia->setCodigoUsuario($arUsuario->getUserName());
                            $arPoligrafia->setFechaCreacion(new \DateTime('now'));
                        }
                        $em->persist($arPoligrafia);
                        $em->flush();
                        if ($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia_nuevo_empleado', array('codigoPoligrafia' => 0)));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia_detalle', array('codigoPoligrafia' => $arPoligrafia->getCodigoPoligrafiaPk())));
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Poligrafia:nuevoEmpleado.html.twig', array(
                    'arPoligrafia' => $arPoligrafia,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/poligrafia/detalle/{codigoPoligrafia}", name="brs_rhu_movimiento_poligrafia_detalle")
     */
    public function detalleAction(Request $request, $codigoPoligrafia) {
        $em = $this->getDoctrine()->getManager();

        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPoligrafia = new \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia();
        $arPoligrafia = $em->getRepository('BrasaRecursoHumanoBundle:RhuPoligrafia')->find($codigoPoligrafia);
        $form = $this->formularioDetalle($arPoligrafia);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnAutorizar')->isClicked()) {
                $arPoligrafia->setEstadoAutorizado(1);
                $em->persist($arPoligrafia);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia_detalle', array('codigoPoligrafia' => $codigoPoligrafia)));
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arPoligrafia->getEstadoAutorizado() == 1) {
                    $arPoligrafia->setEstadoAutorizado(0);
                    $em->persist($arPoligrafia);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_poligrafia_detalle', array('codigoPoligrafia' => $codigoPoligrafia)));
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Poligrafia:detalle.html.twig', array(
                    'arPoligrafia' => $arPoligrafia,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPoligrafia')->listaDQL(
                $session->get('filtroIdentificacion')
        );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            } else {
                $session->set('filtroIdentificacion', null);
            }
        }
        $form = $this->createFormBuilder()
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->getForm();
        return $form;
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
    }

    private function formularioDetalle($arPoligrafia) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        if ($arPoligrafia->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            if ($arPoligrafia->getEstadoCerrado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
            }
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                ->getForm();
        return $form;
    }

}
