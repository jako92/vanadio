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
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContabilizarVacacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/contabilizar/vacacion/", name="brs_rhu_proceso_contabilizar_vacacion")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 69)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnContabilizar')->isClicked()) { 
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();                    
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arComprobanteContable = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();                    
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find($arConfiguracion->getCodigoComprobanteVacacion());
                    //Cuentas
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(7);
                    $codigoCuentaPagadas = $arCuenta->getCodigoCuentaFk();                    
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(8);
                    $codigoCuentaDisfrutadas = $arCuenta->getCodigoCuentaFk(); 
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(9);
                    $codigoCuentaSalud = $arCuenta->getCodigoCuentaFk();                                        
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(10);
                    $codigoCuentaPension = $arCuenta->getCodigoCuentaFk(); 
                    $arCuenta = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(12);
                    $codigoCuentaVacacion = $arCuenta->getCodigoCuentaFk();                                        
                    foreach ($arrSeleccionados AS $codigo) {                                     
                        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigo);
                        $arSucursal = $arVacacion->getEmpleadoRel()->getSucursalRel();
                        $area = $arVacacion->getEmpleadoRel()->getEmpleadoTipoRel()->getInterfaz();                        
                        if($arVacacion->getEstadoContabilizado() == 0) {
                            $arCentroCosto = $arVacacion->getEmpleadoRel()->getCentroCostoContabilidadRel();                                                       
                            $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arVacacion->getEmpleadoRel()->getNumeroIdentificacion()));                            
                            if(!$arTercero) {
                                $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                                $arTercero->setCiudadRel($arVacacion->getEmpleadoRel()->getCiudadRel());
                                $arTercero->setTipoIdentificacionRel($arVacacion->getEmpleadoRel()->getTipoIdentificacionRel());
                                $arTercero->setNumeroIdentificacion($arVacacion->getEmpleadoRel()->getNumeroIdentificacion());
                                $arTercero->setNombreCorto($arVacacion->getEmpleadoRel()->getNombreCorto());
                                $arTercero->setNombre1($arVacacion->getEmpleadoRel()->getNombre1());
                                $arTercero->setNombre2($arVacacion->getEmpleadoRel()->getNombre2());
                                $arTercero->setApellido1($arVacacion->getEmpleadoRel()->getApellido1());
                                $arTercero->setApellido2($arVacacion->getEmpleadoRel()->getApellido2());
                                $arTercero->setDireccion($arVacacion->getEmpleadoRel()->getDireccion());
                                $arTercero->setTelefono($arVacacion->getEmpleadoRel()->getTelefono());
                                $arTercero->setCelular($arVacacion->getEmpleadoRel()->getCelular());
                                $arTercero->setEmail($arVacacion->getEmpleadoRel()->getCorreo());
                                $em->persist($arTercero);                                 
                            }         
                            
                            //Vacaciones
                            if($arVacacion->getVrVacacionBruto() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaDisfrutadas); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setDebito($arVacacion->getVrVacacionBruto());                            
                                    $arRegistro->setDescripcionContable('VACACIONES');
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $em->persist($arRegistro);
                                }             
                            }

                            //Pension
                            if($arVacacion->getVrPension() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaPension); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setCredito($arVacacion->getVrPension());                            
                                    $arRegistro->setDescripcionContable('PENSION');
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $em->persist($arRegistro);
                                }             
                            }                            
                            
                            //Salud
                            if($arVacacion->getVrSalud() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaSalud); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    //$arRegistro->setCentroCostoRel($arCentroCosto);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setCredito($arVacacion->getVrSalud());                            
                                    $arRegistro->setDescripcionContable('SALUD');
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $em->persist($arRegistro);
                                }             
                            }               
                            
                            //Adicionales
                            $arVacacionAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                            $arVacacionAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->findBy(array('codigoVacacionFk' => $codigo));
                            foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($arVacacionAdicional->getPagoConceptoRel()->getCodigoCuentaFk()); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);
                                    if($arCuenta->getExigeCentroCostos()) {
                                        $arRegistro->setCentroCostoRel($arCentroCosto);                                        
                                    }                                     
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha()); 
                                    if($arVacacionAdicional->getVrBonificacion() > 0) {
                                        $arRegistro->setDebito($arVacacionAdicional->getVrBonificacion());    
                                    } else {
                                        $arRegistro->setCredito($arVacacionAdicional->getVrDeduccion());
                                    }                
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $arRegistro->setDescripcionContable($arVacacionAdicional->getPagoConceptoRel()->getNombre());
                                    $em->persist($arRegistro);
                                }                                    
                            }                                                                                                                
                            
                            //Vacaciones por pagar
                            if($arVacacion->getVrVacacion() >  0) {
                                $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuentaVacacion); 
                                if($arCuenta) {
                                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();                            
                                    $arRegistro->setComprobanteRel($arComprobanteContable);                                    
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setNumero($arVacacion->getNumero());
                                    $arRegistro->setNumeroReferencia($arVacacion->getCodigoVacacionPk());
                                    $arRegistro->setFecha($arVacacion->getFecha());
                                    $arRegistro->setCredito($arVacacion->getVrVacacion());                            
                                    $arRegistro->setDescripcionContable('VACACIONES POR PAGAR');
                                    $arRegistro->setSucursalRel($arSucursal);
                                    $arRegistro->setCodigoAreaFk($area);                                    
                                    $em->persist($arRegistro);
                                }             
                            }                            
                            
                            $arVacacion->setEstadoContabilizado(1);                                
                            $em->persist($arVacacion);                                 
                                                         
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_proceso_contabilizar_vacacion'));
            }   
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->formularioLista();
                $this->listar();
            }             
        }       
                
        $arVacaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);                               
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:vacacion.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');        
        $strNombreEmpleado = "";
        if ($session->get('filtroRhuIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroRhuIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            } else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }                
        $form = $this->createFormBuilder()   
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('TxtNumero', TextType::class, array('label' => 'Numero', 'data' => $session->get('filtroRhuVacacionNumero')))
            ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroRhuIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))                
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))                
            ->add('BtnContabilizar', SubmitType::class, array('label'  => 'Contabilizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();     
        $session = $this->get('session');
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->pendientesContabilizarDql(
                $session->get('filtroRhuVacacionNumero'),  
                $session->get('filtroRhuCodigoEmpleado'), 
                $session->get('filtroDesde'), 
                $session->get('filtroHasta')                
                );  
    }                 
    
    private function filtrarLista($form, Request $request) {
        $session = $this->get('session');
        $session->set('filtroRhuIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuVacacionNumero', $form->get('TxtNumero')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaHasta')->getData() == null) {
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
    }              
    
    /**
     * @Route("/rhu/proceso/descontabilizar/vacacion/", name="brs_rhu_proceso_descontabilizar_vacacion")
     */    
    public function descontabilizarVacacionAction() {
        $em = $this->getDoctrine()->getManager();        
        $session = new Session; 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('numeroDesde', NumberType::class, array('label'  => 'Numero desde'))
            ->add('numeroHasta', NumberType::class, array('label'  => 'Numero hasta'))
            ->add('fechaDesde',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                            
            ->add('BtnDescontabilizar', SubmitType::class, array('label'  => 'Descontabilizar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnDescontabilizar')->isClicked()) {
                $intNumeroDesde = $form->get('numeroDesde')->getData();
                $intNumeroHasta = $form->get('numeroHasta')->getData();
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                if($intNumeroDesde != "" || $intNumeroHasta != "" || $dateFechaDesde != "" || $dateFechaHasta != "") {
                    $arRegistros = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                    $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->contabilizadosDql($intNumeroDesde,$intNumeroHasta,$dateFechaDesde,$dateFechaHasta);  
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arRegistro = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoRegistro);
                        $arRegistro->setEstadoContabilizado(0);                                                    
                        $em->persist($arRegistro);    
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', 'Debe seleccionar un filtro', $this);
                }                               
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/Contabilizar:descontabilizarVacacion.html.twig', array(
            'form' => $form->createView()));
    } 
    
}
