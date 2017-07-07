<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvLoteRepository extends EntityRepository {

    public function consultaExistenciaDql($strCodigoItem = '') {
        $dql   = "SELECT l FROM BrasaInventarioBundle:InvLote l WHERE l.cantidadExistencia > 0";        
        if($strCodigoItem != "" ) {
            $dql .= " AND l.codigoItemFk = " . $strCodigoItem;
        }
        $dql .= " ORDER BY l.loteFk";
        return $dql;
    }
    
    public function consultaDisponibleDql($codigoItem = '', $codigoBodega, $fechaDesde = "", $fechaHasta = "", $sinDisponible = 0) {
        $dql   = "SELECT l FROM BrasaInventarioBundle:InvLote l WHERE l.codigoItemFk IS NOT NULL";        
        if($codigoItem != "" ) {
            $dql .= " AND l.codigoItemFk = " . $codigoItem;
        }
        if($codigoBodega!= "" ) {
            $dql .= " AND l.codigoBodegaFk = " . $codigoBodega;
        }
        if($fechaDesde != '') {
            $dql .= " AND l.fechaVencimiento >= '$fechaDesde'";
        }
        if($fechaHasta != '') {
            $dql .= " AND l.fechaVencimiento <= '$fechaHasta'";
        }
        if($sinDisponible == 0) {
            $dql .= " AND l.cantidadDisponible > 0";
        }
        $dql .= " ORDER BY l.loteFk";
        return $dql;
    }    
    
    public function afectar($tipo, $operacion, $codigoItem, $codigoLote, $fechaVecimiento, $codigoBodega, $cantidad) {
        $em = $this->getEntityManager();
        $arLote = new \Brasa\InventarioBundle\Entity\InvLote();
        $arLote = $em->getRepository('BrasaInventarioBundle:InvLote')->find(array('codigoItemFk' => $codigoItem,'loteFk' => $codigoLote,'codigoBodegaFk' => $codigoBodega));
        if(!$arLote) {
           $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigoItem);
           $arBodega = $em->getRepository('BrasaInventarioBundle:InvBodega')->find($codigoBodega);
           $arLote = new \Brasa\InventarioBundle\Entity\InvLote();
           $arLote->setCodigoItemFk($codigoItem);
           $arLote->setItemRel($arItem);
           $arLote->setCodigoBodegaFk($codigoBodega);
           $arLote->setBodegaRel($arBodega);
           $arLote->setLoteFk($codigoLote);
           $arLote->setFechaVencimiento($fechaVecimiento);
           $arLote->setCodigoBodegaFk($codigoBodega);
        } 
        $cantidad = ($operacion * $cantidad) * $tipo; 
        $cantidad += $arLote->getCantidadExistencia();
        $arLote->setCantidadExistencia($cantidad);
        $arLote->setCantidadDisponible($cantidad);
        $em->persist($arLote);
    }        
}