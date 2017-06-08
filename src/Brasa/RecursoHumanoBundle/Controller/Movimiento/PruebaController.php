<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPruebaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PruebaController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/rhu/movimiento/prueba", name="brs_rhu_movimiento_prueba")
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
                    foreach ($arrSelecionados AS $codigoPrueba) {
                        $arPrueba = new \Brasa\RecursoHumanoBundle\Entity\RhuPrueba();
                        $arPrueba = $em->getRepository('BrasaRecursoHumanoBundle:RhuPrueba')->find($codigoPrueba);
                        if ($arPrueba->getEstadoAutorizado() == 1 || $arPrueba->getEstadoCerrado() == 1) {
                            $objMensaje->Mensaje("error", "La prueba " . $codigoPrueba . " ya fue autorizada y/o cerrada, no se pude eliminar");
                        } else {
                            $em->remove($arPrueba);
                        }
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_prueba'));
            }

            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->listar();
                $this->generarExcel();
            }
        }

        $arPruebas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Prueba:lista.html.twig', array('arPrueba' => $arPruebas, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/prueba/nuevo/{codigoPrueba}", name="brs_rhu_movimiento_prueba_nuevo")
     */
    public function nuevoAction(Request $request, $codigoPrueba = 0) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPrueba = new \Brasa\RecursoHumanoBundle\Entity\RhuPrueba();
        if ($codigoPrueba != 0) {
            $arPrueba = $em->getRepository('BrasaRecursoHumanoBundle:RhuPrueba')->find($codigoPrueba);
        } else {
            $arPrueba->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RhuPruebaType::class, $arPrueba);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arPrueba = $form->getData();
            if ($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if (count($arEmpleado) > 0) {
                    $arPrueba->setEmpleadoRel($arEmpleado);
                    if ($arEmpleado->getCodigoContratoActivoFk() != '') {
                        if ($codigoPrueba == 0) {
                            $arPrueba->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                            $arPrueba->setClienteRel($arPrueba->getCentroCostoRel()->getClienteRel());
                            $arPrueba->setCodigoUsuario($arUsuario->getUserName());
                            $arPrueba->setFechaCreacion(new \DateTime('now'));
                        }
                        $em->persist($arPrueba);
                        $em->flush();
                        if ($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_prueba_nuevo', array('codigoPrueba' => 0)));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_prueba_detalle', array('codigoPrueba' => $arPrueba->getCodigoPruebaPk())));
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Prueba:nuevo.html.twig', array(
                    'arPrueba' => $arPrueba,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/prueba/detalle/{codigoPrueba}", name="brs_rhu_movimiento_prueba_detalle")
     */
    public function detalleAction(Request $request, $codigoPrueba) {
        $em = $this->getDoctrine()->getManager();

        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPrueba = new \Brasa\RecursoHumanoBundle\Entity\RhuPrueba();
        $arPrueba = $em->getRepository('BrasaRecursoHumanoBundle:RhuPrueba')->find($codigoPrueba);
        $form = $this->formularioDetalle($arPrueba);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnAutorizar')->isClicked()) {
                $arPrueba->setEstadoAutorizado(1);
                $em->persist($arPrueba);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_prueba_detalle', array('codigoPrueba' => $codigoPrueba)));
            }
            if ($form->get('BtnDesAutorizar')->isClicked()) {
                if ($arPrueba->getEstadoAutorizado() == 1) {
                    $arPrueba->setEstadoAutorizado(0);
                    $em->persist($arPrueba);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_prueba_detalle', array('codigoPrueba' => $codigoPrueba)));
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Prueba:detalle.html.twig', array(
                    'arPrueba' => $arPrueba,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPrueba')->listaDQL(
                $session->get('filtroIdentificacion'), $session->get('filtroCodigoPruebaTipo')
        );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedadesTipo = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuPruebaTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('pt')
                                ->orderBy('pt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoPruebaTipo')) {
            $arrayPropiedadesTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPruebaTipo", $session->get('filtroCodigoPruebaTipo'));
        }
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
                ->add('pruebaTipoRel', EntityType::class, $arrayPropiedadesTipo)
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrar($form) {
        $session = new session;
        $codigoPruebaTipo = "";
        if ($form->get('pruebaTipoRel')->getData()) {
            $codigoPruebaTipo = $form->get('pruebaTipoRel')->getData()->getCodigoPruebaTipoPk();
        }
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroCodigoPruebaTipo', $codigoPruebaTipo);
    }

    private function formularioDetalle($arPrueba) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        if ($arPrueba->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            if ($arPrueba->getEstadoCerrado() == 1) {
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
