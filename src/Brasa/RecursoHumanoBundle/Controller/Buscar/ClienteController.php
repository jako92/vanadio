<?php

namespace Brasa\RecursoHumanoBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class ClienteController extends Controller
{
    var $strDqlLista = "";     
    var $strCodigo = "";
    var $strNombre = "";
    var $strNit = "";
    
    /**
     * @Route("/rhu/buscar/cliente/{campoCodigoCliente}/{campoNombre}", name="brs_rhu_buscar_cliente")
     */      
    public function listaAction(Request $request,$campoCodigoCliente="",$campoNombre="") {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        $arCliente = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Buscar:cliente.html.twig', array(
            'arClientes' => $arCliente,
            'campoNombre' => $campoNombre,
            'campoCodigoCliente' => $campoCodigoCliente,
            'form' => $form->createView()
            ));
    }
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->listaDQL(
                $this->strNombre,                
                $this->strCodigo,
                $this->strNit
                ); 
    }       
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()                                                
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $this->strNit))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        $session = new Session();  
        //$request = $this->getRequest(); 
        //$controles = $request->request->get('form');
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strNit = $form->get('TxtNit')->getData();
        $this->strCodigo = $form->get('TxtCodigo')->getData();
    }    
          
}
