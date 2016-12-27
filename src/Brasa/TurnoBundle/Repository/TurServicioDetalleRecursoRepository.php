<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRecursoRepository extends EntityRepository {
    public function listaDql($codigoServicioDetalle) {
        $dql   = "SELECT pdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso pdr WHERE pdr.codigoServicioDetalleFk = " . $codigoServicioDetalle;
        $dql .= " ORDER BY pdr.posicion";
        return $dql;
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $ar = $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->find($codigo);                
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }
    
    public function listaConsultaDql($codigoCliente = "", $codigoRecurso = "") {
        $dql   = "SELECT sdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso sdr JOIN sdr.servicioDetalleRel sd JOIN sd.servicioRel s WHERE sdr.codigoServicioDetalleRecursoPk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND s.codigoClienteFk = " . $codigoCliente;  
        } 
        if($codigoRecurso != "") {
            $dql .= " AND sdr.codigoRecursoFk = " . $codigoRecurso;  
        }        
        $dql .= " ORDER BY s.codigoClienteFk, sd.codigoServicioFk";
        return $dql;
    }   
    
    /*
     * Valida si el recurso esta en un servicio permanente
     */
    public function validarRecurso($codigoEmpleado) {
        $em = $this->getEntityManager();        
        $boolValidar = FALSE;
        $dql = "SELECT sdr FROM BrasaTurnoBundle:TurServicioDetalleRecurso sdr "
                . "WHERE sdr.codigoRecursoFk = '" . $codigoEmpleado . "' ";
        $objQuery = $em->createQuery($dql);  
        $arServicioDetalleRecurso = $objQuery->getResult();         
        if(count($arServicioDetalleRecurso) > 0) {
            $boolValidar = FALSE;
        } else {
            $boolValidar = TRUE;
        }        
        return $boolValidar;                     
    }     
}