<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurControlPuestoRepository extends EntityRepository {

    public function listaDql($codigoCentroOperacion) {
        $em = $this->getEntityManager();
        $dql = "SELECT cp FROM BrasaTurnoBundle:TurControlPuesto cp WHERE cp.codigoControlPuestoPk <> 0 ";
        if ($codigoCentroOperacion != "") {
            $dql .= " AND cp.codigoCentroOperacionFk = " . $codigoCentroOperacion;
        }
        $dql .= " ORDER BY cp.fecha";
        return $dql;
    }

    public function listaBuscarDql($codigoPuesto = '', $strNombre = "", $strNombreCliente = "") {
        $em = $this->getEntityManager();
        $dql = "SELECT p FROM BrasaTurnoBundle:TurPuesto p JOIN p.clienteRel c WHERE p.codigoPuestoPk <> 0 ";
        if ($strNombre != "") {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if ($codigoPuesto != "") {
            $dql .= " AND p.codigoPuestoPk = " . $codigoPuesto;
        }
        if ($strNombreCliente != "") {
            $dql .= " AND c.nombreCorto LIKE '%" . $strNombreCliente . "%'";
        }
        $dql .= " ORDER BY p.nombre";
        return $dql;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }

}
