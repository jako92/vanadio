<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoTipoRepository extends EntityRepository {

    public function ListaDql($strNombre = "") {
        $em = $this->getEntityManager();
        $dql = "SELECT pt FROM BrasaTurnoBundle:TurPedidoTipo pt WHERE pt.codigoPedidoTipoPk <> 0";
        if ($strNombre != "") {
            $dql .= " AND pt.nombre LIKE '%" . $strNombre . "%'";
        }
        $dql .= " ORDER BY pt.nombre";
        return $dql;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }

}
