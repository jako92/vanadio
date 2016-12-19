<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDevolucionRepository extends EntityRepository {
    
    public function listaDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDevolucion pd WHERE pd.codigoPedidoDevolucionPk <> 0";
        if($numeroPedido != "") {
            $dql .= " AND pd.numero = " . $numeroPedido;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND pd.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND pd.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND pd.estadoAutorizado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND pd.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND pd.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND pd.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND pd.fechaProgramacion <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY pd.fecha DESC";
        return $dql;
    }
 
    
}
