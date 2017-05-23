<?php

namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurCierreMesType;

class CierreMesController extends Controller {

    /**
     * @Route("/tur/proceso/cierre/mes", name="brs_tur_proceso_cierre_mes")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 8)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioGenerar();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if($request->request->get('OpGenerar')) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);
                    $codigoCierreMes = $request->request->get('OpGenerar');
                    $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                    $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                    $strSql = "DELETE FROM tur_cierre_mes_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();
                    $em->getConnection()->executeQuery($strSql);

                    $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arCierreMes->getMes() + 1, 1, $arCierreMes->getAnio()) - 1));
                    $strFechaDesde = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/01";
                    $strFechaHasta = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/" . $strUltimoDiaMes;
                    //Costos del mes          
                    $arCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCosto();
                    $arCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCosto')->findBy(array('anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes()));
                    foreach ($arCostos as $arCostoRecursoHumano) {
                        $costoRecurso = $arCostoRecursoHumano->getVrTotal();
                        $devengado = $arCostoRecursoHumano->getVrNomina();
                        $seguridadSocial = $arCostoRecursoHumano->getVrSeguridadSocial();
                        $prestaciones = $arCostoRecursoHumano->getVrPrestacion();
                        $arCosto = new \Brasa\TurnoBundle\Entity\TurCosto();
                        $arCosto->setCierreMesRel($arCierreMes);
                        $arCosto->setEmpleadoRel($arCostoRecursoHumano->getEmpleadoRel());
                        $arCosto->setAnio($arCierreMes->getAnio());
                        $arCosto->setMes($arCierreMes->getMes());
                        $arCosto->setVrNomina($devengado);
                        $arCosto->setVrPrestaciones($prestaciones);
                        $arCosto->setVrAportesSociales($seguridadSocial);
                        $arCosto->setVrCostoTotal($costoRecurso);

                        $arCentroCostoParticipacion = NULL;
                        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arCostoRecursoHumano->getCodigoEmpleadoFk());
                        if ($arRecurso) {
                            $dql = "SELECT spd.codigoPedidoDetalleFk, "
                                    . "SUM(spd.horasDescanso) as horasDescanso, "
                                    . "SUM(spd.horasDiurnas) as horasDiurnas, "
                                    . "SUM(spd.horasNocturnas) as horasNocturnas, "
                                    . "SUM(spd.horasFestivasDiurnas) as horasFestivasDiurnas, "
                                    . "SUM(spd.horasFestivasNocturnas) as horasFestivasNocturnas, "
                                    . "SUM(spd.horasExtrasOrdinariasDiurnas) as horasExtrasOrdinariasDiurnas, "
                                    . "SUM(spd.horasExtrasOrdinariasNocturnas) as horasExtrasOrdinariasNocturnas, "
                                    . "SUM(spd.horasExtrasFestivasDiurnas) as horasExtrasFestivasDiurnas, "
                                    . "SUM(spd.horasExtrasFestivasNocturnas) as horasExtrasFestivasNocturnas, "
                                    . "SUM(spd.horasRecargoNocturno) as horasRecargoNocturno, "
                                    . "SUM(spd.horasRecargoFestivoDiurno) as horasRecargoFestivoDiurno, "
                                    . "SUM(spd.horasRecargoFestivoNocturno) as horasRecargoFestivoNocturno, "
                                    . "SUM(spd.horasDescanso)*100 as pDS, "
                                    . "SUM(spd.horasDiurnas)*100 as pD, "
                                    . "SUM(spd.horasNocturnas)*135 as pN, "
                                    . "SUM(spd.horasFestivasDiurnas)*175 as pFD, "
                                    . "SUM(spd.horasFestivasNocturnas)*210 as pFN, "
                                    . "SUM(spd.horasExtrasOrdinariasDiurnas)*125 as pEOD, "
                                    . "SUM(spd.horasExtrasOrdinariasNocturnas)*175 as pEON, "
                                    . "SUM(spd.horasExtrasFestivasDiurnas)*200 as pEFD, "
                                    . "SUM(spd.horasExtrasFestivasNocturnas)*250 as pEFN, "
                                    . "SUM(spd.horasRecargoNocturno)*35 as pRN, "
                                    . "SUM(spd.horasRecargoFestivoDiurno)*75 as pRFD, "
                                    . "SUM(spd.horasRecargoFestivoNocturno)*110 as pRFN "
                                    . "FROM BrasaTurnoBundle:TurSoportePagoDetalle spd "
                                    . "WHERE spd.anio =  " . $arCierreMes->getAnio() . " AND spd.mes =  " . $arCierreMes->getMes() . " AND spd.codigoRecursoFk = " . $arRecurso->getCodigoRecursoPk() . " "
                                    . "GROUP BY spd.codigoPedidoDetalleFk";
                            $query = $em->createQuery($dql);
                            $arrayResultados = $query->getResult();
                            $pesoTotal = 0;
                            foreach ($arrayResultados as $detalle) {
                                $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                                $pesoTotal += $peso;
                            }
                            $participacionMayor = 0;
                            foreach ($arrayResultados as $detalle) {
                                $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($detalle['codigoPedidoDetalleFk']);
                                $arCostoDetalle = new \Brasa\TurnoBundle\Entity\TurCostoDetalle();
                                $arCostoDetalle->setAnio($arCierreMes->getAnio());
                                $arCostoDetalle->setMes($arCierreMes->getMes());
                                $arCostoDetalle->setCodigoCierreMesFk($arCierreMes->getCodigoCierreMesPk());
                                $arCostoDetalle->setEmpleadoRel($arCostoRecursoHumano->getEmpleadoRel());
                                $arCostoDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                $arCostoDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                                $arCostoDetalle->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                                $arCostoDetalle->setHorasDescanso($detalle['horasDescanso']);
                                $arCostoDetalle->setHorasDiurnas($detalle['horasDiurnas']);
                                $arCostoDetalle->setHorasNocturnas($detalle['horasNocturnas']);
                                $arCostoDetalle->setHorasFestivasDiurnas($detalle['horasFestivasDiurnas']);
                                $arCostoDetalle->setHorasFestivasNocturnas($detalle['horasFestivasNocturnas']);
                                $arCostoDetalle->setHorasExtrasOrdinariasDiurnas($detalle['horasExtrasOrdinariasDiurnas']);
                                $arCostoDetalle->setHorasExtrasOrdinariasNocturnas($detalle['horasExtrasOrdinariasNocturnas']);
                                $arCostoDetalle->setHorasExtrasFestivasDiurnas($detalle['horasExtrasFestivasDiurnas']);
                                $arCostoDetalle->setHorasExtrasFestivasNocturnas($detalle['horasExtrasFestivasNocturnas']);
                                $arCostoDetalle->setHorasRecargoNocturno($detalle['horasRecargoNocturno']);
                                $arCostoDetalle->setHorasRecargoFestivoDiurno($detalle['horasRecargoFestivoDiurno']);
                                $arCostoDetalle->setHorasRecargoFestivoNocturno($detalle['horasRecargoFestivoNocturno']);

                                $peso = $detalle['pDS'] + $detalle['pD'] + $detalle['pN'] + $detalle['pFD'] + $detalle['pFN'] + $detalle['pEOD'] + $detalle['pEON'] + $detalle['pEFD'] + $detalle['pEFN'] + $detalle['pRN'] + $detalle['pRFD'] + $detalle['pRFN'];
                                $participacionRecurso = 0;
                                if ($peso > 0) {
                                    $participacionRecurso = $peso / $pesoTotal;
                                }
                                $costoDetalle = $participacionRecurso * $costoRecurso;
                                $costoDetalleNomina = $participacionRecurso * $devengado;
                                $costoDetalleSeguridadSocial = $participacionRecurso * $seguridadSocial;
                                $costoDetallePrestaciones = $participacionRecurso * $prestaciones;
                                $participacion = 0;

                                if ($detalle['pDS'] > 0) {
                                    $participacion = $detalle['pDS'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasDescansoCosto($costo);

                                $participacion = 0;
                                if ($detalle['pD'] > 0) {
                                    $participacion = $detalle['pD'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasDiurnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pN'] > 0) {
                                    $participacion = $detalle['pN'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasNocturnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pFD'] > 0) {
                                    $participacion = $detalle['pFD'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasFestivasDiurnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pFN'] > 0) {
                                    $participacion = $detalle['pFN'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasFestivasNocturnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pEOD'] > 0) {
                                    $participacion = $detalle['pEOD'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasExtrasOrdinariasDiurnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pEON'] > 0) {
                                    $participacion = $detalle['pEON'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasExtrasOrdinariasNocturnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pEFD'] > 0) {
                                    $participacion = $detalle['pEFD'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasExtrasFestivasDiurnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pEFN'] > 0) {
                                    $participacion = $detalle['pEFN'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasExtrasFestivasNocturnasCosto($costo);

                                $participacion = 0;
                                if ($detalle['pRN'] > 0) {
                                    $participacion = $detalle['pRN'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasRecargoNocturnoCosto($costo);

                                $participacion = 0;
                                if ($detalle['pRFD'] > 0) {
                                    $participacion = $detalle['pRFD'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasRecargoFestivoDiurnoCosto($costo);

                                $participacion = 0;
                                if ($detalle['pRFN'] > 0) {
                                    $participacion = $detalle['pRFN'] / $peso;
                                }
                                $costo = $participacion * $costoDetalle;
                                $arCostoDetalle->setHorasRecargoFestivoNocturnoCosto($costo);
                                $participacionRecurso = $participacionRecurso * 100;
                                $arCostoDetalle->setParticipacion($participacionRecurso);
                                $arCostoDetalle->setPeso($peso);
                                $arCostoDetalle->setCosto($costoDetalle);
                                $arCostoDetalle->setCostoNomina($costoDetalleNomina);
                                $arCostoDetalle->setCostoSeguridadSocial($costoDetalleSeguridadSocial);
                                $arCostoDetalle->setCostoPrestaciones($costoDetallePrestaciones);
                                $arCostoDetalle->setCentroCostoRel($arPedidoDetalle->getPuestoRel()->getCentroCostoContabilidadRel());
                                $em->persist($arCostoDetalle);
                                if ($participacionMayor < $participacionRecurso) {
                                    $participacionMayor = $participacionRecurso;
                                    $arCentroCostoParticipacion = $arPedidoDetalle->getPuestoRel()->getCentroCostoContabilidadRel();
                                }
                            }
                        }

                        if ($arCostoRecursoHumano->getEmpleadoRel()->getEmpleadoTipoRel()->getOperativo() == 0 || $arCostoRecursoHumano->getEmpleadoRel()->getCentroCostoFijo() == 1) {
                            $arCosto->setCentroCostoRel($arCostoRecursoHumano->getEmpleadoRel()->getCentroCostoContabilidadRel());
                        } else {
                            if ($arCentroCostoParticipacion) {
                                $arCosto->setCentroCostoRel($arCentroCostoParticipacion);
                            }
                        }
                        $em->persist($arCosto);
                    }
                    $em->flush();

                    //Costos de los servicios del mes                
                    $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidosDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->fecha($strFechaDesde, $strFechaHasta);
                    foreach ($arPedidosDetalles as $arPedidoDetalle) {
                        $dql = "SELECT SUM(cd.costo) as costo "
                                . "FROM BrasaTurnoBundle:TurCostoDetalle cd "
                                . "WHERE cd.anio =  " . $arCierreMes->getAnio() . " AND cd.mes =  " . $arCierreMes->getMes() . " AND cd.codigoPedidoDetalleFk = " . $arPedidoDetalle->getCodigoPedidoDetallePk();
                        $query = $em->createQuery($dql);
                        $arrayResultados = $query->getResult();
                        $costo = 0;
                        if ($arrayResultados[0]['costo']) {
                            $costo = $arrayResultados[0]['costo'];
                        }
                        $arCostoServicio = new \Brasa\TurnoBundle\Entity\TurCostoServicio();
                        $arCostoServicio->setCierreMesRel($arCierreMes);
                        $arCostoServicio->setAnio($arCierreMes->getAnio());
                        $arCostoServicio->setMes($arCierreMes->getMes());
                        $arCostoServicio->setPedidoDetalleRel($arPedidoDetalle);
                        $arCostoServicio->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                        $arCostoServicio->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arCostoServicio->setConceptoServicioRel($arPedidoDetalle->getConceptoServicioRel());
                        $arCostoServicio->setModalidadServicioRel($arPedidoDetalle->getModalidadServicioRel());
                        $arCostoServicio->setPeriodoRel($arPedidoDetalle->getPeriodoRel());
                        $arCostoServicio->setDiaDesde($arPedidoDetalle->getDiaDesde());
                        $arCostoServicio->setDiaHasta($arPedidoDetalle->getDiaHasta());
                        $arCostoServicio->setDias($arPedidoDetalle->getDias());
                        $arCostoServicio->setHoras($arPedidoDetalle->getHoras());
                        $arCostoServicio->setHorasDiurnas($arPedidoDetalle->getHorasDiurnas());
                        $arCostoServicio->setHorasNocturnas($arPedidoDetalle->getHorasNocturnas());
                        $arCostoServicio->setCantidad($arPedidoDetalle->getCantidad());
                        $arCostoServicio->setVrTotal($arPedidoDetalle->getVrSubtotal());
                        $arCostoServicio->setVrCostoRecurso($costo);
                        $em->persist($arCostoServicio);
                    }
                    $em->flush();

                    //Crear los centros de costo y puestos donde trabajo el recurso
                    foreach ($arCostos as $arCostoRecursoHumano) {
                        if ($arCostoRecursoHumano->getEmpleadoRel()->getEmpleadoTipoRel()->getOperativo() == 0 || $arCostoRecursoHumano->getEmpleadoRel()->getCentroCostoFijo() == 1) {
                            $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                            $arEmpleadoCentroCosto->setAnio($arCierreMes->getAnio());
                            $arEmpleadoCentroCosto->setMes($arCierreMes->getMes());
                            $arEmpleadoCentroCosto->setCodigoEmpleadoFk($arCostoRecursoHumano->getCodigoEmpleadoFk());
                            $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arCostoRecursoHumano->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk());
                            $arEmpleadoCentroCosto->setCodigoPuestoFk($arCostoRecursoHumano->getEmpleadoRel()->getCodigoPuestoFk());
                            $arEmpleadoCentroCosto->setParticipacion(100);
                            $em->persist($arEmpleadoCentroCosto);
                        } else {
                            $arCostoDetalles = new \Brasa\TurnoBundle\Entity\TurCostoDetalle();
                            $arCostoDetalles = $em->getRepository('BrasaTurnoBundle:TurCostoDetalle')->findBy(array('codigoEmpleadoFk' => $arCostoRecursoHumano->getCodigoEmpleadoFk(), 'anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes()));
                            foreach ($arCostoDetalles as $arCostoDetalle) {
                                $arEmpleadoCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                                $arEmpleadoCentroCosto->setAnio($arCierreMes->getAnio());
                                $arEmpleadoCentroCosto->setMes($arCierreMes->getMes());
                                $arEmpleadoCentroCosto->setCodigoEmpleadoFk($arCostoRecursoHumano->getCodigoEmpleadoFk());
                                $arEmpleadoCentroCosto->setParticipacion($arCostoDetalle->getParticipacion());
                                if ($arCostoDetalle->getCentroCostoRel()) {
                                    $arEmpleadoCentroCosto->setCodigoCentroCostoFk($arCostoDetalle->getCentroCostoRel()->getCodigoCentroCostoPk());
                                }
                                if ($arCostoDetalle->getPuestoRel()) {
                                    $arEmpleadoCentroCosto->setCodigoPuestoFk($arCostoDetalle->getPuestoRel()->getCodigoPuestoPk());
                                }

                                $em->persist($arEmpleadoCentroCosto);
                            }
                        }
                    }
                    $em->flush();

                    //Asignar los centros de costos donde mas trabajo el recurso       
                    /*
                      foreach ($arrRecursos as $arrRecurso) {
                      $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                      $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arrRecurso['codigo_empleado_fk']);
                      if($arEmpleado) {
                      if($arEmpleado->getEmpleadoTipoRel()->getOperativo() == 1) {
                      $arrProgramaciones = $em->getRepository('BrasaTurnoBundle:TurRecurso')->programacionFechaRecurso($arCierreMes->getAnio(), $arCierreMes->getMes(), $arrRecurso['codigo_recurso_fk']);
                      if($arrProgramaciones) {
                      $codigoPuesto = $arrProgramaciones[0]['codigo_puesto_fk'];
                      if($codigoPuesto) {
                      $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
                      if($arPuesto) {
                      $arEmpleado->setPuestoRel($arPuesto);
                      $arEmpleado->setCentroCostoContabilidadRel($arPuesto->getCentroCostoContabilidadRel());
                      $em->persist($arEmpleado);

                      $arRecursoPuesto = new \Brasa\TurnoBundle\Entity\TurRecursoPuesto();
                      $arRecursoPuesto->setAnio($arCierreMes->getAnio());
                      $arRecursoPuesto->setMes($arCierreMes->getMes());
                      $arRecursoPuesto->setCodigoPuestoFk($codigoPuesto);
                      $arRecursoPuesto->setCodigoRecursoFk($arrRecurso['codigo_recurso_fk']);
                      $arRecursoPuesto->setCodigoEmpleadoFk($arrRecurso['codigo_empleado_fk']);
                      $arRecursoPuesto->setCodigoCentroCostoFk($arPuesto->getCodigoCentroCostoContabilidadFk());
                      $em->persist($arRecursoPuesto);
                      }
                      }
                      }
                      }
                      }
                      }
                      $em->flush();
                     */

                    $arCierreMes->setEstadoGenerado(1);
                    $em->persist($arCierreMes);
                    $em->flush();


                    return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
                }
                if($request->request->get('OpGenerarComercial')) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);
                    $codigoCierreMes = $request->request->get('OpGenerarComercial');
                    $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->generarCierreMesComercial($codigoCierreMes);
                    return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
                }
                if($request->request->get('OpDeshacer')) {
                    $codigoCierreMes = $request->request->get('OpDeshacer');
                    $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                    $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);

                    $strSql = "DELETE FROM tur_costo WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_costo_detalle WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_costo_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();
                    $em->getConnection()->executeQuery($strSql);

                    $arCierreMes->setEstadoGenerado(0);
                    $em->persist($arCierreMes);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
                }
                if($request->request->get('OpDeshacerComercial')) {
                    $codigoCierreMes = $request->request->get('OpDeshacerComercial');
                    $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                    $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);

                    $strSql = "DELETE FROM tur_ingreso_pendiente WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    /*$strSql = "DELETE FROM tur_costo_detalle WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_costo_servicio WHERE codigo_cierre_mes_fk = " . $codigoCierreMes;
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM tur_recurso_puesto WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();
                    $em->getConnection()->executeQuery($strSql);
                    $strSql = "DELETE FROM rhu_empleado_centro_costo WHERE anio = " . $arCierreMes->getAnio() . " AND mes = " . $arCierreMes->getMes();
                    $em->getConnection()->executeQuery($strSql);
                    */
                    $arCierreMes->setEstadoGeneradoComercial(0);
                    $em->persist($arCierreMes);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
                }                
                if($request->request->get('OpCerrar')) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);                    
                    $codigoCierreMes = $request->request->get('OpCerrar');
                    $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
                    $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                    $arCostos = new \Brasa\TurnoBundle\Entity\TurCosto();
                    $arCostos = $em->getRepository('BrasaTurnoBundle:TurCosto')->findBy(array('anio' => $arCierreMes->getAnio(), 'mes' => $arCierreMes->getMes()));
                    foreach ($arCostos as $arCosto) {                      
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();                                              
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arCosto->getCodigoEmpleadoFk());
                        if($arEmpleado->getCentroCostoFijo() == 0) {
                            $arEmpleado->setCentroCostoContabilidadRel($arCosto->getCentroCostoRel());
                            $em->persist($arEmpleado);
                        }                                                
                    }                  
                    $arCierreMes->setEstadoCerrado(1);
                    $em->persist($arCierreMes);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
                }
                if($form->get('BtnEliminar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaTurnoBundle:TurCierreMes')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
                }
            }
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->listaDql();
        $arCierreMes = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Procesos/CierreMes:lista.html.twig', array(
                    'arCierreMes' => $arCierreMes,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/proceso/cierre/mes/nuevo/{codigoCierreMes}", name="brs_tur_proceso_cierre_mes_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCierreMes) {
        $em = $this->getDoctrine()->getManager();
        $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
        if ($codigoCierreMes != 0) {
            $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
        }
        $form = $this->createForm(TurCierreMesType::class, $arCierreMes);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arCierreMes = $form->getData();
                $em->persist($arCierreMes);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_proceso_cierre_mes'));
            }
        }
        return $this->render('BrasaTurnoBundle:Procesos/CierreMes:nuevo.html.twig', array(
                    'arSoportePagoPeriodo' => $arCierreMes,
                    'form' => $form->createView()));
    }

    private function formularioGenerar() {
        $form = $this->createFormBuilder()
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->getForm();
        return $form;
    }

}
