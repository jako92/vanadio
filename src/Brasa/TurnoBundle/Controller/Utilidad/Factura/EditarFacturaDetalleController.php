<?php
namespace Brasa\TurnoBundle\Controller\Utilidad\Factura;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurFacturaDetalleEditarType;

class EditarFacturaDetalleController extends Controller
{
    var $strListaDql = "";    
    
    /**
     * @Route("/tur/utilidad/editar/factura/detalle", name="brs_tur_utilidad_editar_factura_detalle")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 95)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                        
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }            
        }        
        $arFacturaDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Utilidades/Factura:listaDetalle.html.twig', array(
            'arFacturaDetalles' => $arFacturaDetalles,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/tur/utilidad/editar/factura/detalle/nuevo/{codigoFacturaDetalle}", name="brs_tur_utilidad_editar_factura_detalle_nuevo")
     */
    public function nuevoAction(Request $request, $codigoFacturaDetalle) {        
        $em = $this->getDoctrine()->getManager();
        $arFacturaDetalle = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();        
        $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->find($codigoFacturaDetalle);
        $form = $this->createForm(TurFacturaDetalleEditarType::class, $arFacturaDetalle);        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFacturaDetalle = $form->getData();                                                     
            $em->persist($arFacturaDetalle);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_utilidad_editar_factura_detalle'));                       
        }
        return $this->render('BrasaTurnoBundle:Utilidades/Factura:nuevoDetalle.html.twig', array(
            'arFacturaDetalle' => $arFacturaDetalle,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->listaDql(
                "",
                $session->get('filtroFacturaCodigoDetalle')
                );
    }      

    private function filtrar ($form) { 
        $session = new Session;            
        $session->set('filtroFacturaCodigoDetalle', $form->get('TxtCodigoDetalle')->getData());
    }   
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;          
        $form = $this->createFormBuilder()
            ->add('TxtCodigoDetalle', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroFacturaCodigoDetalle')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

}