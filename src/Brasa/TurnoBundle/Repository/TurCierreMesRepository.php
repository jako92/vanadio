<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCierreMesRepository extends EntityRepository {

    public function listaDql() {
        $dql   = "SELECT cm FROM BrasaTurnoBundle:TurCierreMes cm";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                                
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigo);                    
                if($arCierreMes->getEstadoGenerado() == 0) {
                    $em->remove($arCierreMes);                    
                }                                     
            }
            $em->flush();
        }
    }    
    
    public function generarCierreMesComercial($codigoCierreMes) {
        $em = $this->getEntityManager();
        $strRespuesta = "";
        $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
        $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
        $ultimoDiaMes = date("d",(mktime(0,0,0,$arCierreMes->getMes()+1,1,$arCierreMes->getAnio())-1));
        $fechaDesde = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/01";        
        $fechaHasta = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/" . $ultimoDiaMes;
        $strDql   = "SELECT count(p.codigoPedidoPk) numero FROM BrasaTurnoBundle:TurPedido p WHERE p.codigoPedidoPk <> 0 AND p.estadoAutorizado = 0 "
                . " AND p.fechaProgramacion >= '" . $fechaDesde . "' AND p.fechaProgramacion <= '" . $fechaHasta. "'";                                     
        $query = $em->createQuery($strDql);
        $arPedidos = $query->getSingleResult();
        $numeroPedidos = $arPedidos['numero'];
        if($numeroPedidos <= 0) {
            $strDql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.vrTotalDetallePendiente > 0 AND pd.mes = " . $arCierreMes->getMes() . " AND pd.anio= " . $arCierreMes->getAnio() . " ORDER BY p.codigoClienteFk, pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";    
            $query = $em->createQuery($strDql);
            $arPedidoDetalles = $query->getResult();
            foreach ($arPedidoDetalles as $arPedidoDetalle) {
                $arIngresoPendiente = new \Brasa\TurnoBundle\Entity\TurIngresoPendiente();
                $arIngresoPendiente->setCodigoCierreMesFk($arCierreMes->getCodigoCierreMesPk());
                $arIngresoPendiente->setAnio($arCierreMes->getAnio());
                $arIngresoPendiente->setMes($arCierreMes->getMes());
                $arIngresoPendiente->setPedidoDetalleRel($arPedidoDetalle);
                $arIngresoPendiente->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                $arIngresoPendiente->setVrSubtotal($arPedidoDetalle->getVrTotalDetallePendiente());
                $em->persist($arIngresoPendiente);
            }
            $arCierreMes->setEstadoGeneradoComercial(1);
            $em->persist($arCierreMes);
            $em->flush();         
            $strSql = "UPDATE tur_pedido SET estado_cierre_mes = 1 WHERE fecha_programacion >= '" . $fechaDesde . "' AND fecha_programacion <= '" . $fechaHasta. "'";           
            $em->getConnection()->executeQuery($strSql);            
        } else {
            $strRespuesta = "Existen pedidos sin autorizar dentro del periodo de cierre, debe autorizarlos para cerrar el mes";
        }
        return $strRespuesta;
    }
}