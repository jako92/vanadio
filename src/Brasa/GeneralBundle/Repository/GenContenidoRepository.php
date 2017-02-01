<?php

namespace Brasa\GeneralBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenContenidoRepository extends EntityRepository {
    
    public function listaDql() {        
        $dql   = "SELECT c FROM BrasaGeneralBundle:GenContenido c WHERE c.codigoContenidoPk <> 0";        
        $dql .= " ORDER BY c.codigoContenidoPk";
        return $dql;
    }    
}