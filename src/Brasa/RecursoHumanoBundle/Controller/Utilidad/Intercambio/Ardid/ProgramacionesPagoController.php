<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\Intercambio\Ardid;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProgramacionesPagoController extends Controller {

    var $strDqlLista = "";
    var $intNumero = 0;

    /**
     * @Route("/rhu/utilidad/intercambio/ardid/programaciones", name="brs_rhu_utilidad_intercambio_ardid_programacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /* if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 1, 1)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            if ($request->request->get('OpTransferir')) {
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                if ($arConfiguracion->getCodigoEmpresaArdid() != 0) {
                    $codigoProgramacionPago = $request->request->get('OpTransferir');
                    $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                    if ($arProgramacionPago->getEstadoExportadoArdid() == 0) {
                        $direccionServidor = $arConfiguracion->getDireccionServidorArdid();
                        $cliente = new \nusoap_client($direccionServidor);
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        //$arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago), array(), 1);
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                        foreach ($arPagos as $arPago) {
                            $result = $cliente->call("getInsertarEmpleado", array(
                                "codigoIdentificacionTipo" => $arPago->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                                "identificacionNumero" => $arPago->getEmpleadoRel()->getNumeroIdentificacion(),
                                "nombre1" => $arPago->getEmpleadoRel()->getNombre1(),
                                "nombre2" => $arPago->getEmpleadoRel()->getNombre2(),
                                "apellido1" => $arPago->getEmpleadoRel()->getApellido1(),
                                "apellido2" => $arPago->getEmpleadoRel()->getApellido2(),
                                "nombreCorto" => $arPago->getEmpleadoRel()->getNombreCorto(),
                                "correo" => $arPago->getEmpleadoRel()->getCorreo(),));
                            echo "emp" . $result;
                            if ($result == '01') {
                                $pension = "";
                                $salud = "";
                                $cargo = "";
                                if ($arPago->getEmpleadoRel()) {
                                    if ($arPago->getEmpleadoRel()->getEntidadPensionRel()) {
                                        $pension = $arPago->getEmpleadoRel()->getEntidadPensionRel()->getNombre();
                                    }
                                    if ($arPago->getEmpleadoRel()->getEntidadSaludRel()) {
                                        $salud = $arPago->getEmpleadoRel()->getEntidadSaludRel()->getNombre();
                                    }
                                    if ($arPago->getEmpleadoRel()->getCargoRel()) {
                                        $cargo = $arPago->getEmpleadoRel()->getCargoRel()->getNombre();
                                    }
                                }
                                $result = $cliente->call("getInsertarPago", array(
                                    "codigoIdentificacionTipo" => $arPago->getEmpleadoRel()->getTipoIdentificacionRel()->getCodigoInterface(),
                                    "identificacionNumero" => $arPago->getEmpleadoRel()->getNumeroIdentificacion(),
                                    "codigoEmpresa" => $arConfiguracion->getCodigoEmpresaArdid(),
                                    "numero" => $arPago->getNumero(),
                                    "codigoPagoTipo" => $arPago->getCodigoPagoTipoFk(),
                                    "fechaDesde" => $arPago->getFechaDesde()->format('Y-m-d'),
                                    "fechaHasta" => $arPago->getFechaHasta()->format('Y-m-d'),
                                    "vrSalario" => $arPago->getVrSalario(),
                                    "vrSalarioEmpleado" => $arPago->getVrSalarioEmpleado(),
                                    "vrDeduccion" => $arPago->getVrDeducciones(),
                                    "vrNeto" => $arPago->getVrNeto(),
                                    "vrDevengado" => $arPago->getVrDevengado(),
                                    "cargo" => $cargo,
                                    "grupoDePago" => $arPago->getCentroCostoRel()->getNombre(),
                                    "zona" => $arPago->getEmpleadoRel()->getZonaRel()->getNombre(),
                                    "periodoPago" => $arPago->getCentroCostoRel()->getPeriodoPagoRel()->getNombre(),
                                    "cuenta" => $arPago->getEmpleadoRel()->getCuenta(),
                                    "banco" => $arPago->getEmpleadoRel()->getBancoRel()->getNombre(),
                                    "pension" => $pension,
                                    "salud" => $salud
                                ));
                                echo "pago" . $result;
                                if ($result == '01') {
                                    $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $arPago->getCodigoPagoPk()));
                                    foreach ($arPagoDetalles as $arPagoDetalle) {
                                        $result = $cliente->call("getInsertarPagoDetalle", array(
                                            "codigoEmpresa" => $arConfiguracion->getCodigoEmpresaArdid(),
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
                        $arProgramacionPago->setEstadoExportadoArdid(1);
                        $em->persist($arProgramacionPago);
                        $em->flush();
                    }                    
                }
            }
            return $this->redirect($this->generateUrl('brs_rhu_utilidad_intercambio_ardid_programacion'));
        }
        $arProgramacionPago = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->getInt('page', 1)/* page number */, 50/* limit per page */);
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
                "", "", "", "", 1, "", 0
        );
    }

}
