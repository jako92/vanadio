<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurFacturaServicioRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT nt FROM BrasaTurnoBundle:TurFacturaServicio nt WHERE nt.codigoFacturaServicioPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND nt.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND nt.codigoFacturaServicioPk = " . $strCodigo;
        }        
        $dql .= " ORDER BY nt.nombre";
        return $dql;
    }                   
    
}