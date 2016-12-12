<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuSsoPeriodoRepository extends EntityRepository {
    
    public function listaDQL() {        
            $em = $this->getEntityManager();
            $dql   = "SELECT p FROM BrasaRecursoHumanoBundle:RhuSsoPeriodo p WHERE p.codigoPeriodoPk <> 0";
            return $dql;
        } 
    
    public function generar($codigoPeriodo) {
        $em = $this->getEntityManager();
        $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
        $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);        
        if($arPeriodo->getEstadoGenerado() == 0) {
            //Genera los detalles del periodo
            $arSucursales = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal();
            $arSucursales = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoSucursal')->findAll();
            foreach ($arSucursales as $arSucursal) {
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle->setSsoPeriodoRel($arPeriodo);
                $arPeriodoDetalle->setSsoSucursalRel($arSucursal);
                $arPeriodoDetalle->setDetalle($arSucursal->getNombre());
                $em->persist($arPeriodoDetalle);            
            }
            $em->flush();
            //Genera los empleados del periodo
            $i = 1;
            $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->contratosPeriodo($arPeriodo->getFechaDesde()->format('Y-m-d'), $arPeriodo->getFechaHasta()->format('Y-m-d'));
            foreach ($arContratos as $arContrato) {
                $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
                $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->findOneBy(array('codigoSucursalFk' => $arContrato->getCentroCostoRel()->getCodigoSucursalFk(), 'codigoPeriodoFk' => $codigoPeriodo));                
                $arPeriodoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado();
                $arPeriodoEmpleado->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arPeriodoEmpleado->setSsoPeriodoRel($arPeriodo);
                $arPeriodoEmpleado->setSsoSucursalRel($arContrato->getCentroCostoRel()->getSucursalRel());
                $arPeriodoEmpleado->setContratoRel($arContrato);               
                $arPeriodoEmpleado->setSsoPeriodoDetalleRel($arPeriodoDetalle);
                $em->persist($arPeriodoEmpleado);
            }

            $arPeriodo->setEstadoGenerado(1);
            $em->persist($arPeriodo);
            $em->flush();            
        }                
        return true;
    }
    
    public function desgenerar($codigoPeriodo) {
        $em = $this->getEntityManager();
        $arPeriodoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle();
        $arPeriodoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodoDetalle')->findOneBy(array('codigoPeriodoFk' => $codigoPeriodo, 'estadoCerrado' => 1));
        if ($arPeriodoDetalle == null){
            $strSql = "DELETE FROM rhu_sso_aporte WHERE codigo_periodo_fk = " . $codigoPeriodo;
            $em->getConnection()->executeQuery($strSql);
            $strSql = "DELETE FROM rhu_sso_periodo_empleado WHERE codigo_periodo_fk = " . $codigoPeriodo;
            $em->getConnection()->executeQuery($strSql); 
            $strSql = "DELETE FROM rhu_sso_periodo_detalle WHERE codigo_periodo_fk = " . $codigoPeriodo;
            $em->getConnection()->executeQuery($strSql);
            $arPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo();
            $arPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoPeriodo')->find($codigoPeriodo);
            $arPeriodo->setEstadoGenerado(0);
            $em->persist($arPeriodo);
            $em->flush();
            return true;
        } else {
            return false;
        }     
    }  
    
}