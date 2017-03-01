<?php

namespace Brasa\ContabilidadBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuentaController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/ctb/buscar/cuenta/{campoCodigo}", name="brs_ctb_buscar_cuenta")
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
        $arCuenta = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaContabilidadBundle:Buscar:cuenta.html.twig', array(
                    'arCuenta' => $arCuenta,
                    'campoCodigo' => $campoCodigo,
                    'form' => $form->createView()
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->listaDQL(
                $this->strCodigo, $this->strNombre
        );
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigo', TextType::class, array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {

        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
    }

}
