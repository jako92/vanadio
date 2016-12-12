<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuConsultaPagoConceptoRepository extends EntityRepository {    
    public function listaDql() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT cpc FROM BrasaRecursoHumanoBundle:RhuConsultaPagoConcepto cpc WHERE cpc.codigoConsultaPagoConceptoPk <> 0";
        return $dql;
    }
        
}