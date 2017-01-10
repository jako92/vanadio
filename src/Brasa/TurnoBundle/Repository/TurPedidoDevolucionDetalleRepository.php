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
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                                
                $arPedidoDevolucionDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucionDetalle')->find($codigo);                                    
                $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDevolucionDetalle->getCodigoPedidoDetalleFk());                 
                $devolucion = $arPedidoDetalle->getVrTotalDetalleDevolucion() - $arPedidoDevolucionDetalle->getVrPrecio();
                $arPedidoDetalle->setVrTotalDetalleDevolucion($devolucion);
                $pendiente = $arPedidoDetalle->getVrSubtotal() - ($arPedidoDetalle->getVrTotalDetalleAfectado() + $arPedidoDetalle->getVrTotalDetalleDevolucion());                                    
                $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
                $em->persist($arPedidoDetalle);                
                $em->remove($arPedidoDevolucionDetalle);                                     
            }                                         
            $em->flush();         
        }
        
    }     
    
}