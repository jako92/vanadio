<?php

namespace Brasa\InventarioBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class BodegaController extends Controller {

    var $strDqlLista = "";
    var $strNit = "";
    var $strNombre = "";

    /**
     * @Route("/inv/burcar/bodega/{campoCodigo}", name="brs_inv_buscar_bodega")
     */
    public function listaAction(Request $request, $campoCodigo) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrarLista($form);
                    $this->lista();
                }
            }
        }
        $arBodega = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Buscar:bodega.html.twig', array(
                    'arBodegas' => $arBodega,
                    'campoCodigo' => $campoCodigo,
                    'form' => $form->createView()
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvBodega')->listaDQL(
        );
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $this->strNit))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strNit = $form->get('TxtNit')->getData();
    }

}
