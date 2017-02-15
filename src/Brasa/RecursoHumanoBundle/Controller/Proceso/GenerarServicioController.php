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
                        //$porcentajeCaja = $arConfiguracion->getAportesPorcentajeCaja();
                        $porcentajeCesantias = $arCentroCosto->getPorcentajeCesantias();        
                        $porcentajeInteresesCesantias = $arCentroCosto->getPorcentajeInteresesCesantias();
                        $porcentajeVacaciones = $arCentroCosto->getPorcentajeVacaciones();
                        $porcentajePrimas = $arCentroCosto->getPorcentajePrimas();            
                        //$porcentajeIndemnizacion = $arConfiguracion->getPrestacionesPorcentajeIndemnizacion();                 
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
                            $arServicio->setVrSalarioPeriodo($arPago->getVrSalarioPeriodo());
                            $arServicio->setVrSalarioEmpleado($arPago->getVrSalarioEmpleado());
                            $arServicio->setVrDevengado($arPago->getVrDevengado());
                            $arServicio->setVrDeducciones($arPago->getVrDeducciones());
                            $arServicio->setVrAdicionalTiempo($arPago->getVrAdicionalTiempo());
                            $arServicio->setVrAdicionalValor($arPago->getVrAdicionalValor());
                            $arServicio->setVrAuxilioTransporte($arPago->getVrAuxilioTransporte());
                            $arServicio->setVrAuxilioTransporteCotizacion($arPago->getVrAuxilioTransporteCotizacion());
                            $arServicio->setVrIngresoBasePrestacion($arPago->getVrIngresoBasePrestacion());
                            $cesantias = ($arPago->getVrIngresoBasePrestacion() * $porcentajeCesantias) / 100;
                            $arServicio->setVrCesantias($cesantias);
                            $interesesCesantias = ($cesantias * $porcentajeInteresesCesantias) / 100;
                            $arServicio->setVrCesantiasIntereses($interesesCesantias);
                            $vacaciones = ($arPago->getVrSalario() * $porcentajeVacaciones) / 100;
                            $arServicio->setVrVacaciones($vacaciones);
                            $primas = ($arPago->getVrIngresoBasePrestacion() * $porcentajePrimas) / 100;
                            $arServicio->setvrPrimas($primas);
                            $arServicio->setVrAdministracion($arCentroCosto->getValorAdministracion());
                            $administracion = $arServicio->getVrAdministracion();
                            $neto = $cesantias + $interesesCesantias + $vacaciones + $primas;
                            $bruto = $cesantias + $interesesCesantias + $vacaciones + $primas; 
                            $prestaciones = $cesantias+ $interesesCesantias+$primas;
                            $totalCobrar = $cesantias + $interesesCesantias + $vacaciones + $primas + $administracion;         
                            $arServicio->setVrNeto($neto);
                            $arServicio->setVrBruto($bruto);
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
