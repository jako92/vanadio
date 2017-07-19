<?php

namespace Brasa\GeneralBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Brasa\GeneralBundle\Form\Type\GenFavoritoType;

class FavoritoController extends Controller {

    /**
     * @Route("/general/utilidad/favorito/lista", name="brs_gen_utilidad_favorito")
     */
    public function listaAction(Request $request) {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $arUsuario = $this->getUser();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
                ->add('nombre', TextType::class, array('required' => false))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaGeneralBundle:GenFavorito')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_gen_utilidad_favorito'));
            }
        }
        $queryFavoritos = $em->getRepository('BrasaGeneralBundle:GenFavorito')->findBy(array('usuario' => $arUsuario->getUsername()));
        $arFavoritos = $paginator->paginate($queryFavoritos, $request->query->getInt('page', 1), 200);
        $session->set('arFavoritos', $arFavoritos);
        return $this->render('BrasaGeneralBundle:Utilidades/Favorito:lista.html.twig', array(
                    'arFavoritos' => $arFavoritos,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/general/utilidad/favorito/nuevo/{codigoFavorito}", name="brs_gen_utilidad_favorito_nuevo")
     */
    public function nuevoAction(Request $request, $codigoFavorito) {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $arUsuario = $this->getUser();
        $arFavorito = new \Brasa\GeneralBundle\Entity\GenFavorito();
        if ($codigoFavorito) {
            $arFavorito = $em->getRepository('BrasaGeneralBundle:GenFavorito')->find($codigoFavorito);
        }
        $form = $this->createForm(GenFavoritoType::class, $arFavorito);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arFavorito = $form->getData();
            $arFavorito->setUsuario($arUsuario->getUsername());
            $em->persist($arFavorito);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_gen_utilidad_favorito_nuevo', array('codigoFavorito' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_gen_utilidad_favorito'));
            }
            $session->set('arFavoritos', $arFavoritos);
        }
        return $this->render('BrasaGeneralBundle:Utilidades/Favorito:nuevo.html.twig', array(
                    'arFavorito' => $arFavorito,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/general/utilidad/favorito/menu/{route}", name="brs_gen_utilidad_favorito_menu")
     */
    public function nuevoMenuAction(Request $request, $route) {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $arUsuario = $this->getUser();
        $arFavorito = new \Brasa\GeneralBundle\Entity\GenFavorito();
        $arFavorito->setNombre($route);
        $arFavorito->setUrlDocumento($route);
        $arFavorito->setUsuario($arUsuario->getUsername());
        $em->persist($arFavorito);
        $em->flush();
        //enviar los favoritos del usuario en el menu
        $arFavoritos = $em->getRepository('BrasaGeneralBundle:GenFavorito')->findBy(array('usuario' => $arUsuario->getUsername()));
        $session->set('arFavoritos', $arFavoritos);
        return $this->redirect($this->generateUrl($route));
    }

}
