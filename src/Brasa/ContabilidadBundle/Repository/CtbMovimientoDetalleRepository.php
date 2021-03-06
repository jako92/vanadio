<?php

namespace Brasa\ContabilidadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CtbMovimientoDetalleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CtbMovimientoDetalleRepository extends EntityRepository
{
    public function DevNroDetalles($codigoMovimiento) {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
                ->select('COUNT(md.codigoMovimientoDetallePk) as cantidad')
                ->from('BrasaContabilidadBundle:CtbMovimientoDetalle', 'md')
                ->where('md.codigoMovimientoFk = :codigoMovimientoFk')                
                ->setParameter('codigoMovimientoFk', $codigoMovimiento)
                ->getQuery();
        
        $arMovimientosDetalles = $query->getResult();
        return $arMovimientosDetalles[0]['cantidad'];
    }        
}