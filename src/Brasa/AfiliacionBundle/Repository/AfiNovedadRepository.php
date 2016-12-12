<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiNovedadRepository extends EntityRepository {  
    
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT n FROM BrasaAfiliacionBundle:AfiNovedad n WHERE n.codigoNovedadPk <> 0";
        $dql .= " ORDER BY n.codigoNovedadPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }
    
    public function diasLicencia($fechaDesde, $fechaHasta, $codigoEmpleado, $tipo) {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT novedad FROM BrasaAfiliacionBundle:AfiNovedad novedad "
                . "WHERE (((novedad.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (novedad.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
                . "OR (novedad.fechaDesde >= '$strFechaDesde' AND novedad.fechaDesde <= '$strFechaHasta') "
                . "OR (novedad.fechaHasta >= '$strFechaHasta' AND novedad.fechaDesde <= '$strFechaDesde')) "
                . "AND novedad.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";

        /*if($tipo == 1) {
            $dql = $dql . "AND (novedad.codigoNovedadTipoFk = 3 OR novedad.codigoNovedadTipoFk = 4)";       
        } else {
            $dql = $dql . "AND novedad.codigoNovedadTipoFk <> 3 AND novedad.codigoNovedadTipoFk <> 4";       
        }*/
        $dql = $dql . "AND novedad.codigoNovedadTipoFk = " .$tipo. "";
        $objQuery = $em->createQuery($dql);  
        $arNovedades = $objQuery->getResult();         
        $intDiasNovedad = 0;
        foreach ($arNovedades as $arNovedad) {
            $intDiaInicio = 1;            
            $intDiaFin = 30;
            if($arNovedad->getFechaDesde() <  $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');                
            } else {
                $intDiaInicio = $arNovedad->getFechaDesde()->format('j');
            }
            if($arNovedad->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');               
            } else {
                $intDiaFin = $arNovedad->getFechaHasta()->format('j');                
            }            
            if($intDiaFin == 31) {
                $intDiaFin = 30;
            }            
            $intDiasNovedad += (($intDiaFin - $intDiaInicio)+1);
        }
        if($intDiasNovedad > 30) {
            $intDiasNovedad = 30;
        }
        return $intDiasNovedad;
    }                
    
}