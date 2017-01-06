<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDevolucionDetalleRepository extends EntityRepository {

    public function listaDql($codigoPedidoDevolucion = "") {
        $dql   = "SELECT pdd FROM BrasaTurnoBundle:TurPedidoDevolucionDetalle pdd WHERE pdd.codigoPedidoDevolucionDetallePk <> 0 ";
        
        if($codigoPedidoDevolucion != '') {
            $dql .= "AND pdd.codigoPedidoDevolucionFk = " . $codigoPedidoDevolucion . " ";  
        }        
        $dql .= " ORDER BY pdd.codigoPedidoDevolucionDetallePk";
        return $dql;
    }                  
    
}