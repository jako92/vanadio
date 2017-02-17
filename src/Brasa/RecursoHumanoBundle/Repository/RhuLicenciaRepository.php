<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuLicenciaRepository extends EntityRepository {
    
    public function listaDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strLicenciaTipo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT l, e FROM BrasaRecursoHumanoBundle:RhuLicencia l JOIN l.empleadoRel e WHERE l.codigoLicenciaPk <> 0";      
        if($strCodigoCentroCosto != "") {
            $dql .= " AND l.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }                       
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if($strLicenciaTipo != "") {
            $dql .= " AND l.codigoLicenciaTipoFk = " . $strLicenciaTipo;
        }
        $dql .= " ORDER BY l.fechaDesde DESC";
        return $dql;
    }                    
    
    public function pendientesCentroCosto($strCodigoCentroCosto) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT l FROM BrasaRecursoHumanoBundle:RhuLicencia l "
                . "WHERE l.codigoCentroCostoFk = " . $strCodigoCentroCosto . " "
                . "AND l.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arLicenciasPendientes = $query->getResult();
        return $arLicenciasPendientes;        
    }
        
    public function pendientesEmpleado($strCodigoEmpleado) {
        $em = $this->getEntityManager();                
        $dql   = "SELECT l FROM BrasaRecursoHumanoBundle:RhuLicencia l "
                . "WHERE l.codigoEmpleadoFk = " . $strCodigoEmpleado . " "
                . "AND l.cantidadPendiente != 0 ";
        $query = $em->createQuery($dql);
        $arLicenciasPendientesEmpleado = $query->getResult();
        return $arLicenciasPendientesEmpleado;        
    }        
    
    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoCentroCosto = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia "
                . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
                . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) ";
        if($codigoEmpleado != "") {
            $dql = $dql . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        }
        if($codigoCentroCosto != "") {
            $dql = $dql . "AND licencia.codigoCentroCostoFk = " . $codigoCentroCosto . " ";
        }        

        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        return $arLicencias;
    }                    
    
    /*
     * Este calculo de dias de incapacidad es solo para periodos de nomina por lo tanto
     * aplica solo en 1 mes
     */    
    public function diasLicencia($fechaDesde, $fechaHasta, $codigoEmpleado, $tipo) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia JOIN licencia.licenciaTipoRel licenciaTipo "
                . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
                . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
                . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";

        if($tipo == 1) {
            $dql = $dql . "AND licenciaTipo.maternidad = 1";       
        } else {
            $dql = $dql . "AND licenciaTipo.maternidad = 0";       
        }
        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        $intDiasLicencia = 0;
        foreach ($arLicencias as $arLicencia) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arLicencia->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arLicencia->getFechaDesde()->format('j');
            }
            if($arLicencia->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');               
            } else {
                $intDiaFin = $arLicencia->getFechaHasta()->format('j');                
            }            
            if($intDiaFin == 31) {
                $intDiaFin = 30;
            }            
            $intDiasLicencia += (($intDiaFin - $intDiaInicio)+1);
        }
        if($intDiasLicencia > 30) {
            $intDiasLicencia = 30;
        }
        return $intDiasLicencia;
    }                
    
    /*
     * Este calculo de dias de incapacidad es solo para periodos de nomina por lo tanto
     * aplica solo en 1 mes
     */    
    public function diasLicenciaPeriodo($fechaDesde, $fechaHasta, $codigoEmpleado) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia "
                . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
                . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
                . "AND licencia.codigoEmpleadoFk = " . $codigoEmpleado ." ";
        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        $intDiasLicencia = 0;
        foreach ($arLicencias as $arLicencia) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arLicencia->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arLicencia->getFechaDesde()->format('j');
            }
            if($arLicencia->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');                 
            } else {
                $intDiaFin = $arLicencia->getFechaHasta()->format('j');
            }
            if($intDiaFin == 31) {
                $intDiaFin = 30;
            }                 
                        
            $intDiasLicencia += (($intDiaFin - $intDiaInicio)+1);
        }
        if($intDiasLicencia > 30) {
            $intDiasLicencia = 30;
        }
        return $intDiasLicencia;
    }                    

    public function diasLicenciaPeriodo31($fechaDesde, $fechaHasta, $codigoEmpleado) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia "
                . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
                . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
                . "AND licencia.codigoEmpleadoFk = " . $codigoEmpleado ." ";
        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        $intDiasLicencia = 0;
        foreach ($arLicencias as $arLicencia) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arLicencia->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arLicencia->getFechaDesde()->format('j');
            }
            if($arLicencia->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');                 
            } else {
                $intDiaFin = $arLicencia->getFechaHasta()->format('j');
            }                 
                        
            $intDiasLicencia += (($intDiaFin - $intDiaInicio)+1);
        }
        return $intDiasLicencia;
    } 
    
    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoLicencia = "") {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $boolValidar = FALSE;
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia "
                . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
                . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
                . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        if($codigoLicencia != ""){
           $dql = $dql. "AND licencia.codigoLicenciaPk <> " .$codigoLicencia;
        }
        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        if(count($arLicencias) > 0) {
            $boolValidar = FALSE;
        } else {
            $boolValidar = TRUE;
        }

        return $boolValidar;                     
    }                            
    
    /*
     * Se usa para verificar si al cierre de un cotrato no hay licencias pendientes
     */
    public function validarCierreContrato($fechaHasta, $codigoEmpleado) {
        $em = $this->getEntityManager();        
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $boolValidar = FALSE;
        $dql = "SELECT licencia FROM BrasaRecursoHumanoBundle:RhuLicencia licencia "
                . "WHERE  licencia.fechaHasta > '$strFechaHasta' "
                . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        $objQuery = $em->createQuery($dql);  
        $arLicencias = $objQuery->getResult();         
        if(count($arLicencias) > 0) {
            $boolValidar = FALSE;
        } else {
            $boolValidar = TRUE;
        }

        return $boolValidar;                     
    }     
}