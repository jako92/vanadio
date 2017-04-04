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
                //$arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array(), array(), 5);
                $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findAll();
                foreach ($arContratos as $arContrato) {
                    $lugarExpedidionIdentificacion = "";
                    if($arContrato->getEmpleadoRel()->getCodigoCiudadExpedicionFk()) {
                        $lugarExpedidionIdentificacion = $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre();
                    }
                    $result = $cliente->call("getInsertarEmpleado", array(
                        "codigoIdentificacionTipo" => $arContrato->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                        "identificacionNumero" => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                        "lugarExpedicionIdentificacion" => $lugarExpedidionIdentificacion,
                        "nombre1" => utf8_decode($arContrato->getEmpleadoRel()->getNombre1()),
                        "nombre2" => utf8_decode($arContrato->getEmpleadoRel()->getNombre2()),
                        "apellido1" => utf8_decode($arContrato->getEmpleadoRel()->getApellido1()),
                        "apellido2" => utf8_decode($arContrato->getEmpleadoRel()->getApellido2()),
                        "nombreCorto" => utf8_decode($arContrato->getEmpleadoRel()->getNombreCorto()),
                        "correo" => $arContrato->getEmpleadoRel()->getCorreo(),));
                    $indiceRespuesta = substr($result, 0, 2);   
                    $contenidoRespuesta = substr($result, 2, strlen($result));
                    if ($indiceRespuesta == '01') {
                        $grupoPago = "";
                        $cargo = "";
                        $vigente = 0;
                        $auxilioTransporte = 0;
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
                        if($arContrato->getAuxilioTransporte()) {
                            $auxilioTransporte = 1;
                        }
                        $result = $cliente->call("getInsertarContrato", array(                            
                            "codigoEmpresa" => $arConfiguracion->getCodigoEmpresaArdid(),
                            "codigo" => $arContrato->getCodigoContratoPk(),                            
                            "tipo" => utf8_decode($arContrato->getContratoTipoRel()->getNombre()),
                            "numero" => $arContrato->getNumero(),                            
                            "codigoClase" => $arContrato->getCodigoContratoClaseFk(),                            
                            "codigoIdentificacionTipo" => $arContrato->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                            "identificacionNumero" => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),                            
                            "fechaDesde" => $arContrato->getFechaDesde()->format('Y-m-d'),
                            "fechaHasta" => $arContrato->getFechaHasta()->format('Y-m-d'),
                            "cargo" => $cargo,
                            "grupoDePago" => $grupoPago,                            
                            "vrSalario" => $arContrato->getVrSalario(),
                            "vigente" => $vigente,
                            "auxilioTransporte" => $auxilioTransporte
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
            if($form->get('BtnSincronizarProgramacion')->isClicked()) {               
                set_time_limit(0);
                ini_set("memory_limit", -1);  
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $direccionServidor = $arConfiguracion->getDireccionServidorArdid();
                $cliente = new \nusoap_client($direccionServidor);
                $error = FALSE;
                $arSoportePagoProgramaciones = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();                                
                $arSoportePagoProgramaciones = $em->getRepository('BrasaTurnoBundle:TurSoportePagoProgramacion')->findBy(array('exportadoArdid' => 0));
                foreach ($arSoportePagoProgramaciones as $arSoportePagoProgramacion) {                                    
                    $result = $cliente->call("getInsertarProgramacion", array(                            
                        "codigoEmpresa" => $arConfiguracion->getCodigoEmpresaArdid(),
                        "codigoSoportePago" => $arSoportePagoProgramacion->getCodigoSoportePagoFk(),                            
                        "dia1" => $arSoportePagoProgramacion->getDia1(),
                        "dia2" => $arSoportePagoProgramacion->getDia2(),
                        "dia3" => $arSoportePagoProgramacion->getDia3(),
                        "dia4" => $arSoportePagoProgramacion->getDia4(),
                        "dia5" => $arSoportePagoProgramacion->getDia5(),
                        "dia6" => $arSoportePagoProgramacion->getDia6(),
                        "dia7" => $arSoportePagoProgramacion->getDia7(),
                        "dia8" => $arSoportePagoProgramacion->getDia8(),
                        "dia9" => $arSoportePagoProgramacion->getDia9(),
                        "dia10" => $arSoportePagoProgramacion->getDia10(),
                        "dia11" => $arSoportePagoProgramacion->getDia11(),
                        "dia12" => $arSoportePagoProgramacion->getDia12(),
                        "dia13" => $arSoportePagoProgramacion->getDia13(),
                        "dia14" => $arSoportePagoProgramacion->getDia14(),
                        "dia15" => $arSoportePagoProgramacion->getDia15(),
                        "dia16" => $arSoportePagoProgramacion->getDia16(),
                        "dia17" => $arSoportePagoProgramacion->getDia17(),
                        "dia18" => $arSoportePagoProgramacion->getDia18(),
                        "dia19" => $arSoportePagoProgramacion->getDia19(),
                        "dia20" => $arSoportePagoProgramacion->getDia20(),
                        "dia21" => $arSoportePagoProgramacion->getDia21(),
                        "dia22" => $arSoportePagoProgramacion->getDia22(),
                        "dia23" => $arSoportePagoProgramacion->getDia23(),
                        "dia24" => $arSoportePagoProgramacion->getDia24(),
                        "dia25" => $arSoportePagoProgramacion->getDia25(),
                        "dia26" => $arSoportePagoProgramacion->getDia26(),
                        "dia27" => $arSoportePagoProgramacion->getDia27(),
                        "dia28" => $arSoportePagoProgramacion->getDia28(),
                        "dia29" => $arSoportePagoProgramacion->getDia29(),
                        "dia30" => $arSoportePagoProgramacion->getDia30(),
                        "dia31" => $arSoportePagoProgramacion->getDia31(),                                                        
                    ));
                    $indiceRespuesta = substr($result, 0, 2);   
                    $contenidoRespuesta = substr($result, 2, strlen($result));   
                    if ($indiceRespuesta == '02') {
                        $objMensaje->Mensaje("error", "Se presento un error con el servicio web trasmitiendo el programacion " . $arSoportePagoProgramacion->getCodigoSoportePagoProgramacionPk() . ":" . $contenidoRespuesta);
                        $error = TRUE;
                        break;
                    }
                    if ($indiceRespuesta == '01') {
                        $arSoportePagoProgramacionActualizar = new \Brasa\TurnoBundle\Entity\TurSoportePagoProgramacion();                                
                        $arSoportePagoProgramacionActualizar = $em->getRepository('BrasaTurnoBundle:TurSoportePagoProgramacion')->find($arSoportePagoProgramacion->getCodigoSoportePagoProgramacionPk());                            
                        $arSoportePagoProgramacionActualizar->setExportadoArdid(1);
                        $em->persist($arSoportePagoProgramacionActualizar);
                    }
                }
                $em->flush();
            }            
            return $this->redirect($this->generateUrl('brs_rhu_utilidad_intercambio_ardid_contrato'));
        }        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Intercambio/Ardid:contrato.html.twig', array(                    
                    'form' => $form->createView()));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
                ->add('BtnSincronizar', SubmitType::class, array('label'  => 'Sincronizar',))
                ->add('BtnSincronizarProgramacion', SubmitType::class, array('label'  => 'Sincronizar programacion',))
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
