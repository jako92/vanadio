<?php

namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GenerarProgramacionDetalleController extends Controller {
    
    var $strListaDetalleDql = "";
    /**
     * @Route("/tur/proceso/generar/programacion/detalle/lista", name="brs_tur_proceso_generar_programacion_detalle_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 4)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($request->request->get('OpGenerar')) {
                    set_time_limit(0);
                    $codigoPedido = $request->request->get('OpGenerar');
                    $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
                    $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
                    $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                    $arProgramacion->setClienteRel($arPedido->getClienteRel());
                    $arProgramacion->setFecha($arPedido->getFechaProgramacion());
                    $arProgramacion->setAnio($arPedido->getFechaProgramacion()->format('Y'));
                    $arProgramacion->setMes($arPedido->getFechaProgramacion()->format('j'));
                    $arUsuario = $this->getUser();
                    $arProgramacion->setUsuario($arUsuario->getUserName());
                    $em->persist($arProgramacion);
                    $arPedido->setEstadoProgramado(true);
                    $em->persist($arPedido);

                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigoPedido));
                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                        $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
                    }
                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacion->getCodigoProgramacionPk());
                    $em->flush();
                    set_time_limit(60);
                    return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_detalle_lista'));
                }
                if ($form->get('BtnGenerar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoPedido) {
                            $arPedidoActualizar = new \Brasa\TurnoBundle\Entity\TurPedido();
                            $arPedidoActualizar = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
                            if ($arPedidoActualizar) {
                                if ($arPedidoActualizar->getEstadoProgramado() == 0) {
                                    $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                                    $arProgramacion->setClienteRel($arPedidoActualizar->getClienteRel());
                                    $arProgramacion->setFecha($arPedidoActualizar->getFechaProgramacion());
                                    $arProgramacion->setAnio($arPedidoActualizar->getFechaProgramacion()->format('Y'));
                                    $arProgramacion->setMes($arPedidoActualizar->getFechaProgramacion()->format('j'));                                    
                                    $em->persist($arProgramacion);
                                    $arPedidoDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                                    $arPedidoDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $arPedidoActualizar->getCodigoPedidoPk()));
                                    foreach ($arPedidoDetalles as $arPedidoDetalle) {
                                        $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->nuevo($arPedidoDetalle->getCodigoPedidoDetallePk(), $arProgramacion);
                                    }
                                    $arPedidoActualizar->setEstadoProgramado(true);
                                    $em->persist($arPedidoActualizar);
                                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacion->getCodigoProgramacionPk());
                                    $em->flush();
                                }
                            }
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_detalle_lista'));
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $this->lista();
                    $this->generarExcel();
                }
                if ($form->get('BtnCerrarProgramacion')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoPedido) {
                            $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
                            $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
                            $arPedido->setEstadoProgramado(1);
                            $em->persist($arPedido);
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_tur_proceso_generar_programacion_detalle_lista'));
                    }
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioLista();
                    $this->lista();
                }                
            }
        }        
        $arPedidosDetalles = $paginator->paginate($em->createQuery($this->strListaDetalleDql), $request->query->get('page', 1), 500);
        return $this->render('BrasaTurnoBundle:Procesos/GenerarProgramacion:lista2.html.twig', array(                    
                    'arPedidosDetalles' => $arPedidosDetalles,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDetalleDql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pedidoSinProgramarDql($session->get('filtroCodigoCliente'));
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroCodigoCliente', $form->get('TxtNit')->getData());
    }    
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;        
        $strNombreCliente = "";
        if ($session->get('filtroCodigoCliente')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($session->get('filtroCodigoCliente'));
            if ($arCliente) {
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
            }
        }        
        $form = $this->createFormBuilder()
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroCodigoCliente')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))                
                ->add('BtnGenerar', SubmitType::class, array('label' => 'Generar seleccionados'))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->add('BtnCerrarProgramacion', SubmitType::class, array('label' => 'Cerrar programacion'))
                ->getForm();
        return $form;
    }

}
