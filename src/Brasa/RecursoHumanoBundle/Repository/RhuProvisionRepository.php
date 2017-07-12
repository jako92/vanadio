<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuProvisionRepository extends EntityRepository {
    
    public function listaDql($codigoProvisionPeriodo = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuProvision p WHERE p.codigoProvisionPk <> 0";
        if($codigoProvisionPeriodo) {
            $dql .= " AND p.codigoProvisionPeriodoFk = " . $codigoProvisionPeriodo;
        }
        $dql .= " ORDER BY p.codigoProvisionPk ASC";
        return $dql;
    }                            
    
    public function pendientesContabilizarDql($anio="", $mes="", $codigoEmpleado = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuProvision p JOIN p.provisionPeriodoRel pp WHERE pp.estadoGenerado = 1 AND pp.estadoCerrado = 1 AND p.estadoContabilizado = 0 ";
        if($anio != "") {
            $dql .=" AND p.anio = " . $anio;
        }
        if($mes != "") {
            $dql .=" AND p.mes = " . $mes;
        }   
        if($codigoEmpleado != "") {
            $dql .=" AND p.codigoEmpleadoFk = " . $codigoEmpleado;
        }        
        $dql .= " ORDER BY p.codigoProvisionPk ASC";
        return $dql;
    }
    
    public function contabilizadosDql($intNumeroDesde = 0, $intNumeroHasta = 0, $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuProvision p WHERE p.estadoContabilizado = 1";
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
            $dql .= " AND p.fechaDesde <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }     
    
    public function eliminar($arrSeleccionados) {        
        $em = $this->getEntityManager();
        $mensaje = '';
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $contabilizado = False;
                $arProvisionContabilizado = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvision')->findBy(array('codigoProvisionPeriodoFk' => $codigo, 'estadoContabilizado' => 1));
                if ($arProvisionContabilizado){
                    $mensaje = "No se puede eliminar la provision ". $codigo.", tiene registros contabilizados!";
                } else {
                    $arProvisionPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuProvisionPeriodo')->find($codigo);
                    if ($arProvisionPeriodo->getEstadoGenerado() == 1){
                        $mensaje = "No se puede eliminar la provision ". $codigo.", tiene registros generados!";
                    } else {
                        $em->remove($arProvisionPeriodo);
                    }
                }  
            }
            $em->flush();
        }
        return $mensaje; 
    }
            
}