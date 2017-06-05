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
            $dql .= " AND pd.fecha >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND pd.fecha <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY pd.fecha DESC";
        return $dql;
    }
 
    public function liquidar($codigoPedidoDevolucion) {        
        $em = $this->getEntityManager();        
        $arPedidoDevolucion = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();            
        $arPedidoDevolucion = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->find($codigoPedidoDevolucion);                
        $total = 0;        
        $arPedidoDevolucionDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle();        
        $arPedidoDevolucionDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucionDetalle')->findBy(array('codigoPedidoDevolucionFk' => $codigoPedidoDevolucion));        
        foreach ($arPedidoDevolucionDetalles as $arPedidoDevolucionDetalle) {
            $total += $arPedidoDevolucionDetalle->getVrPrecio();
        }
        $arPedidoDevolucion->setVrTotal($total);
        $em->persist($arPedidoDevolucion);
        $em->flush();
        return true;
    } 
    
    public function autorizar($codigoPedidoDevolucion) {        
        $em = $this->getEntityManager();        
        $arPedidoDevolucion = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();            
        $arPedidoDevolucion = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->find($codigoPedidoDevolucion);                                
        $arPedidoDevolucion->setEstadoAutorizado(1);                
        $em->persist($arPedidoDevolucion);
        
        $arPedidoDevolucionDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle();
        $arPedidoDevolucionDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucionDetalle')->findBy(array('codigoPedidoDevolucionFk' => $codigoPedidoDevolucion));                                
        foreach ($arPedidoDevolucionDetalles as $arPedidoDevolucionDetalle) {
            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDevolucionDetalle->getCodigoPedidoDetalleFk());                        
            if($arPedidoDevolucion->getCodigoPedidoDevolucionTipoFk() == 'DEV') {
                $devolucion = $arPedidoDetalle->getVrTotalDetalleDevolucion() + $arPedidoDevolucionDetalle->getVrPrecio();
                $arPedidoDetalle->setVrTotalDetalleDevolucion($devolucion);
                $pendiente = $arPedidoDetalle->getVrSubtotal() - ($arPedidoDetalle->getVrTotalDetalleAfectado() + $arPedidoDetalle->getVrTotalDetalleDevolucion() - $arPedidoDetalle->getVrTotalDetalleAdicion());
                $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
                $em->persist($arPedidoDetalle);                            
            }
            if($arPedidoDevolucion->getCodigoPedidoDevolucionTipoFk() == 'ADI') {                                
                $adicion = $arPedidoDetalle->getVrTotalDetalleAdicion() + $arPedidoDevolucionDetalle->getVrPrecio();
                $arPedidoDetalle->setVrTotalDetalleAdicion($adicion);
                $pendiente = $arPedidoDetalle->getVrSubtotal() - ($arPedidoDetalle->getVrTotalDetalleAfectado() + $arPedidoDetalle->getVrTotalDetalleDevolucion() - $arPedidoDetalle->getVrTotalDetalleAdicion());
                $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
                $em->persist($arPedidoDetalle);                            
            }            
        }
        $em->flush();
        return "";
    }  
    
    public function desautorizar($codigoPedidoDevolucion) {        
        $em = $this->getEntityManager();        
        $arPedidoDevolucion = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();            
        $arPedidoDevolucion = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->find($codigoPedidoDevolucion);                                
        $arPedidoDevolucion->setEstadoAutorizado(0);
        $em->persist($arPedidoDevolucion);
        $arPedidoDevolucionDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle();
        $arPedidoDevolucionDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucionDetalle')->findBy(array('codigoPedidoDevolucionFk' => $codigoPedidoDevolucion));                                
        foreach ($arPedidoDevolucionDetalles as $arPedidoDevolucionDetalle) {
            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arPedidoDevolucionDetalle->getCodigoPedidoDetalleFk());                        
            if($arPedidoDevolucion->getCodigoPedidoDevolucionTipoFk() == 'DEV') {
                $devolucion = $arPedidoDetalle->getVrTotalDetalleDevolucion() - $arPedidoDevolucionDetalle->getVrPrecio();
                $arPedidoDetalle->setVrTotalDetalleDevolucion($devolucion);
                $pendiente = $arPedidoDetalle->getVrSubtotal() - ($arPedidoDetalle->getVrTotalDetalleAfectado() + $arPedidoDetalle->getVrTotalDetalleDevolucion() - $arPedidoDetalle->getVrTotalDetalleAdicion());
                $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
                $em->persist($arPedidoDetalle);                            
            }
            if($arPedidoDevolucion->getCodigoPedidoDevolucionTipoFk() == 'ADI') {                                
                $adicion = $arPedidoDetalle->getVrTotalDetalleAdicion() - $arPedidoDevolucionDetalle->getVrPrecio();
                $arPedidoDetalle->setVrTotalDetalleAdicion($adicion);
                $pendiente = $arPedidoDetalle->getVrSubtotal() - ($arPedidoDetalle->getVrTotalDetalleAfectado() + $arPedidoDetalle->getVrTotalDetalleDevolucion() - $arPedidoDetalle->getVrTotalDetalleAdicion());
                $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
                $em->persist($arPedidoDetalle);                            
            }            
        }        
        $em->flush();
        return "";
    }     
}
