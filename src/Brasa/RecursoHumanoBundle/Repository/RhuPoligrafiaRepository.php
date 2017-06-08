<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuPoligrafiaRepository extends EntityRepository {   
    
    public function listaDQL($strIdentificacion = "") {
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPoligrafia p WHERE p.codigoPoligrafiaPK <> 0";
        if($strIdentificacion != "" ) {
            $dql .= " AND p.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        $dql .= " ORDER BY p.codigoPoligrafiaPK DESC";
        return $dql;
    }
    
    public function detalleCobro($codigoCobro) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPoligrafia p WHERE p.codigoCobroFk = " . $codigoCobro;
        return $dql;
    }
    
    public function pendienteCobrarCobro($codigoCliente) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPoligrafia p WHERE p.estadoCobrado = 0 "
                . " AND p.codigoClienteFk = " . $codigoCliente. "";
        return $dql;
    }
}