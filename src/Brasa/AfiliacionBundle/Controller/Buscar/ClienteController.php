<?php

namespace Brasa\AfiliacionBundle\Controller\Buscar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClienteController extends Controller
{
    var $strDqlLista = "";     
    var $strCodigo = "";
    var $strNombre = "";
    
    /**
     * @Route("/afi/buscar/cliente/{campoCodigo}/{campoNombre}", name="brs_afi_buscar_cliente")
     */     
    public function listaAction(Request $request, $campoCodigo, $campoNombre) {
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
        return $this->render('BrasaAfiliacionBundle:Buscar:cliente.html.twig', array(
            'arClientes' => $arCliente,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
            ));
    }        
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->listaDQL(
                $this->strNombre,                
                $this->strCodigo   
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

    private function filtrarLista($form) {
        $session = new Session;
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strCodigo = $form->get('TxtCodigo')->getData();
    }    
          
}
