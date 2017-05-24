<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuIncapacidadRepository extends EntityRepository {
    
    public function listaDQL($intNumero = "", $strCodigoCentroCosto = "", $boolEstadoTranscripcion = "", $strIdentificacion = "", $strNumeroEps = "", $codigoIncapacidadTipo = "", $estadoLegalizado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT i, e FROM BrasaRecursoHumanoBundle:RhuIncapacidad i JOIN i.empleadoRel e WHERE i.codigoIncapacidadPk <> 0";      
        if($intNumero != "") {
            $dql .= " AND i.numero = " . $intNumero;
        } 
        if($strCodigoCentroCosto != "") {
            $dql .= " AND i.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }              
        if($boolEstadoTranscripcion == 1 ) {
            $dql .= " AND i.estadoTranscripcion = 1";
        } elseif($boolEstadoTranscripcion == 0) {
            $dql .= " AND i.estadoTranscripcion = 0";
        }
        if($strNumeroEps != "") {
            $dql .= " AND i.numeroEps = " . $strNumeroEps;
        }         
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        } 
        if($codigoIncapacidadTipo != "") {
            $dql .= " AND i.codigoIncapacidadTipoFk = " . $codigoIncapacidadTipo;
        }        
        if($estadoLegalizado == 1 ) {
            $dql .= " AND i.estadoLegalizado = 1";
        }
        if($estadoLegalizado == "0") {
            $dql .= " AND i.estadoLegalizado = 0";
        }        
        $dql .= " ORDER BY i.codigoIncapacidadPk DESC";
        return $dql;
    }                    
    
    //consulta incapacidades por cobrar
    public function listaIncapacidadesCobrarDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "",$strCodigoEntidadSalud = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT i, e FROM BrasaRecursoHumanoBundle:RhuIncapacidad i JOIN i.empleadoRel e WHERE i.codigoIncapacidadPk <> 0 AND i.estadoCobrar = 1 AND i.vrSaldo > 0";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND i.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND i.fechaDesde >='" . $strDesde. "'";
        }
        if($strHasta != "") {
            $dql .= " AND i.fechaHasta <='" . $strHasta . "'";
        }
        if($strCodigoEntidadSalud != "") {
            $dql .= " AND i.codigoEntidadSaludFk = " . $strCodigoEntidadSalud;
        }
        return $dql;
    }
    
    //consulta incapacidades lista
    public function listaIncapacidadesDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "",$strCodigoEntidadSalud = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT i, e FROM BrasaRecursoHumanoBundle:RhuIncapacidad i JOIN i.empleadoRel e WHERE i.codigoIncapacidadPk <> 0 ";
        if($strCodigoCentroCosto != "") {
            $dql .= " AND i.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND i.fechaDesde >='" .  $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND i.fechaHasta <='" .  $strHasta . "'";
        }
        if($strCodigoEntidadSalud != "") {
            $dql .= " AND i.codigoEntidadSaludFk = " . $strCodigoEntidadSalud;
        }
        return $dql;
    }
    
    //lista de incapacidades pendientes por centro centro de costo para el resumen de la programacioan de pago
    public function pendientesCentroCosto($strCodigoCentroCosto) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT i FROM BrasaRecursoHumanoBundle:RhuIncapacidad i "
                . "WHERE i.codigoCentroCostoFk = " . $strCodigoCentroCosto . " "
                . "AND i.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arIncapacidadesPendientes = $query->getResult();
        return $arIncapacidadesPendientes;        
    }
    
    //lista de incapacidades pendientes por empleado cuando se esta generando la nomina
    public function pendientesEmpleado($strCodigoEmpleado) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT i FROM BrasaRecursoHumanoBundle:RhuIncapacidad i "
                . "WHERE i.codigoEmpleadoFk = " . $strCodigoEmpleado . " "
                . "AND i.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arIncapacidadesPendientesEmpleado = $query->getResult();
        return $arIncapacidadesPendientesEmpleado;        
    }
    
    //lista de incapacidades por cobrar y por entidad de salud
    public function listaIncapacidadesEntidadSaludCobrar($strCodigoEntidadSalud = '') {
        $em = $this->getEntityManager();                
        $dql   = "SELECT i FROM BrasaRecursoHumanoBundle:RhuIncapacidad i "
                . "WHERE i.codigoEntidadSaludFk = " . $strCodigoEntidadSalud . " "
                . "AND i.estadoCobrar = 1 AND i.vrSaldo > 0";
        $query = $em->createQuery($dql);
        $arIncapacidadesCobrar = $query->getResult();
        return $arIncapacidadesCobrar;        
    }
    
    
    /*
     * Este calculo de dias de incapacidad es solo para periodos de nomina por lo tanto
     * aplica solo en 1 mes
     */    
    public function diasIncapacidad($fechaDesde, $fechaHasta, $codigoEmpleado, $tipo) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad JOIN incapacidad.incapacidadTipoRel it "
                . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
                . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
                . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' "
                . "AND it.tipo = " . $tipo;
        $objQuery = $em->createQuery($dql);  
        $arIncapacidades = $objQuery->getResult();         
        $intDiasIncapacidad = 0;
        foreach ($arIncapacidades as $arIncapacidad) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arIncapacidad->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arIncapacidad->getFechaDesde()->format('j');
            }
            if($arIncapacidad->getFechaHasta() > $fechaHasta) {
                $intDiaFin = 30;                
            } else {
                $intDiaFin = $arIncapacidad->getFechaHasta()->format('j');
                if($intDiaFin == 31) {
                    $intDiaFin = 30;
                }
            }            
            $intDiasIncapacidad += (($intDiaFin - $intDiaInicio)+1);
        }
        if($intDiasIncapacidad > 30) {
            $intDiasIncapacidad = 30;
        }
        return $intDiasIncapacidad;                     
    }                    
    
    /*
     * Este calculo de dias de incapacidad es solo para periodos de nomina por lo tanto
     * aplica solo en 1 mes
     */
    public function diasIncapacidadPeriodo($fechaDesde, $fechaHasta, $codigoEmpleado) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad "
                . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
                . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
                . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        $objQuery = $em->createQuery($dql);  
        $arIncapacidades = $objQuery->getResult();         
        $intDiasIncapacidad = 0;
        foreach ($arIncapacidades as $arIncapacidad) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arIncapacidad->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arIncapacidad->getFechaDesde()->format('j');
            }
            if($arIncapacidad->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');                
            } else {
                $intDiaFin = $arIncapacidad->getFechaHasta()->format('j');
            }            
            if($intDiaFin == 31) {
                $intDiaFin = 30;
            }            
            $intDiasIncapacidad += (($intDiaFin - $intDiaInicio)+1);
        }
        if($intDiasIncapacidad > 30) {
            $intDiasIncapacidad = 30;
        }
        return $intDiasIncapacidad;                     
    }                        
    
    /*
     * Tiene en cuena el dia 31
     */        
    public function diasIncapacidadPeriodo31($fechaDesde, $fechaHasta, $codigoEmpleado) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad "
                . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
                . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
                . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        $objQuery = $em->createQuery($dql);  
        $arIncapacidades = $objQuery->getResult();         
        $intDiasIncapacidad = 0;
        foreach ($arIncapacidades as $arIncapacidad) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arIncapacidad->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arIncapacidad->getFechaDesde()->format('j');
            }
            if($arIncapacidad->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');                
            } else {
                $intDiaFin = $arIncapacidad->getFechaHasta()->format('j');
            }                       
            $intDiasIncapacidad += (($intDiaFin - $intDiaInicio)+1);
        }
        return $intDiasIncapacidad;                     
    }    
    
    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoIncapacidad = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $boolValidar = FALSE;
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad "
                . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
                . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) "
                . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        if($codigoIncapacidad != ""){
           $dql = $dql. "AND incapacidad.codigoIncapacidadPk <> " .$codigoIncapacidad;
        }
        $objQuery = $em->createQuery($dql);  
        $arIncapacidades = $objQuery->getResult();         
        if(count($arIncapacidades) > 0) {
            $boolValidar = FALSE;
        } else {
            $boolValidar = TRUE;
        }

        return $boolValidar;                     
    }      
    
    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoCentroCosto = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad "
                . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
                . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) ";
        if($codigoEmpleado != "") {
            $dql = $dql . "AND incapacidad.codigoEmpleadoFk = " . $codigoEmpleado . " ";
        }
        if($codigoCentroCosto != "") {
            $dql = $dql . "AND incapacidad.codigoCentroCostoFk = " . $codigoCentroCosto . " ";
        }
        $objQuery = $em->createQuery($dql);  
        $arIncapacidades = $objQuery->getResult();         
        return $arIncapacidades;
    }    
    
    /*
     * Se usa para verificar si al cierre de un cotrato no hay incapacidades pendientes
     */
    public function validarCierreContrato($fechaHasta, $codigoEmpleado) {
        $em = $this->getEntityManager();        
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $boolValidar = FALSE;
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad "
                . "WHERE  incapacidad.fechaHasta > '$strFechaHasta' "
                . "AND incapacidad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        $objQuery = $em->createQuery($dql);  
        $arIncapacidades = $objQuery->getResult();         
        if(count($arIncapacidades) > 0) {
            $boolValidar = FALSE;
        } else {
            $boolValidar = TRUE;
        }

        return $boolValidar;                     
    }          
    
    /*
     * Se utiliza en la utilidad de turnos para saber 
     */
    public function pendientesAplicarTurnoDql($fechaDesde, $fechaHasta) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde;
        $strFechaHasta = $fechaHasta;
        $dql = "SELECT incapacidad FROM BrasaRecursoHumanoBundle:RhuIncapacidad incapacidad "
                . "WHERE (((incapacidad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (incapacidad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (incapacidad.fechaDesde >= '$strFechaDesde' AND incapacidad.fechaDesde <= '$strFechaHasta') "
                . "OR (incapacidad.fechaHasta >= '$strFechaHasta' AND incapacidad.fechaDesde <= '$strFechaDesde')) ";
        return $dql;
    }
    
    public function pendienteCobrarCobro($codigoCliente) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuIncapacidad e WHERE e.estadoCobrado = 0 "
                . " AND e.codigoClienteFk = " . $codigoCliente. "AND e.estadoCobrar = 1";
        return $dql;
    }
    
    public function detalleCobro($codigoCobro) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuIncapacidad e WHERE e.codigoCobroFk = " . $codigoCobro;
        return $dql;
    }
}