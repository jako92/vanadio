<?php

namespace Brasa\GeneralBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenCuentaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuentasController extends Controller {

    /**
     * @Route("/general/base/cuentas", name="brs_gen_base_cuentas")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 103, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCuenta) {
                        $arCuenta = new \Brasa\GeneralBundle\Entity\GenCuenta();
                        $arCuenta = $em->getRepository('BrasaGeneralBundle:GenCuenta')->find($codigoCuenta);
                        $em->remove($arCuenta);
                        $em->flush();
                    }
                }
            }
        }
        $arCuentas = new \Brasa\GeneralBundle\Entity\GenCuenta();
        $query = $em->getRepository('BrasaGeneralBundle:GenCuenta')->findAll();
        $arCuentas = $paginator->paginate($query, $request->query->getInt('page', 1)/* page number */, 50/* limit per page */);

        return $this->render('BrasaGeneralBundle:Base/Cuentas:lista.html.twig', array(
                    'arCuentas' => $arCuentas,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/general/base/cuentas/nuevo/{codigoCuenta}", name="brs_gen_base_cuentas_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCuenta) {
        $em = $this->getDoctrine()->getManager();
        $arCuenta = new \Brasa\GeneralBundle\Entity\GenCuenta();
        if ($codigoCuenta != 0) {
            $arCuenta = $em->getRepository('BrasaGeneralBundle:GenCuenta')->find($codigoCuenta);
        }
        $form = $this->createForm(GenCuentaType::class, $arCuenta);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arCuenta = $form->getData();
                $em->persist($arCuenta);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_gen_base_cuentas'));
            }
        }
        return $this->render('BrasaGeneralBundle:Base/Cuentas:nuevo.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

}
