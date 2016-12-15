<?php

namespace Brasa\InventarioBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class LoteController extends Controller
{
    var $strDqlLista = "";     
    var $strNit = "";
    var $strNombre = "";
    
    /**
     * @Route("/inv/burcar/lote/{codigoItem}/{campoLote}", name="brs_inv_buscar_lote")
     */      
    public function listaAction(Request $request, $codigoItem, $campoLote) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista($codigoItem);
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista($codigoItem);
            }
        }
        $query = $em->createQuery($this->strDqlLista);        
        $arResultados = $query->getResult();
        $arLotes = $paginator->paginate($arResultados, $request->query->get('page', 1), 50);                
        return $this->render('BrasaInventarioBundle:Buscar:lote.html.twig', array(
            'arLotes' => $arLotes,
            'campoLote' => $campoLote,
            'form' => $form->createView()
            ));
    }        
    
    private function lista($codigoItem) {                        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvLote')->consultaDisponibleDQL(
                $codigoItem);
    }       
    
    private function formularioLista() {                
        $form = $this->createFormBuilder()                                                
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $this->strNit))                            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        $session = new Session;
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strNit = $form->get('TxtNit')->getData();
    }    
          
}
