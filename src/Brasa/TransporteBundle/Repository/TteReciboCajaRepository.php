<?php

namespace Brasa\TransporteBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TteReciboCajaRepository extends EntityRepository {    
    public function RecibosCajaGuiasDetalle($codigoGuia) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT recibos FROM BrasaTransporteBundle:TteReciboCaja recibos WHERE recibos.codigoGuiaFk = " . $codigoGuia;
        $query = $em->createQuery($dql);        
        return $query;
    }    
    
    public function ListaRecibosCaja($fechaDesde, $fechaHasta) {        
        $em = $this->getEntityManager();        
        $dql   = "SELECT rc FROM BrasaTransporteBundle:TteReciboCaja rc WHERE rc.fecha >= '" . $fechaDesde->format('Y/m/d') . " 00:00:00' AND rc.fecha <= '" . $fechaHasta->format('Y/m/d') . " 23:59:59'"; 
        $query = $em->createQuery($dql);        
        return $query;
    }            
}