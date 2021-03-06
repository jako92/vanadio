<?php

namespace Brasa\RecursoHumanoBundle\Repository;

/**
 * RhuInduccionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuInduccionRepository extends \Doctrine\ORM\EntityRepository {

    public function listaDql($strIdentificacion = "", $fechaDesde = "", $fechaHasta = "") {
        $em = $this->getEntityManager();
        $dql = "SELECT i FROM BrasaRecursoHumanoBundle:RhuInduccion i JOIN i.empleadoRel e WHERE i.codigoInduccionPk <> 0";
        if ($strIdentificacion != "") {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if ($fechaDesde != '') {
            $dql .= " AND i.fechaDesde >= '$fechaDesde'";
        }
        if ($fechaHasta != '') {
            $dql .= " AND i.fechaDesde <= '$fechaHasta'";
        }
        $dql .= " ORDER BY i.codigoInduccionPk DESC";
        return $dql;
    }

    public function eliminarInduccion($arrSeleccionados) {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoInduccion) {
                $arInduccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuInduccion')->find($codigoInduccion);
                $em->remove($arInduccion);
            }
            $em->flush();
        }
    }

}
