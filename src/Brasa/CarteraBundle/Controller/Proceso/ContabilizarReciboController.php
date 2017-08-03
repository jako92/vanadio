<?php

namespace Brasa\CarteraBundle\Controller\Proceso;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
        $arConfiguracion = new \Brasa\CarteraBundle\Entity\CarConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaCarteraBundle:CarConfiguracion')->find(1);
        $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();
        $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteRecibo());  
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
                    'arComprobante' => $arComprobanteContable,
                    'form' => $form->createView()));
    }
    
    /**
     * @Route("/car/proceso/contabilizar/recibo/configurar/", name="brs_car_proceso_contabilizar_recibo_configurar")
     */     
    public function configurarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();       
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\CarteraBundle\Entity\CarConfiguracion();                    
        $arConfiguracion = $em->getRepository('BrasaCarteraBundle:CarConfiguracion')->find(1); 
        $form = $this->createFormBuilder()          
            ->add('TxtCodigoComprobante', TextType::class, array('data' => $arConfiguracion->getCodigoComprobanteRecibo()))    
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm(); 
        $form->handleRequest($request);
        if($form->isValid()) {            
            if ($form->get('BtnGuardar')->isClicked()) { 
                $arrControles = $request->request->All();
                $codigoComprobante = $form->get('TxtCodigoComprobante')->getData();
                $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($codigoComprobante);  
                if($arComprobanteContable) {
                    $arConfiguracion->setCodigoComprobanteRecibo($codigoComprobante);
                    $em->persist($arConfiguracion);
                    $em->flush();                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje("error", "El comprobante no existe");
                }                
            }    
        }       
        $arConfiguracion = $em->getRepository('BrasaCarteraBundle:CarConfiguracion')->findAll();                                      
        return $this->render('BrasaCarteraBundle:Proceso/Contabilizar:reciboConfigurar.html.twig', array(
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/car/proceso/descontabilizar/recibo", name="brs_car_proceso_contabilizar_descontabilizar_recibo")
     */
    public function descontabilizarReciboAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $session = new Session;
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('numeroDesde', NumberType::class, array('label'  => 'Pago desde'))
            ->add('numeroHasta', NumberType::class, array('label'  => 'Pago hasta'))
            ->add('fechaDesde',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                                            
            ->add('BtnDescontabilizar', SubmitType::class, array('label'  => 'Descontabilizar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intReciboDesde = $form->get('numeroDesde')->getData();
                $intReciboHasta = $form->get('numeroHasta')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();                
                if($intReciboDesde != "" || $intReciboHasta != "" || $dateFechaDesde != "" || $dateFechaHasta != "") {
                    $arRegistros = new \Brasa\CarteraBundle\Entity\CarRecibo();
                    $arRegistros = $em->getRepository('BrasaCarteraBundle:CarRecibo')->contabilizadosRecibosDql($intReciboDesde, $intReciboHasta, $dateFechaDesde, $dateFechaHasta);
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\CarteraBundle\Entity\CarRecibo();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:CarRecibo')->find($codigoRegistro);
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
        return $this->render('BrasaCarteraBundle:Proceso/Contabilizar:descontabilizarRecibo.html.twig', array(
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
