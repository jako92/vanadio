<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDevolucionConceptoRepository extends EntityRepository {
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pdc FROM BrasaTurnoBundle:TurPedidoDevolucionConcepto pdc WHERE pdc.codigoPedidoDevolucionConceptoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND pdc.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND pdc.codigoPedidoDevolucionConceptoPk = " . $strCodigo;
        }        
        $dql .= " ORDER BY pdc.nombre";
        return $dql;
    }                
}
