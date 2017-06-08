<?php

namespace Brasa\ContabilidadBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IntercambioDatosController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/ctb/utilidades/intercambio/datos/exportar", name="brs_ctb_utilidades_intercambio_datos_exportar")
     */
    public function exportarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 73)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formulario();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($form->get('BtnGenerarIlimitada')->isClicked()) {
                    $this->filtrar($form, $request);
                    $this->listar();
                    $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                    $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
                    $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;

                    $ar = fopen($strArchivo, "a") or
                            die("Problemas en la creacion del archivo plano");
                    //fputs($ar, "Cuenta\tComprobante\tFecha\tDocumento\tDocumento Ref.\tNit\tDetalle\tTipo\tValor\tBase\tCentro de Costo\tTrans. Ext\tPlazo" . "\n");
                    //$arRegistrosExportar = $em->getRepository('BrasaContabilidadBundle:CtbRegistroExportar')->findAll();                                    
                    $query = $em->createQuery($this->strDqlLista);
                    $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                    $arRegistros = $query->getResult();
                    foreach ($arRegistros as $arRegistro) {
                        $floValor = 0;
                        $intTipo = 0;
                        if ($arRegistro->getCredito() == 0) {
                            $floValor = $arRegistro->getDebito();
                            $intTipo = 1;
                        } else {
                            $floValor = $arRegistro->getCredito();
                            $intTipo = 2;
                        }
                        $srtCentroCosto = "";
                        if ($arRegistro->getCodigoCentroCostoFk() != null) {
                            $srtCentroCosto = $arRegistro->getCodigoCentroCostoFk();
                        }
                        $srtNit = "";
                        if ($arRegistro->getCodigoTerceroFk() != null) {
                            $srtNit = $arRegistro->getTerceroRel()->getNumeroIdentificacion();
                        }
                        fputs($ar, $arRegistro->getCodigoCuentaFk() . "\t");
                        fputs($ar, $this->RellenarNr($arRegistro->getCodigoComprobanteFk(), "0", 5) . "\t");
                        fputs($ar, $arRegistro->getFecha()->format('m/d/Y') . "\t");
                        fputs($ar, $this->RellenarNr($arRegistro->getNumero(), "0", 9) . "\t");
                        fputs($ar, $this->RellenarNr($arRegistro->getNumero(), "0", 9) . "\t");
                        fputs($ar, $srtNit . "\t");
                        fputs($ar, $arRegistro->getDescripcionContable() . "\t");
                        fputs($ar, $intTipo . "\t");
                        fputs($ar, $floValor . "\t");
                        fputs($ar, $arRegistro->getBase() . "\t");
                        fputs($ar, $srtCentroCosto . "\t");
                        fputs($ar, "" . "\t");
                        fputs($ar, "" . "\t");
                        fputs($ar, "\n");
                    }
                    fclose($ar);

                    header('Content-Description: File Transfer');
                    header('Content-Type: text/csv; charset=ISO-8859-15');
                    header('Content-Disposition: attachment; filename=' . basename($strArchivo));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($strArchivo));
                    readfile($strArchivo);
                    exit;
                }

                if ($form->get('BtnGenerarOfimatica')->isClicked()) {
                    $this->filtrar($form, $request);
                    $this->listar();
                    $this->generarExcelInterfaceOfimatica();
                }
                if ($form->get('BtnGenerarSoftland')->isClicked()) {
                    $this->filtrar($form, $request);
                    $this->listar();
                    $this->generarSoftland();
                }
                if ($form->get('BtnGenerarSeven')->isClicked()) {
                    $this->filtrar($form, $request);
                    $this->listar();
                    $this->generarExcelInterfaceSeven();
                }                
            }
        }
        return $this->render('BrasaContabilidadBundle:Utilidad/IntercambioDatos:exportar.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroCtbRegistroFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroCtbRegistroFechaDesde');
            $strFechaHasta = $session->get('filtroCtbRegistroFechaHasta');
        }
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaExportarDQL(
                $session->get('filtroCtbCodigoComprobante'), $session->get('filtroCtbNumeroDesde'), $session->get('filtroCtbNumeroHasta'), "", $strFechaDesde, $strFechaHasta
        );
    }

    private function filtrar($form, Request $request) {
        $session = $this->get('session');
        $codigoComprobante = '';
        if ($form->get('comprobanteRel')->getData()) {
            $codigoComprobante = $form->get('comprobanteRel')->getData()->getCodigoComprobantePk();
        }
        $session->set('filtroCtbNumeroDesde', $form->get('TxtNumeroDesde')->getData());
        $session->set('filtroCtbNumeroHasta', $form->get('TxtNumeroHasta')->getData());
        $session->set('filtroCtbCodigoComprobante', $codigoComprobante);
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroCtbRegistroFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroCtbRegistroFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroCtbRegistroFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function formulario() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroCtbRegistroFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroCtbRegistroFechaDesde');
        }
        if ($session->get('filtroCtbRegistroFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroCtbRegistroFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);

        $form = $this->createFormBuilder()
                ->add('TxtNumeroDesde', TextType::class, array('data' => $session->get('filtroCtbNumeroDesde')))
                ->add('TxtNumeroHasta', TextType::class, array('data' => $session->get('filtroCtbNumeroHasta')))
                ->add('comprobanteRel', EntityType::class, array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'label' => 'Codigo',
                'data' => $session->get('filtroCtbCodigoComprobante'),
                'choice_label' => 'nombre',
                'required' => true))
                ->add('fechaDesde', DateType::class, array('data' => $dateFechaDesde,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('data' => $dateFechaHasta,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroCtbRegistroFiltrarFecha')))
                ->add('BtnGenerarOfimatica', SubmitType::class, array('label' => 'Ofimatica',))
                ->add('BtnGenerarIlimitada', SubmitType::class, array('label' => 'Ilimitada',))
                ->add('BtnGenerarSoftland', SubmitType::class, array('label' => 'Softland',))
                ->add('BtnGenerarSeven', SubmitType::class, array('label' => 'Seven',))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
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
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'DESDE')
                ->setCellValue('C1', 'HASTA')
                ->setCellValue('D1', 'IDENTIFICACION')
                ->setCellValue('E1', 'NOMBRE')
                ->setCellValue('F1', 'CENTRO COSTOS')
                ->setCellValue('G1', 'BASICO')
                ->setCellValue('H1', 'TIEMPO EXTRA')
                ->setCellValue('I1', 'VALORES ADICIONALES')
                ->setCellValue('J1', 'AUX. TRANSPORTE')
                ->setCellValue('K1', 'ARP')
                ->setCellValue('L1', 'EPS')
                ->setCellValue('M1', 'PENSION')
                ->setCellValue('N1', 'CAJA')
                ->setCellValue('O1', 'ICBF')
                ->setCellValue('P1', 'SENA')
                ->setCellValue('Q1', 'CESANTIAS')
                ->setCellValue('R1', 'VACACIONES')
                ->setCellValue('S1', 'ADMON')
                ->setCellValue('T1', 'COSTO')
                ->setCellValue('U1', 'TOTAL')
                ->setCellValue('W1', 'NETO')
                ->setCellValue('X1', 'IBC')
                ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                ->setCellValue('Z1', 'DIAS PERIODO')
                ->setCellValue('AA1', 'SALARIO PERIODO')
                ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function generarExcelInterfaceOfimatica() {
        $em = $this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session;
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
        for ($col = 'K'; $col !== 'L'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'BASE')
                ->setCellValue('B1', 'CHEQUE')
                ->setCellValue('C1', 'CODCC')
                ->setCellValue('D1', 'CODCOMPROB')
                ->setCellValue('E1', 'CODIGOCTA')
                ->setCellValue('F1', 'CREDITO')
                ->setCellValue('G1', 'DCTO')
                ->setCellValue('H1', 'DEBITO')
                ->setCellValue('I1', 'DESCRIPCIO')
                ->setCellValue('J1', 'DETALLE')
                ->setCellValue('K1', 'FECHAMVTO')
                ->setCellValue('L1', 'NIT');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistros = $query->getResult();
        foreach ($arRegistros as $arRegistro) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getBase())
                    ->setCellValue('B' . $i, $arRegistro->getNumeroReferencia())
                    ->setCellValue('C' . $i, $arRegistro->getCodigoCentroCostoFk())
                    ->setCellValue('D' . $i, $this->RellenarNr($arRegistro->getCodigoComprobanteFk(), "0", 2))
                    ->setCellValue('E' . $i, $arRegistro->getCodigoCuentaFk())
                    ->setCellValue('F' . $i, $arRegistro->getCredito())
                    ->setCellValue('G' . $i, $arRegistro->getNumero())
                    ->setCellValue('H' . $i, $arRegistro->getDebito())
                    ->setCellValue('I' . $i, $arRegistro->getDescripcionContable())
                    ->setCellValue('J' . $i, $arRegistro->getDescripcionContable())
                    ->setCellValue('K' . $i, $arRegistro->getFecha()->format('Y/m/d'))
                    ->setCellValue('K' . $i, PHPExcel_Shared_Date::PHPToExcel(gmmktime(0, 0, 0, $arRegistro->getFecha()->format('m'), $arRegistro->getFecha()->format('d'), $arRegistro->getFecha()->format('Y'))));
            if ($arRegistro->getCodigoTerceroFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $arRegistro->getTerceroRel()->getNumeroIdentificacion() . "-" . $arRegistro->getTerceroRel()->getDigitoVerificacion());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MovimientoContable.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    public static function RellenarNr($Nro, $Str, $NroCr) {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string) $Nro;
    }
    
    private function generarSoftland() {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $query = $em->createQuery($this->strDqlLista);
        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistro = $query->getResult();
        $strNombreArchivo = "CMDMOVIMIENTO". date('YmdHis'). ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        //$strArchivo = "c:/xampp/" . $strNombreArchivo;                                    
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        //$arEmpleados = new Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();                   
        //Inicio cuerpo
        $strSecuencia = 1;
        foreach ($arRegistro AS $arRegistro) {
            //$ciudad = mbsplit("-", $arEmpleados->getCiudadRel()->getNombre(), 0);
            
            $identificacion = "";
            if($arRegistro->getCodigoTerceroFk()) {
                $identificacion = $arRegistro->getTerceroRel()->getNumeroIdentificacion();
            }
            list($anio, $mes)= explode('/', $arRegistro->getFecha()->Format('Y/m/d'));
            $vrMovimiento = '';
            
            if($arRegistro->getDebito() != 0){
                $vrMovimiento = $arRegistro->getDebito();
            }
            else{
                $vrMovimiento = $arRegistro->getCredito();
            }
            $naturalezaMovimiento = '';
            if($arRegistro->getDebito() != 0){
                $naturalezaMovimiento = 'DNO';
            }else{
                $naturalezaMovimiento = 'CNO';
            }
            $centroCosto = '';
            if($arRegistro->getCodigoCentroCostoFk() == 0){
                $centroCosto = '000';
            }else{
                $centroCosto = "00".$arRegistro->getCodigoCentroCostoFk();
            }
            $tercero = '';
            if($arRegistro->getCuentaRel()->getExigeNit() == 1){
                $tercero = $arRegistro->getTerceroRel()->getNumeroIdentificacion()."-".$arRegistro->getTerceroRel()->getDigitoVerificacion();
            }else{
                $tercero = "00000000000";
            }
            $array = array($anio,"!",$mes,"!",'0000'. $arRegistro->getCodigoComprobanteFk(),"!","00000","!",'00000'. '00000'.'0000'. $arRegistro->getNumero() ,"!",$arRegistro->getFecha()->Format('m/d/Y'),"!", str_pad($strSecuencia, 5, '0', STR_PAD_LEFT) ,"!",$arRegistro->getCodigoCuentaFk(),"!", $centroCosto,"!","000","!", "!", "!" , $tercero,"!","000","!", $tercero ,"!","00000","!",'000000000000000',"!", $arRegistro->getDescripcionContable(),"!", str_pad($vrMovimiento, 15, '0', STR_PAD_LEFT),"!",str_pad($arRegistro->getBase(), 15, '0', STR_PAD_LEFT),"!","000000000000000","!", $naturalezaMovimiento, "!","PRI","!","000000000000000","!","!","!","A","!");
            foreach ($array as $fields) {
                fputs($ar, $fields);
            }
            fputs($ar, "\r\n");
            $strSecuencia ++;
        }
        //fputs($ar, "03" . $this->RellenarNr(($strSecuencia-1), "0", 9) . $strValorTotal . "\n");
        fclose($ar);
        $em->flush();
        //Fin cuerpo                        
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;
    }
    
    private function generarExcelInterfaceSeven() {
        $em = $this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session;
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
                ->setCellValue('A1', 'CODIGO_EMPRESA')
                ->setCellValue('B1', 'TIPO_OPERACION')
                ->setCellValue('C1', 'NUMERO_CUENTA')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'SUCURSAL')
                ->setCellValue('F1', 'PROVEEDOR')
                ->setCellValue('G1', 'DETALLE_PROVEEDOR')
                ->setCellValue('H1', 'TIPO')
                ->setCellValue('I1', 'NUMERO_FACTURA')
                ->setCellValue('J1', 'PREFIJO_FACTURA')
                ->setCellValue('K1', 'CUENTA_CONTABLE')
                ->setCellValue('L1', 'MONEDA')
                ->setCellValue('M1', 'FECHA_TASA')
                ->setCellValue('N1', 'VALOR_TASA')
                ->setCellValue('O1', 'FECHA_VENCIMIENTO')
                ->setCellValue('P1', 'VALOR_TOTAL_CUENTA_PAGAR')
                ->setCellValue('Q1', 'DIAS_GRACIA_INTERES_CORRIENTE')
                ->setCellValue('R1', 'DIAS_GRACIA_INTERES_MORA')
                ->setCellValue('S1', 'PORCENTAJE_INTERES_CORRIENTE')
                ->setCellValue('T1', 'PORCENTAJE_INTERES_MORA')
                ->setCellValue('U1', 'DESCRIPCION')
                ->setCellValue('V1', 'CENTRO_COSTO')
                ->setCellValue('W1', 'PROYECTO')
                ->setCellValue('X1', 'AREA_NEGOCIO')
                ->setCellValue('Y1', 'PORCENTAJE_DISTRIBUCION');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistros = $query->getResult();
        foreach ($arRegistros as $arRegistro) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getBase())
                    ->setCellValue('B' . $i, $arRegistro->getNumeroReferencia())
                    ->setCellValue('C' . $i, $arRegistro->getCodigoCentroCostoFk())
                    ->setCellValue('D' . $i, $this->RellenarNr($arRegistro->getCodigoComprobanteFk(), "0", 2))
                    ->setCellValue('E' . $i, $arRegistro->getCodigoCuentaFk())
                    ->setCellValue('F' . $i, $arRegistro->getCredito())
                    ->setCellValue('G' . $i, $arRegistro->getNumero())
                    ->setCellValue('H' . $i, $arRegistro->getDebito())
                    ->setCellValue('I' . $i, $arRegistro->getDescripcionContable())
                    ->setCellValue('J' . $i, $arRegistro->getDescripcionContable())
                    ->setCellValue('K' . $i, $arRegistro->getFecha()->format('Y/m/d'))
                    ->setCellValue('K' . $i, PHPExcel_Shared_Date::PHPToExcel(gmmktime(0, 0, 0, $arRegistro->getFecha()->format('m'), $arRegistro->getFecha()->format('d'), $arRegistro->getFecha()->format('Y'))));
            if ($arRegistro->getCodigoTerceroFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $arRegistro->getTerceroRel()->getNumeroIdentificacion() . "-" . $arRegistro->getTerceroRel()->getDigitoVerificacion());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MovimientoContable.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }    

}
