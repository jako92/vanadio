<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoBancoRepository extends EntityRepository {        
        
    public function listaDQL($strFecha = "", $codigoPagoBancoTipo = "") {                
        $dql   = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.codigoPagoBancoPk <> 0";
        if($strFecha != "") {
            $dql .= " AND pb.fechaAplicacion = '" .$strFecha. "'";
        }
        if($codigoPagoBancoTipo != "") {
            $dql .= " AND pb.codigoPagoBancoTipoFk = " .$codigoPagoBancoTipo;
        }   
        
        $dql .= " ORDER BY pb.codigoPagoBancoPk DESC";
        return $dql;
    }                            
    
    public function liquidar($codigoPagoBanco) {
        $em = $this->getEntityManager();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arPagoBancoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigoPagoBanco));
        $douTotal = 0;
        $intNumeroRegistros = 0;
        foreach ($arPagoBancoDetalles AS $arPagoBancoDetalle) {
            $douTotal += $arPagoBancoDetalle->getVrPago();
            $intNumeroRegistros++;
        }
        $arPagoBanco->setVrTotalPago($douTotal);
        $arPagoBanco->setNumeroRegistros($intNumeroRegistros);
        $em->persist($arPagoBanco);
        $em->flush();
    }     
 
    public function pendientesContabilizarDql() {        
        $dql   = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.estadoContabilizado = 0 AND pb.estadoAutorizado = 1 AND pb.estadoGenerado = 1 ";       
        $dql .= " ORDER BY pb.codigoPagoBancoPk DESC";
        return $dql;
    } 
    
    public function contabilizadosPagoBancoDql($intPagoDesde = 0, $intPagoHasta = 0, $strDesde = "",$strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.estadoContabilizado = 1";
        if($intPagoDesde != "" || $intPagoDesde != 0) {
            $dql .= " AND pb.codigoPagoFk >= " . $intPagoDesde;
        }
        if($intPagoHasta != "" || $intPagoHasta != 0) {
            $dql .= " AND pb.codigoPagoFk <= " . $intPagoHasta;
        }   
        if($strDesde != "" || $strDesde != 0){
            $dql .= " AND pb.fecha >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if($strHasta != "" || $strHasta != 0) {
            $dql .= " AND pb.fecha <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }        
        
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }     
    
}