<?php
namespace Brasa\TurnoBundle\Controller\Consulta\Pedido;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class detallesController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/consulta/pedidos/detalles", name="brs_tur_consulta_pedidos_detalles")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 45)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
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
            if ($form->get('BtnExcel2')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel2();
            }            
        }
        
        $arPedidosDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Consultas/Pedido:detalle.html.twig', array(
            'arPedidosDetalles' => $arPedidosDetalles,
            'form' => $form->createView()));
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
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaConsultaDql(
                $session->get('filtroPedidoNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroPedidoEstadoAutorizado'), 
                $session->get('filtroPedidoEstadoProgramado'),
                $session->get('filtroPedidoEstadoFacturado'),
                $session->get('filtroPedidoEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroTurnosCodigoPedidoTipo'));
    }

    private function filtrar ($form) {
        $session = new session;
        $arPedidoTipo = $form->get('pedidoTipoRel')->getData();
        if($arPedidoTipo) {
            $session->set('filtroTurnosCodigoPedidoTipo', $arPedidoTipo->getCodigoPedidoTipoPk());
        } else {
            $session->set('filtroTurnosCodigoPedidoTipo', null);
        }         
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
        $arrayPropiedadesPedidoTipo = array(
                'class' => 'BrasaTurnoBundle:TurPedidoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                    ->orderBy('pt.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroTurnosCodigoPedidoTipo')) {
            $arrayPropiedadesPedidoTipo['data'] = $em->getReference("BrasaTurnoBundle:TurPedidoTipo", $session->get('filtroTurnosCodigoPedidoTipo'));
        }        
        
        $form = $this->createFormBuilder()
             ->add('pedidoTipoRel', EntityType::class, $arrayPropiedadesPedidoTipo)
            ->add('TxtNit', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroPedidoEstadoAutorizado')))                
            ->add('estadoProgramado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'PROGRAMADO' => '1', 'SIN PROGRAMAR' => '0'), 'data' => $session->get('filtroPedidoEstadoProgramado')))                                
            ->add('estadoFacturado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'FACTURADO' => '1', 'SIN FACTURAR' => '0'), 'data' => $session->get('filtroPedidoEstadoFacturado')))                                
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0'), 'data' => $session->get('filtroPedidoEstadoAnulado')))                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroPedidoFiltrarFecha')))                             
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnExcel2', SubmitType::class, array('label'  => 'Excel2',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
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
        for($col = 'A'; $col !== 'AN'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'AI'; $col !== 'AN'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NUMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'FH PROG')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'SECTOR')
                    ->setCellValue('H1', 'AUT')                   
                    ->setCellValue('I1', 'PRO')
                    ->setCellValue('J1', 'FAC')
                    ->setCellValue('K1', 'ANU')
                    ->setCellValue('L1', 'PUESTO')
                    ->setCellValue('M1', 'SERVICIO')
                    ->setCellValue('N1', 'MODALIDAD')
                    ->setCellValue('O1', 'PERIODO')
                    ->setCellValue('P1', 'PLANTILLA')
                    ->setCellValue('Q1', 'DESDE')
                    ->setCellValue('R1', 'HASTA')
                    ->setCellValue('S1', 'CANT')
                    ->setCellValue('T1', 'LU')
                    ->setCellValue('U1', 'MA')
                    ->setCellValue('V1', 'MI')
                    ->setCellValue('W1', 'JU')
                    ->setCellValue('X1', 'VI')
                    ->setCellValue('Y1', 'SA')
                    ->setCellValue('Z1', 'DO')
                    ->setCellValue('AA1', 'FE')
                    ->setCellValue('AB1', 'H')
                    ->setCellValue('AC1', 'HD')
                    ->setCellValue('AD1', 'HN')
                    ->setCellValue('AE1', 'HP')
                    ->setCellValue('AF1', 'HDP')
                    ->setCellValue('AG1', 'HNP')                
                    ->setCellValue('AH1', 'DIAS')
                    ->setCellValue('AI1', 'P_MIN')
                    ->setCellValue('AJ1', 'P_AJU')
                    ->setCellValue('AK1', 'SUBTOTAL')
                    ->setCellValue('AL1', 'TOTAL')
                    ->setCellValue('AM1', 'PED_FAC');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalles = $query->getResult();

        foreach ($arPedidosDetalles as $arPedidoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoDetalle->getCodigoPedidoDetallePk())
                    ->setCellValue('B' . $i, $arPedidoDetalle->getPedidoRel()->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedidoDetalle->getPedidoRel()->getNumero())
                    ->setCellValue('D' . $i, $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arPedidoDetalle->getPedidoRel()->getSectorRel()->getNombre())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoAutorizado()))
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoProgramado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoFacturado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getPedidoRel()->getEstadoAnulado()))
                    ->setCellValue('M' . $i, $arPedidoDetalle->getConceptoServicioRel()->getNombre())
                    ->setCellValue('N' . $i, $arPedidoDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('O' . $i, $arPedidoDetalle->getPeriodoRel()->getNombre())                    
                    ->setCellValue('Q' . $i, $arPedidoDetalle->getDiaDesde())
                    ->setCellValue('R' . $i, $arPedidoDetalle->getDiaHasta())
                    ->setCellValue('S' . $i, $arPedidoDetalle->getCantidad())
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getLunes()))
                    ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getMartes()))
                    ->setCellValue('V' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getMiercoles()))
                    ->setCellValue('W' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getJueves()))
                    ->setCellValue('X' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getViernes()))
                    ->setCellValue('Y' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getSabado()))
                    ->setCellValue('Z' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getDomingo()))
                    ->setCellValue('AA' . $i, $objFunciones->devuelveBoolean($arPedidoDetalle->getFestivo()))
                    ->setCellValue('AB' . $i, $arPedidoDetalle->getHoras())
                    ->setCellValue('AC' . $i, $arPedidoDetalle->getHorasDiurnas())
                    ->setCellValue('AD' . $i, $arPedidoDetalle->getHorasNocturnas())
                    ->setCellValue('AE' . $i, $arPedidoDetalle->getHorasProgramadas())
                    ->setCellValue('AF' . $i, $arPedidoDetalle->getHorasDiurnasProgramadas())
                    ->setCellValue('AG' . $i, $arPedidoDetalle->getHorasNocturnasProgramadas())
                    ->setCellValue('AH' . $i, $arPedidoDetalle->getDias())
                    ->setCellValue('AI' . $i, $arPedidoDetalle->getVrPrecioMinimo())
                    ->setCellValue('AJ' . $i, $arPedidoDetalle->getVrPrecioAjustado())
                    ->setCellValue('AK' . $i, $arPedidoDetalle->getVrSubtotal())
                    ->setCellValue('AL' . $i, $arPedidoDetalle->getVrTotalDetalle())
                    ->setCellValue('AM' . $i, $arPedidoDetalle->getVrTotalDetallePendiente());
            if($arPedidoDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('L' . $i, $arPedidoDetalle->getPuestoRel()->getNombre());
            }
            if($arPedidoDetalle->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('P' . $i, $arPedidoDetalle->getPlantillaRel()->getNombre());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosDetalles.xlsx"');
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
    
    private function generarExcel2() {
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
        for($col = 'A'; $col !== 'Q'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'J'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'TIPO')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'CLIENTE')
                    ->setCellValue('D1', 'CODIGO')
                    ->setCellValue('E1', 'PUESTO')
                    ->setCellValue('F1', 'MODALIDAD')
                    ->setCellValue('G1', 'DES')
                    ->setCellValue('H1', 'HAS')
                    ->setCellValue('I1', 'SERVICIO')                    
                    ->setCellValue('J1', 'CANT')
                    ->setCellValue('K1', 'SUBTOTAL')                   
                    ->setCellValue('L1', 'BASE')                   
                    ->setCellValue('M1', 'IVA')
                    ->setCellValue('N1', 'TOTAL')
                    ->setCellValue('O1', 'G_F')
                    ->setCellValue('P1', 'C_COSTO')
                    ->setCellValue('Q1', 'ZONA');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalles = $query->getResult();

        foreach ($arPedidosDetalles as $arPedidoDetalle) { 
            if($arPedidoDetalle->getCompuesto()) {
                $arPedidosDetallesCompuestos = new \Brasa\TurnoBundle\Entity\TurPedidoDetalleCompuesto();
            } else {
                $fechaDesde = $arPedidoDetalle->getAnio()."/" . $arPedidoDetalle->getMes() . "/" . $arPedidoDetalle->getDiaDesde();
                $fechaHasta = $arPedidoDetalle->getAnio()."/" . $arPedidoDetalle->getMes() . "/" . $arPedidoDetalle->getDiaHasta();
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arPedidoDetalle->getPedidoRel()->getPedidoTipoRel()->getNombre())
                        ->setCellValue('B' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNit())
                        ->setCellValue('C' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto())
                        ->setCellValue('D' . $i, $arPedidoDetalle->getCodigoPuestoFk())
                        ->setCellValue('F' . $i, $arPedidoDetalle->getModalidadServicioRel()->getNombre())                                                       
                        ->setCellValue('G' . $i, $fechaDesde)
                        ->setCellValue('H' . $i, $fechaHasta)                    
                        ->setCellValue('I' . $i, $arPedidoDetalle->getConceptoServicioRel()->getNombreFacturacion())                    
                        ->setCellValue('J' . $i, $arPedidoDetalle->getCantidad())
                        ->setCellValue('K' . $i, $arPedidoDetalle->getVrSubtotal())
                        ->setCellValue('L' . $i, $arPedidoDetalle->getVrBaseAiu())
                        ->setCellValue('M' . $i, $arPedidoDetalle->getVrIva())
                        ->setCellValue('N' . $i, $arPedidoDetalle->getVrTotalDetalle());
                if($arPedidoDetalle->getPuestoRel()) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $arPedidoDetalle->getPuestoRel()->getNombre());                
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $i, $arPedidoDetalle->getPuestoRel()->getCodigoCentroCostoContabilidadFk());                
                    if($arPedidoDetalle->getPuestoRel()->getZonaRel()) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $i, $arPedidoDetalle->getPuestoRel()->getZonaRel()->getNombre());
                    }
                }
                if($arPedidoDetalle->getGrupoFacturacionRel()) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $i, $arPedidoDetalle->getGrupoFacturacionRel()->getNombre());                
                }                
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosDetalles.xlsx"');
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