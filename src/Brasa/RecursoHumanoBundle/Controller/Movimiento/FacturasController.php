<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuFacturaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FacturasController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/facturas/lista", name="brs_rhu_facturas_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 16, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFactura) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
                        $arFacturasDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->devuelveNumeroFacturasDetalle($codigoFactura);    
                        if($arFacturasDetalle == 0){
                            $em->remove($arSelecciones);
                            $em->flush();
                        }
                        else {
                            $objMensaje->Mensaje("error", "No se puede eliminar la factura, tiene registros liquidados");
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_lista'));    
                }
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arFacturas = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:lista.html.twig', array('arFacturas' => $arFacturas, 'form' => $form->createView()));
    }       
    
    /**
     * @Route("/rhu/facturas/nuevo/{codigoFactura}", name="brs_rhu_facturas_nuevo")
     */
    public function nuevoAction(Request $request, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        if ($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        }
        else {
           $arFactura->setFecha(new \DateTime('now'));           
        }
        $form = $this->createForm(RhuFacturaType::class, $arFactura);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arFactura = $form->getData(); 
            $arCliente = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
            $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($form->get('clienteRel')->getData());
            $diasPlazo = $arCliente->getPlazoPago() - 1;
            $fechaVence = date('Y-m-d', strtotime('+'.$diasPlazo.' day')) ;  
            $arFactura->setFechaVence(new \DateTime($fechaVence));
            $em->persist($arFactura);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_nuevo', array('codigoFactura' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/facturas/detalle/{codigoFactura}", name="brs_rhu_facturas_detalle")
     */
    public function detalleAction(Request $request, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        $form = $this->formularioDetalle($arFactura);                               
        $form->handleRequest($request);        
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAutorizar')->isClicked()) {                      
                //$this->actualizarDetalle($arrControles, $codigoFactura);
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->autorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                }
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));                                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->desAutorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                }
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));                                
            }
            if($form->get('BtnEliminarDetalleServicio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarServicio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFacturaDetalle) {
                        $arFacturaDetalleEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($codigoFacturaDetalle);
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($arFacturaDetalleEliminar->getCodigoServicioCobrarFk());
                        $arServicioCobrar->setEstadoCobrado(0);
                        $em->persist($arServicioCobrar);
                        $em->remove($arFacturaDetalleEliminar);                        
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }
            if($form->get('BtnEliminarDetalleExamen')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarExamen');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamen) {
                        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
                        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                        $arExamen->setEstadoCobrado(0);
                        $arExamen->setFacturaRel(NULL);                                                                        
                        $em->persist($arExamen);                       
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }            
            if($form->get('BtnEliminarDetalleSeleccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarSeleccion');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccion) {
                        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                        $arSeleccion->setEstadoCobrado(0);
                        $arSeleccion->setFacturaRel(NULL);                                                                        
                        $em->persist($arSeleccion);                       
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }             
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->imprimir($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                } else {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    //if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        if($arConfiguracion->getCodigoFormatoFactura() <= 1) {
                            $objFactura = new \Brasa\RecursoHumanoBundle\Formatos\Factura1();
                            $objFactura->Generar($em, $codigoFactura);                            
                        }                        
                    //}                                         
                }
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }
            
            if($form->get('BtnVistaPrevia')->isClicked()) {          
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                //if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                    if($arConfiguracion->getCodigoFormatoFactura() <= 1) {
                        $objFactura = new \Brasa\RecursoHumanoBundle\Formatos\Factura1();
                        $objFactura->Generar($em, $codigoFactura);                            
                    }                    
                //}                                 
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }                   
        }
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));        
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoFacturaFk' => $codigoFactura));                
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoFacturaFk' => $codigoFactura));                        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'arExamenes' => $arExamenes,
                    'arSelecciones' => $arSelecciones,
                    'form' => $form->createView(),
                    ));
    }
    
    /**
     * @Route("/rhu/facturas/detalle/nuevo/servicio/{codigoFactura}", name="brs_rhu_facturas_detalle_nuevo_servicio")
     */
    public function detalleNuevoServicioAction(Request $request, $codigoFactura) {
        
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', SubmitType::class, array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoServicioCobrar) {
                        $arServicioCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($codigoServicioCobrar);
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arServicioCobrar->getCodigoPagoFk());
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arServicioCobrar->getCodigoCentroCostoFk());
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($arServicioCobrar->getCodigoProgramacionPagoFk());
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arServicioCobrar->getCodigoEmpleadoFk());
                        if($arServicioCobrar->getEstadoCobrado() == 0) {
                            $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                            $arFacturaDetalle->setPagoRel($arPago);
                            $arFacturaDetalle->setEmpleadoRel($arEmpleado);
                            $arFacturaDetalle->setCentroCostoRel($arCentroCosto);
                            $arFacturaDetalle->setProgramacionPagoRel($arProgramacionPago);
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setServicioCobrarRel($arServicioCobrar);
                            $arFacturaDetalle->setFechaDesde($arServicioCobrar->getFechaDesde());
                            $arFacturaDetalle->setFechaHasta($arServicioCobrar->getFechaHasta());
                            $arFacturaDetalle->setVrSalario($arServicioCobrar->getVrSalario());
                            $arFacturaDetalle->setVrSalarioPeriodo($arServicioCobrar->getVrSalarioPeriodo());
                            $arFacturaDetalle->setVrSalarioEmpleado($arServicioCobrar->getVrSalarioEmpleado());
                            $arFacturaDetalle->setVrDevengado($arServicioCobrar->getVrDevengado());
                            $arFacturaDetalle->setVrDeducciones($arServicioCobrar->getVrDeducciones());
                            $arFacturaDetalle->setVrAdicionalTiempo($arServicioCobrar->getVrAdicionalTiempo());
                            $arFacturaDetalle->setVrAdicionalValor($arServicioCobrar->getVrAdicionalValor());
                            $arFacturaDetalle->setVrAuxilioTransporte($arServicioCobrar->getVrAuxilioTransporte());
                            $arFacturaDetalle->setVrAuxilioTransporteCotizacion($arServicioCobrar->getVrAuxilioTransporteCotizacion());
                            $arFacturaDetalle->setVrArp($arServicioCobrar->getVrArp());
                            $arFacturaDetalle->setVrEps($arServicioCobrar->getVrEps());
                            $arFacturaDetalle->setVrPension($arServicioCobrar->getVrPension());
                            $arFacturaDetalle->setVrCaja($arServicioCobrar->getVrCaja());
                            $arFacturaDetalle->setVrSena($arServicioCobrar->getVrSena());
                            $arFacturaDetalle->setVrIcbf($arServicioCobrar->getVrIcbf());
                            $arFacturaDetalle->setVrCesantias($arServicioCobrar->getVrCesantias());
                            $arFacturaDetalle->setVrCesantiasIntereses($arServicioCobrar->getVrCesantiasIntereses());
                            $arFacturaDetalle->setVrPrimas($arServicioCobrar->getVrPrimas());
                            $arFacturaDetalle->setVrVacaciones($arServicioCobrar->getVrVacaciones());
                            $arFacturaDetalle->setVrAporteParafiscales($arServicioCobrar->getVrAporteParafiscales());
                            $arFacturaDetalle->setVrAdicionalPrestacional($arServicioCobrar->getVrAdicionalPrestacional());
                            $arFacturaDetalle->setVrAdicionalNoPrestacional($arServicioCobrar->getVrAdicionalNoPrestacional());
                            $arFacturaDetalle->setVrAdministracion($arServicioCobrar->getVrAdministracion());
                            $arFacturaDetalle->setVrNeto($arServicioCobrar->getVrNeto());
                            $arFacturaDetalle->setVrBruto($arServicioCobrar->getVrBruto());
                            $arFacturaDetalle->setVrTotalCobrar($arServicioCobrar->getVrTotalCobrar());
                            $arFacturaDetalle->setVrCosto($arServicioCobrar->getVrCosto());
                            $arFacturaDetalle->setVrIngresoBaseCotizacion($arServicioCobrar->getVrIngresoBaseCotizacion());
                            $arFacturaDetalle->setEstadoCobrado($arServicioCobrar->getEstadoCobrado());
                            $arFacturaDetalle->setDiasPeriodo($arServicioCobrar->getDiasPeriodo());
                            $em->persist($arFacturaDetalle);                                                     
                            $arServicioCobrar->setEstadoCobrado(1);
                            $em->persist($arServicioCobrar);
                        }                        
                    }                    

                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arServiciosCobrar = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoServicio.html.twig', array(
            'arServiciosCobrar' => $arServiciosCobrar,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/facturas/detalle/nuevo/examen/{codigoFactura}", name="brs_rhu_facturas_detalle_nuevo_examen")
     */
    public function detalleNuevoExamenAction(Request $request, $codigoFactura) {
        
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', SubmitType::class, array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamen) {
                        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
                        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                        if($arExamen->getEstadoCobrado() == 0) {
                            $arExamen->setFacturaRel($arFactura);
                            $arExamen->setEstadoCobrado(1);
                            $em->persist($arExamen);
                        }                                                
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arExamenes = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoExamen.html.twig', array(
            'arExamenes' => $arExamenes,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/rhu/facturas/detalle/nuevo/seleccion/{codigoFactura}", name="brs_rhu_facturas_detalle_nuevo_seleccion")
     */
    public function detalleNuevoSeleccionAction(Request $request, $codigoFactura) {
        
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', SubmitType::class, array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccion) {
                        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                        if($arSeleccion->getEstadoCobrado() == 0) {
                            $arSeleccion->setFacturaRel($arFactura);
                            $arSeleccion->setEstadoCobrado(1);
                            $em->persist($arSeleccion);
                        }                                                
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->pendienteCobrar($arFactura->getCodigoCentroCostoFk()));        
        $arSelecciones = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoSeleccion.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }            
    
    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->listaDql(
                    $session->get('filtroCodigoTerceros'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroNumero'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function filtrar($form) {
        $session = new session;                
        $codigoCliente = '';
        if($form->get('clienteRel')->getData()) {
            $codigoCliente = $form->get('clienteRel')->getData()->getCodigoClientePk();
        }        
        $session->set('filtroCodigoCliente', $codigoCliente);
        $codigoCentroCosto = '';
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto); 
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());
               
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
        
        $arrayPropiedadesClientes = array(
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
        if($session->get('filtroCodigoCliente')) {
            $arrayPropiedadesClientes['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCliente", $session->get('filtroCodigoCliente'));
        }
        
        $form = $this->createFormBuilder()
            ->add('clienteRel', EntityType::class, $arrayPropiedadesClientes)
            ->add('centroCostoRel', EntityType::class, $arrayPropiedadesCentroCosto)
            ->add('TxtNumero', TextType::class, array('label'  => 'Numero','data' => $session->get('filtroNumero')))
            ->add('fechaDesde',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonVistaPrevia = array('label' => 'Vista previa', 'disabled' => false);
        $arrBotonDetalleEliminarDetalleServicio = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleEliminarDetalleExamen = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleEliminarDetalleSeleccion = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminarDetalleServicio['disabled'] = true;            
            $arrBotonDetalleEliminarDetalleExamen['disabled'] = true;
            $arrBotonDetalleEliminarDetalleSeleccion['disabled'] = true;
            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)                    
                    ->add('BtnVistaPrevia', SubmitType::class, $arrBotonVistaPrevia)
                    ->add('BtnAnular', SubmitType::class, $arrBotonAnular)                                    
                    ->add('BtnEliminarDetalleServicio', SubmitType::class, $arrBotonDetalleEliminarDetalleServicio)            
                    ->add('BtnEliminarDetalleExamen', SubmitType::class, $arrBotonDetalleEliminarDetalleExamen)            
                    ->add('BtnEliminarDetalleSeleccion', SubmitType::class, $arrBotonDetalleEliminarDetalleSeleccion)                    
                    ->getForm();                                 
        return $form;
    }
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO FACTURA')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'FECHA VENCE')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'VR. BRUTO')
                    ->setCellValue('H1', 'VR. NETO')
                    ->setCellValue('I1', 'VR. RETENCION FUENTE')
                    ->setCellValue('J1', 'VR. RETENCION CREE')
                    ->setCellValue('K1', 'VR. RETENCION IVA')
                    ->setCellValue('L1', 'VR. BASE AIU')
                    ->setCellValue('M1', 'VR. TOTAL ADMNISTRACION')
                    ->setCellValue('N1', 'VR. TOTAL INGRESO MISION')
                    ->setCellValue('O1', 'VR. COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arFacturas = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFacturas = $query->getResult();
        foreach ($arFacturas as $arFactura) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNumero())
                    ->setCellValue('C' . $i, $arFactura->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFactura->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arFactura->getTerceroRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arFactura->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arFactura->getVrBruto())
                    ->setCellValue('H' . $i, $arFactura->getVrNeto())
                    ->setCellValue('I' . $i, $arFactura->getVrRetencionFuente())
                    ->setCellValue('J' . $i, $arFactura->getVrRetencionCree())
                    ->setCellValue('K' . $i, $arFactura->getVrRetencionIva())
                    ->setCellValue('L' . $i, $arFactura->getVrBaseAIU())
                    ->setCellValue('M' . $i, $arFactura->getVrTotalAdministracion())
                    ->setCellValue('N' . $i, $arFactura->getVrIngresoMision())
                    ->setCellValue('O' . $i, $arFactura->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="facturas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
}
