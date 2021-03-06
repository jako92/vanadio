<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuIncapacidadDiagnosticoRepository extends EntityRepository {
    public function listaDql($strNombre = "", $codigo = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT id FROM BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico id WHERE id.codigoIncapacidadDiagnosticoPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND id.nombre LIKE '%" . $strNombre . "%'";
        }
        if($codigo != "" ) {
            $dql .= " AND id.codigo LIKE '%" . $codigo . "%'";
        }        
        $dql .= " ORDER BY id.nombre";
        return $dql;
    }        
}