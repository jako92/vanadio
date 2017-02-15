<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuCostoRepository extends EntityRepository {    
    
    public function listaDql($anio = "", $mes = "", $numeroIdentificacion = "") {        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuCosto c JOIN c.empleadoRel e WHERE c.codigoCostoPk <> 0";
        if($anio != "" ) {
            $dql .= " AND c.anio = " . $anio;
        }
        if($mes != "" ) {
            $dql .= " AND c.mes = " . $mes;
        }                
        if($numeroIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion = '" . $numeroIdentificacion . "'";
        }        
        $dql .= " ORDER BY c.anio, c.mes DESC";
        return $dql;
    }      
    
    public function generar($codigoCierreMes) {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arCierreMes = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreMes();                
        $arCierreMes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->find($codigoCierreMes);        
        $strDesde = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/01";
        $strUltimoDia = date("d",(mktime(0,0,0,$arCierreMes->getMes()+1,1,$arCierreMes->getAnio())-1));
        $strHasta = $arCierreMes->getAnio() . "/" . $arCierreMes->getMes() . "/" . $strUltimoDia;
        $strSql = "SELECT codigo_empleado_fk, 
            COUNT(codigo_pago_pk) as numeroPagos,
            SUM(vr_devengado) as vrDevengado                                        
            FROM rhu_pago                                                            
            WHERE rhu_pago.codigo_pago_tipo_fk = 1 AND (rhu_pago.fecha_desde_pago >='$strDesde' AND rhu_pago.fecha_desde_pago <='$strHasta')
            GROUP BY codigo_empleado_fk"; 
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $arrPagos = $statement->fetchAll();         
        foreach ($arrPagos as $arrPago) {
            $devengado = $arrPago['vrDevengado'];
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arrPago['codigo_empleado_fk']);
            $arCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCosto();
            $arCosto->setCodigoCierreMesFk($arCierreMes->getCodigoCierreMesPk());
            $arCosto->setEmpleadoRel($arEmpleado);
            $arCosto->setVrNomina($devengado);
            $arCosto->setAnio($arCierreMes->getAnio());
            $arCosto->setMes($arCierreMes->getMes());   
            $seguridadSocial = 0;
            $prestaciones = 0;
            $dql   = "SELECT SUM(p.vrCesantias+p.vrInteresesCesantias+p.vrPrimas+p.vrVacaciones+p.vrIndemnizacion) as prestaciones, "
                    . "SUM(p.vrPension+p.vrSalud+p.vrCaja+p.vrRiesgos+p.vrSena+p.vrIcbf) as seguridadSocial "
                    . "FROM BrasaRecursoHumanoBundle:RhuProvision p "
                    . "WHERE p.codigoEmpleadoFk = " . $arrPago['codigo_empleado_fk'] . " "
                    . "AND p.anio =  " . $arCierreMes->getAnio() . " "
                    . "AND p.mes = " . $arCierreMes->getMes() . " GROUP BY p.codigoEmpleadoFk";
            $query = $em->createQuery($dql);
            $arrProvision = $query->getSingleResult();
            if($arrProvision) {
                $seguridadSocial = $arrProvision['seguridadSocial'];
                $prestaciones = $arrProvision['prestaciones'];                
            }
            $arCosto->setVrSeguridadSocial($seguridadSocial);
            $arCosto->setVrPrestacion($prestaciones);
            $total = $devengado + $seguridadSocial + $prestaciones;
            $arCosto->setVrTotal($total);
            $em->persist($arCosto);            
        }
        $em->flush();        
    }
    
}