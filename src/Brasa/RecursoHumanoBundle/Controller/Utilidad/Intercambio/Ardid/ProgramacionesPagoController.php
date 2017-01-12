<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\Intercambio\Ardid;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProgramacionesPagoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    
    /**
     * @Route("/rhu/utilidad/intercambio/ardid/programaciones", name="brs_rhu_utilidad_intercambio_ardid_programacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 1, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/                
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar();
        if($form->isValid()) {            
            $cliente = new \nusoap_client('http://localhost/servicio/servidor.php');
            $result = $cliente->call("getInsertarEmpleado", 
                    array(
                        "codigoIdentificacionTipo" => "CC", 
                        "identificacionNumero" => "70143086",
                        "nombre1" => "MARIO",
                        "nombre2" => "ANDRES",
                        "apellido1" => "ESTRADA",
                        "apellido2" => "ZULUAGA",
                        "nombreCorto" => "MARIO ANDRES ESTRADA ZULUAGA",));            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($request->request->get('OpTransferir')) {
                $codigoProgramacionPago = $request->request->get('OpTransferir');
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                
                $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                //$arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago), array(), 1);                
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));                
                foreach ($arPagos as $arPago) {
                    $result = $cliente->call("getInsertarEmpleado", 
                            array(
                                "codigoIdentificacionTipo" => $arPago->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(), 
                                "identificacionNumero" => $arPago->getEmpleadoRel()->getNumeroIdentificacion(),
                                "nombre1" => $arPago->getEmpleadoRel()->getNombre1(),
                                "nombre2" => $arPago->getEmpleadoRel()->getNombre2(),
                                "apellido1" => $arPago->getEmpleadoRel()->getApellido1(),
                                "apellido2" => $arPago->getEmpleadoRel()->getApellido2(),
                                "nombreCorto" => $arPago->getEmpleadoRel()->getNombreCorto(),));  
                    if($result == '01') {
                        $result = $cliente->call("getInsertarPago", 
                                array(
                                    "codigoEmpresa" => 1, 
                                    "numero" => $arPago->getNumero()));  
                        if($result == '01') {
                           
                        }
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_utilidad_intercambio_ardid_programacion'));
            }            
        }
        $arProgramacionPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->getInt('page', 1)/*page number*/,50/*limit per page*/);                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Intercambio/Ardid:lista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->getForm();
        return $form;
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQL(
                    "",
                    "",
                    "",
                    "",
                    1,
                    "",
                    0
                    );
    }    
}

