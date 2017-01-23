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
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();            
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $direccionServidor = $arConfiguracion->getDireccionServidorArdid();
            $cliente = new \nusoap_client($direccionServidor);
            /*$result = $cliente->call("getInsertarEmpleado",
                    array(
                        "codigoIdentificacionTipo" => "CC",
                        "identificacionNumero" => "70143086",
                        "nombre1" => "MARIO",
                        "nombre2" => "ANDRES",
                        "apellido1" => "ESTRADA",
                        "apellido2" => "ZULUAGA",
                        "nombreCorto" => "MARIO ANDRES ESTRADA ZULUAGA",)); */
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
                                "nombreCorto" => $arPago->getEmpleadoRel()->getNombreCorto(),
                                "correo" => $arPago->getEmpleadoRel()->getCorreo(),));
                    echo "emp" . $result;
                    if($result == '01') {
                        $pension = "";
                        $salud = "";
                        if($arPago->getEmpleadoRel()) {
                            if($arPago->getEmpleadoRel()->getEntidadPensionRel()) {
                                $pension = $arPago->getEmpleadoRel()->getEntidadPensionRel()->getNombre();
                            }
                            if($arPago->getEmpleadoRel()->getEntidadSaludRel()) {
                                $salud = $arPago->getEmpleadoRel()->getEntidadSaludRel()->getNombre();
                            }
                        }
                        $result = $cliente->call("getInsertarPago",
                                array(
                                    "codigoIdentificacionTipo" => $arPago->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                                    "identificacionNumero" => $arPago->getEmpleadoRel()->getNumeroIdentificacion(),
                                    "codigoEmpresa" => 1,
                                    "numero" => $arPago->getNumero(),
                                    "codigoPagoTipo" => $arPago->getCodigoPagoTipoFk(),
                                    "fechaDesde" => $arPago->getFechaDesde()->format('Y-m-d'),
                                    "fechaHasta" => $arPago->getFechaDesde()->format('Y-m-d'),
                                    "vrSalario" => $arPago->getVrSalario(),
                                    "vrSalarioEmpleado" => $arPago->getVrSalarioEmpleado(),
                                    "vrDeduccion" => $arPago->getVrDeducciones(),
                                    "vrNeto" => $arPago->getVrNeto(),
                                    "vrDevengado" => $arPago->getVrDevengado(),
                                    "grupoDePago" => $arPago->getCentroCostoRel()->getNombre(),
                                    "zona" => $arPago->getEmpleadoRel()->getZonaRel()->getNombre(),
                                    "periodoPago" => $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre(),
                                    "cuenta" => $arPago->getEmpleadoRel()->getCuenta(),
                                    "banco" => $arPago->getEmpleadoRel()->getBancoRel()->getNombre(),
                                    "pension" => $pension,
                                    "salud" => $salud
                                ));
                        echo $result;
                        if($result == '01') {
                            $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                            $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
                            foreach ($arPagoDetalles as $arPagoDetalle) {
                                $result = $cliente->call("getInsertarPagoDetalle",
                                    array(
                                        "codigoEmpresa" => 1,
                                        "numero" => $arPago->getNumero(),
                                        "codigo" => $arPagoDetalle->getCodigoPagoDetallePk(),
                                        "codigoConcepto" => $arPagoDetalle->getCodigoPagoConceptoFk(),
                                        "nombreConcepto" => $arPagoDetalle->getPagoConceptoRel()->getNombre(),
                                        "operacion" => $arPagoDetalle->getOperacion(),
                                        "horas" => $arPagoDetalle->getNumeroHoras(),
                                        "dias" => $arPagoDetalle->getNumeroDias(),
                                        "porcentaje" => $arPagoDetalle->getPorcentajeAplicado(),
                                        "vrHora" => $arPagoDetalle->getVrHora(),
                                        "vrPago" => $arPagoDetalle->getVrPago()
                                    ));
                            }
                        }
                    }
                }
                //return $this->redirect($this->generateUrl('brs_rhu_utilidad_intercambio_ardid_programacion'));
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

