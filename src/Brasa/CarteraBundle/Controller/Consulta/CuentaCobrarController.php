<?php

namespace Brasa\CarteraBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CuentaCobrarController extends Controller {

    var $strListaDql = "";
    var $strFechaDesde = "";
    var $strFechaHasta = "";

    /**
     * @Route("/cartera/consulta/cuentacobrar/lista", name="brs_car_consulta_cuentacobrar_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 50)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $strWhere = "";
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->formularioFiltro();
                    $strWhere .= $this->devFiltro($form);
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $strWhere .= $this->devFiltro($form);
                    $this->generarExcel($strWhere);
                }
                if ($form->get('BtnExcel2')->isClicked()) {
                    $strWhere .= $this->devFiltro($form);
                    $this->generarExcel2($strWhere);
                }                
                if ($form->get('BtnPdf')->isClicked()) {
                    $strWhere .= $this->devFiltro($form);
                    $objEstadoCuenta = new \Brasa\CarteraBundle\Formatos\EstadoCuenta();
                    $objEstadoCuenta->Generar($em, $strWhere);
                }
            }
        }
        $connection = $em->getConnection();
        $strSql = "SELECT  
                            sql_car_cartera_edades.*
                    FROM
                            sql_car_cartera_edades                       
                    WHERE 1 " . $strWhere;
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $resultados = $statement->fetchAll();
        return $this->render('BrasaCarteraBundle:Consultas/CuentasCobrar:lista.html.twig', array(
                    'arCuentasCobrar' => $resultados,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/consulta/cuentacobrar/resumen/{codigoCuentaCobrar}", name="brs_car_consultas_cuentacobrar_resumen")
     */
    public function resumenAction(Request $request, $codigoCuentaCobrar) {
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
        $arReciboDetalles = new \Brasa\CarteraBundle\Entity\CarReciboDetalle();
        $arReciboDetalles = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoCuentaCobrarFk' => $codigoCuentaCobrar));
        return $this->render('BrasaCarteraBundle:Consultas/CuentasCobrar:resumen.html.twig', array(
                    'arReciboDetalles' => $arReciboDetalles,
                    'arCuentaCobrar' => $arCuentaCobrar
        ));
    }

    private function devFiltro($form) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strWhere = "";
        $arTipo = $form->get('cuentaCobrarTipoRel')->getData();
        if ($arTipo) {
            $strWhere .= " AND codigoCuentaCobrarTipoFk = " . $arTipo->getCodigoCuentaCobrarTipoPk();
        }
        $arAsesor = $form->get('asesorRel')->getData();
        if ($arAsesor) {
            $strWhere .= " AND codigoAsesorFk = " . $arAsesor->getCodigoAsesorPk();
        }
        $intRango = $form->get('rango')->getData();
        if ($intRango != 0) {
            $strWhere .= " AND rango = " . $intRango;
        }
        $nit = $form->get('TxtNit')->getData();
        if ($nit != "") {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $nit));
            if ($arCliente) {
                $strWhere .= " AND codigo_cliente_fk = " . $arCliente->getCodigoClientePk();
            }
        }
        $fecha = $form->get('fechaDesde')->getData();
        if ($fecha) {
            $fecha = $fecha->format('Y-m-d');
            $strWhere .= " AND fecha >= " . $fecha;
        }
        $fecha = $form->get('fechaHasta')->getData();
        if ($fecha) {
            $fecha = $fecha->format('Y-m-d');
            $strWhere .= " AND fecha <= " . $fecha;
        }
        $numero = $form->get('TxtNumero')->getData();
        if ($numero != "") {
            $strWhere .= " AND numeroDocumento = " . $numero;
        }
        return $strWhere;
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $arrayPropiedades = array(
            'class' => 'BrasaCarteraBundle:CarCuentaCobrarTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCuentaCobrarTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarCuentaCobrarTipo", $session->get('filtroCuentaCobrarTipo'));
        }
        $arrayPropiedadesAsesor = array(
            'class' => 'BrasaGeneralBundle:GenAsesor',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                                ->orderBy('a.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroAsesor')) {
            $arrayPropiedadesAsesor['data'] = $em->getReference("BrasaGeneralBundle:GenAsesor", $session->get('filtroAsesor'));
        }
        $form = $this->createFormBuilder()
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroPedidoNumero')))
                ->add('cuentaCobrarTipoRel', EntityType::class, $arrayPropiedades)
                ->add('asesorRel', EntityType::class, $arrayPropiedadesAsesor)
                ->add('rango', ChoiceType::class, array('choices' => array('TODOS' => '0', '1 - 30' => '30', '31 - 60' => '60', '61 - 90' => '90', '91 - 180' => '180')))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
                ->add('BtnPdf', SubmitType::class, array('label' => 'PDF',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnExcel2', SubmitType::class, array('label' => 'Excel cobrar',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel($strWhere) {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'J'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'TIPO')
                ->setCellValue('D1', 'TIPO SERVICIO')
                ->setCellValue('E1', 'FECHA')
                ->setCellValue('F1', 'VENCE')
                ->setCellValue('G1', 'SOPORTE')
                ->setCellValue('H1', 'NIT')
                ->setCellValue('I1', 'CLIENTE')
                ->setCellValue('J1', 'ASESOR')
                ->setCellValue('K1', 'VALOR')
                ->setCellValue('L1', 'SALDO')
                ->setCellValue('M1', 'ABONO')
                ->setCellValue('N1', 'PLAZO')
                ->setCellValue('O1', 'VENCIMIENTO')
                ->setCellValue('P1', 'DIAS')
                ->setCellValue('Q1', 'RANGO')
                ->setCellValue('R1', 'GRUPO')
                ->setCellValue('S1', 'SUBGRUPO')
                ->setCellValue('T1', 'CONTACTO')
                ->setCellValue('U1', 'TELEFONO');                
        $i = 2;
        $connection = $em->getConnection();
        $strSql = "SELECT  
                            sql_car_cartera_edades.*
                    FROM
                            sql_car_cartera_edades                       
                    WHERE 1 " . $strWhere;
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $arCuentasCobrar = $statement->fetchAll();
        foreach ($arCuentasCobrar as $arCuentasCobrar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentasCobrar['codigoCuentaCobrarPk'])
                    ->setCellValue('B' . $i, $arCuentasCobrar['numeroDocumento'])
                    ->setCellValue('C' . $i, $arCuentasCobrar['tipoCuentaCobrar'])
                    ->setCellValue('D' . $i, $arCuentasCobrar['servicioTipo'])
                    ->setCellValue('E' . $i, $arCuentasCobrar['fecha'])
                    ->setCellValue('F' . $i, $arCuentasCobrar['fechaVence'])
                    ->setCellValue('G' . $i, $arCuentasCobrar['soporte'])
                    ->setCellValue('H' . $i, $arCuentasCobrar['nitCliente'])
                    ->setCellValue('I' . $i, $arCuentasCobrar['nombreCliente'])
                    ->setCellValue('J' . $i, $arCuentasCobrar['nombreAsesor'])
                    ->setCellValue('K' . $i, $arCuentasCobrar['valorOriginal'])
                    ->setCellValue('L' . $i, $arCuentasCobrar['saldo'])
                    ->setCellValue('M' . $i, $arCuentasCobrar['abono'])
                    ->setCellValue('N' . $i, $arCuentasCobrar['plazo'])
                    ->setCellValue('O' . $i, $arCuentasCobrar['tipoVencimiento'])
                    ->setCellValue('P' . $i, $arCuentasCobrar['diasVencida'])
                    ->setCellValue('Q' . $i, $arCuentasCobrar['rango'])
                    ->setCellValue('R' . $i, $arCuentasCobrar['grupo'])
                    ->setCellValue('S' . $i, $arCuentasCobrar['subgrupo'])
                    ->setCellValue('T' . $i, $arCuentasCobrar['contacto'])
                    ->setCellValue('U' . $i, $arCuentasCobrar['contactoTelefono']);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentasCobrar');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentasCobrar.xlsx"');
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

    private function generarExcel2($strWhere) {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'U'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'J'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'TIPO')
                ->setCellValue('D1', 'TIPO SERVICIO')
                ->setCellValue('E1', 'FECHA')
                ->setCellValue('F1', 'VENCE')
                ->setCellValue('G1', 'SOPORTE')
                ->setCellValue('H1', 'NIT')
                ->setCellValue('I1', 'CLIENTE')
                ->setCellValue('J1', 'ASESOR')
                ->setCellValue('K1', 'VALOR')
                ->setCellValue('L1', 'SALDO')
                ->setCellValue('M1', 'ABONO')
                ->setCellValue('N1', 'PLAZO')
                ->setCellValue('O1', 'VENCIMIENTO')
                ->setCellValue('P1', 'DIAS')
                ->setCellValue('Q1', 'RANGO')
                ->setCellValue('R1', 'GRUPO')
                ->setCellValue('S1', 'SUBGRUPO')
                ->setCellValue('T1', 'CONTACTO')
                ->setCellValue('U1', 'TELEFONO');

        $i = 2;
        $connection = $em->getConnection();
        $strSql = "SELECT  
                            sql_car_cartera_edades.*
                    FROM
                            sql_car_cartera_edades                       
                    WHERE 1 " . $strWhere;
        $statement = $connection->prepare($strSql);
        $statement->execute();
        $arCuentasCobrar = $statement->fetchAll();
        foreach ($arCuentasCobrar as $arCuentasCobrar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentasCobrar['codigoCuentaCobrarPk'])
                    ->setCellValue('B' . $i, $arCuentasCobrar['numeroDocumento'])
                    ->setCellValue('C' . $i, $arCuentasCobrar['tipoCuentaCobrar'])
                    ->setCellValue('D' . $i, $arCuentasCobrar['servicioTipo'])
                    ->setCellValue('E' . $i, $arCuentasCobrar['fecha'])
                    ->setCellValue('F' . $i, $arCuentasCobrar['fechaVence'])
                    ->setCellValue('G' . $i, $arCuentasCobrar['soporte'])
                    ->setCellValue('H' . $i, $arCuentasCobrar['nitCliente'])
                    ->setCellValue('I' . $i, $arCuentasCobrar['nombreCliente'])
                    ->setCellValue('J' . $i, $arCuentasCobrar['nombreAsesor'])
                    ->setCellValue('K' . $i, $arCuentasCobrar['valorOriginal'])
                    ->setCellValue('L' . $i, $arCuentasCobrar['saldo'])
                    ->setCellValue('M' . $i, $arCuentasCobrar['abono'])
                    ->setCellValue('N' . $i, $arCuentasCobrar['plazo'])
                    ->setCellValue('O' . $i, $arCuentasCobrar['tipoVencimiento'])
                    ->setCellValue('P' . $i, $arCuentasCobrar['diasVencida'])
                    ->setCellValue('Q' . $i, $arCuentasCobrar['rango'])
                    ->setCellValue('R' . $i, $arCuentasCobrar['grupo'])
                    ->setCellValue('S' . $i, $arCuentasCobrar['subgrupo'])
                    ->setCellValue('T' . $i, $arCuentasCobrar['contacto'])
                    ->setCellValue('U' . $i, $arCuentasCobrar['contactoTelefono']);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentasCobrar');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentasCobrar.xlsx"');
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
