<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarAnticipoRepository extends EntityRepository
{
   public function listaDql($numero, $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoAnulado = "", $boolEstadoImpreso = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT a FROM BrasaCarteraBundle:CarAnticipo a WHERE a.codigoAnticipoPk <> 0";
        if($numero != "") {
            $dql .= " AND a.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND a.codigoClienteFk = " . $codigoCliente;  
        }    
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND a.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND a.estadoAutorizado = 0";
        }
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND a.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND a.estadoAnulado = 0";
        }
        if($boolEstadoImpreso == 1 ) {
            $dql .= " AND a.estadoImpreso = 1";
        }
        if($boolEstadoImpreso == "0") {
            $dql .= " AND a.estadoImpreso = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND a.fecha >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND a.fecha <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY a.fecha DESC";
        return $dql;
    }
    
   public function listaConsultaDql($numero = "", $codigoCliente = "", $codigoAnticipoTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT a FROM BrasaCarteraBundle:CarAnticipo a WHERE a.codigoAnticipoPk <> 0 ";
        if($numero != "") {
            $dql .= " AND a.numero = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND a.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoAnticipoTipo != "") {
            $dql .= " AND a.codigoAnticipoTipoFk = " . $codigoAnticipoTipo;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND a.fecha >='" . $strFechaDesde . "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND a.fecha <='" . $strFechaHasta . "'";
        }        
        return $dql;
    }
    
   public function anticipos($codigCliente = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT a FROM BrasaCarteraBundle:CarAnticipo a where a.codigoAnticipoPk <> 0 and a.vrAnticipo > 0 and a.codigoClienteFk = " . $codigCliente . "";        
        $query = $em->createQuery($dql);        
        $arAnticipos = $query->getResult();        
        
        return $arAnticipos;
        
    }
    
   public function imprimir($codigo) {
        $em = $this->getEntityManager();  
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();        
        $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigo);        
        if($arAnticipo->getEstadoAutorizado() == 1) {
           if($arAnticipo->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->consecutivo(1);
                $arAnticipo->setNumero($intNumero);
                $arAnticipo->setEstadoImpreso(1);
                $em->persist($arAnticipo);
                $em->flush();
            } 
        } else {
            $strResultado = "Debe autorizar la cotizacion para imprimirla";
        }
        return $strResultado;
    }    
    
   public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                
                if($em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->numeroRegistros($codigo) <= 0) {
                    $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigo);                    
                    if($arAnticipo->getEstadoAutorizado() == 0 && $arAnticipo->getEstadoImpresoAnticipado() == 0) {
                        $em->remove($arAnticipo);                    
                    }                    
                }               
            }
            $em->flush();
        }
    }
    
}