<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Programacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImportarController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/tur/utilidad/programacion/importar", name="brs_tur_utilidad_programacion_importar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 87)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            if ($form->get('BtnCargar')->isClicked()) {
                if ($form['attachment']->getData()) {
                    $this->cargarProgramacion($form);
                } else {
                    $objMensaje->Mensaje('error', "Por favor cargar un archivo adjunto valido");
                }
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->eliminar($arrSeleccionados);
                if ($strResultado) {
                    $objMensaje->Mensaje("error", $strResultado);
                }
            }
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->listaDql();
        $arProgramacionesImportar = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 500);
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:importar.html.twig', array(
                    'arProgramacionesImportar' => $arProgramacionesImportar,
                    'form' => $form->createView()));
    }

    private function formularioLista() {

        $form = $this->createFormBuilder()
                ->add('attachment', FileType::class)
                ->add('BtnCargar', SubmitType::class, array('label' => 'Cargar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->getForm();
        return $form;
    }

    private function cargarProgramacion($form) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $error = "";
        $form['attachment']->getData()->move($arConfiguracion->getRutaTemporal(), "archivo.xls");
        $ruta = $arConfiguracion->getRutaTemporal() . "archivo.xls";
        $arrCarga = array();
        $objPHPExcel = \PHPExcel_IOFactory::load($ruta);
        //Cargar informacion
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            for ($row = 2; $row <= $highestRow; ++$row) {
                $cell = $worksheet->getCellByColumnAndRow(0, $row);
                $codigoRecurso = $cell->getValue();
                $cell = $worksheet->getCellByColumnAndRow(1, $row);
                $codigoPedidoDetalle = $cell->getValue();
                $cell = $worksheet->getCellByColumnAndRow(2, $row);
                $anio = $cell->getValue();
                $cell = $worksheet->getCellByColumnAndRow(3, $row);
                $mes = $cell->getValue();
                $arrTemporal = array('codigoRecurso' => $codigoRecurso,
                    'codigoPedidoDetalle' => $codigoPedidoDetalle, 'anio' => $anio, 'mes' => $mes);
                for ($i = 4; $i <= 34; $i++) {
                    $cell = $worksheet->getCellByColumnAndRow($i, $row);
                    $turno = $cell->getValue();
                    $arrTemporal[$i] = $turno;
                }
                $arrCargas[] = $arrTemporal;
            }
        }

        foreach ($arrCargas as $arrCarga) {
            $error = false;
            $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arrCarga['codigoPedidoDetalle']);
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arPedidoDetalle->getCodigoPuestoFk());
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrCarga['codigoRecurso']);
            $validar = $this->validarHoras($arrCarga);
            if (!$arPedidoDetalle) {
                $error = true;
                $objMensaje->Mensaje("error", 'El codigo pedido detalle '.$arrCarga['codigoPedidoDetalle'].' no esxiste');
            }
            if (!$arPuesto) {
                $error = true;
                $objMensaje->Mensaje("error", $validar['mensaje']);
                $objMensaje->Mensaje("error", 'El codigo puesto '.$arPedidoDetalle->getCodigoPuestoFk().' no esxiste');
            }
            if (!$arRecurso) {
                $error = true;
                $objMensaje->Mensaje("error", $validar['mensaje']);
                $objMensaje->Mensaje("error", 'El codigo recurso '.$arrCarga['codigoRecurso'].' no esxiste');
            }
            if ($error == false) {
                if ($validar['validado']) {
                    $arProgramacionImportar = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->findOneBy(array('codigoRecursoFk' => $arRecurso->getCodigoRecursoPk(), 'codigoPedidoDetalleFk' => $arPedidoDetalle->getCodigoPedidoDetallePk()));
                    if (!$arProgramacionImportar) {
                        $arProgramacionImportar = new \Brasa\TurnoBundle\Entity\TurProgramacionImportar();
                    }
                    $arProgramacionImportar->setCodigoClienteFk($arPedidoDetalle->getPedidoRel()->getCodigoClienteFk());
                    $arProgramacionImportar->setNombreCliente($arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto());
                    $arProgramacionImportar->setCodigoPuestoFk($arPuesto->getCodigoPuestoPk());
                    $arProgramacionImportar->setNombrePuesto($arPuesto->getNombre());
                    $arProgramacionImportar->setCodigoRecursoFk($arRecurso->getCodigoRecursoPk());
                    $arProgramacionImportar->setNombreRecurso($arRecurso->getNombreCorto());
                    $arProgramacionImportar->setCodigoPedidoDetalleFk($arPedidoDetalle->getCodigoPedidoDetallePk());
                    $arProgramacionImportar->setAnio($arrCarga['anio']);
                    $arProgramacionImportar->setMes($arrCarga['mes']);
                    $arProgramacionImportar->setDia1($arrCarga[4]);
                    $arProgramacionImportar->setDia2($arrCarga[5]);
                    $arProgramacionImportar->setDia3($arrCarga[6]);
                    $arProgramacionImportar->setDia4($arrCarga[7]);
                    $arProgramacionImportar->setDia5($arrCarga[8]);
                    $arProgramacionImportar->setDia6($arrCarga[9]);
                    $arProgramacionImportar->setDia7($arrCarga[10]);
                    $arProgramacionImportar->setDia8($arrCarga[11]);
                    $arProgramacionImportar->setDia9($arrCarga[12]);
                    $arProgramacionImportar->setDia10($arrCarga[13]);
                    $arProgramacionImportar->setDia11($arrCarga[14]);
                    $arProgramacionImportar->setDia12($arrCarga[15]);
                    $arProgramacionImportar->setDia13($arrCarga[16]);
                    $arProgramacionImportar->setDia14($arrCarga[17]);
                    $arProgramacionImportar->setDia15($arrCarga[18]);
                    $arProgramacionImportar->setDia16($arrCarga[19]);
                    $arProgramacionImportar->setDia17($arrCarga[20]);
                    $arProgramacionImportar->setDia18($arrCarga[21]);
                    $arProgramacionImportar->setDia19($arrCarga[22]);
                    $arProgramacionImportar->setDia20($arrCarga[23]);
                    $arProgramacionImportar->setDia21($arrCarga[24]);
                    $arProgramacionImportar->setDia22($arrCarga[25]);
                    $arProgramacionImportar->setDia23($arrCarga[26]);
                    $arProgramacionImportar->setDia24($arrCarga[27]);
                    $arProgramacionImportar->setDia25($arrCarga[28]);
                    $arProgramacionImportar->setDia26($arrCarga[29]);
                    $arProgramacionImportar->setDia27($arrCarga[30]);
                    $arProgramacionImportar->setDia28($arrCarga[31]);
                    $arProgramacionImportar->setDia29($arrCarga[32]);
                    $arProgramacionImportar->setDia30($arrCarga[33]);
                    $arProgramacionImportar->setDia31($arrCarga[34]);
                    $arProgramacionImportar->setHorasDiurnas($validar['horasDiurnas']);
                    $arProgramacionImportar->setHorasNocturnas($validar['horasNocturnas']);
                    $arProgramacionImportar->setHoras(($validar['horasDiurnas'] + $validar['horasNocturnas']));
                    $arProgramacionImportar->setHorasPedido($arPedidoDetalle->getPedidoRel()->getHoras());
                    $arProgramacionImportar->setHorasDiurnasPedido($arPedidoDetalle->getPedidoRel()->getHorasDiurnas());
                    $arProgramacionImportar->setHorasNocturnasPedido($arPedidoDetalle->getPedidoRel()->getHorasNocturnas());
                    $em->persist($arProgramacionImportar);
                } else {
                    $error = true;
                    $objMensaje->Mensaje("error", $validar['mensaje']);
                }
            }
        }
        $em->flush();
    }

    private function validarTurno($codigoTurno) {
        $em = $this->getDoctrine()->getManager();
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'errado' => false);
        if ($codigoTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
            if ($arTurno) {
                $arrTurno['turno'] = $codigoTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            } else {
                $arrTurno['errado'] = true;
            }
        }

        return $arrTurno;
    }

    private function validarHoras($arrCarga) {
        $arrDetalle = array('validado' => true, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'mensaje' => '');
        $horasDiurnas = 0;
        $horasNocturnas = 0;

        for ($i = 4; $i <= 34; $i++) {
            $codigoTurno = $arrCarga[$i];
            if ($codigoTurno != '') {
                $arrTurno = $this->validarTurno($codigoTurno);
                if ($arrTurno['errado'] == true) {
                    $arrDetalle['validado'] = false;
                    $arrDetalle['mensaje'] = "El turno " . $codigoTurno . " no esta creado";
                    break;
                }
                $horasDiurnas += $arrTurno['horasDiurnas'];
                $horasNocturnas += $arrTurno['horasNocturnas'];
            }
        }
        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        return $arrDetalle;
    }

    private function generarExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AK'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO RECURSO')
                ->setCellValue('B1', 'CODIGO PEDIDO DETALLE')
                ->setCellValue('C1', 'AÑO')
                ->setCellValue('D1', 'MES')
                ->setCellValue('E1', '1')
                ->setCellValue('F1', '2')
                ->setCellValue('G1', '3')
                ->setCellValue('H1', '4')
                ->setCellValue('I1', '5')
                ->setCellValue('J1', '6')
                ->setCellValue('K1', '7')
                ->setCellValue('L1', '8')
                ->setCellValue('M1', '9')
                ->setCellValue('N1', '10')
                ->setCellValue('O1', '11')
                ->setCellValue('P1', '12')
                ->setCellValue('Q1', '13')
                ->setCellValue('R1', '14')
                ->setCellValue('S1', '15')
                ->setCellValue('T1', '16')
                ->setCellValue('U1', '17')
                ->setCellValue('V1', '18')
                ->setCellValue('W1', '19')
                ->setCellValue('X1', '20')
                ->setCellValue('Y1', '21')
                ->setCellValue('Z1', '22')
                ->setCellValue('AA1', '23')
                ->setCellValue('AB1', '24')
                ->setCellValue('AC1', '25')
                ->setCellValue('AD1', '26')
                ->setCellValue('AE1', '27')
                ->setCellValue('AF1', '28')
                ->setCellValue('AG1', '29')
                ->setCellValue('AH1', '30')
                ->setCellValue('AI1', '31');

        $i = 2;
        $arProgramacionesImportar = new \Brasa\TurnoBundle\Entity\TurProgramacionImportar();
        $arProgramacionesImportar = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->findAll();
        foreach ($arProgramacionesImportar as $arProgramacionImportar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionImportar->getCodigoRecursoFk())
                    ->setCellValue('B' . $i, $arProgramacionImportar->getCodigoPedidoDetalleFk())
                    ->setCellValue('C' . $i, $arProgramacionImportar->getAnio())
                    ->setCellValue('D' . $i, $arProgramacionImportar->getMes())
                    ->setCellValue('E' . $i, $arProgramacionImportar->getDia1())
                    ->setCellValue('F' . $i, $arProgramacionImportar->getDia2())
                    ->setCellValue('G' . $i, $arProgramacionImportar->getDia3())
                    ->setCellValue('H' . $i, $arProgramacionImportar->getDia4())
                    ->setCellValue('I' . $i, $arProgramacionImportar->getDia5())
                    ->setCellValue('J' . $i, $arProgramacionImportar->getDia6())
                    ->setCellValue('K' . $i, $arProgramacionImportar->getDia7())
                    ->setCellValue('L' . $i, $arProgramacionImportar->getDia8())
                    ->setCellValue('M' . $i, $arProgramacionImportar->getDia9())
                    ->setCellValue('N' . $i, $arProgramacionImportar->getDia10())
                    ->setCellValue('O' . $i, $arProgramacionImportar->getDia11())
                    ->setCellValue('P' . $i, $arProgramacionImportar->getDia12())
                    ->setCellValue('Q' . $i, $arProgramacionImportar->getDia13())
                    ->setCellValue('R' . $i, $arProgramacionImportar->getDia14())
                    ->setCellValue('S' . $i, $arProgramacionImportar->getDia15())
                    ->setCellValue('T' . $i, $arProgramacionImportar->getDia16())
                    ->setCellValue('U' . $i, $arProgramacionImportar->getDia17())
                    ->setCellValue('V' . $i, $arProgramacionImportar->getDia18())
                    ->setCellValue('W' . $i, $arProgramacionImportar->getDia19())
                    ->setCellValue('X' . $i, $arProgramacionImportar->getDia20())
                    ->setCellValue('Y' . $i, $arProgramacionImportar->getDia21())
                    ->setCellValue('Z' . $i, $arProgramacionImportar->getDia22())
                    ->setCellValue('AA' . $i, $arProgramacionImportar->getDia23())
                    ->setCellValue('AB' . $i, $arProgramacionImportar->getDia24())
                    ->setCellValue('AC' . $i, $arProgramacionImportar->getDia25())
                    ->setCellValue('AD' . $i, $arProgramacionImportar->getDia26())
                    ->setCellValue('AE' . $i, $arProgramacionImportar->getDia27())
                    ->setCellValue('AF' . $i, $arProgramacionImportar->getDia28())
                    ->setCellValue('AG' . $i, $arProgramacionImportar->getDia29())
                    ->setCellValue('AH' . $i, $arProgramacionImportar->getDia30())
                    ->setCellValue('AI' . $i, $arProgramacionImportar->getDia31());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionPlantilla');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ProgramacionPlantilla.xlsx"');
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
