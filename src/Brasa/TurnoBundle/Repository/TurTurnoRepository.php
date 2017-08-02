<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurTurnoRepository extends EntityRepository {
    public function listaDQL($codigoTurno = "", $strNombre = "", $strHoraDesde = "", $strHoraHasta = "") {
        $dql   = "SELECT t FROM BrasaTurnoBundle:TurTurno t WHERE t.codigoTurnoPk <> ''";
        if ($codigoTurno != "") {
            $dql .= " AND t.codigoTurnoPk = '" . $codigoTurno."'";
        }
        if ($strNombre != "") {
            $dql .= " AND t.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strHoraDesde != "") {
            $dql .= " AND t.horaDesde BETWEEN '" . $strHoraDesde. "' AND '" . $strHoraDesde. "'";
        }
        if($strHoraHasta != "") {
            $dql .= " AND t.horaHasta BETWEEN '" . $strHoraHasta. "' AND '" . $strHoraHasta. "'";
        }    
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
    /**
     * Esta función permite obtener la iformación de uno o varios turnos.
     * @param int $id
     * @return array
     */
    public function getInformacionTurnos($id = null){
        $em = $this->getEntityManager();
        if($id !== null){
            $arTurnos = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($id);
            return array(
                    'id' => $arTurnos->getCodigoTurnoPk(),
                    'desde' => $arTurnos->getHoraDesde()->format("H:i:s"),
                    'hasta' => $arTurnos->getHoraHasta()->format("H:i:s"),
                );
        }
        $arTurnos = $em->getRepository('BrasaTurnoBundle:TurTurno')->findAll();
        $info = [];
        foreach($arTurnos AS $turno){
            $info[$turno->getCodigoTurnoPk()] = array(
                'desde' => $turno->getHoraDesde()->format("H:i:s"),
                'hasta' => $turno->getHoraHasta()->format("H:i:s"),
            );
        }
        return $info;
    }
}