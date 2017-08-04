<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuPruebaRepository extends EntityRepository {   
    
    public function listaDQL($strIdentificacion = "",$codigoPruebaTipo = "") {
        $dql   = 
          "SELECT p "
        . "FROM BrasaRecursoHumanoBundle:RhuPrueba p "
        . "WHERE p.codigoPruebaPk <> 0";
        if($strIdentificacion != "" ) {
            $dql .= " AND p.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($codigoPruebaTipo != "" ) {
            $dql .= " AND p.codigoPruebaTipoFk = " . $codigoPruebaTipo;
        }
        $dql .= " ORDER BY p.codigoPruebaPk DESC";
        return $dql;
    }
    
    public function detalleCobro($codigoCobro) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPrueba p WHERE p.codigoCobroFk = " . $codigoCobro;
        return $dql;
    }
    
    public function pendienteCobrarCobro($codigoCliente) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuPrueba p WHERE p.estadoCobrado = 0 "
                . " AND p.codigoClienteFk = " . $codigoCliente. "";
        return $dql;
    }
}