<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuCobroRepository extends EntityRepository {    
    
    public function listaDql() {                
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuCobro c WHERE c.codigoCobroPk <> 0";       
        $dql .= " ORDER BY c.codigoCobroPk DESC";
        return $dql;
    }                        
    
}