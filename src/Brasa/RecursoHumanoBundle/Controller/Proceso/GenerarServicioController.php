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
                            $ingresoBasePrestaciones = $arPago->getVrIngresoBasePrestacion() + $arPago->getVrAuxilioTransporteCotizacion();                            
                            $auxilioTransporte = $arPago->getVrAuxilioTransporte();
                            $arServicio->setVrAuxilioTransporte($auxilioTransporte);
                            $arServicio->setVrSalarioPeriodo($arPago->getVrSalarioPeriodo());
                            $arServicio->setVrSalarioEmpleado($arPago->getVrSalarioEmpleado());
                            $arServicio->setVrDevengado($arPago->getVrDevengado());
                            $arServicio->setVrDeducciones($arPago->getVrDeducciones());
                            $arServicio->setVrAuxilioTransporteCotizacion($arPago->getVrAuxilioTransporteCotizacion());
                            $arServicio->setVrIngresoBasePrestacion($ingresoBasePrestaciones);
                            $arServicio->setVrIngresoBaseCotizacion($arPago->getVrIngresoBaseCotizacion());
                            
                            $prestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->adicionalPrestacional($arPago->getCodigoPagoPk());
                            //Valor de las horas extra y los adicionales prestacionales
                            $prestacional += $arPago->getVrExtra();
                            //Valor de los adicionales no prestacionales
                            $noPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->adicionalNoPrestacional($arPago->getCodigoPagoPk());                                                        
                            
                            
                            $ibc = $arPago->getVrIngresoBaseCotizacion();
                            if($arContrato->getCodigoTipoTiempoFk() == 2) {
                                $ibc = $arPago->getVrSalarioEmpleado();
                            }
                            //Pension
                            $pensionEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->valorPensionPago($arPago->getCodigoPagoPk());                                    
                            $pension = round((($ibc * 16) / 100) - $pensionEmpleado);                                                            
                            $arServicio->setVrPension($pension);                                                        
                            
                            //Salud
                            $saludEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->valorSaludPago($arPago->getCodigoPagoPk());                                    
                            $salud = round(($ibc * 4) / 100); 
                            $salud -= $saludEmpleado;
                            $arServicio->setVrSalud($salud); 

                            
                            //Calculo de prestaciones
                            $cesantias = round(($ingresoBasePrestaciones * $porcentajeCesantias) / 100);
                            $arServicio->setVrCesantias($cesantias);
                            $arServicio->setPorcentajeCesantias($porcentajeCesantias);
                            $interesesCesantias = round(($ingresoBasePrestaciones * $porcentajeInteresesCesantias) / 100);
                            $arServicio->setVrCesantiasIntereses($interesesCesantias);
                            $arServicio->setPorcentajeInteresesCesantias($porcentajeInteresesCesantias);
                            $primas = round(($ingresoBasePrestaciones * $porcentajePrimas) / 100);
                            $arServicio->setvrPrimas($primas);
                            $arServicio->setPorcentajePrimas($porcentajePrimas);
                            $porcentajePrestaciones = $porcentajeCesantias + $porcentajePrimas + $porcentajeInteresesCesantias;
                            $arServicio->setPorcentajePrestaciones($porcentajePrestaciones);
                            $totalPrestaciones = $cesantias + $interesesCesantias + $primas;
                            $arServicio->setVrPrestaciones($totalPrestaciones);                                                        
                            
                            //Vacaciones
                            $salarioDescuento = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->valorSalarioDescuento($arPago->getCodigoPagoPk());                                                                
                            $recargoNorturno = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->recargoNocturnoPago($arPago->getCodigoPagoPk());
                            //$salarioVacaciones = ($arPago->getVrSalarioEmpleado() / 30) * $arPago->getDiasPeriodo();                            
                            $salarioVacaciones = ($arPago->getVrSalario() - $salarioDescuento);
                            $vacaciones = round((($salarioVacaciones + $recargoNorturno) * $porcentajeVacaciones) / 100);
                            $arServicio->setVrVacaciones($vacaciones);
                            $arServicio->setPorcentajeVacaciones($porcentajeVacaciones);
                            //Riesgos
                            $porcentajeRiesgos = $arPago->getContratoRel()->getClasificacionRiesgoRel()->getPorcentaje();
                            $riesgos = round(($ibc * $porcentajeRiesgos) / 100);
                            $arServicio->setPorcentajeRiesgos($porcentajeRiesgos);
                            $arServicio->setVrRiesgos($riesgos);                            
                            $caja = round(($ibc * $porcentajeCaja) / 100);
                            $arServicio->setVrCaja($caja);  
                            $arServicio->setPorcentajeCaja($porcentajeCaja);
                            $aporteParafiscales = round(($vacaciones * 4) / 100);                            
                            $arServicio->setVrAporteParafiscales($aporteParafiscales);   
                            

                            $arServicio->setVrPrestacional($prestacional);
                            $arServicio->setVrNoPrestacional($noPrestacional);
                            $operacion = ($salarioBasico + $prestacional + $noPrestacional + $auxilioTransporte + $riesgos + $pension + $caja + $cesantias + $interesesCesantias + $vacaciones + $primas + $aporteParafiscales);
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
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }             
        }       
                
        $arProgramacionPagos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarServicio:servicio.html.twig', array(
            'arProgramacionPagos' => $arProgramacionPagos,
            'form' => $form->createView()));
    }          
    
    /**
     * @Route("/rhu/proceso/generar/servicio/desgenerar", name="brs_rhu_proceso_generar_servicio_desgenerar")
     */    
    public function desgenerarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new Session; 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();       
        $form = $this->createFormBuilder()
            ->add('numero', NumberType::class, array('label'  => 'Numero'))
            ->add('BtnDesgenerar', SubmitType::class, array('label'  => 'Desgenerar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnDesgenerar')->isClicked()) {
                $codigoProgramacionPago = $form->get('numero')->getData();
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                if($arProgramacionPago) {
                    if($arProgramacionPago->getServicioGenerado() == 1) {
                        $arServiciosCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                        $arServiciosCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago, 'estadoCobrado' => 1));
                        if(!$arServiciosCobrar) {
                            $arServiciosCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                            $arServiciosCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));                            
                            foreach ($arServiciosCobrar as $arServicioCobrar) {
                                $arServicioCobrarEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($arServicioCobrar->getCodigoServicioCobrarPk());
                                $em->remove($arServicioCobrarEliminar);
                            }
                            $arProgramacionPago->setServicioGenerado(0);
                            $em->persist($arProgramacionPago);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        } else {
                           $objMensaje->Mensaje('error', 'Algunos de estos servicios ya tienen cobro y no se puede desgenerar la programacion', $this); 
                        }                           
                    } else {
                        $objMensaje->Mensaje('error', 'La programacion de pago no esta generada', $this);
                    }                 
                } else {
                    $objMensaje->Mensaje('error', 'La programacion de pago no existe', $this);
                }                               
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarServicio:desgenerar.html.twig', array(
            'form' => $form->createView()));
    }    
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $arrayPropiedadesCentroCosto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }   
        $arrayPropiedadesCliente = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroRhuCodigoCliente')) {
            $arrayPropiedadesCliente['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCliente", $session->get('filtroRhuCodigoCliente'));
        }         
        $form = $this->createFormBuilder()   
            ->add('clienteRel', EntityType::class, $arrayPropiedadesCliente)
            ->add('centroCostoRel', EntityType::class, $arrayPropiedadesCentroCosto)
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pendientesGenerarServicioDql(
                $session->get('filtroRhuCodigoCliente'),
                $session->get('filtroCodigoCentroCosto'));  
    }         
    
    private function filtrarLista($form, Request $request) {
        $session = $this->get('session'); 
        $codigoCentroCosto = '';
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }       
        $codigoCliente = '';
        if($form->get('clienteRel')->getData()) {
            $codigoCliente = $form->get('clienteRel')->getData()->getCodigoClientePk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);        
        $session->set('filtroRhuCodigoCliente', $codigoCliente);                       
    }     
    
}
