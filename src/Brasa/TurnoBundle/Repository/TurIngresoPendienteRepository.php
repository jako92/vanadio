<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurIngresoPendienteRepository extends EntityRepository {

    public function listaDql($codigoCliente = "", $anio = "", $mes = "") {
        $dql   = "SELECT ip FROM BrasaTurnoBundle:TurIngresoPendiente ip WHERE ip.codigoIngresoPendientePk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND ip.codigoClienteFk = " . $codigoCliente;  
        }
        if($anio != "") {
            $dql .= " AND ip.anio = " . $anio;  
        }        
        if($mes != "") {
            $dql .= " AND ip.mes = " . $mes;  
        }     
        $dql .= " ORDER BY ip.anio, ip.mes DESC";
        return $dql;
    }  
    
}