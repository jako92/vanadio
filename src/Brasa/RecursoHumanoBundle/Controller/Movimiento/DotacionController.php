<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionElementoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DotacionController extends Controller
{
    var $strListaDql = "";

    /**
     * @Route("/rhu/base/empleado/dotacion/lista", name="brs_rhu_dotacion_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 17, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoDotacion) {
                        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
                        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find($codigoDotacion);
                        if ($arDotacion->getEstadoAutorizado() == 1){
                            $objMensaje->Mensaje("error", "La dotación ". $codigoDotacion ." ya fue autorizada, no se pude eliminar");
                        }else{
                            $arRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->validarDotacionesDQL($codigoDotacion);
                            if ($arRegistros){
                                $objMensaje->Mensaje("error", "La dotación ". $codigoDotacion ." contiene registros asignados");
                            }else{
                                $em->remove($arDotacion);
                            }
                        }
                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_dotacion_lista'));
                }
            }

            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arDotaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Dotacion:lista.html.twig', array('arDotaciones' => $arDotaciones, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/base/empleado/dotacion/nuevo/{codigoDotacion}", name="brs_rhu_dotacion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoDotacion = 0) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();    
        if($codigoDotacion != 0) {
            $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find($codigoDotacion);
        } else {
            $arDotacion->setFechaEntrega(new \DateTime('now'));
        }
        $form = $this->createForm(RhuDotacionType::class, $arDotacion);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arDotacion = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arDotacion->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {                        
                        $arDotacion->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                        $arDotacion->setFecha(new \DateTime('now'));
                        if($codigoDotacion == 0) {
                            $arDotacion->setCodigoUsuario($arUsuario->getUserName());
                            $codigoCargo = $arEmpleado->getCodigoCargoFk();
                            $arDotacionCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
                            $arDotacionCargos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->findBy(array('codigoCargoFk' => $codigoCargo));
                            $arDotacionTipo = $form->get('dotacionTipoRel')->getData();
                            if ($arDotacionTipo->getCodigoDotacionTipoPk() == 1){
                                foreach ($arDotacionCargos as $arDotacionCargo) {
                                    $arDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                                    $arDotacionDetalle->setDotacionRel($arDotacion);
                                    $arDotacionDetalle->setDotacionElementoRel($arDotacionCargo->getDotacionElementoRel());
                                    $arDotacionDetalle->setCantidadAsignada($arDotacionCargo->getCantidadAsignada());
                                    //$arDotacionDetalle->setSerie(0);
                                    //$arDotacionDetalle->setLote(0);
                                    $em->persist($arDotacionDetalle);
                                }
                            
                            }
                        }
                        $em->persist($arDotacion);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_dotacion_nuevo', array('codigoDotacion' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $arDotacion->getCodigoDotacionPk() )));
                        }                        
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }                
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Dotacion:nuevo.html.twig', array(
            'arDotacion' => $arDotacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/base/empleado/dotacion/detalle/{codigoDotacion}", name="brs_rhu_dotacion_detalle")
     */
    public function detalleAction(Request $request, $codigoDotacion) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find($codigoDotacion);
        $form = $this->formularioDetalle($arDotacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arDotacion->getEstadoAutorizado() == 0) {
                    $arDotacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->findBy(array('codigoDotacionFk' => $codigoDotacion));
                    if ($arDotacionDetalle != null){
                        $arDotacion->setEstadoAutorizado(1);
                        $em->persist($arDotacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoDotacion)));                                                
                    } else {
                        $objMensaje->Mensaje("error", "La dotación no tiene detalles, no se puede autorizar");
                    }    
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arDotacion->getEstadoAutorizado() == 1) {
                    $arDotacion->setEstadoAutorizado(0);
                    $em->persist($arDotacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoDotacion)));                                                
                }
            }            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arDotacion->getEstadoAutorizado() == 1) {
                    $objFormatoDotacionDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDotacionDetalle();
                    $objFormatoDotacionDetalle->Generar($em, $codigoDotacion);
                }    
            }
            if($form->get('BtnGenerarSalidaInventario')->isClicked()) {
                if($arDotacion->getEstadoAutorizado() == 1 && $arDotacion->getEstadoSalidaInventario() == 0) {
                    $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
                    $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find(2);
                    $arTercero = new \Brasa\InventarioBundle\Entity\InvTercero();
                    $arTercero = $em->getRepository('BrasaInventarioBundle:InvTercero')->find(11);
                    $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
                    $arMovimiento->setTerceroRel($arTercero);
                    $arMovimiento->setFormaPagoRel($arTercero->getFormaPagoRel());
                    $arMovimiento->setDocumentoRel($arDocumento);
                    $arMovimiento->setSoporte('DOT'.$arDotacion->getCodigoDotacionPk());
                    $arMovimiento->setOperacionInventario($arDocumento->getOperacionInventario());
                    $arMovimiento->setCodigoDocumentoClaseFk($arDocumento->getCodigoDocumentoClaseFk());
                    $arMovimiento->setFechaVence(new \DateTime('now'));
                    $arMovimiento->setFecha(new \DateTime('now'));
                    $em->persist($arMovimiento);
                    $arDotacionDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                    $arDotacionDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->findBy(array('codigoDotacionFk' => $codigoDotacion));
                    foreach ($arDotacionDetalles as $arDotacionDetalle) {
                        $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
                        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($arDotacionDetalle->getDotacionElementoRel()->getCodigoItemFk());
                        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
                        $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                        $arMovimientoDetalle->setItemRel($arItem);                        
                        $arMovimientoDetalle->setFechaVencimiento(new \DateTime('now'));
                        $arMovimientoDetalle->setAfectaInventario($arItem->getAfectaInventario());
                        $arMovimientoDetalle->setOperacionInventario($arMovimiento->getOperacionInventario());                        
                        $arMovimientoDetalle->setPorcentajeIva($arItem->getPorcentajeIva());                        
                        $arMovimientoDetalle->setCantidad($arDotacionDetalle->getCantidadAsignada());
                        $em->persist($arMovimientoDetalle);                        
                    }
                    $arDotacion->setEstadoSalidaInventario(1);
                    $em->persist($arDotacion);
                    $em->flush();                    
                }    
                return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoDotacion)));
            }            
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arDotacion->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoDotacionPk) {
                            $arDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                            $arDotacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->find($codigoDotacionPk);
                            if($arDotacionDetalle->getCodigoDotacionDetalleEnlaceFk()) {
                                $arDotacionDetalleEnlace = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                                $arDotacionDetalleEnlace = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->find($arDotacionDetalle->getCodigoDotacionDetalleEnlaceFk());                            
                                $arDotacionDetalleEnlace->setCantidadDevuelta($arDotacionDetalleEnlace->getCantidadDevuelta() - $arDotacionDetalle->getCantidadAsignada());
                                $em->persist($arDotacionDetalleEnlace);
                            }
                            $em->remove($arDotacionDetalle);
                        }
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoDotacion)));
                }
            }

        }
        $arDotacionDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
        $arDotacionDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->FindBy(array('codigoDotacionFk' => $codigoDotacion));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Dotacion:detalle.html.twig', array(
                    'arDotacion' => $arDotacion,
                    'arDotacionDetalles' => $arDotacionDetalles,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/base/empleado/dotacion/detalle/nuevo/{codigoDotacion}", name="brs_rhu_dotacion_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoDotacion) {
        
        $em = $this->getDoctrine()->getManager();
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find($codigoDotacion);
        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        $arDotacionElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnGuardar')->isClicked()) {
                if ($arDotacion->getEstadoAutorizado() == 0){
                    if (isset($arrControles['TxtCantidad'])) {
                        $intIndice = 0;
                        foreach ($arrControles['LblCodigo'] as $intCodigo) {
                            if($arrControles['TxtCantidad'][$intIndice] > 0 ){
                                $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                                $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($intCodigo);
                                $arDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                                $arDotacionDetalle->setDotacionRel($arDotacion);
                                $arDotacionDetalle->setDotacionElementoRel($arDotacionElemento);
                                $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                                $arDotacionDetalle->setCantidadAsignada($intCantidad);
                                $arDotacionDetalle->setCantidadDevuelta(0);
                                $em->persist($arDotacionDetalle);
                            }
                            $intIndice++;
                        }
                    }
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Dotacion:detallenuevo.html.twig', array(
            'arDotacion' => $arDotacion,
            'arDotacionElementos' => $arDotacionElementos,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/base/empleado/dotacion/detalle/devolucion/{codigoDotacion}", name="brs_rhu_dotacion_detalle_devolucion")
     */
    public function detalleDevolucionAction(Request $request, $codigoDotacion) {
        
        $em = $this->getDoctrine()->getManager();
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->find($codigoDotacion);
        $arDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
        $arDotacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->dotacionDevolucion($arDotacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnGuardar')->isClicked()) {
                if ($arDotacion->getEstadoAutorizado() == 0){
                    if (isset($arrControles['TxtCantidad'])) {
                        $intIndice = 0;
                        foreach ($arrControles['LblCodigo'] as $intCodigo) {
                            if($arrControles['TxtCantidad'][$intIndice] > 0 ){
                                $arDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                                $arDotacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionDetalle')->find($intCodigo);
                                $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                                $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($arDotacionDetalle->getCodigoDotacionElementoFk());
                                $arDotacionDetalleDevolucion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
                                $arDotacionDetalleDevolucion->setDotacionRel($arDotacion);
                                $arDotacionDetalleDevolucion->setDotacionElementoRel($arDotacionElemento);
                                $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                                $arDotacionDetalleDevolucion->setCantidadAsignada($intCantidad);
                                $arDotacionDetalleDevolucion->setCodigoDotacionDetalleEnlaceFk($arDotacionDetalle->getCodigoDotacionDetallePk());
                                $em->persist($arDotacionDetalleDevolucion);
                                $arDotacionDetalle->setCantidadDevuelta($arDotacionDetalle->getCantidadDevuelta() + $intCantidad);                            
                                $em->persist($arDotacionDetalle);
                            }
                            $intIndice++;
                        }
                    }
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Dotacion:detalleDevolucion.html.twig', array(
            'arDotacion' => $arDotacion,
            'arDotacionDetalle' => $arDotacionDetalle,
            'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->listaDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroCodigoCentroCosto')
                );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedades = array(
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
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = new session;
        $codigoCentroCosto ="";
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        } 
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }    
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonGenerarSalidaInventario = array('label' => 'Generar salida inventario', 'disabled' => true);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonEliminarDetalle['disabled'] = true;
            if($ar->getEstadoSalidaInventario() == 0) { 
                $arrBotonGenerarSalidaInventario['disabled'] = false;            
            }
            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getCodigoDotacionTipoFk() == 2) {
            $arrBotonGenerarSalidaInventario['disabled'] = true; 
        }
        $form = $this->createFormBuilder()                        
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)            
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)    
                    ->add('BtnGenerarSalidaInventario', SubmitType::class, $arrBotonGenerarSalidaInventario)                
                    ->add('BtnEliminarDetalle', SubmitType::class, $arrBotonEliminarDetalle)                                
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Fecha')
                            ->setCellValue('C1', 'Centro Centro')
                            ->setCellValue('D1', 'Identificación')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Número Interno Referencia');
                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arDotaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
                $arDotaciones = $query->getResult();

                foreach ($arDotaciones as $arDotacion) {

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDotacion->getCodigoDotacionPk())
                            ->setCellValue('B' . $i, $arDotacion->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arDotacion->getCentroCostoRel()->getNombre())
                            ->setCellValue('D' . $i, $arDotacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arDotacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arDotacion->getCodigoInternoReferencia());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Dotacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Dotacion.xlsx"');
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
