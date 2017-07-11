<?php

namespace Brasa\TurnoBundle\Controller\Proceso\Contabilizar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PedidoController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/tur/proceso/contabilizar/pedido", name="brs_tur_proceso_contabilizar_pedido")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnContabilizar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $respuesta = $em->getRepository('BrasaTurnoBundle:TurPedido')->contabilizar($arrSeleccionados);
                    if ($respuesta != "") {
                        $objMensaje->Mensaje("error", $respuesta);
                    }
                    return $this->redirect($this->generateUrl('brs_tur_proceso_contabilizar_pedido'));
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $this->lista();
                }
            }
        }

        $arPedidos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Procesos/Contabilizar:pedido.html.twig', array(
                    'arPedidos' => $arPedidos,
                    'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/proceso/contabilizar/pedido/descontabilizar", name="brs_rhu_proceso_contabilizar_pedido_descontabilizar")
     */
    public function descontabilizarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
                ->add('numeroDesde', NumberType::class, array('label' => 'Numero desde'))
                ->add('numeroHasta', NumberType::class, array('label' => 'Numero hasta'))
                ->add('fechaDesde', DateType::class, array( 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array( 'attr' => array('class' => 'date',)))
                ->add('BtnDescontabilizar', SubmitType::class, array('label' => 'Descontabilizar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnDescontabilizar')->isClicked()) {
                    $intNumeroDesde = $form->get('numeroDesde')->getData();
                    $intNumeroHasta = $form->get('numeroHasta')->getData();
                    $dateFechaDesde = $form->get('fechaDesde')->getData();
                    $dateFechaHasta = $form->get('fechaHasta')->getData();
                    if (($intNumeroDesde != "" && $intNumeroHasta != "") || ($dateFechaDesde != "" && $dateFechaHasta != "")) {
                        $dql = $em->getRepository('BrasaTurnoBundle:TurPedido')->listaFechaDql($dateFechaDesde, $dateFechaHasta, $intNumeroDesde, $intNumeroHasta);
                        $query = $em->createQuery($dql);
                        $arPedidos = $query->getResult();
                        foreach ($arPedidos as $arPedido) {
                            $arPedidoAct = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($arPedido->getCodigoPedidoPk());
                            $arPedidoAct->setEstadoContabilizado(0);
                            $em->persist($arPedidoAct);
                        }
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        $objMensaje->Mensaje('error', 'Debe seleccionar el filtro correctamente', $this);
                    }
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Procesos/Contabilizar:pedidoDescontabilizar.html.twig', array(
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroPedidoFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }
        $this->strListaDql = $em->getRepository('BrasaTurnoBundle:TurPedido')->listaPendienteContabilizarCierreMesDql(
                $session->get('filtroPedidoNumero'), $session->get('filtroCodigoCliente'), $strFechaDesde, $strFechaHasta);
    }

    private function filtrar($form) {
        $session = new Session;
        
        $session->set('filtroPedidoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCodigoCliente', $form->get('TxtNit')->getData());
        $session->set('filtroNombreCliente', $form->get('TxtNombreCliente')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroPedidoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroPedidoFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroPedidoFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $strNombreCliente = "";
        $session = new Session;
        if ($session->get('filtroCodigoCliente')) {
            $strNombreCliente = $session->get('filtroNombreCliente');
        }
        $session->set('filtroNombreCliente', $strNombreCliente);
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroPedidoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
        }
        if ($session->get('filtroPedidoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroCodigoCliente')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroPedidoNumero')))
                ->add('fechaDesde', DateType::class, array('data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroPedidoFiltrarFecha')))
                ->add('BtnContabilizar', SubmitType::class, array('label' => 'Contabilizar',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

}
