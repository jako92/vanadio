<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCentroTrabajoType;

class CentroTrabajoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/base/centro/trabajo/lista", name="brs_rhu_base_centro_trabajo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 31, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->filtrarLista($form);
        $this->listar();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados == null) {
                } else {
                    foreach ($arrSeleccionados AS $codigoCentroTrabajo) {
                            $arCentroTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo();
                            $arCentroTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroTrabajo')->find($codigoCentroTrabajo);
                            $em->remove($arCentroTrabajo);
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_centro_trabajo_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        $arCentroTrabajo = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/CentroTrabajo:lista.html.twig', array(
                    'arCentroTrabajo' => $arCentroTrabajo,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/centro/trabajo/nuevo/{codigoCentroTrabajo}/{codigoCliente}", name="brs_rhu_base_centro_trabajo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCentroTrabajo, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $arUsuario = $this->getUser();
        if ($codigoCentroTrabajo == 0) {
            $arCentroTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo();
        } else {
            $arCentroTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroTrabajo')->find($codigoCentroTrabajo);
        }

        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
        $nombre = $arCliente->getNombreCorto();
        $form = $this->createForm(RhuCentroTrabajoType::class, $arCentroTrabajo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCentroTrabajo = $form->getData();
            $arCentroTrabajo->setClienteRel($arCliente);
            $em->persist($arCentroTrabajo);
            $em->flush();
            //return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle'));
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/CentroTrabajo:nuevo.html.twig', array(
                    'arCentroTrabajo' => $arCentroTrabajo,
                      'arClientes'=>$arCliente,
                        'nombre' => $nombre,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroTrabajo')->listaDql(
                $session->get('filtroNombre')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $session->get('filtroNombre')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
//        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
    }

}
