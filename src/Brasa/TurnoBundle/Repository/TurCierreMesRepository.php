<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurCierreMesRepository extends EntityRepository {

    public function listaDql() {
        $dql   = "SELECT cm FROM BrasaTurnoBundle:TurCierreMes cm";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {                                
                $arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigo);                    
                if($arCierreMes->getEstadoGenerado() == 0) {
                    $em->remove($arCierreMes);                    
                }                                     
            }
            $em->flush();
        }
    }     

}