<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPuestoDotacionRepository extends EntityRepository {    
    public function ListaDql($codigoCliente, $strNombre = "", $strCodigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPuesto p WHERE p.codigoClienteFk = " . $codigoCliente;
        if($strNombre != "" ) {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND p.codigoPuestoPk LIKE '%" . $strCodigo . "%'";
        }
        $dql .= " ORDER BY p.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurPuestoDotacion')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
    public function ListaDqlConsulta($codigoCliente = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPuestoDotacion pd WHERE pd.codigoPuestoDotacionPk <> 0 ";
        if($codigoCliente != "") {
            $dql .= " AND pd.codigoClienteFk = '" . $codigoCliente . "'";
        }
        $dql .= " ORDER BY pd.codigoPuestoDotacionPk DESC";
        return $dql;
    }
}