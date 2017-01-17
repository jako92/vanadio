<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoDetalleRepository extends EntityRepository {
    
    public function listaDql($codigoPago = "", $codigoProgramacionPagoDetalle = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd WHERE pd.codigoPagoDetallePk <> 0";

        if($codigoPago != "") {
            $dql .= " AND pd.codigoPagoFk = " . $codigoPago;
        } 
        if($codigoProgramacionPagoDetalle != "") {
            $dql .= " AND pd.codigoProgramacionPagoDetalleFk = " . $codigoProgramacionPagoDetalle;
        }        
        $dql .= " ORDER BY pd.codigoPagoConceptoFk";
        return $dql;
    }                            
    
    public function listaDetalleDql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "", $strDesde = "", $strHasta = "", $strCodigoPagoConcepto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd, p, e FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoPagoConcepto != "") {
            $dql .= " AND pd.codigoPagoConceptoFk = " . $strCodigoPagoConcepto;
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
        if($strDesde != "") {
            $dql .= " AND p.fechaDesde >= '" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <= '" . $strHasta . "'";
        } 
        $dql .= " ORDER BY p.codigoPagoPk DESC";
        return $dql;
    }      
    
    public function listaDetalleResumenDql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "", $strDesde = "", $strHasta = "", $strCodigoPagoConcepto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e.numeroIdentificacion as codigoEmpleado,e.numeroIdentificacion as Identificacion,e.nombreCorto as Empleado,pd.codigoPagoConceptoFk as codigoConcepto, pc.nombre as Concepto, pd.operacion as operacion, SUM(pd.vrPago) as Valor FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e JOIN pd.pagoConceptoRel pc WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoPagoConcepto != "") {
            $dql .= " AND pd.codigoPagoConceptoFk = " . $strCodigoPagoConcepto;
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
        if($strDesde != "") {
            $dql .= " AND p.fechaDesde >= '" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <= '" . $strHasta . "'";
        } 
        $dql .= " GROUP BY e.numeroIdentificacion, pd.codigoPagoConceptoFk, pd.operacion";
        //return $dql;
         $query = $em->createQuery($dql);
        $douRetencion = $query->getResult();
        return $douRetencion;
    }      

    public function listaDetalleResumen2Dql($intNumero = 0, $strCodigoCentroCosto = "", $strIdentificacion = "", $intTipo = "", $strDesde = "", $strHasta = "", $strCodigoPagoConcepto = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pd.codigoPagoConceptoFk as codigoConcepto, pc.nombre as Concepto, pd.operacion as operacion, SUM(pd.vrPago) as Valor FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e JOIN pd.pagoConceptoRel pc WHERE p.codigoPagoPk <> 0";
        if($intNumero != "" && $intNumero != 0) {
            $dql .= " AND p.numero = " . $intNumero;
        }
        if($strCodigoPagoConcepto != "") {
            $dql .= " AND pd.codigoPagoConceptoFk = " . $strCodigoPagoConcepto;
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
        if($strDesde != "") {
            $dql .= " AND p.fechaDesde >= '" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaDesde <= '" . $strHasta . "'";
        } 
        $dql .= " GROUP BY pd.codigoPagoConceptoFk, pd.operacion";
        //return $dql;
         $query = $em->createQuery($dql);
        $douRetencion = $query->getResult();
        return $douRetencion;
    }      
    
    public function pagosDetallesProgramacionPago($codigoProgramacionPago) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoProgramacionPagoFk = " . $codigoProgramacionPago . " ORDER BY p.codigoEmpleadoFk, pd.codigoPagoConceptoFk";
        $query = $em->createQuery($dql);
        $arPagosDetalles = $query->getResult();                
        return $arPagosDetalles;
    }
    
    public function devuelveRetencionFuenteEmpleadoFecha($codigoEmpleado, $strFechaCertificado) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as Retencion FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " "
                . "AND pd.codigoPagoConceptoFk = 26"
                . "AND p.fechaDesde like '%" . $strFechaCertificado . "%' ";
        $query = $em->createQuery($dql);
        $douRetencion = $query->getSingleScalarResult();
        return $douRetencion;
    }
    
    public function devuelveInteresesCesantiasFechaCertificadoIngreso($codigoEmpleado, $fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as Neto FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.codigoEmpleadoFk = " . $codigoEmpleado . " AND p.estadoPagado = 1 AND p.codigoPagoTipoFk = 3"
                . "AND p.fechaDesde >= '" . $fechaDesde . "' AND p.fechaDesde <= '" . $fechaHasta . "'"
                . "AND pd.codigoPagoConceptoFk = 30 ";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }
    
    public function listaDqlPagosDetallePeriodoAportes($strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p, e, pd FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN p.empleadoRel e WHERE p.codigoPagoTipoFk = 1 AND p.estadoPagado = 1";
        
        if ($strDesde != ""){
            $dql .= " AND p.fechaDesdePago >='" . $strDesde->format('Y-m-d'). "'";
        }
        if($strHasta != "") {
            $dql .= " AND p.fechaHastaPago <='" . $strHasta->format('Y-m-d') . "'";
        }

        $query = $em->createQuery($dql);
        $arPagosDetalles = $query->getResult();                
        return $arPagosDetalles;
    }
    
    public function recargosNocturnosFecha($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        
        $fechaDesdeAnio = $fechaHasta->format('Y-m-d');
        $nuevafecha = strtotime ( '-365 day' , strtotime ( $fechaDesdeAnio ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );        
        $fechaDesdeAnio = date_create($nuevafecha);                    
        if($fechaDesde < $fechaDesdeAnio) {
            $fechaDesde = $fechaDesdeAnio;
            $meses = 12;
        } else {
            $interval = date_diff($fechaDesde, $fechaHasta); 
            $meses = $interval->format('%m');  
            if($meses == 0) {
                $meses = 1;
            }
        }
        
        $dql   = "SELECT SUM(pd.vrIngresoBaseCotizacionAdicional) as recagosNocturnos FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                . "WHERE pc.recargoNocturno = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde->format('Y/m/d') . "' AND p.fechaDesdePago <= '" . $fechaHasta->format('Y/m/d') . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $recargosNocturnos = $arrayResultado[0]['recagosNocturnos'];
        if($recargosNocturnos == null) {
            $recargosNocturnos = 0;
        }
        $recargosNocturnos = $recargosNocturnos / $meses;
        $recargosNocturnos =  $recargosNocturnos + $arContrato->getPromedioRecargoNocturnoInicial();
        return $recargosNocturnos;
    }   
    
    public function recargosNocturnos($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBaseCotizacionAdicional) as recagosNocturnos FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                . "WHERE pc.recargoNocturno = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $recargosNocturnos = $arrayResultado[0]['recagosNocturnos'];
        if($recargosNocturnos == null) {
            $recargosNocturnos = 0;
        }
        return $recargosNocturnos;
    }     
    
    public function ibp($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBasePrestacion) as ibp FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.estadoPagado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado[0]['ibp'];
        if($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }
    
    public function auxTransporteCertificadoIngreso($fechaDesde, $fechaHasta, $codigoEmpleado,$codigoConceptoAuxTransporte) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as AuxTransporte FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.estadoPagado = 1 AND p.codigoEmpleadoFk = " . $codigoEmpleado . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "' AND pd.codigoPagoConceptoFk = '" . $codigoConceptoAuxTransporte . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $auxTransporte = $arrayResultado[0]['AuxTransporte'];
        if($auxTransporte == null) {
            $auxTransporte = 0;
        }
        return $auxTransporte;
    }
    public function saludCertificadoIngreso($fechaDesde, $fechaHasta, $codigoEmpleado,$codigoConcepto) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as salud FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.estadoPagado = 1 AND p.codigoEmpleadoFk = " . $codigoEmpleado . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "' AND pd.codigoPagoConceptoFk = '" . $codigoConcepto . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $vrSalud = $arrayResultado[0]['salud'];
        if($vrSalud == null) {
            $vrSalud = 0;
        }
        return $vrSalud;
    }
    public function pensionCertificadoIngreso($fechaDesde, $fechaHasta, $codigoEmpleado,$codigoConcepto) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrPago) as pension FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.estadoPagado = 1 AND p.codigoEmpleadoFk = " . $codigoEmpleado . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "' AND pd.codigoPagoConceptoFk = '" . $codigoConcepto . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $vrPension = $arrayResultado[0]['pension'];
        if($vrPension == null) {
            $vrPension = 0;
        }
        return $vrPension;
    }
    
    //Este no incluye concepto de auxilio transporte
    public function ibpVacaciones($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBasePrestacion) as ibp FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                . "WHERE p.estadoPagado = 1 AND pc.conceptoAuxilioTransporte = 0 AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado[0]['ibp'];
        if($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }    
    
    public function ibpConceptos($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBasePrestacion) as ibp FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                . "WHERE pc.conceptoComision = 1 AND p.estadoPagado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibp = $arrayResultado[0]['ibp'];
        if($ibp == null) {
            $ibp = 0;
        }
        return $ibp;
    }         
    
    public function ibc($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBaseCotizacion) as ibc FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p "
                . "WHERE p.estadoPagado = 1 AND p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "'";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibc = $arrayResultado[0]['ibc'];
        if($ibc == null) {
            $ibc = 0;
        }
        return $ibc;
    }         
    
    public function ibcVacaciones($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBaseCotizacion) as ibc FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                . "WHERE p.estadoPagado = 1 AND pc.conceptoVacacion = 1 AND  p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "' ";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibc = $arrayResultado[0]['ibc'];
        if($ibc == null) {
            $ibc = 0;
        }
        return $ibc;
    }         
    
    public function ibcIncapacidad($fechaDesde, $fechaHasta, $codigoContrato) {
        $em = $this->getEntityManager();
        $dql   = "SELECT SUM(pd.vrIngresoBaseCotizacionIncapacidad) as ibc FROM BrasaRecursoHumanoBundle:RhuPagoDetalle pd JOIN pd.pagoRel p JOIN pd.pagoConceptoRel pc "
                . "WHERE p.estadoPagado = 1 AND pc.conceptoIncapacidad = 1 AND  p.codigoContratoFk = " . $codigoContrato . " "
                . "AND p.fechaDesdePago >= '" . $fechaDesde . "' AND p.fechaDesdePago <= '" . $fechaHasta . "' ";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $ibc = $arrayResultado[0]['ibc'];
        if($ibc == null) {
            $ibc = 0;
        }
        return $ibc;
    }     
}