<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuVacacionRepository extends EntityRepository {        
    
    public function listaVacacionesDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $boolEstadoPagado = "", $boolEstadoAutorizado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT v, e FROM BrasaRecursoHumanoBundle:RhuVacacion v JOIN v.empleadoRel e WHERE v.codigoVacacionPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND v.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolEstadoPagado == 1 ) {
            $dql .= " AND v.estadoPagoGenerado = 1";
        } 
        if($boolEstadoPagado == '0') {
            $dql .= " AND v.estadoPagoGenerado = 0";
        }
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND v.estadoAutorizado = 1";
        } 
        if($boolEstadoAutorizado == '0') {
            $dql .= " AND v.estadoAutorizado = 0";
        }
        $dql .= " ORDER BY v.codigoVacacionPk DESC";
        return $dql;
    }

    public function liquidar($codigoVacacion) {        
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();            
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);                         
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $arVacacion->getContratoRel();
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        if ($fechaDesdePeriodo == null){
            $fechaDesdePeriodo = $arContrato->getFechaDesde();
        }
        $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);
        $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;        
        $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
        if ($fechaDesdePeriodo == null){
            $fechaDesdePeriodo = $arContrato->getFechaDesde();
        }
        $strFechaDesde = $fechaDesdePeriodo->format('Y-m-d');
        $strFechaDesde = strtotime ( '+1 day' , strtotime ( $strFechaDesde ) ) ;
        $strFechaDesde = date ( 'Y-m-d' , $strFechaDesde );        
        $fechaDesdePeriodo = date_create($strFechaDesde);       

        $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta($intDias, $fechaDesdePeriodo);
        $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
        $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);                        
        //365 dias reales 
        $interval = date_diff($fechaDesdePeriodo, $fechaHastaPeriodo);
        $diasPeriodo = $interval->format('%a');        
        
        //360 dias para que de 12 meses
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $diasPeriodo = $objFunciones->diasPrestaciones($fechaDesdePeriodo, $fechaHastaPeriodo);  
        $mesesPeriodo = $diasPeriodo / 30;        
        $arVacacion->setDiasPeriodo($diasPeriodo);
        $arVacacion->setMesesPeriodo($mesesPeriodo);
        $intDias = $arVacacion->getDiasVacaciones();
        $floSalario = $arVacacion->getEmpleadoRel()->getVrSalario();        
        //Analizar cambios de salario
        $fecha = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime ( '-90 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00");        
        $floSalarioPromedio = 0;        
        $fechaDesdeRecargos = $arVacacion->getFecha()->format('Y-m-d');
        $nuevafecha = strtotime ( '-365 day' , strtotime ( $fechaDesdeRecargos ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );        
        $fechaDesde = date_create($nuevafecha);      
        
        $fechaDesdeRecargos = $nuevafecha;
        $fechaHastaRecargos = $arVacacion->getFecha()->format('Y-m-d');
        $interval = date_diff($fechaDesdePeriodo, $fechaHastaPeriodo);
        $recargosNocturnos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargosNocturnos($fechaDesdePeriodo->format('Y-m-d'), $fechaHastaPeriodo->format('Y-m-d'), $arContrato->getCodigoContratoPk());                    
             
        $arVacacion->setVrRecargoNocturno($recargosNocturnos);
        $arVacacion->setVrRecargoNocturnoInicial($arContrato->getIbpRecargoNocturnoInicial());                
        $recargosNocturnosTotal = $recargosNocturnos + $arContrato->getIbpRecargoNocturnoInicial();        
        $promedioRecargosNocturnos = $recargosNocturnosTotal / $mesesPeriodo;               
        $promedioRecargosNocturnos = round($promedioRecargosNocturnos);
        $arVacacion->setVrPromedioRecargoNocturno($promedioRecargosNocturnos);        
        if($arContrato->getCodigoSalarioTipoFk() == 1) {
            $floSalarioPromedio = $arContrato->getVrSalario();
        } else {            
            $floSalarioPromedio = $arContrato->getVrSalario() + $promedioRecargosNocturnos;
        }       
        if($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
            $floSalarioPromedio = $arVacacion->getVrSalarioPromedioPropuesto();
        }
        $floTotalVacacionBrutoDisfrute = $floSalarioPromedio / 30 * $arVacacion->getDiasDisfrutadosReales();
        $floTotalVacacionBrutoPagados = 0;
        //Validar si son pagadas todas
        if($arVacacion->getDiasDisfrutadosReales() > 1) {
            $floTotalVacacionBrutoPagados = $floSalarioPromedio / 30 * $arVacacion->getDiasPagados();            
        } else {              
            if($arVacacion->getVrSalarioPromedioPropuesto() > 0) {
                $floTotalVacacionBrutoPagados = $arVacacion->getVrSalarioPromedioPropuesto() / 30 * $arVacacion->getDiasPagados(); 
            } else {
                $floTotalVacacionBrutoPagados = $arContrato->getVrSalario() / 30 * $arVacacion->getDiasPagados();                                        
            }            
        }        
        $floTotalVacacionBruto = $floTotalVacacionBrutoDisfrute + $floTotalVacacionBrutoPagados;  
        if($arContrato->getSalarioIntegral() == 0) {
            $basePrestaciones = $floTotalVacacionBrutoDisfrute;
        } else {
            $basePrestaciones = ($floTotalVacacionBrutoDisfrute * 70) / 100;
        }
        $porcentajeSalud = $arContrato->getTipoSaludRel()->getPorcentajeEmpleado();                    
        $douSalud = ($basePrestaciones * $porcentajeSalud) / 100;
        $douSalud = round($douSalud);
        $arVacacion->setVrSalud($douSalud);
        
        $porcentajePension = $arContrato->getTipoPensionRel()->getPorcentajeEmpleado();                    
        if ($basePrestaciones >= ($arConfiguracion->getVrSalario() * 4)){
            $douPension = ($basePrestaciones * 5) / 100;
        } else {
            $douPension = ($basePrestaciones * $porcentajePension) / 100;
        }
        $douPension = round($douPension);
        $arVacacion->setVrPension($douPension); 
        
        $floDeducciones = 0;
        $floBonificaciones = 0;        
        $arVacacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
        $arVacacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        foreach ($arVacacionAdicionales as $arVacacionAdicional) {
            if($arVacacionAdicional->getVrDeduccion() > 0) {
                $floDeducciones += $arVacacionAdicional->getVrDeduccion();
            }
            if($arVacacionAdicional->getVrBonificacion() > 0) {
                $floBonificaciones += $arVacacionAdicional->getVrBonificacion();
            }            
        }                  
        $floBonificaciones = round($floBonificaciones);
        $floDeducciones = round($floDeducciones);
        $floSalario = round($floSalario);
        $floSalarioPromedio = round($floSalarioPromedio);
        $floTotalVacacionBruto = round($floTotalVacacionBruto);
        $promedioIbc = $floTotalVacacionBruto/$arVacacion->getDiasVacaciones();
        $promedioIbc = round($promedioIbc);
        $arVacacion->setVrIbcPromedio($promedioIbc);
        $arVacacion->setVrBonificacion($floBonificaciones);
        $arVacacion->setVrDeduccion($floDeducciones);
        $arVacacion->setVrVacacionBruto($floTotalVacacionBruto);
        $floTotalVacacion = ($floTotalVacacionBruto+$floBonificaciones) - $floDeducciones - $arVacacion->getVrPension() - $arVacacion->getVrSalud();        
        $floTotalVacacion = round($floTotalVacacion);
        $arVacacion->setVrVacacion($floTotalVacacion);        
        $arVacacion->setVrSalarioActual($floSalario);
        $arVacacion->setVrSalarioPromedio($floSalarioPromedio);
        $em->persist($arVacacion);
        $em->flush();
        
        return true;
    } 
    
    public function pagar($codigoVacacion) {        
        $em = $this->getEntityManager();
        $validar = "";
        $arrCreditos =  $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->recumenCredito($codigoVacacion);
        foreach ($arrCreditos as $arrCredito) {
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arrCredito['codigoCreditoFk']);
            if($arCredito->getSaldo() < $arrCredito['total']) {
                $validar = "El credito " . $arrCredito['codigoCreditoFk'] . " tiene un saldo de " . $arCredito->getSaldo() . " y la deduccion de " . $arrCredito['total'] . " lo supera";
            }
        }        
        if($validar == "") {
            $arVacacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
            $arVacacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->findBy(array('codigoVacacionFk' => $codigoVacacion));                                 
            foreach ($arVacacionAdicionales as $arVacacionAdicional){
                if ($arVacacionAdicional->getCodigoCreditoFk() != null){
                    $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($arVacacionAdicional->getCodigoCreditoFk());                   
                    $arCredito->setSaldo($arCredito->getSaldo() - $arVacacionAdicional->getVrDeduccion());                        
                    $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                    $arCredito->setTotalPagos($arCredito->getTotalPagos() + $arVacacionAdicional->getVrDeduccion());
                    
                    $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
                    $arPagoCredito->setCreditoRel($arCredito);                        
                    $arPagoCredito->setfechaPago(new \ DateTime("now"));
                    $arCreditoTipoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipoPago')->find(3);
                    $arPagoCredito->setCreditoTipoPagoRel($arCreditoTipoPago);
                    $arPagoCredito->setVrCuota($arVacacionAdicional->getVrDeduccion());
                    $arPagoCredito->setSaldo($arCredito->getSaldo());
                    $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                    $em->persist($arPagoCredito);
                    if($arCredito->getSaldo() <= 0){
                        $arCredito->setEstadoPagado(1);        
                    }                    
                    $em->persist($arCredito);
                }    
            }                         
        } 
        
        return $validar;
        
    }
    
    public function devuelveVacacionesFecha($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(v.vrVacacion) as Vacaciones FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE v.codigoEmpleadoFk = " . $codigoEmpleado 
                . "AND v.fechaDesdePeriodo >= '" . $fechaDesde . "' AND v.fechaHastaPeriodo <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function dias($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $dql = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
                . "AND v.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND v.codigoContratoFk = " . $codigoContrato . " AND v.diasDisfrutados > 0";
        
        $query = $em->createQuery($dql);
        $arVacaciones = $query->getResult();
        $intDiasDevolver = 0;
        $vrIbc = 0;
        foreach ($arVacaciones as $arVacacion) {
            $intDias = 0;
            $dateFechaDesde =  "";
            $dateFechaHasta =  "";                            
            if($arVacacion->getFechaDesdeDisfrute() <  $fechaDesde == true) {
                $dateFechaDesde = $fechaDesde;
            } else {
                $dateFechaDesde = $arVacacion->getFechaDesdeDisfrute();
            }

            if($arVacacion->getFechaHastaDisfrute() >  $fechaHasta == true) {
                $dateFechaHasta = $fechaHasta;
            } else {
                $dateFechaHasta = $arVacacion->getFechaHastaDisfrute();
            }
            if($dateFechaDesde != "" && $dateFechaHasta != "") {
                $intDias = $dateFechaDesde->diff($dateFechaHasta);
                $intDias = $intDias->format('%a');
                $intDias = $intDias + 1;
                $intDiasDevolver += $intDias;
            }
            $vrIbc += $intDias * $arVacacion->getVrIbcPromedio();
        }
        $arrDevolver = array('dias' => $intDiasDevolver, 'ibc' => $vrIbc);
        return $arrDevolver;
    }    
    
    //Seguridad social
    public function diasVacacionesDisfrute($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoContrato) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (((v.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (v.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (v.fechaDesdeDisfrute >= '$strFechaDesde' AND v.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (v.fechaHastaDisfrute >= '$strFechaHasta' AND v.fechaDesdeDisfrute <= '$strFechaDesde')) "
                . "AND v.codigoEmpleadoFk = " . $codigoEmpleado . " AND v.codigoContratoFk = " . $codigoContrato;
        $objQuery = $em->createQuery($dql);  
        $arVacacionesDisfrute = $objQuery->getResult();        
        $intDiasVacacionesTotal = 0;
        $vrAporteParafiscales = 0;
        foreach ($arVacacionesDisfrute as $arVacacionDisfrute) {            
            $intDiasVacaciones = 0;
            $intDiaInicio = 1;            
            $intDiaFin = 30;            
            $intDiaFinLiquidacion = date("d",(mktime(0,0,0,$fechaHasta->format('m')+1,1,$fechaHasta->format('Y'))-1));
            if($arVacacionDisfrute->getFechaDesdeDisfrute() <  $fechaDesde) {
                $intDiaInicio = 1;                
            } else {
                $intDiaInicio = $arVacacionDisfrute->getFechaDesdeDisfrute()->format('j');
            }
            if($arVacacionDisfrute->getFechaHastaDisfrute() < $fechaHasta) {
                $intDiaFin = $arVacacionDisfrute->getFechaHastaDisfrute()->format('j');
                $intDiaFinLiquidacion = $arVacacionDisfrute->getFechaHastaDisfrute()->format('j');
            }            
            $intDiasVacaciones = (($intDiaFin - $intDiaInicio)+1);
            $intDiasVacacionesLiquidar = (($intDiaFinLiquidacion - $intDiaInicio)+1);
            if($intDiasVacaciones == 1 && $arVacacionDisfrute->getDiasDisfrutados() <= 0) {
                $intDiasVacaciones = 0;
                $intDiasVacacionesLiquidar = 0;
                
            }     
            $intDiasVacacionesTotal += $intDiasVacaciones;
            //$arVacacionDisfrute = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
            if($arVacacionDisfrute->getDiasDisfrutados() > 1) {
                $vrDiaDisfrute = ($arVacacionDisfrute->getVrVacacionBruto() / $arVacacionDisfrute->getDiasVacaciones());    
                $vrAporteParafiscales += $intDiasVacacionesLiquidar * $vrDiaDisfrute;
            } else {
                $vrAporteParafiscales += $arVacacionDisfrute->getVrVacacionBruto();
            }        
            //$vrDiaVacacion = $arVacacionDisfrute->getVrVacacionBruto() / $arVacacionDisfrute->getDiasVacaciones();
            //$vrAporteParafiscales = 
        }
        if($intDiasVacacionesTotal > 30) {
            $intDiasVacacionesTotal = 30;
        }
        $arrVacaciones = array('dias' => $intDiasVacacionesTotal, 'aporte' => $vrAporteParafiscales);
        return $arrVacaciones;                     
    }         
    
    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoCentroCosto = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT vacacion FROM BrasaRecursoHumanoBundle:RhuVacacion vacacion "
                . "WHERE (((vacacion.fechaDesdeDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (vacacion.fechaHastaDisfrute BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (vacacion.fechaDesdeDisfrute >= '$strFechaDesde' AND vacacion.fechaDesdeDisfrute <= '$strFechaHasta') "
                . "OR (vacacion.fechaHastaDisfrute >= '$strFechaHasta' AND vacacion.fechaDesdeDisfrute <= '$strFechaDesde')) ";
        if($codigoEmpleado != "") {
            $dql = $dql . "AND vacacion.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        }
        if($codigoCentroCosto != "") {
            $dql = $dql . "AND vacacion.codigoCentroCostoFk = " . $codigoCentroCosto . " ";
        }        

        $objQuery = $em->createQuery($dql);  
        $arVacaciones = $objQuery->getResult();         
        return $arVacaciones;
    }           
    
    public function pendientesContabilizarDql() {        
        $dql   = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v WHERE v.estadoContabilizado = 0 AND v.estadoPagoGenerado = 1 AND v.vrVacacion > 0 ";       
        $dql .= " ORDER BY v.codigoVacacionPk DESC";
        return $dql;
    }  
    
    public function contabilizadosDql($intNumeroDesde = 0, $intNumeroHasta = 0,$strDesde = "",$strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT v FROM BrasaRecursoHumanoBundle:RhuVacacion v WHERE v.estadoContabilizado = 1 AND v.estadoPagoGenerado = 1";
        if($intNumeroDesde != "" || $intNumeroDesde != 0) {
            $dql .= " AND v.codigoVacacionPk >= " . $intNumeroDesde;
        }
        if($intNumeroHasta != "" || $intNumeroHasta != 0) {
            $dql .= " AND v.codigoVacacionPk <= " . $intNumeroHasta;
        }   
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND v.fecha >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND v.fecha <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }         
 
    public function deduccionesAportes($codigoContrato, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(v.vrPension) as vrPension, SUM(v.vrSalud) as vrSalud FROM BrasaRecursoHumanoBundle:RhuVacacion v "
                . "WHERE (v.fecha >= '" . $fechaDesde->format('Y-m-d') . "' AND v.fecha <='" . $fechaHasta->format('Y-m-d') . "') "
                . "AND v.codigoContratoFk = " . $codigoContrato;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $resultados = $arrayResultado[0];
        if($resultados['vrPension'] == null) {
           $resultados['vrPension'] = 0; 
        }
        if($resultados['vrSalud'] == null) {
           $resultados['vrSalud'] = 0; 
        }                 
        return $resultados;        
    }    
    public function generarNovedadTurnos($codigoVacacion) {
        $em = $this->getEntityManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);                
        return 0;        
    }     
}

