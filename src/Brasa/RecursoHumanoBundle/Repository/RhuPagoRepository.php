<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoRepository extends EntityRepository {
    
    public function anular($codigoPago) {        
        $em = $this->getEntityManager();
        $respuesta = "";
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();  
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);                                
        if($arPago->getEstadoPagadoBanco() == 0) {
            if($arPago->getEstadoPagadoBanco() == 0) {
                $arPago->setVrAdicionalCotizacion(0);
                $arPago->setVrAdicionalTiempo(0);
                $arPago->setVrAdicionalValor(0);
                $arPago->setVrAdicionalValorNoPrestasional(0);
                $arPago->setVrAuxilioTransporte(0);
                $arPago->setVrAuxilioTransporteCotizacion(0);
                $arPago->setVrBruto(0);
                $arPago->setVrCosto(0);
                $arPago->setVrDeducciones(0);
                $arPago->setVrDevengado(0);
                $arPago->setVrIngresoBaseCotizacion(0);
                $arPago->setVrIngresoBasePrestacion(0);
                $arPago->setVrNeto(0);
                $arPago->setVrSalario(0);
                $arPago->setVrSalarioEmpleado(0);
                $arPago->setVrSalarioPeriodo(0);
                $arPago->setDiasAusentismo(0);
                $arPago->setDiasLaborados(0);
                $arPago->setDiasPeriodo(0);
                $arPago->setEstadoAnulado(1);
                $em->persist($arPago);
                $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago)); 
                foreach ($arPagoDetalles as $arPagoDetalle) {
                    $arPagoDetalleAct = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                    $arPagoDetalleAct = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->find($arPagoDetalle->getCodigoPagoDetallePk()); 
                    $arPagoDetalleAct->setVrDia(0);
                    $arPagoDetalleAct->setVrExtra(0);
                    $arPagoDetalleAct->setVrHora(0);
                    $arPagoDetalleAct->setVrIngresoBaseCotizacion(0);
                    $arPagoDetalleAct->setVrIngresoBaseCotizacionAdicional(0);
                    $arPagoDetalleAct->setVrIngresoBaseCotizacionIncapacidad(0);
                    $arPagoDetalleAct->setVrIngresoBaseCotizacionSalario(0);
                    $arPagoDetalleAct->setVrIngresoBasePrestacion(0);
                    $arPagoDetalleAct->setVrPago(0);
                    $arPagoDetalleAct->setVrPagoOperado(0);
                    $arPagoDetalleAct->setVrTotal(0);
                    $arPagoDetalleAct->setNumeroDias(0);
                    $arPagoDetalleAct->setNumeroHoras(0);
                    $arPagoDetalleAct->setSalud(0);
                    $arPagoDetalleAct->setPension(0);                    
                    $em->persist($arPagoDetalleAct);
                }
                $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();                
                $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($arPago->getCodigoProgramacionPagoDetalleFk()); 
                if($arProgramacionPagoDetalle) {
                    if($arPago->getCodigoPagoTipoFk() == 3) {
                        $arProgramacionPagoDetalle->setVrInteresCesantia(0);                        
                    }
                    $em->persist($arProgramacionPagoDetalle);
                }
                $em->flush();
            } else {
                $respuesta = "El pago no puede estar anulado para anularse";
            }
        } else {
            $respuesta = "El pago no puede estar pagado por banco para anularse";
        }
        
        return $respuesta;
    }        
    
    public function liquidar($codigoPago, $arConfiguracion) {        
        $em = $this->getEntityManager();
        $douSalario = 0;
        $douAuxilioTransporte = 0;
        $douAdicionTiempo = 0;
        $douAdicionValor = 0;
        $douAdicionValorNoPrestacional = 0;
        $douAdicionCotizacion = 0;
        $douPension = 0;
        $douEps = 0;
        $douDeducciones = 0;
        $douDevengado = 0;        
        $douNeto = 0;
        $douIngresoBaseCotizacion = 0;
        $douIngresoBasePrestacion = 0;
        $intDiasAusentismo = 0;
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));         
        foreach ($arPagoDetalles as $arPagoDetalle) {
            if($arPagoDetalle->getOperacion() == 1) {
                $douDevengado = $douDevengado + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getOperacion() == -1) {
                $douDeducciones = $douDeducciones + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getPagoConceptoRel()->getComponeSalario() == 1) {
                $douSalario = $douSalario + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoAuxilioTransporte() == 1) {
                $douAuxilioTransporte = $douAuxilioTransporte + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoPension() == 1) {
                $douPension = $douPension + $arPagoDetalle->getVrPago();
            }
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoSalud() == 1) {
                $douEps = $douEps + $arPagoDetalle->getVrPago();
            }            
            if($arPagoDetalle->getPagoConceptoRel()->getConceptoAdicion() == 1) {
                if($arPagoDetalle->getOperacion() == 1) {                
                    if($arPagoDetalle->getPagoConceptoRel()->getComponeValor() == 1) {
                        $douAdicionValor = $douAdicionValor + $arPagoDetalle->getVrPago();    
                    } else {
                        $douAdicionTiempo = $douAdicionTiempo + $arPagoDetalle->getVrPago();    
                    }                    
                }                                
            }
            
            if($arPagoDetalle->getAdicional() == 1) {
                if($arPagoDetalle->getPrestacional() == 0) {
                    if($arPagoDetalle->getOperacion() == 1) {
                        $douAdicionValorNoPrestacional = $douAdicionValorNoPrestacional + $arPagoDetalle->getVrPago();
                    }                    
                }
            }
            $douIngresoBaseCotizacion += $arPagoDetalle->getVrIngresoBaseCotizacion();
            $douIngresoBasePrestacion += $arPagoDetalle->getVrIngresoBasePrestacion();
            $intDiasAusentismo += $arPagoDetalle->getDiasAusentismo();
            $douAdicionCotizacion += $arPagoDetalle->getVrIngresoBaseCotizacionAdicional();
        }
        
        //$arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();                        
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);                                
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $arPago->getContratoRel();
        $arPago->setVrDevengado($douDevengado);
        $arPago->setVrDeducciones($douDeducciones);
        $douNeto = $douDevengado - $douDeducciones;
        $arPago->setVrNeto($douNeto);
        $arPago->setVrSalario($douSalario);        
        $arPago->setVrAuxilioTransporte($douAuxilioTransporte);        
        $arPago->setVrAdicionalTiempo($douAdicionTiempo);
        $arPago->setVrAdicionalValor($douAdicionValor);
        $arPago->setVrAdicionalValorNoPrestasional($douAdicionValorNoPrestacional);
        $arPago->setVrAdicionalCotizacion($douAdicionCotizacion);                
        $arPago->setVrIngresoBaseCotizacion($douIngresoBaseCotizacion);
        $arPago->setVrIngresoBasePrestacion($douIngresoBasePrestacion);
        $arPago->setDiasAusentismo($intDiasAusentismo);
        
        $floSalarioMinimo = $arConfiguracion->getVrSalario();
        $floVrDiaMinimo = $floSalarioMinimo/30;        
        $douIngresoBaseCotizacionMinimo = $floVrDiaMinimo * $arPago->getDiasPeriodo();
        if($douIngresoBaseCotizacion < $douIngresoBaseCotizacionMinimo) {
            $douIngresoBaseCotizacion = $douIngresoBaseCotizacionMinimo;
        }                                
        $arPago->setVrCosto(0);                       
        $em->persist($arPago);        
        return $douNeto;
    }    
    
    public function listaDql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($intTipo != "" && $intTipo != 0) {
            $dql .= " AND p.codigoPagoTipoFk =" . $intTipo;
        }        
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND p.fechaHasta <='" . $strHasta . "'";
        }
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }                        
    
    public function listaImpresionDql($codigoPago = "", $codigoProgramacionPago = "", $codigoZona = "", $codigoSubzona = "", $porFecha = false, $fechaDesde = "", $fechaHasta = "", $dato = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($codigoPago != "") {
            $dql .= " AND p.codigoPagoPk = " . $codigoPago;
        }
        if($codigoProgramacionPago != "") {
            $dql .= " AND p.codigoProgramacionPagoFk = " . $codigoProgramacionPago;
        }                  
        if($codigoZona != "") {
            $dql .= " AND e.codigoZonaFk = " . $codigoZona;
        }
        if($codigoSubzona != "") {
            $dql .= " AND e.codigoSubzonaFk = " . $codigoSubzona;
        }        
        if($dato != "") {
            $dql .= " AND e.dato = " . $dato;
        }         
        if($porFecha == true) {
            if($fechaDesde != "" && $fechaHasta != "") {
                $dql .= " AND (p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "')";
            }
        }
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }                            
    
    public function listaConsultaPagosDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "", $strPago = "",$strProgramacionPago = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . $strHasta . "'";
        }
        if($strPago != "") {
            $dql .= " AND p.codigoPagoPk ='" . $strPago . "'";
        }
        
        return $dql;
    }
    
    public function listaConsultaPagosDetallesDQL($strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd, p FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e WHERE pd.codigoPagoDetallePk <> 0";
          
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        $dql .= " ORDER BY e.numeroIdentificacion";
        return $dql;
    }
    
    public function contabilizadosPagoNominaDql($intNumeroDesde = 0, $intNumeroHasta = 0,$strDesde = "",$strHasta = "", $codigoPagoTipo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoContabilizado = 1 AND p.estadoPagado = 1";
        if($intNumeroDesde != "" || $intNumeroDesde != 0) {
            $dql .= " AND p.numero >= " . $intNumeroDesde;
        }
        if($intNumeroHasta != "" || $intNumeroHasta != 0) {
            $dql .= " AND p.numero <= " . $intNumeroHasta;
        }   
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND p.fechaDesde >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND p.fechaHasta <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        if($codigoPagoTipo != "") {
            $dql .= " AND p.codigoPagoTipoFk = " . $codigoPagoTipo;
        }         
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    } 
    
    public function pendientesContabilizarDql() {        
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoContabilizado = 0 AND p.estadoPagado = 1 AND p.vrNeto >= 0";       
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    } 
    
    public function pendientesContabilizarProvisionDql() {        
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoContabilizadoProvision = 0 AND p.estadoPagado = 1 AND p.vrNeto > 0";       
        $dql .= " ORDER BY p.codigoPagoPk ASC";
        return $dql;
    } 

    public function pendientePagoBancoDql($codigoBanco = "", $codigoPagoTipo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.estadoPagado = 1 AND p.estadoPagadoBanco = 0 AND p.codigoPagoTipoFk = " .$codigoPagoTipo;
        
        if($codigoBanco != "") {
            $dql .= " AND e.codigoBancoFk = " . $codigoBanco;
        }           
        return $dql;
    }    
    
    public function listaDqlCostos($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 ";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
           // $strDesde = new \DateTime($strDesde);
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            //$strHasta = new \DateTime($strHasta);
            $dql .= " AND p.fechaHasta <='" . $strHasta . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function listaDqlPagosDeducciones($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            //$strDesde = new \DateTime($strDesde);
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            //$strHasta = new \DateTime($strHasta);
            $dql .= " AND p.fechaHasta <='" .  $strHasta . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function generarPagoDetalleSede ($codigoPago) {
        $em = $this->getEntityManager();
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $arProgramacionPagoDetalleSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();
            $arProgramacionPagoDetalleSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalleSede')->findBy(array('codigoProgramacionPagoDetalleFk' => $arPagoDetalle->getCodigoProgramacionPagoDetalleFk()));
            foreach ($arProgramacionPagoDetalleSedes as $arProgramacionPagoDetalleSede) {                
                $arPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede();
                $arPagoDetalleSede->setPagoRel($arPagoDetalle->getPagoRel());
                $arPagoDetalleSede->setPagoConceptoRel($arPagoDetalle->getPagoConceptoRel());                                                        
                $arPagoDetalleSede->setSedeRel($arProgramacionPagoDetalleSede->getSedeRel());                                                        
                $arPagoDetalleSede->setVrPago(($arPagoDetalle->getVrPago() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);
                $arPagoDetalleSede->setOperacion($arPagoDetalle->getOperacion());
                $arPagoDetalleSede->setPorcentajeAplicado($arPagoDetalle->getPorcentajeAplicado());
                $arPagoDetalleSede->setNumeroHoras(($arPagoDetalle->getNumeroHoras() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);
                $arPagoDetalleSede->setVrPagoOperado(($arPagoDetalle->getVrPagoOperado() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                
                $arPagoDetalleSede->setVrTotal(($arPagoDetalle->getVrTotal() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                                
                $arPagoDetalleSede->setVrIngresoBaseCotizacion(($arPagoDetalle->getVrIngresoBaseCotizacion() * $arProgramacionPagoDetalleSede->getPorcentajeParticipacion()) / 100);                                
                $em->persist($arPagoDetalleSede);
            }
        }
        $em->flush();
    }
    
    public function pendienteCobrar($codigoCentroCosto) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.estadoCobrado = 0 "
                . " AND p.codigoCentroCostoFk = " . $codigoCentroCosto;
        return $dql;
    }
    
    public function listaPagosDQL($codigoProgramacionPago) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPago p WHERE p.codigoProgramacionPagoFk = ". $codigoProgramacionPago ."";
        return $dql;
    }
    
    public function devuelveCostosFecha($codigoEmpleado, $fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as IBC, SUM(p.vrPension) as Pension, SUM(p.vrEps) as Salud, SUM(p.vrAuxilioTransporte) as AuxilioTransporte, MIN(p.fechaDesde) as fechaInicio, MAX(p.fechaHasta) as fechaFin FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 "
                . "AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function devuelvePrimasFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrNeto) as Neto FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 AND p.codigoPagoTipoFk = 2 "
                . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    /*public function devuelveAuxTransporteFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as IBC, SUM(p.vrAuxilioTransporte) as AuxilioTransporte, MIN(p.fechaDesde) as fechaInicio, MAX(p.fechaHasta) as fechaFin, SUM(p.vrAdicionalValorNoPrestasional) as NoPrestacional, SUM(p.vrAuxilioTransporte) as AuxTransporte, SUM(p.vrIngresoBaseCotizacion) as Prestacional FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }*/
    public function devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT MIN(p.fechaDesde) as fechaInicio, MAX(p.fechaHasta) as fechaFin, SUM(p.vrAdicionalValorNoPrestasional) as NoPrestacional, SUM(p.vrIngresoBasePrestacion) as Prestacional FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function tiempoSuplementario($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrAdicionalCotizacion) as suplementario FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $floSuplementario = $arrayResultado[0]['suplementario'];
        if($floSuplementario == null) {
            $floSuplementario = 0;
        }
        return $floSuplementario;
    }

    public function ibc($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.vrIngresoBaseCotizacion) as ibc FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibc = $arrayResultado[0]['ibc'];
        if($ibc == null) {
            $ibc = 0;
        }
        return $ibc;
    }
    
    public function tiempoSuplementarioCartaLaboral($intPeriodo, $codigoContrato) {
        $em = $this->getEntityManager(); 
        $dql   = "SELECT vr_devengado FROM rhu_pago "
                . "WHERE estado_pagado = 1 " 
                . "AND codigo_pago_tipo_fk = 1 " 
                . "AND codigo_contrato_fk = " . $codigoContrato . " "
                . "ORDER BY codigo_pago_pk DESC LIMIT 2";
        /*$query = $em->createQuery($dql)
                    ->setFirstResult(0)
                    ->setMaxResults($intPeriodo);*/
        $connection = $em->getConnection();
        $statement = $connection->prepare($dql);        
        $statement->execute();
        $results = $statement->fetchAll();
        $dato = 0;
        foreach ($results as $results) {
            $dato += $results['vr_devengado'];
            
        }
        //$floSuplementario = $arrayResultado[0]['suplementario'];
        return $dato;
    }
    
    public function noPrestacionalCartaLaboral($intPeriodo, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT vr_adicional_valor_no_prestacional FROM rhu_pago "
                . "WHERE estado_pagado = 1 " 
                . "AND codigo_pago_tipo_fk = 1 " 
                . "AND codigo_contrato_fk = " . $codigoContrato . " "
                . "ORDER BY codigo_pago_pk DESC LIMIT 2";
        /*$query = $em->createQuery($dql)
                    ->setFirstResult(0)
                    ->setMaxResults($intPeriodo);*/
        $connection = $em->getConnection();
        $statement = $connection->prepare($dql);        
        $statement->execute();
        $results = $statement->fetchAll();
        $dato = 0;
        foreach ($results as $results) {
            $dato += $results['vr_adicional_valor_no_prestacional'];
            
        }
        //$floSuplementario = $arrayResultado[0]['suplementario'];
        return $dato;
    }
    
    public function devuelveCostosDane($fechaDesde, $fechaHasta, $fechaProceso) {
        $em = $this->getEntityManager();
        $dql   = "SELECT p, c FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.contratoRel c WHERE p.codigoPagoPk <> 0"
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "' AND p.codigoPagoTipoFk <> 3 ";
                if ($fechaProceso != ""){
                    $dql .= " AND p.fechaDesde LIKE '%".$fechaProceso. "%' AND p.fechaHasta LIKE '%".$fechaProceso. "%'";
                }
                
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    //consulta pago pendientes al banco
    public function listaPagoPendientesBancoDql($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.estadoPagadoBanco = 0";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND p.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaHasta <='" . $strHasta . "'";
        }
        //$dql .= " ORDER BY p.empleadoRel.nombreCorto";
        return $dql;
    }                            
    
    public function diasAusentismo($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(p.diasAusentismo) as diasAusentismo FROM BrasaRecursoHumanoBundle:RhuPago p "
                . "WHERE p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $intDiasAusentismo = $arrayResultado[0]['diasAusentismo'];
        if($intDiasAusentismo == null) {
            $intDiasAusentismo = 0;
        }
        return $intDiasAusentismo;
    }  
    
    public function listaDqlPagosPeriodoAportes($strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e FROM BrasaRecursoHumanoBundle:RhuPago p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesdePago >='" . $strDesde->format('Y-m-d'). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaHastaPago <='" . $strHasta->format('Y-m-d') . "'";
        }

        return $dql;
    }
 
    /*public function pagosFecha($strDesde = "", $strHasta = "", $codigoEmpleado = "") {
        $em = $this->getEntityManager();             
        $strSql = "SELECT
                    COUNT(codigo_pago_pk) as numeroPagos,
                    SUM(vr_neto) as vrNeto                                        
                    FROM rhu_pago                                                            
                    WHERE rhu_pago.codigo_empleado_fk = $codigoEmpleado AND (rhu_pago.fecha_desde >='$strDesde' AND rhu_pago.fecha_hasta <='$strHasta')
                    GROUP BY codigo_empleado_fk"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $results = $statement->fetchAll();        
        
        return $results;        
    }*/
    public function pagoDevengadoFecha($strDesde = "", $strHasta = "", $codigoEmpleado = "") {
        $em = $this->getEntityManager();             
        $strSql = "SELECT
                    COUNT(codigo_pago_pk) as numeroPagos,
                    SUM(vr_devengado) as vrDevengado                                        
                    FROM rhu_pago                                                            
                    WHERE rhu_pago.codigo_empleado_fk = $codigoEmpleado AND (rhu_pago.fecha_desde_pago >='$strDesde' AND rhu_pago.fecha_desde_pago <='$strHasta')
                    GROUP BY codigo_empleado_fk"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $results = $statement->fetchAll();        
        
        return $results;        
    }    
}