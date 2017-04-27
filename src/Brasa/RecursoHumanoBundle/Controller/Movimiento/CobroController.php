<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCobroType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuServicioCobrarType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CobroController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/cobro/lista", name="brs_rhu_cobro_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        //if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 16, 1)) {
        //    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        //}
        $paginator  = $this->get('knp_paginator');        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCobro) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($codigoCobro);
                        $arCobrosDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobroDetalle')->devuelveNumeroCobrosDetalle($codigoCobro);    
                        if($arCobrosDetalle == 0){
                            $em->remove($arSelecciones);
                            $em->flush();
                        }
                        else {
                            $objMensaje->Mensaje("error", "No se puede eliminar la cobro, tiene registros liquidados");
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_cobro_lista'));    
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
        $arCobros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Cobro:lista.html.twig', array(
            'arCobros' => $arCobros, 
            'form' => $form->createView()));
    }       
    
    /**
     * @Route("/rhu/cobro/nuevo/{codigoCobro}", name="brs_rhu_cobro_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCobro) {
        $em = $this->getDoctrine()->getManager();        
        $arCobro = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
        if ($codigoCobro != 0) {
            $arCobro = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($codigoCobro);
        } else {
           $arCobro->setFecha(new \DateTime('now'));           
        }
        $form = $this->createForm(RhuCobroType::class, $arCobro);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCobro = $form->getData();                           
            $em->persist($arCobro);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_cobro_nuevo', array('codigoCobro' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_cobro_detalle', array('codigoCobro' => $arCobro->getCodigoCobroPk())));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Cobro:nuevo.html.twig', array(
            'arCobro' => $arCobro,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/cobro/detalle/{codigoCobro}", name="brs_rhu_cobro_detalle")
     */
    public function detalleAction(Request $request, $codigoCobro) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arCobro = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
        $arCobro = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($codigoCobro);
        $form = $this->formularioDetalle($arCobro);                               
        $form->handleRequest($request);        
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAutorizar')->isClicked()) {                      
                //$this->actualizarDetalle($arrControles, $codigoCobro);
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->autorizar($codigoCobro);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                }
                return $this->redirect($this->generateUrl('brs_rhu_cobro_detalle', array('codigoCobro' => $codigoCobro)));                                
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->desAutorizar($codigoCobro);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                }
                return $this->redirect($this->generateUrl('brs_rhu_cobro_detalle', array('codigoCobro' => $codigoCobro)));                                
            }
            if($form->get('BtnEliminarDetalleServicio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarServicio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {                        
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($codigo);                                                
                        $arServicioCobrar->setEstadoCobrado(0);
                        $arServicioCobrar->setCobroRel(null);
                        $em->persist($arServicioCobrar);                        
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->liquidar($codigoCobro);
                    return $this->redirect($this->generateUrl('brs_rhu_cobro_detalle', array('codigoCobro' => $codigoCobro)));
                }
            }           
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->imprimir($codigoCobro);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado);
                } else {
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    //if($arCobro->getCobroTipoRel()->getTipo() == 1) {
                        if($arConfiguracion->getCodigoFormatoCobro() <= 1) {
                            $objCobro = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCobro();
                            $objCobro->Generar($em, $codigoCobro);                            
                        }                        
                    //}                                         
                }
                return $this->redirect($this->generateUrl('brs_rhu_cobro_detalle', array('codigoCobro' => $codigoCobro)));                                                
            }           
            if ($form->get('BtnDetalleExcel')->isClicked()) {                
                $this->generarDetalleExcel($codigoCobro);
            }
        }
        $strDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->detalleCobro($codigoCobro);
        $arServiciosCobrar = $paginator->paginate($em->createQuery($strDql), $request->query->get('page', 1), 20);                        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Cobro:detalle.html.twig', array(
                    'arCobro' => $arCobro,
                    'arServiciosCobrar' => $arServiciosCobrar,
                    'form' => $form->createView(),
                    ));
    }
    
    /**
     * @Route("/rhu/cobro/detalle/editar/{codigoCobro}/{codigoServicioCobrar}", name="brs_rhu_cobro_detalle_editar")
     */
    public function detalleEditarAction(Request $request, $codigoServicioCobrar, $codigoCobro) {
        $em = $this->getDoctrine()->getManager();        
        $arServicioCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();        
        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($codigoServicioCobrar);

        $form = $this->createForm(RhuServicioCobrarType::class, $arServicioCobrar);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arServicioCobrar = $form->getData(); 
            $operacion = ($arServicioCobrar->getVrSalario() + $arServicioCobrar->getVrPrestacional() + $arServicioCobrar->getVrNoPrestacional() + $arServicioCobrar->getVrAuxilioTransporteCotizacion() + $arServicioCobrar->getVrRiesgos() + $arServicioCobrar->getVrPension() + $arServicioCobrar->getVrCaja() + $arServicioCobrar->getVrPrestaciones() + $arServicioCobrar->getVrVacaciones() + $arServicioCobrar->getVrAporteParafiscales());
            if($arServicioCobrar->getAdministracionFijo()) {
                $valorAdministracion = $arServicioCobrar->getValorAdministracionFijo();
            } else {
                $valorAdministracion = ($operacion * $arServicioCobrar->getPorcentajeAdministracion()) / 100;
            }
            $totalCobro = $operacion + $valorAdministracion;
            $arServicioCobrar->setVrOperacion(round($operacion));
            $arServicioCobrar->setVrAdministracion(round($valorAdministracion));
            $arServicioCobrar->setVrTotalCobro(round($totalCobro));
            $em->persist($arServicioCobrar);
            $em->flush();
            $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->liquidar($codigoCobro);
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Cobro:detalleEditar.html.twig', array(
            'arServicioCobrar' => $arServicioCobrar,
            'form' => $form->createView()));
    }      
    
    /**
     * @Route("/rhu/cobro/detalle/nuevo/servicio/{codigoCobro}", name="brs_rhu_cobro_detalle_nuevo_servicio")
     */
    public function detalleNuevoServicioAction(Request $request, $codigoCobro) {        
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arCobro = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find($codigoCobro);                
        $form = $this->createFormBuilder()
            ->add('BtnAgregar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {            
            if($form->get('BtnAgregar')->isClicked()) {
                //$arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoServicioCobrar) {
                        $arServicioCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($codigoServicioCobrar);
                        if(!$arServicioCobrar->getCodigoCobroFk()) {
                            $arServicioCobrar->setEstadoCobrado(1);
                            $arServicioCobrar->setCobroRel($arCobro);
                            $em->persist($arServicioCobrar);
                        }
                    }                    
                    $em->flush();                    
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->liquidar($codigoCobro);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->pendienteCobrar($arCobro->getCodigoClienteFk()));        
        $arServiciosCobrar = $paginator->paginate($query, $request->query->get('page', 1), 300);                       
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Cobro:detalleNuevoServicio.html.twig', array(
            'arServiciosCobrar' => $arServiciosCobrar,
            'arCobro' => $arCobro,
            'form' => $form->createView()));
    }                  
    
    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->listaDql(
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
        $arrBotonDetalleExcel = array('label' => 'Excel', 'disabled' => false);                
        $arrBotonDetalleEliminarDetalleServicio = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleEliminarDetalleExamen = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleEliminarDetalleSeleccion = array('label' => 'Eliminar', 'disabled' => false);
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
                    ->setCellValue('A1', 'ID');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arCobros = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
        $arCobros = $query->getResult();
        foreach ($arCobros as $arCobro) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCobro->getCodigoCobroPk());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cobros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="cobro.xlsx"');
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
    
    private function generarDetalleExcel($codigoCobro = '') {
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
                ->setCellValue('B1', 'DOCUMENTO')
                ->setCellValue('C1', 'EMPLEADO')
                ->setCellValue('D1', 'SALARIO')
                ->setCellValue('E1', 'IBP')
                ->setCellValue('F1', 'IBC')
                ->setCellValue('G1', 'BASICO')
                ->setCellValue('H1', 'PRESTACIONAL')
                ->setCellValue('I1', 'NO_PRESTACIONAL')
                ->setCellValue('J1', 'TTE')
                ->setCellValue('K1', 'PENSION')
                ->setCellValue('L1', 'SALUD')
                ->setCellValue('M1', 'RIESGOS')
                ->setCellValue('N1', 'POR_RIE')
                ->setCellValue('O1', 'CAJA')
                ->setCellValue('P1', 'SENA')
                ->setCellValue('Q1', 'ICBF')
                ->setCellValue('R1', 'PRESTACIONES')
                ->setCellValue('S1', 'VACACIONES')
                ->setCellValue('T1', 'A_PARAF')
                ->setCellValue('U1', 'OPERACION')
                ->setCellValue('V1', 'ADMON')
                ->setCellValue('W1', 'TOTAL');
                    
        $i = 2;
        //$query = $em->createQuery($this->strSqlLista);
        $arCobroDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arCobroDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->findBy(array('codigoCobroFk' => $codigoCobro));
        //$arPagoBancos = $query->getResult();
        foreach ($arCobroDetalle as $arCobroDetalle) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCobroDetalle->getCodigoServicioCobrarPk())
                    ->setCellValue('B' . $i, $arCobroDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arCobroDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arCobroDetalle->getVrSalarioEmpleado())
                    ->setCellValue('E' . $i, $arCobroDetalle->getVrIngresoBasePrestacion())
                    ->setCellValue('F' . $i, $arCobroDetalle->getVrIngresoBaseCotizacion())
                    ->setCellValue('G' . $i, $arCobroDetalle->getVrSalario())
                    ->setCellValue('H' . $i, $arCobroDetalle->getVrPrestacional())
                    ->setCellValue('I' . $i, $arCobroDetalle->getVrNoPrestacional())
                    ->setCellValue('J' . $i, $arCobroDetalle->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arCobroDetalle->getVrPension())
                    ->setCellValue('L' . $i, $arCobroDetalle->getVrSalud())
                    ->setCellValue('M' . $i, $arCobroDetalle->getVrRiesgos())
                    ->setCellValue('N' . $i, $arCobroDetalle->getPorcentajeRiesgos())
                    ->setCellValue('O' . $i, $arCobroDetalle->getVrCaja())
                    ->setCellValue('P' . $i, $arCobroDetalle->getVrSena())
                    ->setCellValue('Q' . $i, $arCobroDetalle->getVrIcbf())
                    ->setCellValue('R' . $i, $arCobroDetalle->getVrPrestaciones())
                    ->setCellValue('S' . $i, $arCobroDetalle->getVrVacaciones())
                    ->setCellValue('T' . $i, $arCobroDetalle->getVrAporteParafiscales())
                    ->setCellValue('U' . $i, $arCobroDetalle->getVrOperacion())
                    ->setCellValue('V' . $i, $arCobroDetalle->getVrAdministracion())
                    ->setCellValue('W' . $i, $arCobroDetalle->getVrTotalCobro());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('CobroDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CobroDetalles.xlsx"');
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
