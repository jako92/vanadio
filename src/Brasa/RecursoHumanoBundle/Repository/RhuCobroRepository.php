<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuCobroRepository extends EntityRepository {    
    
    public function listaDql() {                
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuCobro c WHERE c.codigoCobroPk <> 0";       
        $dql .= " ORDER BY c.codigoCobroPk DESC";
        return $dql;
    }                        
    
    public function liquidar($codigoCobro) {        
        $em = $this->getEntityManager();       
        $numeroRegistros = 0;
        $basico = 0;
        $prestacional = 0;
        $noPrestacional = 0;
        $auxilioTransporte = 0;
        $pension = 0;
        $salud = 0;
        $riesgos = 0;
        $caja = 0;
        $sena = 0;
        $icbf = 0;
        $prestaciones = 0;
        $vacaciones = 0;
        $aporteParafiscales = 0;
        $administracion = 0;
        
        $arServiciosCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arServiciosCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->findBy(array('codigoCobroFk' => $codigoCobro));                
        foreach ($arServiciosCobrar as $arServicioCobrar) {
            $basico += $arServicioCobrar->getVrSalario();
            $prestacional += $arServicioCobrar->getVrPrestacional();
            $noPrestacional += $arServicioCobrar->getVrNoPrestacional();
            $auxilioTransporte += $arServicioCobrar->getVrAuxilioTransporte();
            $pension += $arServicioCobrar->getVrPension();
            $salud += $arServicioCobrar->getVrSalud();
            $riesgos += $arServicioCobrar->getVrRiesgos();
            $caja += $arServicioCobrar->getVrCaja();
            $sena += $arServicioCobrar->getVrSena();
            $icbf += $arServicioCobrar->getVrIcbf();
            $prestaciones += $arServicioCobrar->getVrPrestaciones();
            $vacaciones += $arServicioCobrar->getVrVacaciones();
            $aporteParafiscales += $arServicioCobrar->getVrAporteParafiscales();
            $administracion += $arServicioCobrar->getVrAdministracion(); 
            $numeroRegistros++;
        }        
        $arCobro = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($codigoCobro);                
        $arCobro->setVrBasico($basico);
        $arCobro->setVrPrestacional($prestacional);
        $arCobro->setVrNoPrestacional($noPrestacional);
        $arCobro->setVrAuxilioTransporte($auxilioTransporte);
        $arCobro->setVrPension($pension);
        $arCobro->setVrSalud($salud);
        $arCobro->setVrRiesgos($riesgos);
        $arCobro->setVrCaja($caja);
        $arCobro->setVrSena($sena);
        $arCobro->setVrIcbf($icbf);
        $arCobro->setVrPrestaciones($prestaciones);
        $arCobro->setVrVacaciones($vacaciones);
        $arCobro->setVrAporteParafiscales($aporteParafiscales);
        $arCobro->setVrAdministracion($administracion);
        $arCobro->setNumeroRegistros($numeroRegistros);
        $em->persist($arCobro);
        $em->flush();
        return true;
    }    
    
}