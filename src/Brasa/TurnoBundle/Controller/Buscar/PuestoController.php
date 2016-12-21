<?php

namespace Brasa\TurnoBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PuestoController extends Controller
{
    var $strDqlLista = "";     
    var $strCodigo = "";
    var $strNombre = "";
    
    
    /**
     * @Route("/tur/burcar/puesto/{campoCodigo}/{codigoCliente}", name="brs_tur_buscar_puesto")
     */    
    public function listaAction(Request $request, $campoCodigo, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista($codigoCliente);
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arPuesto = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaTurnoBundle:Buscar:puesto.html.twig', array(
            'arPuestos' => $arPuesto,
            'campoCodigo' => $campoCodigo,            
            'form' => $form->createView()
            ));
    }        
    
    /**
     * @Route("/tur/burcar/puesto2/{campoCodigo}/{campoNombre}", name="brs_tur_buscar_puesto2")
     */
    public function buscarAction(Request $request, $campoCodigo, $campoNombre) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista2();
        $form->handleRequest($request);
        $this->lista2();
        if ($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista2($form);
                $this->lista2();
            }
        }
        $arPuestos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Buscar:puesto2.html.twig', array(
            'arPuestos' => $arPuestos,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
            ));
    }     
    
    private function lista($codigoCliente) {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPuesto')->listaDQL(
                $this->strCodigo,
                $codigoCliente,
                $this->strNombre                   
                ); 
    }   

    private function lista2() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPuesto')->listaBuscarDql(
                "",
                $session->get('filtroTurnoPuestoNombre'),
                $session->get('filtroTurnoPuestoNombreCliente')
                );
    }    
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()                                                
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista2($form) {
        $session = new Session();
        $session->set('filtroTurnoPuestoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroTurnoPuestoNombreCliente', $form->get('TxtNombreCliente')->getData());
    }    
    
    private function formularioLista2() {
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroTurnoPuestoNombre')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroTurnoPuestoNombreCliente')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function filtrarLista($form) {
        $session = new session;      
        
    }    
          
}
