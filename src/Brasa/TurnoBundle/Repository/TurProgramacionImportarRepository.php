<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurProgramacionImportarRepository extends EntityRepository {

    public function listaDql() {
        $dql = "SELECT pi FROM BrasaTurnoBundle:TurProgramacionImportar pi WHERE pi.codigoProgramacionImportarPk <> 0 ";
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

    public function nuevoProgramacion($codigo, $arProgramacion) {
        $em = $this->getEntityManager();
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();
        $arProgramacionImportar = new \Brasa\TurnoBundle\Entity\TurProgramacionImportar();
        $arProgramacionImportar = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->find($codigo);
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionImportar->getCodigoPedidoDetalleFk());
        $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arProgramacionImportar->getCodigoPuestoFk());
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arProgramacionImportar->getCodigoRecursoFk());

        //Empezar a guardar los valores para la programacion.
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
        $arProgramacionDetalle->setRecursoRel($arRecurso);
        $arProgramacionDetalle->setPuestoRel($arPuesto);
        $arProgramacionDetalle->setAnio($arProgramacionImportar->getAnio());
        $arProgramacionDetalle->setMes($arProgramacionImportar->getMes());
        $arProgramacionDetalle->setDia1($arProgramacionImportar->getDia1());
        $arProgramacionDetalle->setDia2($arProgramacionImportar->getDia2());
        $arProgramacionDetalle->setDia3($arProgramacionImportar->getDia3());
        $arProgramacionDetalle->setDia4($arProgramacionImportar->getDia4());
        $arProgramacionDetalle->setDia5($arProgramacionImportar->getDia5());
        $arProgramacionDetalle->setDia6($arProgramacionImportar->getDia6());
        $arProgramacionDetalle->setDia7($arProgramacionImportar->getDia7());
        $arProgramacionDetalle->setDia8($arProgramacionImportar->getDia8());
        $arProgramacionDetalle->setDia9($arProgramacionImportar->getDia9());
        $arProgramacionDetalle->setDia10($arProgramacionImportar->getDia10());
        $arProgramacionDetalle->setDia11($arProgramacionImportar->getDia11());
        $arProgramacionDetalle->setDia12($arProgramacionImportar->getDia12());
        $arProgramacionDetalle->setDia13($arProgramacionImportar->getDia13());
        $arProgramacionDetalle->setDia14($arProgramacionImportar->getDia14());
        $arProgramacionDetalle->setDia15($arProgramacionImportar->getDia15());
        $arProgramacionDetalle->setDia16($arProgramacionImportar->getDia16());
        $arProgramacionDetalle->setDia17($arProgramacionImportar->getDia17());
        $arProgramacionDetalle->setDia18($arProgramacionImportar->getDia18());
        $arProgramacionDetalle->setDia19($arProgramacionImportar->getDia19());
        $arProgramacionDetalle->setDia20($arProgramacionImportar->getDia20());
        $arProgramacionDetalle->setDia21($arProgramacionImportar->getDia21());
        $arProgramacionDetalle->setDia22($arProgramacionImportar->getDia22());
        $arProgramacionDetalle->setDia23($arProgramacionImportar->getDia23());
        $arProgramacionDetalle->setDia24($arProgramacionImportar->getDia24());
        $arProgramacionDetalle->setDia25($arProgramacionImportar->getDia25());
        $arProgramacionDetalle->setDia26($arProgramacionImportar->getDia26());
        $arProgramacionDetalle->setDia27($arProgramacionImportar->getDia27());
        $arProgramacionDetalle->setDia28($arProgramacionImportar->getDia28());
        $arProgramacionDetalle->setDia29($arProgramacionImportar->getDia29());
        $arProgramacionDetalle->setDia30($arProgramacionImportar->getDia30());
        $arProgramacionDetalle->setDia31($arProgramacionImportar->getDia31());
        $arProgramacionDetalle->setHoras($arProgramacionImportar->getHoras());
        $arProgramacionDetalle->setHorasDiurnas($arProgramacionImportar->getHorasDiurnas());
        $arProgramacionDetalle->setHorasNocturnas($arProgramacionImportar->getHorasNocturnas());
        $em->persist($arProgramacionDetalle);
        //Cambiar el estado de la programacion de los importados
        $arProgramacionImportar->setEstadoProgramado(1);
        $em->persist($arProgramacionImportar);
        $arPedidoDetalle->setEstadoProgramado(1);
        $em->persist($arProgramacionImportar);
        $em->flush();
    }

}
