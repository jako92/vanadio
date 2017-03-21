<?php

namespace Brasa\CarteraBundle\Controller\Utilidad\Recibo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\CarteraBundle\Form\Type\CarReciboCorregirType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CorregirController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/cartera/utilidad/recibo/corregir", name="brs_cartera_utilidad_recibo_corregir")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 116, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                    $this->lista();
                }
            }
        }
        $arRecibos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Utilidad/Recibo:corregirLista.html.twig', array(
                    'arRecibos' => $arRecibos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/utilidad/recibo/corregir/nuevo/{codigoRecibo}", name="brs_cartera_utilidad_recibo_corregir_nuevo")
     */
    public function nuevoAction(Request $request, $codigoRecibo) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);
        
        $form = $this->createForm(CarReciboCorregirType::class, $arRecibo);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arRecibo = $form->getData();
                $em->persist($arRecibo);
                $em->flush();                
                return $this->redirect($this->generateUrl('brs_cartera_utilidad_recibo_corregir'));
            }
        }
        return $this->render('BrasaCarteraBundle:Utilidad/Recibo:corregir.html.twig', array(
                    'arRecibo' => $arRecibo,
                    'form' => $form->createView()));
    }


    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaDQL(
                $session->get('filtroReciboNumero'), $session->get('filtroCodigoCliente'), $session->get('filtroReciboEstadoImpreso'));
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroReciboNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroReciboEstadoImpreso', $form->get('estadoImpreso')->getData());
        $session->set('filtroNit', $form->get('TxtNit')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }

        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroReciboNumero')))
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('estadoImpreso', ChoiceType::class, array('choices' => array('TODOS' => '2', 'IMPRESO' => '1', 'SIN IMPRIMIR' => '0'), 'data' => $session->get('filtroReciboEstadoImpreso')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }   

}
