<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NotificacionesController extends Controller
{
    /**
     * @Route("/rhu/utilidades/notificaciones/cierreprogramacionpago", name="brs_rhu_utilidades_notificaciones_cierreprogramacionpago")
     */
    public function cierreProgramacionPagoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 76)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->createFormBuilder()
            ->add('BtnEnviar', SubmitType::class, array('label'  => 'Enviar',))
            ->getForm();
        $form->handleRequest($request);        
        if($form->isValid()) {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('',0, ""));
            if($form->get('BtnEnviar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        echo $codigoCentroCosto;
                    }
                }
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Prueba email')
                    ->setFrom('maestradaz3@gmail.com')
                    ->setTo('jefedesarrollo@jgefectivo.com')
                    ->setBody(
                    $this->renderView('BrasaRecursoHumanoBundle:Utilidades/Notificaciones:emailCierreProgramacionPago.html.twig',
                          array('name' => "Mario")
                      ),
            'text/html'                            
                                
                    );
                $this->get('mailer')->send($message);
                
                
            }
        }  
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaFechaPagoDQL());        
        $arCentrosCostos = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Notificaciones:cierreProgramacionPago.html.twig', array(
            'arCentrosCostos' => $arCentrosCostos,
            'form' => $form->createView()
            ));
    }    
}
