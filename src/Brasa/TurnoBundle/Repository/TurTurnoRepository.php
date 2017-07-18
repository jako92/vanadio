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
}