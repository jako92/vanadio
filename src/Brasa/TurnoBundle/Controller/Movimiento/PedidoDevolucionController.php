<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurPedidoDevolucionType;
use PHPExcel_Style_Border;

class PedidoDevolucionController extends Controller
{
    var $strListaDql = "";   
    
    /**
     * @Route("/tur/movimiento/pedido/devolucion/", name="brs_tur_movimiento_pedido_devolucion")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 27, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {             
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedido')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido'));                 
                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }
        $arPedidosDevolucion = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Movimientos/PedidoDevolucion:lista.html.twig', array(
            'arPedidosDevolucion' => $arPedidosDevolucion,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/pedido/devolucion/nuevo/{codigoPedidoDevolucion}", name="brs_tur_movimiento_pedido_devolucion_nuevo")
     */     
    public function nuevoAction(Request $request, $codigoPedidoDevolucion) {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();        
        $arPedidoDevolucion = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();        
        if($codigoPedidoDevolucion != 0) {
            $arPedidoDevolucion = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->find($codigoPedidoDevolucion);            
        }else{
            $arPedidoDevolucion->setFecha(new \DateTime('now'));            
        }
        $form = $this->createForm(TurPedidoDevolucionType::class, $arPedidoDevolucion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPedido = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arPedido->setClienteRel($arCliente);                    
                    if($codigoPedido != 0){
                        $numeroRegistros = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->numeroRegistros($codigoPedido);
                        if($numeroRegistros <= 0) {
                            $fechaProgramacion = $arPedido->getFechaProgramacion()->format('Y/m/');
                            $arPedido->setFechaProgramacion(date_create($fechaProgramacion . '01'));                            
                        } else {
                            $arPedido->setFechaProgramacion($fechaOriginal);
                            $objMensaje->Mensaje("error", "No se actualizo la fecha del pedido porque tiene detalles, debe eliminarlos antes de cambiar la fecha");
                        }
                    } else {
                        $fechaProgramacion = $arPedido->getFechaProgramacion()->format('Y/m/');
                        $arPedido->setFechaProgramacion(date_create($fechaProgramacion . '01'));                                                
                    }
                    
                    $arUsuario = $this->getUser();
                    $arPedido->setUsuario($arUsuario->getUserName());
                    $em->persist($arPedido);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_nuevo', array('codigoPedido' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $arPedido->getCodigoPedidoPk())));
                    }                       
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe");
                }                             
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/PedidoDevolucion:nuevo.html.twig', array(
            'arPedidoDevolucion' => $arPedidoDevolucion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/pedido/detalle/{codigoPedido}", name="brs_tur_movimiento_pedido_detalle")
     */     
    public function detalleAction(Request $request, $codigoPedido) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arPedido = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedido = $em->getRepository('BrasaTurnoBundle:TurPedido')->find($codigoPedido);
        $form = $this->formularioDetalle($arPedido);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) { 
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoPedido);                
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurPedido')->autorizar($codigoPedido);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
            }    
            if($form->get('BtnAnular')->isClicked()) {                                 
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurPedido')->anular($codigoPedido);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
            }            
            
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arPedido->getEstadoAutorizado() == 1) {
                    $arPedido->setEstadoAutorizado(0);
                    $em->persist($arPedido);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
                }
            } 
            if($form->get('BtnProgramar')->isClicked()) {            
                if($arPedido->getEstadoProgramado() == 0 && $arPedido->getEstadoAutorizado() == 1) {                    
                    $codigoProgramacion = $this->programar($codigoPedido);
                    if($codigoProgramacion != 0) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                                        
                    }                    
                }
            }    
            if($form->get('BtnFacturar')->isClicked()) {            
                if($arPedido->getEstadoFacturado() == 0 && $arPedido->getEstadoAutorizado() == 1) {                    
                    $codigoFactura = $em->getRepository('BrasaTurnoBundle:TurPedido')->facturar($codigoPedido,  $this->getUser()->getUsername());
                    if($codigoFactura != 0) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                                        
                    }                    
                }
            }            
            if($form->get('BtnDesprogramar')->isClicked()) {            
                if($arPedido->getEstadoProgramado() == 1) {
                    $arPedido->setEstadoProgramado(0);
                    $em->persist($arPedido);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
                }
            }       
            if($form->get('BtnDetalleMarcar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->marcarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
            } 
            if($form->get('BtnDetalleAjuste')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->ajustarSeleccionados($arrSeleccionados);                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));                
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {                
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoPedido);                                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }
            if($form->get('BtnDetalleExcel')->isClicked()) {                
                $this->generarExcelDetalle($codigoPedido);
            }            
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }
            if($form->get('BtnDetalleDesprogramar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados AS $codigo) {                
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                
                    $arPedidoDetalle->setEstadoProgramado(0);
                    $em->persist($arPedidoDetalle);                  
                }                                         
                $em->flush();  
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }         
            if($form->get('BtnDetalleConceptoActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalleConcepto($arrControles, $codigoPedido);                                 
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }            
            if($form->get('BtnDetalleConceptoEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionarPedidoConcepto');
                $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurPedido')->liquidar($codigoPedido);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_detalle', array('codigoPedido' => $codigoPedido)));
            }            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arPedido->getEstadoAutorizado() == 1) {
                    $objPedido = new \Brasa\TurnoBundle\Formatos\FormatoPedido();
                    $objPedido->Generar($em, $codigoPedido);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una cotizacion sin estar autorizada");
                }
            }  
            
        }

        //$arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        //$arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->findBy(array ('codigoPedidoFk' => $codigoPedido));
        
        $dql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaDql($codigoPedido);       
        $arPedidoDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);
        $dql = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalleConcepto')->listaDql($codigoPedido);       
        $arPedidoDetalleConceptos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);                
        return $this->render('BrasaTurnoBundle:Movimientos/Pedido:detalle.html.twig', array(
                    'arPedido' => $arPedido,
                    'arPedidoDetalle' => $arPedidoDetalle,
                    'arPedidoDetalleConceptos' => $arPedidoDetalleConceptos,
                    'form' => $form->createView()
                    ));
    }
      
    
    private function lista() {   
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroPedidoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');                    
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->listaDQL(
                $session->get('filtroPedidoNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroPedidoEstadoAutorizado'), 
                $session->get('filtroPedidoEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta);
    }      
    
    private function filtrar ($form) {
        $session = new session;    
        $session->set('filtroPedidoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPedidoEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroPedidoEstadoProgramado', $form->get('estadoProgramado')->getData());          
        $session->set('filtroPedidoEstadoFacturado', $form->get('estadoFacturado')->getData());          
        $session->set('filtroPedidoEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroPedidoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroPedidoFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroPedidoFiltrarFecha', $form->get('filtrarFecha')->getData());
        
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroPedidoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroPedidoFechaDesde');
        }
        if($session->get('filtroPedidoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroPedidoFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroPedidoEstadoAutorizado')))                
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0'), 'data' => $session->get('filtroPedidoEstadoAnulado')))                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroPedidoFiltrarFecha')))                 
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonProgramar = array('label' => 'Programar', 'disabled' => true);        
        $arrBotonFacturar = array('label' => 'Facturar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleExcel = array('label' => 'Excel', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleDesprogramar = array('label' => 'Desprogramar', 'disabled' => false);
        $arrBotonDetalleMarcar = array('label' => 'Marcar', 'disabled' => false);        
        $arrBotonDetalleAjuste = array('label' => 'Ajuste', 'disabled' => false);                
        $arrBotonDesprogramar = array('label' => 'Desprogramar', 'disabled' => true);        
        $arrBotonDetalleConceptoActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleConceptoEliminar = array('label' => 'Eliminar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonProgramar['disabled'] = false;
            $arrBotonAnular['disabled'] = false; 
            $arrBotonDetalleConceptoActualizar['disabled'] = true;
            $arrBotonDetalleConceptoEliminar['disabled'] = true;            
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;                
                $arrBotonDetalleDesprogramar['disabled'] = true;
            } else {
                if($ar->getEstadoFacturado() == 0) {
                    $arrBotonFacturar['disabled'] = false;
                }
            }
        } else {            
            $arrBotonDesAutorizar['disabled'] = true;                        
            $arrBotonImprimir['disabled'] = true;            
        }
        if($ar->getEstadoAprobado() == 1 && $ar->getEstadoAnulado() == 0) {
            $arrBotonDesAutorizar['disabled'] = true;                        
        } 
        if($ar->getEstadoProgramado() == 1 && $ar->getEstadoAnulado() == 0) {
            $arrBotonDesprogramar['disabled'] = false;
            $arrBotonProgramar['disabled'] = true; 
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnFacturar', SubmitType::class, $arrBotonFacturar)                
                    ->add('BtnProgramar', SubmitType::class, $arrBotonProgramar)            
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                 
                    ->add('BtnAnular', SubmitType::class, $arrBotonAnular)                                     
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleExcel', SubmitType::class, $arrBotonDetalleExcel)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnDetalleDesprogramar', SubmitType::class, $arrBotonDetalleDesprogramar)
                    ->add('BtnDetalleMarcar', SubmitType::class, $arrBotonDetalleMarcar)
                    ->add('BtnDetalleAjuste', SubmitType::class, $arrBotonDetalleAjuste)                
                    ->add('BtnDesprogramar', SubmitType::class, $arrBotonDesprogramar)  
                    ->add('BtnDetalleConceptoActualizar', SubmitType::class, $arrBotonDetalleConceptoActualizar)
                    ->add('BtnDetalleConceptoEliminar', SubmitType::class, $arrBotonDetalleConceptoEliminar)                                
                    ->getForm();
        return $form;
    }       
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'M'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NÚMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'AÑO')
                    ->setCellValue('F1', 'MES')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'SECTOR')
                    ->setCellValue('I1', 'AUT')
                    ->setCellValue('J1', 'PRO')
                    ->setCellValue('K1', 'FAC')
                    ->setCellValue('L1', 'ANU')
                    ->setCellValue('M1', 'HORAS')
                    ->setCellValue('N1', 'H.DIURNAS')
                    ->setCellValue('O1', 'H.NOCTURNAS')
                    ->setCellValue('P1', 'P.MINIMO')
                    ->setCellValue('Q1', 'P.AJUSTADO')
                    ->setCellValue('R1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidos = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedidos = $query->getResult();

        foreach ($arPedidos as $arPedido) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedido->getCodigoPedidoPk())
                    ->setCellValue('B' . $i, $arPedido->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedido->getNumero())
                    ->setCellValue('D' . $i, $arPedido->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPedido->getFechaProgramacion()->format('Y'))
                    ->setCellValue('F' . $i, $arPedido->getFechaProgramacion()->format('F'))                    
                    ->setCellValue('G' . $i, $arPedido->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arPedido->getSectorRel()->getNombre())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoProgramado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoFacturado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arPedido->getEstadoAnulado()))
                    ->setCellValue('M' . $i, $arPedido->getHoras())
                    ->setCellValue('N' . $i, $arPedido->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arPedido->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arPedido->getVrTotalPrecioMinimo())
                    ->setCellValue('Q' . $i, $arPedido->getVrTotalPrecioAjustado())
                    ->setCellValue('R' . $i, $arPedido->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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