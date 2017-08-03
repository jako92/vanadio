<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionImportarRepository extends EntityRepository {

    public function listaDql() {
        $dql = "SELECT pi FROM BrasaTurnoBundle:TurProgramacionImportar pi WHERE pi.codigoProgramacionImportarPk <> 0 AND pi.estadoProgramado = 0";
        $dql .= " ORDER BY pi.codigoPuestoFk, pi.codigoPedidoDetalleFk";
        return $dql;
    }

    public function listaPendienteProgramar($codigoCliente) {
        $dql = "SELECT pi FROM BrasaTurnoBundle:TurProgramacionImportar pi WHERE pi.codigoProgramacionImportarPk <> 0 AND pi.estadoProgramado = 0";
        $dql .= " AND pi.codigoClienteFk = " . $codigoCliente . "";
        $dql .= " ORDER BY pi.codigoPuestoFk, pi.codigoPedidoDetalleFk";
        return $dql;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        $strResultado = "";
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoProgramacionImportar) {
                $arProgramacionImportar = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->find($codigoProgramacionImportar);
                if ($arProgramacionImportar->getEstadoProgramado() == 0) {
                    $em->remove($arProgramacionImportar);
                } else {
                    $strResultado .= "El recurso " . $arProgramacionImportar->getNombreRecurso() . " ya tiene un turno programado en programacion de turnos";
                }
            }
            $em->flush();
        }
        return $strResultado;
    }

}
