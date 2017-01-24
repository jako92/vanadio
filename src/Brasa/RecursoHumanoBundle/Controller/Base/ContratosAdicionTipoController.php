<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoAdicionTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuContratosAdicionTipo controller.
 *
 */
class ContratosAdicionTipoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/contrato/adicion/tipo/lista", name="brs_rhu_base_contrato_adicion_tipo_lista")
     */ 
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 43, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoContratoAdicionTipo) {
                        $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicionTipo();
                        $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoAdicionTipo')->find($codigoContratoAdicionTipo);
                        $em->remove($arContratoTipo);                   
                    }
                    $em->flush(); 
                    return $this->redirect($this->generateUrl('brs_rhu_base_contrato_adicion_tipo_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de contrato porque esta siendo utilizado', $this);
                  }    
            }                        
        }
        
        $arContratosAdicionTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoAdicionTipo:listar.html.twig', array(
                    'arContratosAdicionTipos' => $arContratosAdicionTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/contrato/adicion/tipo/nuevo/{codigoContratoAdicionTipo}", name="brs_rhu_base_contrato_adicion_tipo_nuevo")
     */ 
    public function nuevoAction(Request $request, $codigoContratoAdicionTipo) {
        $em = $this->getDoctrine()->getManager();
        $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicionTipo();
        if ($codigoContratoAdicionTipo != 0) {
            $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoAdicionTipo')->find($codigoContratoAdicionTipo);
        }    
        $form = $this->createForm(RhuContratoAdicionTipoType::class, $arContratoTipo); 
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContratoTipo = $form->getData();
            $em->persist($arContratoTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contrato_adicion_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoAdicionTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoAdicionTipo')->listaDql();         
    }        
    
    
    
}
