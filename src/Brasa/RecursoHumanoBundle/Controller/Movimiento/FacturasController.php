<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuFacturaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuNotaCreditoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuFacturaDetalleNuevoType;
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
            $arUsuario = $this->getUser();
            $arFactura->setUsuario($arUsuario->getUserName());
            $arFactura->setOperacion($arFactura->getFacturaTipoRel()->getOperacion());            
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
     * @Route("/rhu/facturas/nuevo/nota/credito/{codigoFactura}", name="brs_rhu_facturas_nuevo_nota_credito")
     */
    public function nuevoNotaCreditoAction(Request $request, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        if ($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        }
        else {
           $arFactura->setFecha(new \DateTime('now'));           
        }
        $form = $this->createForm(RhuNotaCreditoType::class, $arFactura);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arFactura = $form->getData(); 
            $arCliente = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
            $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($form->get('clienteRel')->getData());
            $diasPlazo = $arCliente->getPlazoPago() - 1;
            $fechaVence = date('Y-m-d', strtotime('+'.$diasPlazo.' day')) ;  
            $arFactura->setFechaVence(new \DateTime($fechaVence));
            $arFactura->setOperacion(1);
            $em->persist($arFactura);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_nuevo', array('codigoFactura' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:nuevoNotaCredito.html.twig', array(
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
                        if($arFacturaDetalleEliminar->getCodigoCobroFk()) {
                            $arCobro = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($arFacturaDetalleEliminar->getCodigoCobroFk());
                            $arCobro->setEstadoCobrado(0);
                            $em->persist($arCobro);                            
                        }
                        $em->remove($arFacturaDetalleEliminar);                        
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
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                        $objFactura = new \Brasa\RecursoHumanoBundle\Formatos\FormatoFactura();
                        $objFactura->Generar($em, $codigoFactura);                                                    
                    }
                    if ($arFactura->getFacturaTipoRel()->getTipo() == 2) {
                        $objNotaCredito = new \Brasa\RecursoHumanoBundle\Formatos\NotaCredito1();
                        $objNotaCredito->Generar($em, $codigoFactura);  
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }
            
            if($form->get('BtnVistaPrevia')->isClicked()) {          
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                if($arFactura->getFacturaTipoRel()->getTipo() == 1) {
                    if($arConfiguracion->getCodigoFormatoFactura() <= 1) {
                        $objFactura = new \Brasa\RecursoHumanoBundle\Formatos\FormatoFactura();
                        $objFactura->Generar($em, $codigoFactura);                            
                    }                    
                }                                 
                if($arFactura->getFacturaTipoRel()->getTipo() == 2) {
                    $objNotaCredito = new \Brasa\RecursoHumanoBundle\Formatos\NotaCredito1();
                    $objNotaCredito->Generar($em, $codigoFactura);                     
                }
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));                                                
            }
            if ($form->get('BtnDetalleExcel')->isClicked()) {                
                $this->generarDetalleExcel($codigoFactura);
            }
        }
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'form' => $form->createView(),
                    ));
    }
    
    /**
     * @Route("/rhu/movimiento/factura/detalle/nuevo/{codigoFactura}/{codigoFacturaDetalle}", name="brs_rhu_movimiento_factura_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoFactura, $codigoFacturaDetalle = 0) {
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        if ($codigoFacturaDetalle != 0) {
            $arFacturaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($codigoFacturaDetalle);
        } else {
            $arFacturaDetalle->setFacturaRel($arFactura);
        }
        $form = $this->createForm(RhuFacturaDetalleNuevoType::class, $arFacturaDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arFacturaDetalle = $form->getData();
                $arConceptoFactura = $form->get('facturaConceptoRel')->getData();
                $arFacturaDetalle->setPorIva($arConceptoFactura->getPorIva());
                $arFacturaDetalle->setPorBaseIva($arConceptoFactura->getPorBaseIva());                
                $arFacturaDetalle->setOperacion($arFactura->getOperacion());
                $em->persist($arFacturaDetalle);
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevo.html.twig', array(
                    'arFactura' => $arFactura,
                    'form' => $form->createView()));
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
                    foreach ($arrSeleccionados AS $codigo) {
                        $arCobro = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
                        $arCobro = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($codigo);
                        if($arCobro->getEstadoCobrado() == 0) {
                            $arFacturaConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaConcepto();
                            $arFacturaConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaConcepto')->find(1);
                            $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();                            
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setCobroRel($arCobro);   
                            $arFacturaDetalle->setFacturaConceptoRel($arFacturaConcepto);
                            $arFacturaDetalle->setCantidad($arCobro->getNumeroRegistros());
                            $arFacturaDetalle->setVrOperacion($arCobro->getVrOperacion());
                            $arFacturaDetalle->setVrAdministracion($arCobro->getVrAdministracion());
                            $precio = $arCobro->getVrTotalCobro() / $arCobro->getNumeroRegistros();
                            $arFacturaDetalle->setVrPrecio($precio);
                            $arFacturaDetalle->setVrSubtotal($arCobro->getVrTotalCobro());
                            $arFacturaDetalle->setPorIva($arFacturaConcepto->getPorIva());
                            $arFacturaDetalle->setPorBaseIva($arFacturaConcepto->getPorBaseIva());
                            $em->persist($arFacturaDetalle);                                                     
                            $arCobro->setEstadoCobrado(1);
                            $em->persist($arCobro);
                        }                        
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->pendienteCobrar($arFactura->getCodigoClienteFk()));        
        $arCobros = $paginator->paginate($query, $request->query->get('page', 1), 50);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoServicio.html.twig', array(
            'arCobros' => $arCobros,
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/movimiento/factura/detalle/factura/nuevo/{codigoFactura}/{tipoCruce}", name="brs_rhu_movimiento_factura_detalle_factura_nuevo")
     */
    public function detalleFacturaNuevoAction(Request $request, $codigoFactura, $tipoCruce) {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        $form = $this->createFormBuilder()
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar',))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnGuardar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();                            
                            $arFacturaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($codigo);
                            $arFacturaDetalleNueva = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
                            $arFacturaDetalleNueva->setFacturaRel($arFactura);
                            $arFacturaDetalleNueva->setFacturaConceptoRel($arFacturaDetalle->getFacturaConceptoRel());                                                                                    
                            $arFacturaDetalleNueva->setFacturaDetalleRel($arFacturaDetalle);
                            $arFacturaDetalleNueva->setCantidad($arFacturaDetalle->getCantidad());
                            $arFacturaDetalleNueva->setVrPrecio($arFacturaDetalle->getVrPrecio());
                            $arFacturaDetalleNueva->setPorIva($arFacturaDetalle->getPorIva());                            
                            $arFacturaDetalleNueva->setPorBaseIva($arFacturaDetalle->getFacturaConceptoRel()->getPorBaseIva());
                            $arFacturaDetalleNueva->setOperacion($arFactura->getOperacion());
                            $em->persist($arFacturaDetalleNueva);
                        }
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    //$this->filtrarDetalleNuevo($form);
                }
            }
        }
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->listaCliente($arFactura->getCodigoClienteFk(), "", $tipoCruce);
        $arFacturaDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 500);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Facturas:detalleNuevoFactura.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'form' => $form->createView()));
    }    
    
    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->listaDql(
                    $session->get('filtroCodigoCliente'),
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
        $arrBotonDetalleExcel = array('label' => 'Excel', 'disabled' => false);        
        $arrBotonVistaPrevia = array('label' => 'Vista previa', 'disabled' => false);
        $arrBotonDetalleEliminarDetalleServicio = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminarDetalleServicio['disabled'] = true;                        
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
                    ->add('BtnDetalleExcel', SubmitType::class, $arrBotonDetalleExcel)
                    ->add('BtnVistaPrevia', SubmitType::class, $arrBotonVistaPrevia)
                    ->add('BtnAnular', SubmitType::class, $arrBotonAnular)                                    
                    ->add('BtnEliminarDetalleServicio', SubmitType::class, $arrBotonDetalleEliminarDetalleServicio)            
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
        for($col = 'A'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                    
        } 
        for($col = 'G'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        } 
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
                    ->setCellValue('O1', 'COMENTARIOS');

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
                    ->setCellValue('E' . $i, $arFactura->getClienteRel()->getNombreCorto())
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
    
    private function generarDetalleExcel($codigoFactura = '') {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();        
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
        for($col = 'A'; $col !== 'W'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        } 
        for($col = 'E'; $col !== 'W'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }          
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'PAGO')
                    ->setCellValue('C1', 'DOCUMENTO')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'BASICO')
                    ->setCellValue('F1', 'VR ADICIONAL PREST')
                    ->setCellValue('G1', 'VR ADICIONAL NO PREST')
                    ->setCellValue('H1', 'AUX TRANSPORTE')
                    ->setCellValue('I1', 'ARP')
                    ->setCellValue('J1', 'EPS')
                    ->setCellValue('K1', 'PENSION')
                    ->setCellValue('L1', 'CAJA')
                    ->setCellValue('M1', 'SENA')
                    ->setCellValue('N1', 'ICBF')
                    ->setCellValue('O1', 'CESANTIAS')
                    ->setCellValue('P1', 'CESANTIAS INT')
                    ->setCellValue('Q1', 'PRIMAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'A. PARAFISCALES')
                    ->setCellValue('T1', 'ADMON')
                    ->setCellValue('U1', 'COSTO')
                    ->setCellValue('V1', 'TOTAL COBRO');
                    
        $i = 2;
        //$query = $em->createQuery($this->strSqlLista);
        $arFacturaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));
        //$arPagoBancos = $query->getResult();
        foreach ($arFacturaDetalle as $arFacturaDetalle) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFacturaDetalle->getCodigoFacturaDetallePk())
                    ->setCellValue('B' . $i, $arFacturaDetalle->getCodigoPagoFk())
                    ->setCellValue('C' . $i, $arFacturaDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arFacturaDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arFacturaDetalle->getVrSalario())
                    ->setCellValue('F' . $i, $arFacturaDetalle->getVrAdicionalPrestacional())
                    ->setCellValue('G' . $i, $arFacturaDetalle->getVrAdicionalNoPrestacional())
                    ->setCellValue('H' . $i, $arFacturaDetalle->getVrAuxilioTransporte())
                    ->setCellValue('I' . $i, $arFacturaDetalle->getVrArp())
                    ->setCellValue('J' . $i, $arFacturaDetalle->getVrEps())
                    ->setCellValue('K' . $i, $arFacturaDetalle->getVrPension())
                    ->setCellValue('L' . $i, $arFacturaDetalle->getVrCaja())
                    ->setCellValue('M' . $i, $arFacturaDetalle->getVrSena())
                    ->setCellValue('N' . $i, $arFacturaDetalle->getVrIcbf())
                    ->setCellValue('O' . $i, $arFacturaDetalle->getVrCesantias())
                    ->setCellValue('P' . $i, $arFacturaDetalle->getVrCesantiasIntereses())
                    ->setCellValue('Q' . $i, $arFacturaDetalle->getVrPrimas())
                    ->setCellValue('R' . $i, $arFacturaDetalle->getVrVacaciones())
                    ->setCellValue('S' . $i, $arFacturaDetalle->getVrAporteParafiscales())
                    ->setCellValue('T' . $i, $arFacturaDetalle->getVrAdministracion())
                    ->setCellValue('U' . $i, $arFacturaDetalle->getVrCosto())
                    ->setCellValue('V' . $i, $arFacturaDetalle->getVrTotalCobrar());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('FacturaDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="FacturaDetalles.xlsx"');
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
