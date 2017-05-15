<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuServicioCobrarRepository extends EntityRepository {
    
    public function listaServiciosPorCobrarDQL($strCodigoCentroCosto = "", $strIdentificacion = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT s, e FROM BrasaRecursoHumanoBundle:RhuServicioCobrar s JOIN s.empleadoRel e WHERE s.codigoServicioCobrarPk <> 0 AND s.estadoCobrado <> 1";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND e.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND s.fechaDesde >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND s.fechaHasta <='" . $strHasta . "'";
        }
        
        return $dql;
    }
    
    public function pendienteCobrar($codigoCliente,$codigoCentroTrabajo="") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT sc FROM BrasaRecursoHumanoBundle:RhuServicioCobrar sc WHERE sc.estadoCobrado = 0 "
                . " AND sc.codigoClienteFk = " . $codigoCliente . " ";
        if($codigoCentroTrabajo != "") {
            $dql .= " AND sc.codigoCentroTrabajoFk = " . $codigoCentroTrabajo;
        } 
        $dql .= "ORDER BY sc.codigoCentroTrabajoFk"
                
                ;        
        return $dql;
    }
    
    public function detalleCobro($codigoCobro) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT sc FROM BrasaRecursoHumanoBundle:RhuServicioCobrar sc WHERE sc.codigoCobroFk = " . $codigoCobro;
        return $dql;
    }    
}