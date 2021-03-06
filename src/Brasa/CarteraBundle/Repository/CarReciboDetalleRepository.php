<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarReciboDetalleRepository extends EntityRepository {
    
    public function detalleConsultaDql($numero = "", $codigoCliente = "", $codigoCuentaCobrarTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT rd FROM BrasaCarteraBundle:CarReciboDetalle rd JOIN rd.reciboRel r  WHERE rd.codigoReciboDetallePk <> 0 ";
        if($numero != "") {
            $dql .= " AND rd.numeroFactura = " . $numero;  
        }
        if($codigoCliente != "") {
            $dql .= " AND r.codigoClienteFk = " . $codigoCliente;  
        }
        if($codigoCuentaCobrarTipo != "") {
            $dql .= " AND rd.codigoCuentaCobrarTipoFk = " . $codigoCuentaCobrarTipo;  
        }
        if ($strFechaDesde != ""){
            $dql .= " AND r.fecha >='" . $strFechaDesde. "'";
        }
        if($strFechaHasta != "") {
            $dql .= " AND r.fecha <='" . $strFechaHasta . "'";
        }        
        return $dql;
    } 
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arReciboDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->find($codigo);                
                $em->remove($arReciboDetalle);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(rd.codigoReciboDetallePk) as numeroRegistros FROM BrasaCarteraBundle:CarReciboDetalle rd "
                . "WHERE rd.codigoReciboFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrReciboDetalles = $query->getSingleResult(); 
        if($arrReciboDetalles) {
            $intNumeroRegistros = $arrReciboDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }  

    public function liquidar($codigoRecibo) {        
        $em = $this->getEntityManager();        
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();        
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo); 
        $intCantidad = 0;        
        $pago = 0;
        $pagoTotal = 0;
        $floContribucion = 0;
        $floEstampilla = 0;
        $floDescuento = 0;
        $floAjustePeso = 0;
        $floRetencionIca = 0;
        $floRetencionIva = 0;
        $floRetencionFuente = 0;
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigoRecibo);         
        $arRecibosDetalle = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();        
        $arRecibosDetalle = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));         
        foreach ($arRecibosDetalle as $arReciboDetalle) {
            $floContribucion += $arReciboDetalle->getVrContribucion();
            $floEstampilla += $arReciboDetalle->getVrEstampilla();
            $floDescuento += $arReciboDetalle->getVrDescuento();
            $floAjustePeso += $arReciboDetalle->getVrAjustePeso();
            $floRetencionIca += $arReciboDetalle->getVrRetencionIca();
            $floRetencionIva += $arReciboDetalle->getVrRetencionIva();
            $floRetencionFuente += $arReciboDetalle->getVrRetencionFuente();
            $pago += $arReciboDetalle->getVrPago() * $arReciboDetalle->getOperacion();       
            $pagoTotal += $arReciboDetalle->getVrPagoAfectar() * $arReciboDetalle->getOperacion();
        }                 
        $arRecibo->setVrPago($pago);
        $arRecibo->setVrPagoTotal($pagoTotal);
        $arRecibo->setVrTotalContribucion($floContribucion);
        $arRecibo->setVrTotalEstampilla($floEstampilla);
        $arRecibo->setVrTotalDescuento($floDescuento);
        $arRecibo->setVrTotalAjustePeso($floAjustePeso);
        $arRecibo->setVrTotalRetencionIca($floRetencionIca);
        $arRecibo->setVrTotalRetencionIva($floRetencionIva);
        $arRecibo->setVrTotalRetencionFuente($floRetencionFuente);
        $em->persist($arRecibo);
        $em->flush();
        return true;
    }    
    
    public function vrPago($codigoCuentaCobrar) {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(rd.vrPagoAfectar as valor FROM BrasaCarteraBundle:CarReciboDetalle rd JOIN rd.reciboRel r "
                . "WHERE rd.codigoCuentaCobrarFk = " . $codigoCuentaCobrar . " AND r.estadoAutorizado = 1";
        $query = $em->createQuery($dql);
        $vrTotalPago = $query->getSingleScalarResult();
        if (!$vrTotalPago) {
            $vrTotalPago = 0;
        }
        return $vrTotalPago;
    } 
    
    public function vrPagoAplicacion($codigoCuentaCobrar) {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(rd.vrPagoAfectar as valor FROM BrasaCarteraBundle:CarReciboDetalle rd JOIN rd.reciboRel r "
                . "WHERE rd.codigoCuentaCobrarAplicacionFk = " . $codigoCuentaCobrar . " AND r.estadoAutorizado = 1";
        $query = $em->createQuery($dql);
        $vrTotalPago = $query->getSingleScalarResult();
        if (!$vrTotalPago) {
            $vrTotalPago = 0;
        }
        return $vrTotalPago;
    }    

    public function vrPagoRecibo($codigoCuentaCobrar, $codigoRecibo) {
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(rd.vrPagoAfectar as valor FROM BrasaCarteraBundle:CarReciboDetalle rd "
                . "WHERE rd.codigoCuentaCobrarFk = " . $codigoCuentaCobrar . " AND rd.codigoReciboFk = " . $codigoRecibo;
        $query = $em->createQuery($dql);
        $vrTotalPago = $query->getSingleScalarResult();
        if (!$vrTotalPago) {
            $vrTotalPago = 0;
        }
        return $vrTotalPago;
    }    
    
}