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
            $arPedidoDevolucion = $form->getData();            
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arPedidoDevolucion->setClienteRel($arCliente);                                        
                    $arUsuario = $this->getUser();
                    $arPedidoDevolucion->setUsuario($arUsuario->getUserName());
                    $em->persist($arPedidoDevolucion);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_devolucion_nuevo', array('codigoPedidoDevolucion' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_devolucion_detalle', array('codigoPedidoDevolucion' => $arPedidoDevolucion->getCodigoPedidoDevolucionPk())));
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
     * @Route("/tur/movimiento/pedido/devolucion/detalle/{codigoPedidoDevolucion}", name="brs_tur_movimiento_pedido_devolucion_detalle")
     */     
    public function detalleAction(Request $request, $codigoPedidoDevolucion) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arPedidoDevolucion = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();
        $arPedidoDevolucion = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->find($codigoPedidoDevolucion);
        $form = $this->formularioDetalle($arPedidoDevolucion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) { 
                if($arPedidoDevolucion->getEstadoAutorizado() == 0) {
                    $strResultado = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->autorizar($codigoPedidoDevolucion);
                    if($strResultado != "") {
                        $objMensaje->Mensaje("error", $strResultado);
                    } else {
                        $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->liquidar($codigoPedidoDevolucion);                        
                    }                     
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_devolucion_detalle', array('codigoPedidoDevolucion' => $codigoPedidoDevolucion)));                
            }              
            
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arPedidoDevolucion->getEstadoAutorizado() == 1) {                    
                    $strResultado = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->desautorizar($codigoPedidoDevolucion);
                    if($strResultado != "") {
                        $objMensaje->Mensaje("error", $strResultado);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_devolucion_detalle', array('codigoPedidoDevolucion' => $codigoPedidoDevolucion)));                
            } 
           
            if($form->get('BtnDetalleActualizar')->isClicked()) { 
                if($arPedidoDevolucion->getEstadoAutorizado() == 0) {
                    $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->liquidar($codigoPedidoDevolucion);
                }                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_devolucion_detalle', array('codigoPedidoDevolucion' => $codigoPedidoDevolucion)));
            }             
            
            if($form->get('BtnDetalleEliminar')->isClicked()) {  
                if($arPedidoDevolucion->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucionDetalle')->eliminarSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->liquidar($codigoPedidoDevolucion);                    
                }
                return $this->redirect($this->generateUrl('brs_tur_movimiento_pedido_devolucion_detalle', array('codigoPedidoDevolucion' => $codigoPedidoDevolucion)));
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
        
        $dql = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucionDetalle')->listaDql($codigoPedidoDevolucion);       
        $arPedidoDevolucionDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);
        return $this->render('BrasaTurnoBundle:Movimientos/PedidoDevolucion:detalle.html.twig', array(
                    'arPedidoDevolucion' => $arPedidoDevolucion,
                    'arPedidoDevolucionDetalle' => $arPedidoDevolucionDetalle,
                    'form' => $form->createView()
                    ));
    }
      
    /**
     * @Route("/tur/movimiento/pedido/devolucion/detalle/nuevo/{codigoPedidoDevolucion}/{codigoPedidoDevolucionDetalle}", name="brs_tur_movimiento_pedido_devolucion_detalle_nuevo")
     */    
    public function detalleNuevoAction(Request $request, $codigoPedidoDevolucion, $codigoPedidoDevolucionDetalle = 0) {
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPedidoDevolucion = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();
        $arPedidoDevolucion = $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->find($codigoPedidoDevolucion);        
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {    
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);                        
                        $arPedidoDevolucionDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle();
                        $arPedidoDevolucionDetalle->setPedidoDevolucionRel($arPedidoDevolucion);   
                        $arPedidoDevolucionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arPedidoDevolucionDetalle->setVrPrecio($arPedidoDetalle->getVrTotalDetallePendiente());                                
                        $em->persist($arPedidoDevolucionDetalle); 
                        $devolucion = $arPedidoDetalle->getVrTotalDetalleDevolucion() + $arPedidoDevolucionDetalle->getVrPrecio();
                        $arPedidoDetalle->setVrTotalDetalleDevolucion($devolucion);
                        $pendiente = $arPedidoDetalle->getVrSubtotal() - ($arPedidoDetalle->getVrTotalDetalleAfectado() + $arPedidoDetalle->getVrTotalDetalleDevolucion());                                                
                        $arPedidoDetalle->setVrTotalDetallePendiente($pendiente);
                        $em->persist($arPedidoDetalle);                        
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->liquidar($codigoPedidoDevolucion);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            } 
            if ($form->get('BtnFiltrar')->isClicked()) {            
                $this->filtrarDetalleNuevo($form);
            }
        }
        
        $arPedidoDetalles = $paginator->paginate($em->createQuery($this->listaDetalleNuevo($arPedidoDevolucion->getCodigoClienteFk())), $request->query->get('page', 1), 500);        
        return $this->render('BrasaTurnoBundle:Movimientos/PedidoDevolucion:detalleNuevoPedido.html.twig', array(
            'arPedidoDevolucion' => $arPedidoDevolucion,
            'arPedidoDetalles' => $arPedidoDetalles,            
            'form' => $form->createView()));
    }    
    
    private function lista() {   
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroPedidoDevolucionFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroPedidoDevolucionFechaDesde');
            $strFechaHasta = $session->get('filtroPedidoDevolucionFechaHasta');                    
        }
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDevolucion')->listaDQL(
                $session->get('filtroPedidoDevolucionNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroPedidoDevolucionEstadoAutorizado'), 
                $session->get('filtroPedidoDevolucionEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta);
    }      
    
    private function listaDetalleNuevo($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pendientesFacturarDql(
                $codigoCliente, 
                "",
                $session->get('filtroPedidoDevolucionNumero')
                );
        return $strDql;
    }    
    
    private function filtrar ($form) {
        $session = new session;    
        $session->set('filtroPedidoDevolucionNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPedidoDevolucionEstadoAutorizado', $form->get('estadoAutorizado')->getData());                          
        $session->set('filtroPedidoDevolucionEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroPedidoDevolucionFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroPedidoDevolucionFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroPedidoDevolucionFiltrarFecha', $form->get('filtrarFecha')->getData());
        
    }    
    
    private function filtrarDetalleNuevo ($form) {
        $session = new session;        
        $session->set('filtroPedidoDevolucionNumero', $form->get('TxtNumero')->getData());
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
        if($session->get('filtroPedidoDevolucionFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroPedidoDevolucionFechaDesde');
        }
        if($session->get('filtroPedidoDevolucionFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroPedidoDevolucionFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroPedidoDevolucionNumero')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroPedidoDevolucionEstadoAutorizado')))                
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0'), 'data' => $session->get('filtroPedidoDevolucionEstadoAnulado')))                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroPedidoDevolucionFiltrarFecha')))                 
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);                
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);                
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;            
            
        } else {            
            $arrBotonDesAutorizar['disabled'] = true;                        
            $arrBotonImprimir['disabled'] = true;            
        }
        $form = $this->createFormBuilder()                    
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)                    
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)                                                                                
                    ->getForm();
        return $form;
    }       
    
    private function formularioDetalleNuevo() {
        $session = new session;       
        $form = $this->createFormBuilder()
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroPedidoDevolucionNumero'), 'required'  => false))                
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar',))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
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
        for($col = 'A'; $col !== 'E'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'D'; $col !== 'E'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDevoluciones = new \Brasa\TurnoBundle\Entity\TurPedidoDevolucion();
        $arPedidosDevoluciones = $query->getResult();

        foreach ($arPedidosDevoluciones as $arPedidoDevolucion) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoDevolucion->getCodigoPedidoDevolucionPk())
                    ->setCellValue('B' . $i, $arPedidoDevolucion->getFecha()->format('Y/m/d'))                   
                    ->setCellValue('C' . $i, $arPedidoDevolucion->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arPedidoDevolucion->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidoDevolucion');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidoDevolucion.xlsx"');
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