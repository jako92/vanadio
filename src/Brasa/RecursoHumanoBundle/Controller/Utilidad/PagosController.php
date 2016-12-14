<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PagosController extends Controller
{
    /**
     * @Route("/rhu/utilidades/pagos/generar/periodo", name="brs_rhu_utilidades_pagos_generar_periodo")
     */
    public function generarPeriodoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 74)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('ChkMostrarInactivos', CheckboxType::class, array('label'=> '', 'required'  => false,))
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => ''))
            ->add('BtnBuscar', SubmitType::class, array('label'  => 'Filtrar'))            
            ->add('BtnGenerarNomina', SubmitType::class, array('label'  => 'Generar nomina',))
            ->add('BtnGenerarPrima', SubmitType::class, array('label'  => 'Generar prima',))            
            ->add('BtnGenerarCesantias', SubmitType::class, array('label'  => 'Generar cesantias',))            
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('',0, ""));
            if($form->get('BtnGenerarNomina')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($codigoCentroCosto, 1);
                    }
                }
            }
            if($form->get('BtnGenerarPrima')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($codigoCentroCosto, 2);
                    }
                }
            } 
            if($form->get('BtnGenerarCesantias')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarProgramacionPago($codigoCentroCosto, 3);
                    }
                }
            }            
            if($form->get('BtnBuscar')->isClicked()) {
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL($form->get('TxtNombre')->getData(), $form->get('ChkMostrarInactivos')->getData(), ""));
            }
        } else {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL($form->get('TxtNombre')->getData(), 1, 0));
        }
        $arCentroCosto = $paginator->paginate($query, $request->query->get('page', 1), 100);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPeriodoPago.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()
            ));
    }  
}
