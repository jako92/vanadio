<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCierreMesType;

class CierreMesController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/proceso/cierre/mes", name="brs_rhu_proceso_cierre_mes")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 62)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/ 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);                       
        $this->listar();
        if($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoCierreMes = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPrestacion')->generar();
            }
        }
        $arCierresMes = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreMes:lista.html.twig', array(
            'arCierresMes' => $arCierresMes,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/proceso/cierre/mes/nuevo/{codigoCierreMes}", name="brs_rhu_proceso_cierre_mes_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCierreMes = 0) {        
        $em = $this->getDoctrine()->getManager();        
        $arCierreMes = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreMes();       
        if($codigoCierreMes != 0) {
            $arCierreMes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->find($codigoCierreMes);
        }
        
        $form = $this->createForm(RhuCierreMesType::class, $arCierreMes);                     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCierreMes = $form->getData();                                                                                                          
            $em->persist($arCierreMes);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_mes'));                                                                                                                                                                                                             
        }                

        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreMes:nuevo.html.twig', array(
            'form' => $form->createView()));
    }         

    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->listaDql();
    }

}
