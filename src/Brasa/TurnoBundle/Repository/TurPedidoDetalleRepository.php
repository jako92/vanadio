<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurPedidoDetalleRepository extends EntityRepository {

    public function listaDql($codigoPedido = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd WHERE pd.codigoPedidoDetallePk <> 0 ";
        
        if($codigoPedido != '') {
            $dql .= "AND pd.codigoPedidoFk = " . $codigoPedido . " ";  
        }        
        $dql .= " ORDER BY pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";
        return $dql;
    }        
    
    public function listaConsultaDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "", $codigoPedidoTipo = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.codigoPedidoDetallePk <> 0 ";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        } 
        if($codigoPedidoTipo != "") {
            $dql .= " AND p.codigoPedidoTipoFk = " . $codigoPedidoTipo;  
        }         
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND pd.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND pd.estadoProgramado = 0";
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
        $dql .= " ORDER BY p.codigoClienteFk, pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";        
        return $dql;
    }     
    
    public function listaConsultaPendienteFacturarDql($numeroPedido = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolEstadoProgramado = "", $boolEstadoFacturado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE pd.vrTotalDetallePendiente > 0 ";
        if($numeroPedido != "") {
            $dql .= " AND p.numero = " . $numeroPedido;  
        }
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;  
        } 
        if($boolEstadoProgramado == 1 ) {
            $dql .= " AND pd.estadoProgramado = 1";
        }
        if($boolEstadoProgramado == "0") {
            $dql .= " AND pd.estadoProgramado = 0";
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
        $dql .= " ORDER BY p.codigoClienteFk, pd.codigoGrupoFacturacionFk, pd.codigoPuestoFk";        
        return $dql;
    }                 
    
    public function liquidar($codigoPedidoDetalle) {        
        $em = $this->getEntityManager();        
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();         
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);                 
        $intCantidad = 0;
        $douTotalHoras = 0;
        $douTotalHorasDiurnas = 0;
        $douTotalHorasNocturnas = 0;
        $subtotalGeneral = 0;
        $douTotalServicio = 0;
        $totalIva = 0;
        $douTotalMinimoServicio = 0;
        $douTotalCostoCalculado = 0;        
        $arPedidosDetalleCompuesto = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto();        
        $arPedidosDetalleCompuesto = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleCompuesto')->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));         
        foreach ($arPedidosDetalleCompuesto as $arPedidoDetalleCompuesto) {            
            $intDiasFacturar = 0;
            if($arPedidoDetalleCompuesto->getPeriodoRel()->getCodigoPeriodoPk() == 2 || $arPedidoDetalleCompuesto->getLiquidarDiasReales() == 1) {
                $intDias = $arPedidoDetalleCompuesto->getDiaHasta() - $arPedidoDetalleCompuesto->getDiaDesde();
                $intDias += 1;
                if($arPedidoDetalleCompuesto->getDiaHasta() == 0 || $arPedidoDetalleCompuesto->getDiaDesde() == 0) {
                    $intDias = 0;
                }
                $intDiasFacturar = $intDias;
            } else {  
                $intDias = date("d",(mktime(0,0,0,$arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('m')+1,1,$arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y'))-1));
                $intDiasFacturar = 30;
            }

            $intHorasRealesDiurnas = 0;
            $intHorasRealesNocturnas = 0;            
            $intHorasDiurnasLiquidacion = 0;
            $intHorasNocturnasLiquidacion = 0;                        
            $intDiasOrdinarios = 0;
            $intDiasSabados = 0;
            $intDiasDominicales = 0;
            $intDiasFestivos = 0;                       
            
            $strFechaDesde = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalleCompuesto->getDiaDesde();
            $strFechaHasta = $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y-m') ."-". $arPedidoDetalleCompuesto->getDiaHasta();
            $arFestivos = $em->getRepository('BrasaGeneralBundle:GenFestivo')->festivos($strFechaDesde, $strFechaHasta);
            $fecha = $strFechaDesde;
            for($i = 0; $i < $intDias; $i++) {
                $nuevafecha = strtotime ( '+'.$i.' day' , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                $dateNuevaFecha = date_create($nuevafecha);
                $diaSemana = $dateNuevaFecha->format('N');
                if($this->festivo($arFestivos, $dateNuevaFecha) == 1) {
                    $intDiasFestivos += 1;
                    if($arPedidoDetalleCompuesto->getFestivo() == 1) {                   
                        $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                        $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                    }                    
                } else {
                    if($diaSemana == 1) {
                        $intDiasOrdinarios += 1; 
                        if($arPedidoDetalleCompuesto->getLunes() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }
                    } 
                    if($diaSemana == 2) {
                        $intDiasOrdinarios += 1; 
                        if($arPedidoDetalleCompuesto->getMartes() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }
                    }                
                    if($diaSemana == 3) {
                        $intDiasOrdinarios += 1; 
                        if($arPedidoDetalleCompuesto->getMiercoles() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }
                    }    
                    if($diaSemana == 4) {
                        $intDiasOrdinarios += 1; 
                        if($arPedidoDetalleCompuesto->getJueves() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }
                    }                
                    if($diaSemana == 5) {
                        $intDiasOrdinarios += 1; 
                        if($arPedidoDetalleCompuesto->getViernes() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }
                    }                
                    if($diaSemana == 6) {
                       $intDiasSabados += 1; 
                        if($arPedidoDetalleCompuesto->getSabado() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }                   
                    }
                    if($diaSemana == 7) {
                       $intDiasDominicales += 1; 
                        if($arPedidoDetalleCompuesto->getDomingo() == 1) {                   
                            $intHorasRealesDiurnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas();
                            $intHorasRealesNocturnas +=  $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas();                        
                        }                   
                    }                    
                }                                
            }  
            if($arPedidoDetalleCompuesto->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                if($arPedidoDetalle->getLiquidarDiasReales() == 0) {
                    $intDiasOrdinarios = 0;
                    $intDiasSabados = 0;
                    $intDiasDominicales = 0;
                    $intDiasFestivos = 0;                     
                    if($arPedidoDetalleCompuesto->getLunes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arPedidoDetalleCompuesto->getMartes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arPedidoDetalleCompuesto->getMiercoles() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arPedidoDetalleCompuesto->getJueves() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arPedidoDetalleCompuesto->getViernes() == 1) {
                        $intDiasOrdinarios += 4;
                    }
                    if($arPedidoDetalleCompuesto->getSabado() == 1) {
                        $intDiasSabados = 4;    
                    }
                    if($arPedidoDetalleCompuesto->getDomingo() == 1) {
                        $intDiasDominicales = 4;    
                    }                
                    if($arPedidoDetalleCompuesto->getFestivo() == 1) {
                        $intDiasFestivos = 2;    
                    }                               
                    $intTotalDias = $intDiasOrdinarios + $intDiasSabados + $intDiasDominicales + $intDiasFestivos;
                    $intHorasDiurnasLiquidacion = $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasDiurnas() * $intTotalDias;
                    $intHorasNocturnasLiquidacion = $arPedidoDetalleCompuesto->getConceptoServicioRel()->getHorasNocturnas() * $intTotalDias;                                                                   
                } else {
                    $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                    $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;                                                                                      
                }                
            } else {
                $intHorasDiurnasLiquidacion = $intHorasRealesDiurnas;
                $intHorasNocturnasLiquidacion = $intHorasRealesNocturnas;                 
            }
            $intHorasRealesDiurnas = $intHorasRealesDiurnas * $arPedidoDetalleCompuesto->getCantidad();
            $intHorasRealesNocturnas = $intHorasRealesNocturnas * $arPedidoDetalleCompuesto->getCantidad();
            $intHorasDiurnasLiquidacion = $intHorasDiurnasLiquidacion * $arPedidoDetalleCompuesto->getCantidad();
            $intHorasNocturnasLiquidacion = $intHorasNocturnasLiquidacion * $arPedidoDetalleCompuesto->getCantidad();             
            $douHoras = $intHorasRealesDiurnas + $intHorasRealesNocturnas ;                                                            
            $arPedidoDetalleCompuestoActualizar = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto();        
            $arPedidoDetalleCompuestoActualizar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleCompuesto')->find($arPedidoDetalleCompuesto->getCodigoPedidoDetalleCompuestoPk());                         
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1); 
            $floValorBaseServicio = $arPedidoDetalle->getVrSalarioBase() * $arPedidoDetalle->getPedidoRel()->getSectorRel()->getPorcentaje();        
            $floValorBaseServicioMes = $floValorBaseServicio + ($floValorBaseServicio * $arPedidoDetalleCompuesto->getModalidadServicioRel()->getPorcentaje() / 100);                        
            $floVrHoraDiurna = ((($floValorBaseServicioMes * 59.7) / 100)/30)/16;            
            $floVrHoraNocturna = ((($floValorBaseServicioMes * 40.3) / 100)/30)/8;                                  
            if($arPedidoDetalleCompuesto->getPeriodoRel()->getCodigoPeriodoPk() == 1) {
                $precio = ($intHorasDiurnasLiquidacion * $floVrHoraDiurna) + ($intHorasNocturnasLiquidacion * $floVrHoraNocturna);
            } else {
                $precio = ($intHorasRealesDiurnas * $floVrHoraDiurna) + ($intHorasRealesNocturnas * $floVrHoraNocturna);    
            }
            
            $precio = round($precio);
            $floVrMinimoServicio = $precio;
            
            $floVrServicio = 0;
            $subTotalDetalle = 0;
            if($arPedidoDetalleCompuestoActualizar->getVrPrecioAjustado() != 0) {
                $floVrServicio = $arPedidoDetalleCompuestoActualizar->getVrPrecioAjustado() * $arPedidoDetalle->getCantidad();
                $precio = $arPedidoDetalleCompuestoActualizar->getVrPrecioAjustado();
            } else {
                $floVrServicio = $floVrMinimoServicio * $arPedidoDetalle->getCantidad();                
            }
            $subTotalDetalle = $floVrServicio;
            $subtotalGeneral += $subTotalDetalle;
            $baseAiuDetalle = $subTotalDetalle*10/100;
            $ivaDetalle = $baseAiuDetalle*$arPedidoDetalleCompuesto->getPorcentajeIva()/100;
            $totalIva += $ivaDetalle;
            $totalDetalle = $subTotalDetalle + $ivaDetalle;
            
            $arPedidoDetalleCompuestoActualizar->setVrSubtotal($subTotalDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrBaseAiu($baseAiuDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrIva($ivaDetalle);
            $arPedidoDetalleCompuestoActualizar->setVrTotalDetalle($totalDetalle);             
            $arPedidoDetalleCompuestoActualizar->setVrPrecioMinimo($floVrMinimoServicio);
            $arPedidoDetalleCompuestoActualizar->setVrPrecio($precio);
                        
            $arPedidoDetalleCompuestoActualizar->setHoras($douHoras);
            $arPedidoDetalleCompuestoActualizar->setHorasDiurnas($intHorasRealesDiurnas);
            $arPedidoDetalleCompuestoActualizar->setHorasNocturnas($intHorasRealesNocturnas);
            $arPedidoDetalleCompuestoActualizar->setDias($intDias);
            
            $em->persist($arPedidoDetalleCompuestoActualizar);            
            $douTotalHoras += $douHoras;
            $douTotalHorasDiurnas += $intHorasRealesDiurnas;
            $douTotalHorasNocturnas += $intHorasRealesNocturnas;
            $douTotalMinimoServicio += $floVrMinimoServicio;            
            $douTotalServicio += $floVrServicio;
            $intCantidad++;
        }            
        
        $arPedidoDetalle->setHoras($douTotalHoras);
        $arPedidoDetalle->setHorasDiurnas($douTotalHorasDiurnas);
        $arPedidoDetalle->setHorasNocturnas($douTotalHorasNocturnas);
                
        $arPedidoDetalle->setVrPrecioMinimo($douTotalMinimoServicio);        
        $baseAiu = $subtotalGeneral*10/100;        
        $total = $subtotalGeneral + $totalIva;
        $arPedidoDetalle->setVrSubtotal($subtotalGeneral);
        $arPedidoDetalle->setVrBaseAiu($baseAiu);
        $arPedidoDetalle->setVrIva($totalIva);
        $arPedidoDetalle->setVrTotalDetalle($total);        
        $arPedidoDetalle->setVrTotalDetallePendiente($subtotalGeneral - $arPedidoDetalle->getVrTotalDetalleAfectado());
        $em->persist($arPedidoDetalle);
        $em->flush();
        return true;
    }    
    
    public function pendientesCliente($codigoCliente) {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND pd.estadoProgramado = 0 AND p.estadoAnulado = 0 AND p.estadoAutorizado = 1";
                
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }
    
    public function fecha($strFechaDesde = "", $strFechaHasta = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd "
                . "FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                . "JOIN pd.pedidoRel p "                
                . "WHERE p.fechaProgramacion >= '" . $strFechaDesde . "' AND p.fechaProgramacion <='" . $strFechaHasta . "'";                
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function listaCliente($codigoCliente, $fechaProgramacion = '', $codigoPuesto = "", $programado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0 ";
        if($fechaProgramacion != '') {
            $dql .= " AND p.fechaProgramacion >= '" . $fechaProgramacion . "'";
        }
        if($programado == 1) {
            $dql .= " AND pd.estadoProgramado = 1";
        }        
        if($programado == '0') {
            $dql .= " AND pd.estadoProgramado = 0";
        }        
        if($codigoPuesto != "" && $codigoPuesto != 0) {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto;
        }
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    

    public function listaClienteFecha($codigoCliente, $codigoPuesto = "", $programado = "", $anio = "", $mes = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.codigoClienteFk = " . $codigoCliente . " AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0 ";
        if($anio != '') {
            $dql .= " AND pd.anio >= " . $anio;
        }
        if($mes != '') {
            $dql .= " AND pd.mes >= " . $mes;
        }        
        if($programado == 1) {
            $dql .= " AND pd.estadoProgramado = 1";
        }        
        if($programado == '0') {
            $dql .= " AND pd.estadoProgramado = 0";
        }        
        if($codigoPuesto != "" && $codigoPuesto != 0) {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto;
        }
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    

    public function listaFecha($anio = "", $mes = "", $codigoCliente = "", $codigoPuesto = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p "
                . "WHERE p.estadoAutorizado = 1 AND p.estadoAnulado = 0 AND pd.anio >= $anio AND pd.mes >= $mes ";
        if($codigoCliente != "" && $codigoCliente != 0) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }   
        if($codigoPuesto != "" && $codigoPuesto != 0) {
            $dql .= " AND pd.codigoPuestoFk = " . $codigoPuesto;
        }           
        $query = $em->createQuery($dql);
        $arResultado = $query->getResult();
        return $arResultado;                
    }    
    
    public function pendientesFacturarDql($codigoCliente, $boolMostrarTodo = 0, $numero = '', $nombreGrupoFacturacion = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT pd FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p LEFT JOIN pd.grupoFacturacionRel gf "
                . "WHERE p.estadoAutorizado = 1 AND p.estadoAnulado = 0 AND pd.vrTotalDetallePendiente > 0 ";
        if($boolMostrarTodo == 0) {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        } 
        if($numero != '') {
            $dql .= " AND p.numero = " . $numero;
        }
        if($nombreGrupoFacturacion != '') {
            $dql .= " AND gf.nombre like '%" . $nombreGrupoFacturacion."%'";
        }
        return $dql;                
    }    
    
    public function eliminarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {  
                $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPedidoDetalleFk' => $codigo));
                $arFacturaDetalle = $em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoPedidoDetalleFk' => $codigo));
                if(!$arProgramacionDetalle && !$arFacturaDetalle) {
                    /*$arPedidoDetalleCompuestos = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleCompuesto')->findBy(array('codigoPedidoDetalleFk' => $codigo));
                    foreach ($arPedidoDetalleCompuestos as $arPedidoDetalleCompuesto) {
                        $arPedidoDetalleCompuestoEliminar = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleCompuesto')->find($arPedidoDetalleCompuesto->getCodigoPedidoDetalleCompuestoPk());
                        $em->remove($arPedidoDetalleCompuestoEliminar);                                             
                    }*/
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                                    
                    $em->remove($arPedidoDetalle);                     
                }                                     
            }                                         
            $em->flush();         
        }
        
    }        
    
    public function numeroRegistros($codigo) {
        $em = $this->getEntityManager();
        $arDetalles = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array('codigoPedidoFk' => $codigo));
        return count($arDetalles);
    }  
    
    public function validarPuesto($codigo) {        
        $em = $this->getEntityManager();
        $intNumeroRegistros = 0;
        $dql   = "SELECT COUNT(pd.codigoPedidoDetallePk) as numeroRegistros FROM BrasaTurnoBundle:TurPedidoDetalle pd "
                . "WHERE pd.codigoPedidoFk = " . $codigo . " AND pd.codigoPuestoFk IS NULL";
        $query = $em->createQuery($dql);
        $arrPedidosDetalles = $query->getSingleResult(); 
        if($arrPedidosDetalles) {
            $intNumeroRegistros = $arrPedidosDetalles['numeroRegistros'];
        }
        return $intNumeroRegistros;
    }     
 
    public function actualizarPendienteFacturar($codigoPedidoDetalle) {        
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        
        $totalAfectado = 0;
        $dql   = "SELECT SUM(fd.subtotalOperado) as valor FROM BrasaTurnoBundle:TurFacturaDetalle fd JOIN fd.facturaRel f "
                . "WHERE fd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle . " AND f.estadoAutorizado = 1";
        $query = $em->createQuery($dql);
        $arrFacturaDetalle = $query->getSingleResult();         
        if($arrFacturaDetalle) {
            if($arrFacturaDetalle['valor']) {
                $totalAfectado = $arrFacturaDetalle['valor'];
            }            
        }       
        
        $totalDevolucion = 0;
        $dql   = "SELECT SUM(pdd.vrPrecio) as valor FROM BrasaTurnoBundle:TurPedidoDevolucionDetalle pdd "
                . "WHERE pdd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle . " AND pdd.codigoPedidoDevolucionTipoFk = 'DEV'";
        $query = $em->createQuery($dql);
        $arrPedidoDevolucionDetalle = $query->getSingleResult();         
        if($arrPedidoDevolucionDetalle) {
            if($arrPedidoDevolucionDetalle['valor']) {
                $totalDevolucion = $arrPedidoDevolucionDetalle['valor'];
            }            
        }                   
        
        $totalAdicion = 0;
        $dql   = "SELECT SUM(pdd.vrPrecio) as valor FROM BrasaTurnoBundle:TurPedidoDevolucionDetalle pdd "
                . "WHERE pdd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle . " AND pdd.codigoPedidoDevolucionTipoFk = 'ADI'";
        $query = $em->createQuery($dql);
        $arrPedidoDevolucionDetalle = $query->getSingleResult();         
        if($arrPedidoDevolucionDetalle) {
            if($arrPedidoDevolucionDetalle['valor']) {
                $totalAdicion = $arrPedidoDevolucionDetalle['valor'];
            }            
        }        

        $arPedidoDetalle->setVrTotalDetalleAfectado($totalAfectado);
        $arPedidoDetalle->setVrTotalDetalleDevolucion($totalDevolucion);
        $arPedidoDetalle->setVrTotalDetalleAdicion($totalAdicion);
        $pendiente = $arPedidoDetalle->getVrSubtotal() - ($totalAfectado + $totalDevolucion - $totalAdicion);
        $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
        $em->persist($arPedidoDetalle);
            
    }  
    
    public function actualizarHorasProgramadas($codigoPedidoDetalle) {        
        $em = $this->getEntityManager();
        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
        $dql   = "SELECT SUM(pd.horas) as horas, SUM(pd.horasDiurnas) as horasDiurnas, SUM(pd.horasNocturnas) as horasNocturnas FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p "
                . "WHERE pd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle;
        $query = $em->createQuery($dql);
        $arrProgramacionDetalle = $query->getSingleResult(); 
        if($arrProgramacionDetalle) {
            $horasProgramadas = 0;
            $horasDiurnas = 0;
            $horasNocturnas = 0;
            if($arrProgramacionDetalle['horas']) {
                $horasProgramadas = $arrProgramacionDetalle['horas'];
            }
            if($arrProgramacionDetalle['horasDiurnas']) {
                $horasDiurnas = $arrProgramacionDetalle['horasDiurnas'];
            }
            if($arrProgramacionDetalle['horasNocturnas']) {
                $horasNocturnas = $arrProgramacionDetalle['horasNocturnas'];
            }
            $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
            $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnas);
            $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnas);
            $em->persist($arPedidoDetalle);
        }       
    }     
    
    public function marcarSeleccionados($arrSeleccionados) {        
        if(count($arrSeleccionados) > 0) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {                
                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                if($arPedidoDetalle->getMarca() == 1) {
                    $arPedidoDetalle->setMarca(0);
                } else {
                    $arPedidoDetalle->setMarca(1);
                }                
            }                                         
            $em->flush();       
        }
        
    }                     

    public function festivo($arFestivos, $dateFecha) {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }      
    
    public function pedidoSinProgramarDql($codigoCliente = "") {
        $dql   = "SELECT pd, p FROM BrasaTurnoBundle:TurPedidoDetalle pd JOIN pd.pedidoRel p WHERE p.estadoProgramado = 0 AND p.estadoAutorizado = 1 AND p.estadoAnulado = 0";        
        if($codigoCliente != "") {
            $dql .= " AND p.codigoClienteFk = " . $codigoCliente;
        }        
        $dql .= " ORDER BY pd.codigoPedidoFk";
        return $dql;
    }     
    
}