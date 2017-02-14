<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoRepository extends EntityRepository {

    public function listaDql($codigoRecurso = "", $anio = "", $mes = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaTurnoBundle:TurCosto c WHERE c.codigoCostoPk <> 0";
        
        if($codigoRecurso != "") {
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
            if($arRecurso) {
                $dql .= " AND c.codigoEmpleadoFk = " . $arRecurso->getCodigoEmpleadoFk();  
            }            
        } 
        if($anio != "") {
            $dql .= " AND c.anio = " . $anio;  
        }     
        if($mes != "") {
            $dql .= " AND c.mes = " . $mes;  
        }        
        return $dql;
    }    

}