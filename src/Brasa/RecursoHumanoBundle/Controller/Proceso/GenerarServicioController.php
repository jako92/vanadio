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
                        $porcentajeAporteParafiscales = $porcentajePrimas + $porcentajeSena + $porcentajeIcbf;
                        $porcentajeAdministracion = $arCentroCosto->getPorcentajeAdministracion();
                        $valorAdministracion = $arCentroCosto->getValorAdministracion();                        
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();            
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPago->getCodigoProgramacionPagoPk())); 
                        foreach ($arPagos as $arPago){
                            $arServicio = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPago->getCodigoEmpleadoFk());
                            $arServicio->setPagoRel($arPago);                            
                            $arServicio->setCentroCostoRel($arCentroCosto);
                            $arServicio->setEmpleadoRel($arEmpleado);
                            $arServicio->setProgramacionPagoRel($arProgramacionPago);
                            $arServicio->setFechaDesde($arPago->getFechaDesdePago());
                            $arServicio->setFechaHasta($arPago->getFechaHastaPago());
                            $arServicio->setVrSalario($arPago->getVrSalario());
                            $salarioBasico = $arPago->getVrSalario();
                            $adicionalPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->adicionalPrestacional($arPago->getCodigoPagoPk());
                            $adicionalNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->adicionalNoPrestacional($arPago->getCodigoPagoPk());                            
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
                            $arServicio->setVrIngresoBasePrestacion($arPago->getVrIngresoBasePrestacion());
                            $arp = ($arPago->getVrIngresoBaseCotizacion() * $arPago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje()) / 100;
                            $arServicio->setVrArp($arp);
                            $cesantias = ($arPago->getVrIngresoBasePrestacion() * $porcentajeCesantias) / 100;
                            $arServicio->setVrCesantias($cesantias);
                            $interesesCesantias = ($cesantias * $porcentajeInteresesCesantias) / 100;
                            $arServicio->setVrCesantiasIntereses($interesesCesantias);
                            $vacaciones = ($arPago->getVrSalario() * $porcentajeVacaciones) / 100;
                            $arServicio->setVrVacaciones($vacaciones);
                            $primas = ($arPago->getVrIngresoBasePrestacion() * $porcentajePrimas) / 100;
                            $arServicio->setvrPrimas($primas);
                            $caja = ($arPago->getVrIngresoBasePrestacion() * $porcentajeCaja) / 100;
                            $arServicio->setVrCaja($caja);                                                                                                                
                            $pension = ($arPago->getVrIngresoBaseCotizacion() * $arPago->getContratoRel()->getTipoPensionRel()->getPorcentajeEmpleado()) / 100;
                            $arServicio->setVrPension($pension);
                            $aporteParafiscales = ($vacaciones * $porcentajeAporteParafiscales) / 100;                            
                            $arServicio->setVrAporteParafiscales($aporteParafiscales);                                                        
                            $neto = ($salarioBasico + $adicionalPrestacional + $adicionalNoPrestacional + $auxilioTransporte + $arp + $pension + $caja + $cesantias + $interesesCesantias + $vacaciones + $primas + $aporteParafiscales);
                            if ($arCentroCosto->getAplicaPorcentajeAdministracion() == true){
                                $valorAdministracion = ($neto * $porcentajeAdministracion) / 100;
                            }                            
                            $bruto = $neto + $valorAdministracion;                             
                            $totalCobrar = $neto + $valorAdministracion;         
                            $arServicio->setVrNeto($neto);
                            $arServicio->setVrBruto($bruto);
                            $arServicio->setVrCosto($neto);
                            $arServicio->setVrAdministracion($valorAdministracion);
                            $arServicio->setVrTotalCobrar($totalCobrar);
                            $arServicio->setDiasPeriodo($arPago->getDiasPeriodo());                            
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
