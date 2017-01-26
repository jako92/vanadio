<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurControlPuestoDetalleRepository extends EntityRepository {    
    
    public function listaDql($codigoControlPuesto = '') {
        $em = $this->getEntityManager();
        $dql   = "SELECT cpd FROM BrasaTurnoBundle:TurControlPuestoDetalle cpd WHERE cpd.codigoControlPuestoFk = " . $codigoControlPuesto;

        //$dql .= " ORDER BY p.nombre";
        return $dql;
    }            

    public function listaBuscarDql($codigoPuesto = '', $strNombre = "", $strNombreCliente = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPuesto p JOIN p.clienteRel c WHERE p.codigoPuestoPk <> 0 ";
        if($strNombre != "" ) {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if($codigoPuesto != "" ) {
            $dql .= " AND p.codigoPuestoPk = " . $codigoPuesto;
        }
        if($strNombreCliente != "" ) {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombreCliente . "%'";
        }        
        $dql .= " ORDER BY p.nombre";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }           
    
}