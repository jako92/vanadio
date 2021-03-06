<?php

namespace Brasa\InventarioBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class ItemController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/inv/burcar/item/{campoCodigo}/{campoNombre}", name="brs_inv_buscar_item")
     */
    public function listaAction(Request $request, $campoCodigo, $campoNombre) {
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
        $arItem = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Buscar:item.html.twig', array(
                    'arItems' => $arItem,
                    'campoCodigo' => $campoCodigo,
                    'campoNombre' => $campoNombre,
                    'form' => $form->createView()
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvItem')->listaDQL(
                $this->strNombre, $this->strCodigo
        );
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
                ->add('TxtNombreItem', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigoItem', TextType::class, array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $this->strNombre = $form->get('TxtNombreItem')->getData();
        $this->strCodigo = $form->get('TxtCodigoItem')->getData();
    }

}
