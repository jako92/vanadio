<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleConceptoRepository extends EntityRepository {    
    
    public function listaDql($codigoPedido = "") {
        $dql   = "SELECT sdc FROM BrasaTurnoBundle:TurPedidoDetalleConcepto sdc WHERE sdc.codigoPedidoDetalleConceptoPk <> 0 ";
        
        if($codigoPedido != '') {
            $dql .= "AND sdc.codigoPedidoFk = " . $codigoPedido . " ";  
        }               
        return $dql;
    }        
    
    public function listaClienteDql($codigoCliente = "") {
        $dql   = "SELECT pdc FROM BrasaTurnoBundle:TurPedidoDetalleConcepto pdc JOIN pdc.pedidoRel p WHERE pdc.estadoFacturado = 0 ";        
        if($codigoCliente != '') {
            $dql .= "AND p.codigoClienteFk = " . $codigoCliente . " ";  
        }               
        return $dql;
    } 
    
    public function listaConsultaPendienteFacturarDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalleConcepto pd JOIN pd.pedidoRel p WHERE pd.total > 0 ";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        } 
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND p.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND p.estadoProgramado = 0";
        }  
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND p.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND p.estadoAutorizado = 0";
        }         
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND pd.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND pd.estadoFacturado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND p.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND p.estadoAnulado = 0";
        }
        if($strFechaDesde != "") {
            $dql .= " AND p.fechaProgramacion >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND p.fechaProgramacion <= '" . $strFechaHasta . "'";
        }      
        //$dql .= " ORDER BY p.codigoClienteFk, pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";        
        return $dql;
    }         
    
    public function eliminar($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                                
                $ar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->find($codigo);  
                $em->remove($ar);                  
            }                                         
            $em->flush();       
        }
        
    }        
    
    public function numeroRegistros($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(fd.codigoFacturaDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurFacturaDetalle fd "
                . "WHERE fd.codigoFacturaFk = " . $codigo;
        $query = $em->createQuery($dql);
        $arrFacturaDetalles = $query->getSingleResult(); 
        if($arrFacturaDetalles) {
            $intNumeroRegistros = $arrFacturaDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }          
    
}