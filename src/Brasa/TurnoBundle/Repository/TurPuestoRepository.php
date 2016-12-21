<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPuestoRepository extends EntityRepository {    
    public function listaDql($codigoPuesto = '', $codigoCliente = '', $strNombre = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT p FROM BrasaTurnoBundle:TurPuesto p WHERE p.codigoPuestoPk <> 0 ";
        if($codigoCliente != "" ) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }
        if($strNombre != "" ) {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if($codigoPuesto != "" ) {
            $dql .= " AND p.codigoPuestoPk = " . $codigoPuesto;
        }
        $dql .= " ORDER BY p.nombre";
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
    
    public function liquidar($codigoPuesto) {        
        $em = $this->getEntityManager();        
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();        
        $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto); 
        $costo = 0;
        $arPuestoDotaciones = new \Brasa\TurnoBundle\Entity\TurPuestoDotacion();        
        $arPuestoDotaciones = $em->getRepository('BrasaTurnoBundle:TurPuestoDotacion')->findBy(array('codigoPuestoFk' => $codigoPuesto));         
        foreach ($arPuestoDotaciones as $arPuestoDotacion) {
            $costo += $arPuestoDotacion->getTotal();
        }
        $arPuesto->setCostoDotacion($costo);
        $em->persist($arPuesto);
        $em->flush();
        return true;
    }        
    
}