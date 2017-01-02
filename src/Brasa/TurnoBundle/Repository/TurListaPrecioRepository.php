<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurListaPrecioRepository extends EntityRepository {    
    public function ListaDql($strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT lp FROM BrasaTurnoBundle:TurListaPrecio lp WHERE lp.codigoListaPrecioPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND lp.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND lp.codigoListaPrecioPk = " . $strCodigo;
        }        
        $dql .= " ORDER BY lp.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(coulp($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurListaPrecio')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }     
}