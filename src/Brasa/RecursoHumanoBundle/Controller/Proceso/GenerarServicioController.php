<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GenerarServicioController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/generar/servicio/", name="brs_rhu_proceso_generar_servicio")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 66)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        $paginator  = $this->get('knp_paginator');        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnGenerar')->isClicked()) { 
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {                                        
                    foreach ($arrSeleccionados AS $codigo) {                                       
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();            
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigo);
                        $arCentroCosto  = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();            
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());                         
                        $porcentajeCesantias = $arCentroCosto->getPorcentajeCesantias();        
                        $porcentajeInteresesCesantias = $arCentroCosto->getPorcentajeInteresesCesantias();
                        $porcentajeVacaciones = $arCentroCosto->getPorcentajeVacaciones();
                        $porcentajePrimas = $arCentroCosto->getPorcentajePrimas();
                        $porcentajeCaja = $arCentroCosto->getPorcentajeCaja();
                        $porcentajeSena = 0;
                        $porcentajeIcbf = 0;                        
                        $porcentajeAdministracion = $arCentroCosto->getPorcentajeAdministracion();
                        $valorAdministracion = $arCentroCosto->getValorAdministracion();                        
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();            
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPago->getCodigoProgramacionPagoPk())); 
                        foreach ($arPagos as $arPago){
                            $arServicio = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPago->getCodigoEmpleadoFk());
                            $arContrato = $arPago->getContratoRel();
                            $arServicio->setPagoRel($arPago);                            
                            $arServicio->setCentroCostoRel($arCentroCosto);
                            $arServicio->setClienteRel($arCentroCosto->getClienteRel());
                            $arServicio->setEmpleadoRel($arEmpleado);
                            $arServicio->setProgramacionPagoRel($arProgramacionPago);
                            $arServicio->setFechaDesde($arPago->getFechaDesdePago());
                            $arServicio->setFechaHasta($arPago->getFechaHastaPago());
                            $arServicio->setVrSalario($arPago->getVrSalario());
                            $salarioBasico = $arPago->getVrSalario();
                            $adicionalPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->adicionalPrestacional($arPago->getCodigoPagoPk());
                            $adicionalNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->adicionalNoPrestacional($arPago->getCodigoPagoPk());                            
                            $ingresoBasePrestaciones = $arPago->getVrIngresoBasePrestacion() + $arPago->getVrAuxilioTransporteCotizacion();                            
                            $arServicio->setVrAdicionalPrestacional($adicionalPrestacional);
                            $arServicio->setVrAdicionalNoPrestacional($adicionalNoPrestacional);
                            $auxilioTransporte = $arPago->getVrAuxilioTransporte();
                            $arServicio->setVrAuxilioTransporte($auxilioTransporte);
                            $arServicio->setVrSalarioPeriodo($arPago->getVrSalarioPeriodo());
                            $arServicio->setVrSalarioEmpleado($arPago->getVrSalarioEmpleado());
                            $arServicio->setVrDevengado($arPago->getVrDevengado());
                            $arServicio->setVrDeducciones($arPago->getVrDeducciones());
                            $arServicio->setVrAdicionalTiempo($arPago->getVrAdicionalTiempo());
                            $arServicio->setVrAdicionalValor($arPago->getVrAdicionalValor());                            
                            $arServicio->setVrAuxilioTransporteCotizacion($arPago->getVrAuxilioTransporteCotizacion());
                            $arServicio->setVrIngresoBasePrestacion($ingresoBasePrestaciones);
                            $arServicio->setVrIngresoBaseCotizacion($arPago->getVrIngresoBaseCotizacion());
                            
                            //Aportes seguridad social
                            $pension = round(($arPago->getVrIngresoBaseCotizacion() * $arPago->getContratoRel()->getTipoPensionRel()->getPorcentajeEmpleador()) / 100);                            
                            $arServicio->setVrPension($pension);                                                        
                            
                            //Calculo de prestaciones
                            $cesantias = round(($ingresoBasePrestaciones * $porcentajeCesantias) / 100);
                            $arServicio->setVrCesantias($cesantias);
                            $interesesCesantias = round(($ingresoBasePrestaciones * $porcentajeInteresesCesantias) / 100);
                            $arServicio->setVrCesantiasIntereses($interesesCesantias);
                            $primas = round(($ingresoBasePrestaciones * $porcentajePrimas) / 100);
                            $arServicio->setvrPrimas($primas);
                            $totalPrestaciones = $cesantias + $interesesCesantias + $primas;
                            $arServicio->setVrPrestaciones($totalPrestaciones);                                                        
                            $salarioDescuento = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->valorSalarioDescuento($arPago->getCodigoPagoPk());                                    
                            $recargoNorturno = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargoNocturnoPago($arPago->getCodigoPagoPk());
                            $vacaciones = round((($arPago->getVrSalario() + $recargoNorturno + $salarioDescuento) * $porcentajeVacaciones) / 100);
                            $arServicio->setVrVacaciones($vacaciones);
                            $porcentajeRiesgos = $arPago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                            $riesgos = round(($arPago->getVrIngresoBaseCotizacion() * $porcentajeRiesgos) / 100);
                            $arServicio->setPorcentajeRiesgos($porcentajeRiesgos);
                            $arServicio->setVrRiesgos($riesgos);                            
                            $caja = round(($arPago->getVrIngresoBaseCotizacion() * $porcentajeCaja) / 100);
                            $arServicio->setVrCaja($caja);                                                                                                                
                            $aporteParafiscales = round(($vacaciones * 4) / 100);                            
                            $arServicio->setVrAporteParafiscales($aporteParafiscales);   
                            
                            //Informacion adicional
                            //Valor de las horas extra y los adicionales prestacionales
                            $prestacional = $arPago->getVrAdicionalPrestacional() + $arPago->getVrExtra();
                            //Valor de los adicionales no prestacionales
                            $noPrestacional = $arPago->getVrAdicionalNoPrestacional();
                            $arServicio->setVrPrestacional($prestacional);
                            $arServicio->setVrNoPrestacional($noPrestacional);
                            $operacion = ($salarioBasico + $adicionalPrestacional + $adicionalNoPrestacional + $auxilioTransporte + $riesgos + $pension + $caja + $cesantias + $interesesCesantias + $vacaciones + $primas + $aporteParafiscales);
                            if ($arCentroCosto->getAplicaPorcentajeAdministracion() == true){
                                $valorAdministracion = ($operacion * $porcentajeAdministracion) / 100;
                                $arServicio->setAdministracionFijo(0);
                                $arServicio->setValorAdministracionFijo(0);  
                                $arServicio->setPorcentajeAdministracion($porcentajeAdministracion);
                            } else {
                                $valorAdministracion = $arCentroCosto->getValorAdministracion();
                                $arServicio->setAdministracionFijo(1);
                                $arServicio->setValorAdministracionFijo($valorAdministracion);                                 
                            }         
                                                        
                            $totalCobro = $operacion + $valorAdministracion;         
                            $arServicio->setVrOperacion($operacion);
                            $arServicio->setVrAdministracion($valorAdministracion);                            
                            $arServicio->setVrTotalCobro($totalCobro);
                            $arServicio->setDiasPeriodo($arPago->getDiasPeriodo());   
                            
                            //Horas de novedad
                            $horasNovedad = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->horasIncapacidad($arPago->getCodigoPagoPk());
                            
                            //Novedad de ingreso y retiro  
                            $ingreso = "";
                            $retiro = "";
                            if($arContrato->getFechaDesde() >= $arProgramacionPago->getFechaDesde()) {
                                $ingreso = $arContrato->getFechaDesde()->format('Y-m-d');
                            }
                            if($arContrato->getIndefinido() == 0 && $arContrato->getFechaHasta() <= $arProgramacionPago->getFechaHasta()) {                    
                                $retiro = $arContrato->getFechaHasta()->format('Y-m-d');                    
                            }                            
                            $arServicio->setIngreso($ingreso);
                            $arServicio->setRetiro($retiro);
                            $arServicio->setHorasIncapacidad($horasNovedad);
                            $em->persist($arServicio);                            
                        }                          
                        $arProgramacionPago->setServicioGenerado(1);
                        $em->persist($arProgramacionPago);
                        $em->flush(); 
                    }                                        
                }      
                
                return $this->redirect($this->generateUrl('brs_rhu_proceso_generar_servicio'));            
            }            
        }       
                
        $arProgramacionPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarServicio:servicio.html.twig', array(
            'arProgramacionPagos' => $arProgramacionPagos,
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pendientesGenerarServicioDql();  
    }         
    
    
    
}
