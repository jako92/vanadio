<?php

namespace Brasa\CarteraBundle\Controller\Proceso;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContabilizarReciboController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/car/proceso/contabilizar/recibo/", name="brs_car_proceso_contabilizar_recibo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        //Validar si tiene permiso especial de contabilizar facturas de recurso humano
        /* if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 119)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted() && $form->isSubmitted()) {
            if ($form->get('BtnContabilizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $respuesta = $em->getRepository('BrasaCarteraBundle:CarRecibo')->contabilizar($arrSeleccionados);
                if ($respuesta != "") {
                    $objMensaje->Mensaje("error", $respuesta);
                }
                return $this->redirect($this->generateUrl('brs_car_proceso_contabilizar_recibo'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
        }

        $arRecibos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Proceso/Contabilizar:recibo.html.twig', array(
                    'arRecibos' => $arRecibos,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroReciboFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroReciboFechaDesde');
            $strFechaHasta = $session->get('filtroReciboFechaHasta');
        }
        $this->strListaDql = $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaPendienteContabilizarDql(
                $session->get('filtroReciboNumero'), $session->get('filtroReciboEstadoAutorizado'), $strFechaDesde, $strFechaHasta, $session->get('filtroReciboEstadoAnulado'));
    }

    private function filtrar($form) {
        $session = new Session;
        $session->set('filtroReciboNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroReciboEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroReciboEstadoAnulado', $form->get('estadoAnulado')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroReciboFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroReciboFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroReciboFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroReciboFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroReciboFechaDesde');
        }
        if ($session->get('filtroReciboFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroReciboFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroReciboNumero')))
                ->add('estadoAutorizado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroReciboEstadoAutorizado')))
                ->add('estadoAnulado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0'), 'data' => $session->get('filtroReciboEstadoAnulado')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroReciboFiltrarFecha')))
                ->add('BtnContabilizar', SubmitType::class, array('label' => 'Contabilizar',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

}
