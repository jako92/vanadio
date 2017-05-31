<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuFacturaRepository extends EntityRepository {
    
    public function listaDql($strCodigoCliente = "", $strNumero = "", $strDesde = "", $strHasta = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT f, t FROM BrasaRecursoHumanoBundle:RhuFactura f JOIN f.clienteRel t WHERE f.codigoFacturaPk <> 0";
        if($strCodigoCliente != "") {
            $dql .= " AND f.codigoClienteFk = " . $strCodigoCliente;
        }          
        if($strNumero != "" ) {
            $dql .= " AND f.numero = '" . $strNumero . "'";
        }
        if ($strDesde != ""){
            $dql .= " AND f.fecha >='" . $strDesde . "'";
        }
        if($strHasta != "") {
            $dql .= " AND f.fecha <='" . $strHasta . "'";
        }
        $dql .= " ORDER BY f.codigoFacturaPk DESC";
        return $dql;
    }
    
    public function liquidar($codigoFactura) {        
        $em = $this->getEntityManager();
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);         
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
        $douAdministracion = 0;
        $douIngresoMision = 0;
        $subtotal = 0;
        $baseIva = 0;
        $retencionFuente = 0;
        $retencionIva = 0;
        $iva = 0;
        $total = 0;
        $ingresoMision = 0;
        $administracion = 0;
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $arFacturaDetalleAct = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
            $arFacturaDetalleAct = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($arFacturaDetalle->getCodigoFacturaDetallePk());            
            $subtotalDetalle = $arFacturaDetalle->getVrPrecio() * $arFacturaDetalle->getCantidad();
            $baseIvaDetalle = ($subtotalDetalle * $arFacturaDetalle->getPorBaseIva()) / 100;
            $ivaDetalle = ($baseIvaDetalle * $arFacturaDetalle->getPorIva()) / 100;            
            $totalDetalle = $subtotalDetalle + $ivaDetalle;   
            $arFacturaDetalleAct->setOperacion($arFactura->getOperacion());
            $arFacturaDetalleAct->setVrSubtotal($subtotalDetalle);
            $arFacturaDetalleAct->setVrSubtotalOperado($subtotalDetalle * $arFacturaDetalleAct->getOperacion());

            $arFacturaDetalleAct->setVrBaseIva($baseIvaDetalle);
            $arFacturaDetalleAct->setVrIva($ivaDetalle);
            $arFacturaDetalleAct->setVrTotal($totalDetalle);
            $em->persist($arFacturaDetalleAct); 
            
            $subtotal += $subtotalDetalle;
            $baseIva += $baseIvaDetalle;
            $iva += $ivaDetalle;
            $total += $totalDetalle;
            $ingresoMision += $arFacturaDetalle->getVrOperacion();
            $administracion += $arFacturaDetalle->getVrAdministracion();
        }     
        $subtotal = round($subtotal);
        $iva = round($iva);
        $total = round($total);               
        
        $topeRetencionFuente = 0;
        if($arFactura->getFacturaServicioRel()->getTipoRetencionFuente() == 1) {
            $topeRetencionFuente = $arConfiguracion->getBaseRetencionFuente();
        }
        if($arFactura->getFacturaServicioRel()->getTipoRetencionFuente() == 2) {
            $topeRetencionFuente = $arConfiguracion->getBaseRetencionFuenteCompras();
        }
        if($arFactura->getFacturaTipoRel()->getTipo() == 2) {
            if($arConfiguracion->getAplicarTopeRetencionFuenteNotasCredito() == 0) {
                $topeRetencionFuente = 0;
            }         
        }            
        $porRetencionFuente = $arFactura->getFacturaServicioRel()->getPorRetencionFuente();
        $porBaseRetencionFuente = $arFactura->getFacturaServicioRel()->getPorBaseRetencionFuente();
        $baseRetencionFuente = ($subtotal * $porBaseRetencionFuente) / 100;
        $baseRetencionFuente = $baseRetencionFuente;
        if($baseRetencionFuente >= $topeRetencionFuente) {
            if($arFactura->getClienteRel()->getRegimenSimplificado() == 0) {
                $retencionFuente = ($baseRetencionFuente * $porRetencionFuente ) / 100;
                $retencionFuente = round($retencionFuente);
            }            
        }        
        if($arFactura->getClienteRel()->getAutoretenedor()) {
            if($iva > 0) {
                $retencionIva = ($iva * 15) / 100;
                $retencionIva = round($retencionIva);
            }                
        }         
        $totalNeto = $subtotal + $iva - $retencionFuente - $retencionIva;
        $arFactura->setVrSubtotal($subtotal);
        $arFactura->setVrBaseAIU($baseIva);
        $arFactura->setVrRetencionFuente($retencionFuente);
        $arFactura->setVrIva($iva);
        $arFactura->setVrRetencionIva($retencionIva);    
        $arFactura->setVrNeto($totalNeto);
        $arFactura->setVrBruto($total);        
        $arFactura->setVrIngresoMision($ingresoMision);
        $arFactura->setVrTotalAdministracion($administracion);
                
        //$arFactura->setVrRetencionCree($douRetencionCREE);
        
        $em->persist($arFactura);
        $em->flush();
        return true;
    }
    
    public function autorizar($codigoFactura) {
        $em = $this->getEntityManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        $strResultado = "";
        if($arFactura->getEstadoAutorizado() == 0) {

            //if($arFactura->getFacturaTipoRel()->getTipo() == 1) {

                // Validar valor pendiente
                /*$dql   = "SELECT fd.codigoPedidoDetalleFk, SUM(fd.subtotalOperado) as vrPrecio FROM BrasaRecursoHumanoBundle:RhuFacturaDetalle fd "
                        . "WHERE fd.codigoFacturaFk = " . $codigoFactura . " "
                        . "GROUP BY fd.codigoPedidoDetalleFk";
                $query = $em->createQuery($dql);
                $arrFacturaDetalles = $query->getResult();*/
                /*foreach ($arrFacturaDetalles as $arrFacturaDetalle) {
                    if($arrFacturaDetalle['codigoPedidoDetalleFk']) {
                        $arPedidoDetalle = new \Brasa\RecursoHumanoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:TurPedidoDetalle')->find($arrFacturaDetalle['codigoPedidoDetalleFk']);
                        $floPrecio = $arrFacturaDetalle['vrPrecio'];
                        if(round($arPedidoDetalle->getVrTotalDetallePendiente()) < round($floPrecio)) {
                            $strResultado .= "Para el detalle de pedido " . $arrFacturaDetalle['codigoPedidoDetalleFk'] . " no puede facturar mas de lo pendiente valor a facturar = " . $floPrecio . " valor pendiente = " . $arPedidoDetalle->getVrTotalDetallePendiente();
                        }
                    }
                }*/
            //}

            /*if($strResultado == "") {                                
                $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
                foreach ($arFacturaDetalles as $arFacturaDetalle) {
                    if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {

                            $arPedidoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                            $floValorTotalPendiente = $arPedidoDetalle->getVrTotalDetallePendiente() - $arFacturaDetalle->getSubtotalOperado();
                            $arPedidoDetalle->setVrTotalDetallePendiente($floValorTotalPendiente);                            
                            $floValorTotalAfectado = $arPedidoDetalle->getVrTotalDetalleAfectado() + $arFacturaDetalle->getSubtotalOperado();
                            $arPedidoDetalle->setVrTotalDetalleAfectado($floValorTotalAfectado);
                            if($floValorTotalPendiente <= 0) {
                                $arPedidoDetalle->setEstadoFacturado(1);
                            }
                            $em->persist($arPedidoDetalle);                            
                    }                                                
                }                                
                $arFactura->setEstadoAutorizado(1);
                $em->persist($arFactura);
                $em->flush();
            }*/
                $arFactura->setEstadoAutorizado(1);
                $em->persist($arFactura);
                $em->flush();
        } else {
            $strResultado = "Ya esta autorizado";
        }
        return $strResultado;
    }

    public function desAutorizar($codigoFactura) {
        $em = $this->getEntityManager();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        $strResultado = "";
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() == 0) {            
            /*$arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
            $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {                    
                    $arPedidoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());                        
                    $floValorTotalPendiente = $arPedidoDetalle->getVrTotalDetallePendiente() + $arFacturaDetalle->getSubtotalOperado();
                    $arPedidoDetalle->setVrTotalDetallePendiente($floValorTotalPendiente);
                    $floValorTotalAfectado = $arPedidoDetalle->getVrTotalDetalleAfectado() - $arFacturaDetalle->getSubtotalOperado();
                    $arPedidoDetalle->setVrTotalDetalleAfectado($floValorTotalAfectado);
                    $arPedidoDetalle->setEstadoFacturado(0);
                    $em->persist($arPedidoDetalle);                        
                }
            }*/           
            $arFactura->setEstadoAutorizado(0);
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "No se puede des-autorizar la factura si esta impresa o anulada";
        }
        return $strResultado;
    }

    public function imprimir($codigoFactura) {
        $em = $this->getEntityManager();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();       
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        if($arFactura->getEstadoAutorizado() == 1) {
            if($arFactura->getNumero() == 0) {
                $intNumero = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaTipo')->consecutivo($arFactura->getCodigoFacturaTipoFk());
                $arFactura->setNumero($intNumero);
                $arFactura->setFecha(new \DateTime('now'));
                $dateFechaVence = $objFunciones->sumarDiasFecha($arFactura->getPlazoPago(), $arFactura->getFecha());
                $arFactura->setFechaVence($dateFechaVence);                                
                $arClienteRecursoHumano = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();                
                $arClienteRecursoHumano = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($arFactura->getCodigoClienteFk());
                $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteRecursoHumano->getNit()));
                if ($arClienteCartera == null){
                    $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                    $arClienteCartera->setAsesorRel($arClienteRecursoHumano->getAsesorRel());
                    $arClienteCartera->setFormaPagoRel($arClienteRecursoHumano->getFormaPagoRel());
                    $arClienteCartera->setCiudadRel($arClienteRecursoHumano->getCiudadRel());
                    $arClienteCartera->setNit($arClienteRecursoHumano->getNit());
                    $arClienteCartera->setDigitoVerificacion($arClienteRecursoHumano->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arClienteRecursoHumano->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arClienteRecursoHumano->getPlazoPago());
                    $arClienteCartera->setDireccion($arClienteRecursoHumano->getDireccion());
                    $arClienteCartera->setTelefono($arClienteRecursoHumano->getTelefono());
                    $arClienteCartera->setCelular($arClienteRecursoHumano->getCelular());
                    $arClienteCartera->setFax($arClienteRecursoHumano->getFax());
                    $arClienteCartera->setEmail($arClienteRecursoHumano->getEmail());
                    $arClienteCartera->setUsuario($arFactura->getUsuario());
                    $em->persist($arClienteCartera);
                }                    
                    $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find($arFactura->getFacturaTipoRel()->getCodigoDocumentoCartera());
                    if($arCuentaCobrarTipo) {
                        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrar->setClienteRel($arClienteCartera);
                        $arCuentaCobrar->setAsesorRel($arClienteRecursoHumano->getAsesorRel());
                        $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                        $arCuentaCobrar->setFecha($arFactura->getFecha());
                        $arCuentaCobrar->setFechaVence($arFactura->getFechaVence());
                        $arCuentaCobrar->setCodigoFactura($arFactura->getCodigoFacturaPk());
                        $arCuentaCobrar->setSoporte($arFactura->getSoporte());
                        $arCuentaCobrar->setNumeroDocumento($arFactura->getNumero());
                        $arCuentaCobrar->setValorOriginal($arFactura->getVrNeto());
                        $saldoOperado = $arFactura->getVrNeto() * $arCuentaCobrarTipo->getOperacion();
                        $arCuentaCobrar->setSaldo($arFactura->getVrNeto());
                        $arCuentaCobrar->setSaldoOperado($saldoOperado);                        
                        $arCuentaCobrar->setSubtotal($arFactura->getVrSubtotal());                                                
                        $arCuentaCobrar->setRetencionFuente($arFactura->getVrRetencionFuente());
                        $arCuentaCobrar->setRetencionIva($arFactura->getVrRetencionIva());
                        $arCuentaCobrar->setRetencionIca(0);
                        $arCuentaCobrar->setTotalNeto($arFactura->getVrNeto());
                        $arCuentaCobrar->setPlazo($arFactura->getPlazoPago());
                        $arCuentaCobrar->setAbono(0);
                        $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
                        $arCuentaCobrar->setServicioTipo($arFactura->getFacturaServicioRel()->getNombre());
                        $em->persist($arCuentaCobrar);                        
                    }
            }
            $em->persist($arFactura);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la factura para imprimirla";
        }
        return $strResultado;
    }

    public function anular($codigoFactura) {
        $em = $this->getEntityManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();        
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        
        $strResultado = "";
        if($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAnulado() == 0 && $arFactura->getNumero() != 0 && $arFactura->getEstadoContabilizado() == 0) {
            $boolAnular = TRUE;
            $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
            $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
            //Devolver saldo a los pedidos
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                    $arPedidoDetalleAct = new \Brasa\RecursoHumanoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalleAct = $em->getRepository('BrasaRecursoHumanoBundle:TurPedidoDetalle')->find($arFacturaDetalle->getCodigoPedidoDetalleFk());
                    $floValorTotalPendiente = $arPedidoDetalleAct->getVrTotalDetallePendiente() + $arFacturaDetalle->getVrPrecio();
                    $arPedidoDetalleAct->setVrTotalDetallePendiente($floValorTotalPendiente);
                    $arPedidoDetalleAct->setEstadoFacturado(0);
                    $em->persist($arPedidoDetalleAct);                    
                }
            }
            //Actualizar los detalles de la factura a cero
            foreach ($arFacturaDetalles as $arFacturaDetalle) {
                $arFacturaDetalleAct = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                $arFacturaDetalleAct = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($arFacturaDetalle->getCodigoFacturaDetallePk());
                $arFacturaDetalle->setVrPrecio(0);
                $arFacturaDetalle->setCantidad(0);
                $arFacturaDetalle->setSubtotal(0);
                $arFacturaDetalle->setSubtotalOperado(0);
                $arFacturaDetalle->setBaseIva(0);
                $arFacturaDetalle->setIva(0);
                $arFacturaDetalle->setTotal(0);
                $em->persist($arFacturaDetalle);
            }
            $arFactura->setVrSubtotal(0);
            $arFactura->setVrRetencionFuente(0);
            $arFactura->setVrBaseAIU(0);
            $arFactura->setVrIva(0);
            $arFactura->setVrTotal(0);
            $arFactura->setVrTotalNeto(0);
            $arFactura->setEstadoAnulado(1);
            $em->persist($arFactura);

            
            //Anular cuenta por cobrar
            /*$arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
            $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->findOneBy(array('codigoCuentaCobrarTipoFk' => 2, 'numeroDocumento' => $arFactura->getNumero()));
            if($arCuentaCobrar) {
                if($arCuentaCobrar->getValorOriginal() == $arCuentaCobrar->getSaldo()) {
                    $arCuentaCobrar->setSaldo(0);
                    $arCuentaCobrar->setValorOriginal(0);
                    $arCuentaCobrar->setAbono(0);
                    $em->persist($arCuentaCobrar);
                }
            }*/
            $em->flush();

        } else {
            $strResultado = "La factura debe estar autorizada e impresa, no puede estar previamente anulada ni contabilizada";
        }
        return $strResultado;
    }
}