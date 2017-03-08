<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoPeriodoEmpleadoRepository extends EntityRepository {
    
    public function listaDql($codigoPeriodoDetalle, $strCodigoCentroCosto = "") {                    
            $dql   = "SELECT pe, e FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe JOIN pe.empleadoRel e "
                    ."WHERE pe.codigoPeriodoDetalleFk = " . $codigoPeriodoDetalle . " ";
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            return $dql;
    }
    
    public function listaTrasladoDql($codigoPeriodoDetalle, $strCodigoCentroCosto, $strCodigoSucursal, $numeroIdentificacion = "", $codigoPeriodo = "") {                    
            $dql   = "SELECT pe, e FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe JOIN pe.empleadoRel e "
                    ."WHERE pe.codigoPeriodoDetalleFk <> " . $codigoPeriodoDetalle . " AND pe.codigoPeriodoFk = $codigoPeriodo ";
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            if($strCodigoSucursal != "") {
                $dql .= " AND pe.codigoPeriodoDetalleFk = " . $strCodigoSucursal;
            }
            if($numeroIdentificacion != '') {
                $dql .= " AND e.numeroIdentificacion = " . $numeroIdentificacion;
            }            
            return $dql;
    }
    
    public function listaCopiarDql($codigoPeriodoDetalle, $strCodigoCentroCosto, $strCodigoSucursal, $numeroIdentificacion = "", $codigoPeriodo = "" ) {                    
            $dql   = "SELECT pe, e FROM BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado pe JOIN pe.empleadoRel e "
                    ."WHERE pe.codigoPeriodoDetalleFk <> " . $codigoPeriodoDetalle . " AND pe.codigoPeriodoFk = $codigoPeriodo ";
            if($strCodigoCentroCosto != "") {
                $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
            }
            if($strCodigoSucursal != "") {
                $dql .= " AND pe.codigoPeriodoDetalleFk = " . $strCodigoSucursal;
            }
            if($numeroIdentificacion != '') {
                $dql .= " AND e.numeroIdentificacion = " . $numeroIdentificacion;
            }
            return $dql;
    }
        
    public function actualizar($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $ibcMinimoDia = $arConfiguracionNomina->getVrSalario() / 30;
        $salarioMinimo = $arConfiguracionNomina->getVrSalario();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);             
        $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
        $arPeriodoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));     
        foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {
            $arPeriodoEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
            $arPeriodoEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($arPeriodoEmpleado->getCodigoPeriodoEmpleadoPk());                        
            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoEmpleado->getCodigoContratoFk());                            
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            $strNovedadIngreso = " ";
            $strNovedadRetiro = " ";
            $intDiasCotizar = 0;
            $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
            if($arContrato->getIndefinido() == 1) {
                $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
            } else {
                if($arContrato->getFechaHasta() > $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {
                    $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
                } else {
                    $dateFechaHasta = $arContrato->getFechaHasta();
                }
            }

            if($arContrato->getFechaDesde() <  $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde() == true) {
                $dateFechaDesde = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde();
            } else {
                $dateFechaDesde = $arContrato->getFechaDesde();
            }

            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');                        
                $intDiasCotizar = $intDias + 1;
                if($intDiasCotizar == 31) {
                    $intDiasCotizar = $intDiasCotizar - 1;
                } else {
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 28) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 2;
                        }
                    }
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 29) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 1;
                        }
                    }                    
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 31) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            if($arContrato->getFechaDesde()->format('d') != 31) {
                                $intDiasCotizar = $intDiasCotizar - 1;
                            }                                    
                        }
                    }                            
                }
            }
            
            if($arContrato->getFechaDesde() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()) {
                $strNovedadIngreso = "X";
            }
            if($arContrato->getIndefinido() == 0 && $arContrato->getFechaHasta() <= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {                    
                $strNovedadRetiro = "X";                    
            }
            $floSalario = $arContrato->getVrSalario();
            if($arContrato->getSalarioIntegral() == 1) {
                $arPeriodoEmpleadoActualizar->setSalarioIntegral('X');
            }
            $arPeriodoEmpleadoActualizar->setVrSalario($floSalario);            
            $arPeriodoEmpleadoActualizar->setIngreso($strNovedadIngreso);
            $arPeriodoEmpleadoActualizar->setRetiro($strNovedadRetiro);
            $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());            
            $arPeriodoEmpleadoActualizar->setVrSuplementario($floSuplementario);
            
            $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
            $arPeriodoEmpleadoActualizar->setDiasLicencia($intDiasLicencia);          
            $intDiasIncapacidadGeneral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
            $intDiasLicenciaMaternidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
            $arPeriodoEmpleadoActualizar->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
            $intDiasIncapacidadLaboral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
            $arPeriodoEmpleadoActualizar->setDiasIncapacidadLaboral($intDiasIncapacidadLaboral);                                                
            $arPeriodoEmpleadoActualizar->setDias($intDiasCotizar);
            $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->diasVacacionesDisfrute($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), $arPeriodoEmpleado->getCodigoContratoFk());
            if($strNovedadRetiro == 'X') {
                $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1));
                if($arLiquidacion) {
                    $arrVacaciones['aporte'] += $arLiquidacion->getVrVacaciones();
                }
            }            
            $arPeriodoEmpleadoActualizar->setDiasVacaciones($arrVacaciones['dias']);            
            $arPeriodoEmpleadoActualizar->setVrVacaciones($arrVacaciones['aporte']);
            $arPeriodoEmpleadoActualizar->setTarifaPension($arContrato->getTipoPensionRel()->getPorcentajeEmpleador());
            $arPeriodoEmpleadoActualizar->setTarifaRiesgos($arContrato->getClasificacionRiesgoRel()->getPorcentaje());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());
            if($arContrato->getCodigoTipoCotizanteFk() == 19) {
                $intDiasLaborados = $intDiasCotizar;
            } else {
               $intDiasLaborados = $intDiasCotizar - $intDiasLicencia; 
            }
            $arrIbc = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibc($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());                        
            $ibc = $arrIbc['ibc'];
            $ibc = round($ibc);
            $ibcMinimo = ($salarioMinimo / 30) * $intDiasLaborados;            
            if($ibc < $ibcMinimo) {
                $ibc = $ibcMinimo;
            }
            $arPeriodoEmpleadoActualizar->setHoras($arrIbc['horas']);
            $ibcSalario = round(($floSalario / 30) * $intDiasLaborados);
            if($ibcSalario != $ibc) {
                $arPeriodoEmpleadoActualizar->setVariacionTransitoriaSalario('X');
            }
            //Se quita porque ya en la generacion se calcula el 70%
            /*if($arContrato->getSalarioIntegral() == 1) {
                $ibc = ($ibc * 70) / 100;
            }*/
            $arPeriodoEmpleadoActualizar->setIbc($ibc);                        
            $em->persist($arPeriodoEmpleadoActualizar);
            $arPeriodoDetalle->setEstadoActualizado(1);
            $em->persist($arPeriodoDetalle);
        }
        $em->flush();            
        return true;
    }
    
    public function actualizar2($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $ibcMinimoDia = $arConfiguracionNomina->getVrSalario() / 30;
        $salarioMinimo = $arConfiguracionNomina->getVrSalario();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->find($codigoPeriodoDetalle);             
        $arPeriodoEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
        $arPeriodoEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->findBy(array('codigoPeriodoDetalleFk' => $codigoPeriodoDetalle));     
        foreach ($arPeriodoEmpleados as $arPeriodoEmpleado) {
            $arPeriodoEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
            $arPeriodoEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoEmpleado')->find($arPeriodoEmpleado->getCodigoPeriodoEmpleadoPk());                        
            $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arPeriodoEmpleado->getCodigoContratoFk());                            
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";
            $strNovedadIngreso = " ";
            $strNovedadRetiro = " ";
            $intDiasCotizar = 0;
            $fechaTerminaCotrato = $arContrato->getFechaHasta()->format('Y-m-d');
            if($arContrato->getIndefinido() == 1) {
                $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
            } else {
                if($arContrato->getFechaHasta() > $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {
                    $dateFechaHasta = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta();
                } else {
                    $dateFechaHasta = $arContrato->getFechaHasta();
                }
            }

            if($arContrato->getFechaDesde() <  $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde() == true) {
                $dateFechaDesde = $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde();
            } else {
                $dateFechaDesde = $arContrato->getFechaDesde();
            }

            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');                        
                $intDiasCotizar = $intDias + 1;
                if($intDiasCotizar == 31) {
                    $intDiasCotizar = $intDiasCotizar - 1;
                } else {
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 28) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 2;
                        }
                    }
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 29) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            $intDiasCotizar = $intDiasCotizar + 1;
                        }
                    }                    
                    if($arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('d') == 31) {
                        if($arContrato->getFechaHasta() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta() || $arContrato->getIndefinido() == 1) {
                            if($arContrato->getFechaDesde()->format('d') != 31) {
                                $intDiasCotizar = $intDiasCotizar - 1;
                            }                                    
                        }
                    }                            
                }
            }
            
            if($arContrato->getFechaDesde() >= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()) {
                $strNovedadIngreso = "X";
            }
            if($arContrato->getIndefinido() == 0 && $arContrato->getFechaHasta() <= $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()) {                    
                $strNovedadRetiro = "X";                    
            }
            $floSalario = $arContrato->getVrSalario();
            $diaSalario = $floSalario / 30;
            if($arContrato->getSalarioIntegral() == 1) {
                $arPeriodoEmpleadoActualizar->setSalarioIntegral('X');
            }
            $strSql = "DELETE FROM rhu_sso_periodo_empleado_detalle WHERE codigo_periodo_empleado_fk = " . $arPeriodoEmpleadoActualizar->getCodigoPeriodoEmpleadoPk();
            $em->getConnection()->executeQuery($strSql); 
            
            $arrIbpOrdinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibcOrdinario($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());                        
            
            $diasLicenciaTotal = 0;
            $diasIncapacidadTotal = 0;
            $diasVacacionesTotal = 0;
            $arrLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->licencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());                                                
            foreach ($arrLicencias as $arrLicencia) {
                $diasLicenciaTotal += $arrLicencia['dias'];
            }
            $arrIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->incapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());                                                
            foreach ($arrIncapacidades as $arrIncapacidad) {
               $diasIncapacidadTotal += $arrIncapacidad['dias']; 
            }
            $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->vacacion($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());                                    
            foreach ($arrVacaciones as $arrVacacion) {
                $diasVacacionesTotal += $arrVacacion['dias'];
            }
            $diasOrdinariosTotal = $intDiasCotizar - $diasLicenciaTotal - $diasIncapacidadTotal - $diasVacacionesTotal;
            // nos indica si ya aplico las novedades de ingreso y retiro para periodos sin dias ordinarios
            $novedadesIngresoRetiro = FALSE;
            
            //Vacaciones
            $diasVacaciones = 0;  
            $ibcVacaciones = 0;            
            foreach ($arrVacaciones as $arrVacacion) {
                $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();                
                $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($arrVacacion['codigoVacacionFk']);                
                $diasVacaciones += $arrVacacion['dias'];
                $ibcVacaciones += $arrVacacion['ibc'];
                $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
                $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
                $arPeriodoEmpleadoDetalle->setDias($arrVacacion['dias']);
                $arPeriodoEmpleadoDetalle->setHoras($arrVacacion['horas']);
                $arPeriodoEmpleadoDetalle->setVrSalario($floSalario);
                $arPeriodoEmpleadoDetalle->setIbc($arrVacacion['ibc']);
                //$arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacaciones);
                $arPeriodoEmpleadoDetalle->setVacaciones(1);
                $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);                            
                $arPeriodoEmpleadoDetalle->setTarifaSalud(4);                            
                $arPeriodoEmpleadoDetalle->setTarifaCaja(4);                   
                $arPeriodoEmpleadoDetalle->setFechaDesde(date_create($arrVacacion['fechaDesdeNovedad']));
                $arPeriodoEmpleadoDetalle->setFechaHasta(date_create($arrVacacion['fechaHastaNovedad']));
                $diaSalarioVacacion = $ibcVacaciones / $arrVacacion['dias'];
                if($diaSalarioVacacion != $diaSalario) {
                    $arPeriodoEmpleadoDetalle->setVariacionTransitoriaSalario('X');
                }                
                if($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
                    $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);            
                    if($strNovedadRetiro == 'X') {
                        $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                        $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1));
                        if($arLiquidacion) {
                            $ibcVacaciones = $arLiquidacion->getVrVacaciones();
                            $arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacaciones);
                        }
                    }   
                    if($strNovedadIngreso == "X") {
                        $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
                    }  
                    $novedadesIngresoRetiro = TRUE;
                }
                $em->persist($arPeriodoEmpleadoDetalle);
            }                        
            
            //Incapacidades
            $diasIncapacidad = 0;
            $diasIncapacidadLaboral = 0;            
            foreach ($arrIncapacidades as $arrIncapacidad) {
                $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
                $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad;                
                $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($arrIncapacidad['codigoIncapacidadFk']);                
                if($arIncapacidad->getIncapacidadTipoRel()->getTipo() == 1) {                    
                    $diasIncapacidad += $arrIncapacidad['dias'];
                    $arPeriodoEmpleadoDetalle->setIncapacidadGeneral(1);
                } else {
                    $diasIncapacidadLaboral += $arrIncapacidad['dias'];
                    $arPeriodoEmpleadoDetalle->setIncapacidadLaboral(1);
                }
                
                $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
                $arPeriodoEmpleadoDetalle->setDias($arrIncapacidad['dias']);
                $arPeriodoEmpleadoDetalle->setHoras($arrIncapacidad['horas']);
                $arPeriodoEmpleadoDetalle->setVrSalario($floSalario);
                $ibcIncapacidad = ceil($arrIncapacidad['ibc']);                
                $arPeriodoEmpleadoDetalle->setIbc($ibcIncapacidad);   
                $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);                            
                $arPeriodoEmpleadoDetalle->setTarifaSalud(4);                                 
                $arPeriodoEmpleadoDetalle->setFechaDesde(date_create($arrIncapacidad['fechaDesdeNovedad']));
                $arPeriodoEmpleadoDetalle->setFechaHasta(date_create($arrIncapacidad['fechaHastaNovedad']));             
                $diaSalarioLicencia = $ibcIncapacidad / $arrIncapacidad['dias'];
                if($diaSalarioLicencia != $diaSalario) {
                    $arPeriodoEmpleadoDetalle->setVariacionTransitoriaSalario('X');
                }
                if($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
                    $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);               
                    if($strNovedadRetiro == 'X') {
                        $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                    }                     
                    if($strNovedadIngreso == "X") {
                        $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
                    }  
                    $novedadesIngresoRetiro = TRUE;
                }                
                $em->persist($arPeriodoEmpleadoDetalle);
            }                           
            
            //Licencias
            $diasLicencia = 0;
            $diasLicenciaMaternidad = 0;            
            foreach ($arrLicencias as $arrLicencia) {
                $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
                $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();                
                $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($arrLicencia['codigoLicenciaFk']);
                
                if($arLicencia->getLicenciaTipoRel()->getMaternidad() == 1) {
                    $diasLicenciaMaternidad += $arrLicencia['dias'];
                    $arPeriodoEmpleadoDetalle->setLicenciaMaternidad(1);
                    $ibcLicencia = $arrLicencia['ibc'];                    
                    $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
                    $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);                                
                    $arPeriodoEmpleadoDetalle->setTarifaSalud(4);                               
                } else {
                    $ibcLicencia = $diaSalario * $arrLicencia['dias'];
                    $diasLicencia += $arrLicencia['dias'];
                    $arPeriodoEmpleadoDetalle->setLicencia(1);                    
                    $arPeriodoEmpleadoDetalle->setTarifaPension(12);
                    $arPeriodoEmpleadoDetalle->setTarifaSalud(0);
                }                    
                
                $arPeriodoEmpleadoDetalle->setIbc($ibcLicencia);
                $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
                $arPeriodoEmpleadoDetalle->setDias($arrLicencia['dias']);
                $arPeriodoEmpleadoDetalle->setHoras($arrLicencia['horas']);
                $arPeriodoEmpleadoDetalle->setVrSalario($floSalario);                                
                $arPeriodoEmpleadoDetalle->setFechaDesde(date_create($arrLicencia['fechaDesdeNovedad']));
                $arPeriodoEmpleadoDetalle->setFechaHasta(date_create($arrLicencia['fechaHastaNovedad']));           
                if($diasOrdinariosTotal <= 0 && $novedadesIngresoRetiro == FALSE) {
                    $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
                    $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);               
                    if($strNovedadRetiro == 'X') {
                        $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                    }                     
                    if($strNovedadIngreso == "X") {
                        $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
                    }  
                    $novedadesIngresoRetiro = TRUE;
                }                 
                $em->persist($arPeriodoEmpleadoDetalle);
            }            
            
            $diasOrdinarios = $intDiasCotizar - $diasLicenciaMaternidad - $diasLicencia - $diasIncapacidadLaboral - $diasIncapacidad - $diasVacaciones;
            $horasOrdinarias = $diasOrdinarios * 8;
            $ibc = $arrIbpOrdinario['ibc'];
            $ibc = round($ibc);
            $ibcMinimo = ($salarioMinimo / 30) * $diasOrdinarios;            
            if($ibc < $ibcMinimo) {
                $ibc = ceil($ibcMinimo);
            }            
            $arPeriodoEmpleadoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleadoDetalle();
            $arPeriodoEmpleadoDetalle->setSsoPeriodoEmpleadoRel($arPeriodoEmpleadoActualizar);
            $arPeriodoEmpleadoDetalle->setDias($diasOrdinarios);
            $arPeriodoEmpleadoDetalle->setHoras($horasOrdinarias);
            $arPeriodoEmpleadoDetalle->setVrSalario($floSalario);
            $arPeriodoEmpleadoDetalle->setIbc($ibc);            
            $porcentaje = $arContrato->getTipoPensionRel()->getPorcentajeEmpleador() + 4;
            $arPeriodoEmpleadoDetalle->setTarifaPension($porcentaje);                        
            $arPeriodoEmpleadoDetalle->setTarifaSalud(4);            
            $arPeriodoEmpleadoDetalle->setTarifaRiesgos($arContrato->getClasificacionRiesgoRel()->getPorcentaje());
            $arPeriodoEmpleadoDetalle->setTarifaCaja(4); 
            $arPeriodoEmpleadoDetalle->setIngreso($strNovedadIngreso);
            $arPeriodoEmpleadoDetalle->setRetiro($strNovedadRetiro);            
            if($strNovedadRetiro == 'X') {
                $arPeriodoEmpleadoDetalle->setFechaRetiro($arContrato->getFechaHasta());
                $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1));
                if($arLiquidacion) {
                    $ibcVacaciones = $arLiquidacion->getVrVacaciones();
                    $arPeriodoEmpleadoDetalle->setVrVacaciones($ibcVacaciones);
                }
            }   
            if($strNovedadIngreso == "X") {
                $arPeriodoEmpleadoDetalle->setFechaIngreso($arContrato->getFechaDesde());
            }            
            $diaSalarioOrdinario = 0;
            if($diasOrdinarios > 0) {
                $diaSalarioOrdinario = $ibc / $diasOrdinarios;
            } else {
                echo "Problema dias " . $arPeriodoEmpleado->getEmpleadoRel()->getNumeroIdentificacion() . " ";
            }
            
            if($diaSalarioOrdinario != $diaSalario) {
                $arPeriodoEmpleadoDetalle->setVariacionTransitoriaSalario('X');
            }         

            $em->persist($arPeriodoEmpleadoDetalle);            
            
            //$arrLicencias
            
//            $arPeriodoEmpleadoActualizar->setVrSalario($floSalario);            
//            $arPeriodoEmpleadoActualizar->setIngreso($strNovedadIngreso);
//            $arPeriodoEmpleadoActualizar->setRetiro($strNovedadRetiro);
//            $floSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementario($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());            
//            $arPeriodoEmpleadoActualizar->setVrSuplementario($floSuplementario);
//            
//            $intDiasLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
//            $arPeriodoEmpleadoActualizar->setDiasLicencia($intDiasLicencia);          
//            $intDiasIncapacidadGeneral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
//            $arPeriodoEmpleadoActualizar->setDiasIncapacidadGeneral($intDiasIncapacidadGeneral);
//            $intDiasLicenciaMaternidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->diasLicencia($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 1);
//            $arPeriodoEmpleadoActualizar->setDiasLicenciaMaternidad($intDiasLicenciaMaternidad);
//            $intDiasIncapacidadLaboral = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->diasIncapacidad($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), 2);
//            $arPeriodoEmpleadoActualizar->setDiasIncapacidadLaboral($intDiasIncapacidadLaboral);                                                
//            $arPeriodoEmpleadoActualizar->setDias($intDiasCotizar);
//            $arrVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->diasVacacionesDisfrute($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde(), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta(), $arPeriodoEmpleado->getCodigoEmpleadoFk(), $arPeriodoEmpleado->getCodigoContratoFk());
//            if($strNovedadRetiro == 'X') {
//                $arLiquidacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->findOneBy(array('codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'estadoAutorizado' => 1));
//                if($arLiquidacion) {
//                    $arrVacaciones['aporte'] += $arLiquidacion->getVrVacaciones();
//                }
//            }            
//            $arPeriodoEmpleadoActualizar->setDiasVacaciones($arrVacaciones['dias']);            
//            $arPeriodoEmpleadoActualizar->setVrVacaciones($arrVacaciones['aporte']);
//            $arPeriodoEmpleadoActualizar->setTarifaRiesgos($arContrato->getClasificacionRiesgoRel()->getPorcentaje());                        
            $arPeriodoEmpleadoActualizar->setCodigoEntidadPensionPertenece($arContrato->getEntidadPensionRel()->getCodigoInterface());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadSaludPertenece($arContrato->getEntidadSaludRel()->getCodigoInterface());
            $arPeriodoEmpleadoActualizar->setCodigoEntidadCajaPertenece($arContrato->getEntidadCajaRel()->getCodigoInterface());
//            if($arContrato->getCodigoTipoCotizanteFk() == 19) {
//                $intDiasLaborados = $intDiasCotizar;
//            } else {
//               $intDiasLaborados = $intDiasCotizar - $intDiasLicencia; 
//            }
//            $arrIbc = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->ibc($arPeriodoDetalle->getSsoPeriodoRel()->getFechaDesde()->format('Y-m-d'), $arPeriodoDetalle->getSsoPeriodoRel()->getFechaHasta()->format('Y-m-d'), $arContrato->getCodigoContratoPk());                        
//            $ibc = $arrIbc['ibc'];
//            $ibc = round($ibc);
//            $ibcMinimo = ($salarioMinimo / 30) * $intDiasLaborados;            
//            if($ibc < $ibcMinimo) {
//                $ibc = $ibcMinimo;
//            }
//            $arPeriodoEmpleadoActualizar->setHoras($arrIbc['horas']);
//            $ibcSalario = round(($floSalario / 30) * $intDiasLaborados);
//            if($ibcSalario != $ibc) {
//                $arPeriodoEmpleadoActualizar->setVariacionTransitoriaSalario('X');
//            }

            //Se quita porque ya en la generacion se calcula el 70%
            /*if($arContrato->getSalarioIntegral() == 1) {
                $ibc = ($ibc * 70) / 100;
            }*/
            $arPeriodoEmpleadoActualizar->setVrSalario($floSalario);                        
            //$arPeriodoEmpleadoActualizar->setVrVacaciones($ibcVacaciones);
            $arPeriodoEmpleadoActualizar->setDias($intDiasCotizar);        
            $arPeriodoEmpleadoActualizar->setIbc($ibc);                        
            $em->persist($arPeriodoEmpleadoActualizar);
            $arPeriodoDetalle->setEstadoActualizado(1);
            $em->persist($arPeriodoDetalle);
        }
        $em->flush();            
        return true;
    }   
    
    public function insertarDetalle($codigoPeriodoDetalle) {
        $em = $this->getEntityManager();    
    }
        
}