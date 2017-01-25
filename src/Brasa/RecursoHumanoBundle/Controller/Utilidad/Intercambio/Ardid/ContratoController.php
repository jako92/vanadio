<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\Intercambio\Ardid;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ContratoController extends Controller {

    var $strDqlLista = "";
    var $intNumero = 0;

    /**
     * @Route("/rhu/utilidad/intercambio/ardid/contrato", name="brs_rhu_utilidad_intercambio_ardid_contrato")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /* if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 1, 1)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {    
            if($form->get('BtnSincronizar')->isClicked()) {               
                set_time_limit(0);
                ini_set("memory_limit", -1);  
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $direccionServidor = $arConfiguracion->getDireccionServidorArdid();
                $cliente = new \nusoap_client($direccionServidor);
                $error = FALSE;
                $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                //$arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoContratoPk' => 2203), array(), 1);
                $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findAll();
                foreach ($arContratos as $arContrato) {
                    $result = $cliente->call("getInsertarEmpleado", array(
                        "codigoIdentificacionTipo" => $arContrato->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                        "identificacionNumero" => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                        "nombre1" => $arContrato->getEmpleadoRel()->getNombre1(),
                        "nombre2" => $arContrato->getEmpleadoRel()->getNombre2(),
                        "apellido1" => $arContrato->getEmpleadoRel()->getApellido1(),
                        "apellido2" => $arContrato->getEmpleadoRel()->getApellido2(),
                        "nombreCorto" => $arContrato->getEmpleadoRel()->getNombreCorto(),
                        "correo" => $arContrato->getEmpleadoRel()->getCorreo(),));
                    $indiceRespuesta = substr($result, 0, 2);   
                    $contenidoRespuesta = substr($result, 2, strlen($result));
                    if ($indiceRespuesta == '01') {
                        $grupoPago = "";
                        $cargo = "";
                        $vigente = 0;
                        if ($arContrato->getEmpleadoRel()) {
                            if ($arContrato->getCargoRel()) {
                                $cargo = $arContrato->getCargoRel()->getNombre();
                            }                                  
                        }
                        if($arContrato->getCentroCostoRel()) {
                            $grupoPago = $arContrato->getCentroCostoRel()->getNombre();                            
                        }
                        if($arContrato->getEstadoActivo()) {
                            $vigente = 1;
                        }
                        $result = $cliente->call("getInsertarContrato", array(
                            "codigoEmpresa" => $arConfiguracion->getCodigoEmpresaArdid(),
                            "numero" => $arContrato->getNumero(),
                            "codigo" => $arContrato->getCodigoContratoPk(),                            
                            "codigoClase" => $arContrato->getCodigoContratoClaseFk(),                            
                            "codigoIdentificacionTipo" => $arContrato->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                            "identificacionNumero" => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),                            
                            "fechaDesde" => $arContrato->getFechaDesde()->format('Y-m-d'),
                            "fechaHasta" => $arContrato->getFechaHasta()->format('Y-m-d'),
                            "cargo" => $cargo,
                            "grupoDePago" => $grupoPago,                            
                            "vrSalario" => $arContrato->getVrSalario(),
                            "vigente" => $vigente
                        ));
                        $indiceRespuesta = substr($result, 0, 2);   
                        $contenidoRespuesta = substr($result, 2, strlen($result));   
                        if ($indiceRespuesta == '02') {
                            $objMensaje->Mensaje("error", "Se presento un error con el servicio web trasmitiendo el contrato " . $arContrato->getCodigoContratoPk() . ":" . $contenidoRespuesta);
                            $error = TRUE;
                            break;
                        }
                    } else {
                            $objMensaje->Mensaje("error", "Se presento un error con el servicio web trasmitiendo el empleado del contrato  " . $arContrato->getCodigoContratoPk() . ":" . $contenidoRespuesta);
                            $error = TRUE;
                            break;
                    }
                }
                /*if($error == FALSE) {
                    $arProgramacionPago->setEstadoExportadoArdid(1);
                    $em->persist($arProgramacionPago);
                    $em->flush();                            
                } */                                   
            }
            return $this->redirect($this->generateUrl('brs_rhu_utilidad_intercambio_ardid_contrato'));
        }        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Intercambio/Ardid:contrato.html.twig', array(                    
                    'form' => $form->createView()));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
                ->add('BtnSincronizar', SubmitType::class, array('label'  => 'Sincronizar',))
                ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQL(
                "", "", "", "", 1, "", 0
        );
    }

}
