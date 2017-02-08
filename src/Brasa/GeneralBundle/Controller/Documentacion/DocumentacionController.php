<?php

namespace Brasa\GeneralBundle\Controller\Documentacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\DoctrineBundle\ConnectionFactory;

class DocumentacionController extends Controller {

    /**
     * @Route("/general/documentacion/", name="brasa_general_documentacion")
     */
    public function documentacionAction() {
        $em = $this->get('doctrine.orm.entity_manager');
        $form = $this->createFormBuilder()
                ->getForm();

        return $this->render('BrasaGeneralBundle:Documentacion:documentacion.html.twig', array(
                    'form' => $form->createView(),));
    }

    /**
     * @Route("/general/documentacion/programacion/turnos", name="brasa_general_documentacion_programacion")
     */
    public function programacionAction() {
        $em = $this->get('doctrine.orm.entity_manager');
        $form = $this->createFormBuilder()
                ->getForm();

        return $this->render('BrasaGeneralBundle:Documentacion:programacionTurnos.html.twig', array(
                    'form' => $form->createView(),));
    }
    
        /**
     * @Route("/general/documentacion/recursos/humanos", name="brasa_general_documentacion_rhu")
     */
    public function rhuAction() {
       $em = $this->get('doctrine.orm.entity_manager');
       $form = $this->createFormBuilder()
                ->getForm();
        
        return $this->render('BrasaGeneralBundle:Documentacion:rhu.html.twig',array(
            'form' => $form->createView(),));
    }   
    
        /**
     * @Route("/general/documentacion/cartera", name="brasa_general_documentacion_cartera")
     */
    public function carteraAction() {
       $em = $this->get('doctrine.orm.entity_manager');
       $form = $this->createFormBuilder()
                ->getForm();
        
        return $this->render('BrasaGeneralBundle:Documentacion:cartera.html.twig',array(
            'form' => $form->createView(),));
    }   
    
          /**
     * @Route("/general/documentacion/inventario", name="brasa_general_documentacion_inventario")
     */
    public function inventariAction() {
       $em = $this->get('doctrine.orm.entity_manager');
       $form = $this->createFormBuilder()
                ->getForm();
        
        return $this->render('BrasaGeneralBundle:Documentacion:inventario.html.twig',array(
            'form' => $form->createView(),));
    } 
    
          /**
     * @Route("/general/documentacion/contabilidad", name="brasa_general_documentacion_contabilidad")
     */
    public function contabilidadAction() {
       $em = $this->get('doctrine.orm.entity_manager');
       $form = $this->createFormBuilder()
                ->getForm();
        
        return $this->render('BrasaGeneralBundle:Documentacion:contabilidad.html.twig',array(
            'form' => $form->createView(),));
    }   
    
          /**
     * @Route("/general/documentacion/general", name="brasa_general_documentacion_general")
     */
    public function generalAction() {
       $em = $this->get('doctrine.orm.entity_manager');
       $form = $this->createFormBuilder()
                ->getForm();
        
        return $this->render('BrasaGeneralBundle:Documentacion:general.html.twig',array(
            'form' => $form->createView(),));
    }   

}
