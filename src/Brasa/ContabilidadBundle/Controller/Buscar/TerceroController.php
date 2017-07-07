<?php

namespace Brasa\ContabilidadBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TerceroController extends Controller {

    var $strDqlLista = "";
    var $strIdentificacion = "";
    var $strNombre = "";

    /**
     * @Route("/ctb/buscar/tercero/{campoCodigo}/{campoNombre}", name="brs_ctb_buscar_tercero")
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
        $arTercero = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaContabilidadBundle:Buscar:tercero.html.twig', array(
                    'arTerceros' => $arTercero,
                    'campoCodigo' => $campoCodigo,
                    'campoNombre' => $campoNombre,
                    'form' => $form->createView()
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->listaDQL(
                $this->strNombre, $this->strIdentificacion
        );
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $this->strIdentificacion))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $this->strIdentificacion = $form->get('TxtIdentificacion')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
    }

}
