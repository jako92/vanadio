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
        $arCierreMes = new \Brasa\TurnoBundle\Entity\TurCierreMes();
        $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
        $strDql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaConsultaPendienteFacturarDql(
               "", 
                "", 
                "", 
                "", 
                "", 
                "", 
                "", 
                "");        
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
    }
}