<?php

namespace Brasa\InventarioBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * MovimientosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvMovimientoRepository extends EntityRepository { 
    
    public function listaDql($codigoDocumento = '', $strCodigo = '', $strNumero = '', $soporte = '') {
        $dql   = "SELECT m FROM BrasaInventarioBundle:InvMovimiento m WHERE m.codigoDocumentoFk = $codigoDocumento ";
        if($strNumero != "" ) {
            $dql .= " AND m.numero = " . $strNumero;
        }
        if($strCodigo != "" ) {
            $dql .= " AND m.codigoMovimientoPk = " . $strCodigo;
        }       
        if($soporte != "" ) {
            $dql .= " AND m.soporte = '" . $soporte . "'";
        }        
        $dql .= " ORDER BY m.codigoMovimientoPk DESC";
        return $dql;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        $respuesta = false;                        
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                if($em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->numeroRegistros($codigo) <= 0) {
                    $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigo);
                    if ($arMovimiento->getEstadoAutorizado() == 1){
                        $respuesta = true;                        
                    } else {
                        if($arMovimiento->getEstadoAutorizado() == 0 && $arMovimiento->getNumero() == 0) {
                            $em->remove($arMovimiento);
                            $respuesta = false;
                        }
                    }
                }
            }
            $em->flush();
        }
        return $respuesta;
    }
    
    public function autorizar($codigoMovimiento) {
        $em = $this->getEntityManager();
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        if($arMovimiento->getOperacionInventario() == 1) {
            $respuesta = $this->validarEntrada($codigoMovimiento);
        } 
        if($arMovimiento->getOperacionInventario() == -1) {
            $respuesta = $this->validarSalida($codigoMovimiento);
        }
        
        if($respuesta == "") {

            $dql   = "SELECT md.codigoBodegaFk, md.codigoItemFk, md.loteFk, md.fechaVencimiento, md.codigoBodegaFk, md.operacionInventario, md.cantidad, md.afectaInventario FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                    . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento;
            $query = $em->createQuery($dql);
            $arrMovimientoDetalles = $query->getResult();
            foreach ($arrMovimientoDetalles as $arrMovimientoDetalle) {
                if($arrMovimientoDetalle['afectaInventario']) {
                    $em->getRepository('BrasaInventarioBundle:InvLote')->afectar(1, $arrMovimientoDetalle['operacionInventario'], $arrMovimientoDetalle['codigoItemFk'], $arrMovimientoDetalle['loteFk'], $arrMovimientoDetalle['fechaVencimiento'], $arrMovimientoDetalle['codigoBodegaFk'], $arrMovimientoDetalle['cantidad']);                    
                }                
            }
            $arMovimiento->setEstadoAutorizado(1);
            $em->persist($arMovimiento);
        }
        return $respuesta;
    }
    
    public function desautorizar($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        if($respuesta == "") {
            $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
            $dql   = "SELECT md.codigoBodegaFk, md.codigoItemFk, md.loteFk, md.fechaVencimiento, md.codigoBodegaFk, md.operacionInventario, md.cantidad, md.afectaInventario FROM BrasaInventarioBundle:InvMovimientoDetalle md "
                    . "WHERE md.codigoMovimientoFk = " . $codigoMovimiento;
            $query = $em->createQuery($dql);
            $arrMovimientoDetalles = $query->getResult();
            foreach ($arrMovimientoDetalles as $arrMovimientoDetalle) {
                if($arrMovimientoDetalle['afectaInventario']) {
                    $em->getRepository('BrasaInventarioBundle:InvLote')->afectar(-1, $arrMovimientoDetalle['operacionInventario'], $arrMovimientoDetalle['codigoItemFk'], $arrMovimientoDetalle['loteFk'], $arrMovimientoDetalle['fechaVencimiento'], $arrMovimientoDetalle['codigoBodegaFk'], $arrMovimientoDetalle['cantidad']);                    
                }                
            }
            $arMovimiento->setEstadoAutorizado(0);
            $em->persist($arMovimiento);
        }
        return $respuesta;
    }    
    
    public function imprimir($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        if($respuesta == "") {
            $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();           
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
            if($arMovimiento->getNumero() <= 0) {
                if($arMovimiento->getDocumentoRel()->getAsignarConsecutivoImpresion()) {
                    $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
                    $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($arMovimiento->getCodigoDocumentoFk());                
                    $consecutivo = $arDocumento->getConsecutivo();
                    $arDocumento->setConsecutivo($consecutivo + 1);
                    $em->persist($arDocumento);
                    $arMovimiento->setNumero($consecutivo);                                
                }
                //$arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                //$arAsesor = $em->getRepository('BrasaGeneralBundle:GenAsesor')->find(1);
                //$arFormaPago = $em->getRepository('BrasaGeneralBundle:GenFormaPago')->find($arMovimiento->getTerceroRel()->getCodigoFormaPagoClienteFk());
                $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find(3);
                $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arMovimiento->getTerceroRel()->getNit()));
                if ($arClienteCartera == null){
                    $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                    $arClienteCartera->setFormaPagoRel($arMovimiento->getTerceroRel()->getFormaPagoRel());
                    //$arClienteCartera->setAsesorRel($arAsesor);
                    $arClienteCartera->setCiudadRel($arMovimiento->getTerceroRel()->getCiudadRel());
                    $arClienteCartera->setNit($arMovimiento->getTerceroRel()->getNit());
                    $arClienteCartera->setDigitoVerificacion($arMovimiento->getTerceroRel()->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arMovimiento->getTerceroRel()->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arMovimiento->getTerceroRel()->getPlazoPagoCliente());
                    $arClienteCartera->setDireccion($arMovimiento->getTerceroRel()->getDireccion());
                    $arClienteCartera->setTelefono($arMovimiento->getTerceroRel()->getTelefono());
                    $arClienteCartera->setCelular($arMovimiento->getTerceroRel()->getCelular());
                    $arClienteCartera->setFax($arMovimiento->getTerceroRel()->getFax());
                    $arClienteCartera->setEmail($arMovimiento->getTerceroRel()->getEmail());
                    //$arClienteCartera->setCodigoUsuario($arUsuario->getUserName());
                    $em->persist($arClienteCartera);                                    
                }                 
                $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();                 
                $arCuentaCobrar->setClienteRel($arClienteCartera);
                //$arCuentaCobrar->setAsesorRel($arAsesor);
                $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                $arCuentaCobrar->setFecha($arMovimiento->getFecha());
                $arCuentaCobrar->setFechaVence($arMovimiento->getFecha());
                $arCuentaCobrar->setNumeroDocumento($arMovimiento->getNumero());
                $arCuentaCobrar->setCodigoFactura($arMovimiento->getCodigoMovimientoPk());
                $arCuentaCobrar->setSoporte($arMovimiento->getSoporte());
                $arCuentaCobrar->setValorOriginal($arMovimiento->getVrNeto());
                $arCuentaCobrar->setSaldo($arMovimiento->getVrNeto());
                $arCuentaCobrar->setPlazo($arClienteCartera->getPlazoPago());                
                $arCuentaCobrar->setOperacion($arMovimiento->getOperacionComercial());
                $arCuentaCobrar->setSubtotal($arMovimiento->getVrSubtotal());
                $arCuentaCobrar->setAbono(0);
                $em->persist($arCuentaCobrar);
            }                                                   
            $arMovimiento->setEstadoImpreso(1);
            $em->persist($arMovimiento);
        }
        return $respuesta;
    }    
    
    public function validarEntrada($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        //Valida si tiene registros
        $validarNumeroRegistros = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->numeroRegistros($codigoMovimiento);
        if($validarNumeroRegistros <= 0) {
            $respuesta = "El movimiento no tiene registros";            
        }
        //Valida las cantidades
        $validarCantidad = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarCantidad($codigoMovimiento);
        if($validarCantidad > 0) {
            $respuesta = "Existen detalles con cantidad en cero";
        }  
        
        $validarLote = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarLote($codigoMovimiento);
        if($validarLote > 0) {
            $respuesta = "Existen detalles sin lote";
        }         
        $validarBodega = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarBodega($codigoMovimiento);
        if($validarBodega > 0) {
            $respuesta = "Existen detalles sin bodega o con codigo de bodega incorrecta";
        }
        return $respuesta;        
    }
    
    public function validarSalida($codigoMovimiento) {
        $em = $this->getEntityManager();
        $respuesta = "";
        //Valida si tiene registros
        $validarNumeroRegistros = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->numeroRegistros($codigoMovimiento);
        if($validarNumeroRegistros <= 0) {
            $respuesta = "El movimiento no tiene registros";            
        }
        //Valida las cantidades
        $validarCantidad = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarCantidad($codigoMovimiento);
        if($validarCantidad > 0) {
            $respuesta = "Existen detalles con cantidad en cero";
        }  
        
        $validarLote = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarLote($codigoMovimiento);
        if($validarLote > 0) {
            $respuesta = "Existen detalles sin lote";
        }         
        $validarBodega = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarBodega($codigoMovimiento);
        if($validarBodega > 0) {
            $respuesta = "Existen detalles sin bodega o con codigo de bodega incorrecta";
        }
        if($respuesta == "") {
            $validarExistencia = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->validarExistencia($codigoMovimiento);
            if($validarExistencia == FALSE) {
                $respuesta = "Cantidades con existencias insuficientes";
            }               
        }     
        return $respuesta;        
    }    
    
    public function liquidar($codigoMovimiento) {
        $em = $this->getEntityManager();
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();        
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        $subtotal = 0;
        $iva = 0;
        $baseIva = 0;
        $total = 0;
        $retencionFuente = 0;
        $retencionIva = 0;
        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->findBy(array('codigoMovimientoFk' => $codigoMovimiento));
        foreach ($arMovimientoDetalle as $arMovimientoDetalle) {
            $arMovimientoDetalleAct = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
            $arMovimientoDetalleAct = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->find($arMovimientoDetalle->getCodigoDetalleMovimientoPk());
            $subtotalDetalle = $arMovimientoDetalle->getValor() * $arMovimientoDetalle->getCantidad();
            $descuento = 0;
            if($arMovimientoDetalle->getPorcentajeDescuento() > 0){
                $descuento = ($subtotalDetalle * $arMovimientoDetalle->getPorcentajeDescuento())/100;
            }
            $subtotalDetalle = $subtotalDetalle - $descuento;
            //$baseIvaDetalle = ($subtotalDetalle * $arMovimientoDetalle->getPorcentajeIva()) / 100;
            //$baseIvaDetalle = $baseIvaDetalle;
            $ivaDetalle = ($subtotalDetalle * $arMovimientoDetalle->getPorcentajeIva()) / 100;
            $ivaDetalle = $ivaDetalle;
            $totalDetalle = $subtotalDetalle + $ivaDetalle;
            $totalDetalle = $totalDetalle;
            $arMovimientoDetalleAct->setOperacionInventario($arMovimiento->getOperacionInventario());
            $arMovimientoDetalleAct->setVrSubtotal($subtotalDetalle);
            $arMovimientoDetalleAct->setVrSubtotalOperadoInventario($subtotalDetalle * $arMovimientoDetalleAct->getOperacionInventario());
            $arMovimientoDetalleAct->setVrDescuento($descuento);
            $arMovimientoDetalleAct->setVrIva($ivaDetalle);
            $arMovimientoDetalleAct->setVrTotal($totalDetalle);
            $em->persist($arMovimientoDetalleAct);

            $subtotal += $subtotalDetalle;
            $iva += $ivaDetalle;
            //$baseIva += $baseIvaDetalle;
            $total += $totalDetalle;
        }
        
        //$porRetencionFuente = $arMovimiento->getFacturaServicioRel()->getPorRetencionFuente();
        //$porBaseRetencionFuente = $arMovimiento->getFacturaServicioRel()->getPorBaseRetencionFuente();
        //$baseRetencionFuente = ($subtotal * $porBaseRetencionFuente) / 100;
        //$baseRetencionFuente = $baseRetencionFuente;
        /*if($baseRetencionFuente >= $arConfiguracion->getBaseRetencionFuente()) {
            $retencionFuente = ($baseRetencionFuente * $porRetencionFuente ) / 100;
        }*/
        if($arMovimiento->getCodigoDocumentoClaseFk() == 3) {
            
            $porcentajeRetencion = $arMovimiento->getFacturaTipoRel()->getPorcentajeRetencionFuente();            
            if($subtotal > $arMovimiento->getFacturaTipoRel()->getBaseRetencionFuente()) {
                $retencionFuente = ($subtotal * $porcentajeRetencion) / 100;                
            }            
            if($arMovimiento->getTerceroRel()->getAutoretenedor()) {
                if($iva > 0) {
                    $retencionIva = ($iva * 15) / 100;
                }                
            }
        }  

        $subtotal = round($subtotal);
        $iva = round($iva);       
        $retencionFuente = round($retencionFuente); 
        $retencionIva = round($retencionIva);
        $totalBruto = $subtotal + $iva;          
        $totalNeto = $subtotal + $iva - $retencionFuente - $retencionIva;        
        $arMovimiento->setVrSubtotal($subtotal);
        $arMovimiento->setVrSubtotalOperado($subtotal * $arMovimiento->getOperacionInventario());       
        $arMovimiento->setVrRetencionFuente($retencionFuente);
        $arMovimiento->setVrRetencionIva($retencionIva);
        $arMovimiento->setVrIva($iva);
        $arMovimiento->setvrBruto($totalBruto);
        $arMovimiento->setVrNeto($totalNeto);                
        $em->persist($arMovimiento);
        $em->flush();
        return true;
    }
}