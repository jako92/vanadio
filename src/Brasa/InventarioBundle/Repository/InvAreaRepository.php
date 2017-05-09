<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InvBodegaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvAreaRepository extends EntityRepository
{
    public function listaDql($strNombre = "", $strCodigo = "") {
        $dql   = "SELECT b FROM BrasaInventarioBundle:InvBodega b WHERE b.codigoBodegaPk is not null";        
        if($strCodigo != "") {
            $dql .= " AND b.codigoBodegaPk = '" . $strCodigo . "'";
        }
        if($strNombre != "") {
            $dql .= " AND b.nombre like '%" . $strNombre. "%'";
        }
        $dql .= " ORDER BY b.nombre ASC";
        return $dql;
    }     
}