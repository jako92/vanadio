<?php

namespace Brasa\InventarioBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class regenerarController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/inv/proceso/regenerar", name="brs_inv_proceso_regenerar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /* if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 48)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->getRepository('BrasaInventarioBundle:InvItem')->regenerar();
            }
        }

        return $this->render('BrasaInventarioBundle:Proceso:regenerar.html.twig', array(
                    'form' => $form->createView()));
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;

        $form = $this->createFormBuilder()
                ->add('BtnRegenerar', SubmitType::class, array('label' => 'Regenerar'))
                ->getForm();
        return $form;
    }


}
