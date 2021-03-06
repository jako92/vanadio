<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuRequisitoDetalleRepository extends EntityRepository {
    
    public function listaDql($strIdentificacion = "", $strFecha = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT rd, r FROM BrasaRecursoHumanoBundle:RhuRequisitoDetalle rd JOIN rd.requisitoRel r WHERE rd.estadoEntregado = 0 AND rd.estadoNoAplica = 0";
   
        if($strIdentificacion != "" ) {
            $dql .= " AND r.numeroIdentificacion = '" . $strIdentificacion . "'";
        }
        $dql .= " ORDER BY rd.codigoRequisitoDetallePk DESC";
        return $dql;
    }     
}