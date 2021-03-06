<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoServicioRepository extends EntityRepository {

    public function listaDql($codigoCliente = "", $anio = "", $mes = "", $codigoPuesto = "") {
        $dql   = "SELECT cs FROM BrasaTurnoBundle:TurCostoServicio cs WHERE cs.codigoCostoServicioPk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND cs.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoPuesto != "") {
            $dql .= " AND cs.codigoPuestoFk = " . $codigoPuesto;  
        }        
        if($anio != "") {
            $dql .= " AND cs.anio = " . $anio;  
        }        
        if($mes != "") {
            $dql .= " AND cs.mes = " . $mes;  
        }     
        $dql .= " ORDER BY cs.anio, cs.mes DESC";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                                
                $arSoportePagoPeriodo = $em->getRepository('BrasaTurnoBundle:TurSoportePagoPeriodo')->find($codigo);                    
                if($arSoportePagoPeriodo->getEstadoGenerado() == 0) {
                    $em->remove($arSoportePagoPeriodo);                    
                }                                     
            }
            $em->flush();
        }
    }     

}