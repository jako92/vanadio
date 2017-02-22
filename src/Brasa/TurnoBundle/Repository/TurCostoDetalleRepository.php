<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCostoDetalleRepository extends EntityRepository {

    public function listaDql($codigoEmpleado = "", $anio = "", $mes = "", $codigoPedidoDetalle = "") {
        $dql   = "SELECT cd FROM BrasaTurnoBundle:TurCostoDetalle cd WHERE cd.codigoCostoDetallePk <> 0";
        if($codigoEmpleado != "") {
            $dql .= " AND cd.codigoEmpleadoFk = " . $codigoEmpleado;  
        }  
        if($codigoPedidoDetalle != "") {
            $dql .= " AND cd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;  
        }         
        if($anio != "") {
            $dql .= " AND cd.anio = " . $anio;  
        }     
        if($mes != "") {
            $dql .= " AND cd.mes = " . $mes;  
        }         
        return $dql;
    }    

    public function listaConsultaDql($codigoCliente = "", $anio = "", $mes = "", $codigoPuesto = "") {
        $dql   = "SELECT cd FROM BrasaTurnoBundle:TurCostoDetalle cd WHERE cd.codigoCostoDetallePk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND cd.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoPuesto != "") {
            $dql .= " AND cd.codigoPuestoFk = " . $codigoPuesto;  
        }        
        if($anio != "") {
            $dql .= " AND cd.anio = " . $anio;  
        }                
        if($mes != "") {
            $dql .= " AND cd.mes = " . $mes;  
        }        
        return $dql;
    }    
    
}