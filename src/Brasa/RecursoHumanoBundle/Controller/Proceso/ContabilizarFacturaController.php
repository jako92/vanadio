<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContabilizarFacturaController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/proceso/contabilizar/factura/", name="brs_rhu_proceso_contabilizar_factura")
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
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted() && $form->isSubmitted()) {
            if ($form->get('BtnContabilizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $respuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->contabilizar($arrSeleccionados);
                if ($respuesta != "") {
                    $objMensaje->Mensaje("error", $respuesta);
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_factura'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
        }

        $arFacturas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:factura.html.twig', array(
                    'arFacturas' => $arFacturas,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroFacturaFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->listaPendienteContabilizarDql(
                $session->get('filtroFacturaNumero'), $session->get('filtroFacturaEstadoAutorizado'), $strFechaDesde, $strFechaHasta, $session->get('filtroFacturaEstadoAnulado'));
    }

    private function filtrar($form) {
        $session = new Session;
        $session->set('filtroFacturaNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroFacturaEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroFacturaEstadoAnulado', $form->get('estadoAnulado')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroFacturaFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroFacturaFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroFacturaFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroFacturaFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroFacturaFechaDesde');
        }
        if ($session->get('filtroFacturaFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroFacturaFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroFacturaNumero')))
                ->add('estadoAutorizado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroFacturaEstadoAutorizado')))
                ->add('estadoAnulado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0'), 'data' => $session->get('filtroFacturaEstadoAnulado')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFacturaFiltrarFecha')))
                ->add('BtnContabilizar', SubmitType::class, array('label' => 'Contabilizar',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

}
