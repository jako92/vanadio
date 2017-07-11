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
                    $this->cargarTurno($form);
                } else {
                    $objMensaje->Mensaje('error', "Por favor cargar un archivo adjunto valido");
                }
            }
        }
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->listaDql();
        $arProgramacionesImportar = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:importar.html.twig', array(
                    'arProgramacionesImportar' => $arProgramacionesImportar,
                    'form' => $form->createView()));
    }

    private function formularioLista() {

        $form = $this->createFormBuilder()
                ->add('attachment', FileType::class)
                ->add('BtnCargar', SubmitType::class, array('label' => 'Cargar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->getForm();
        return $form;
    }

    private function cargarTurno($form) {
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
                $arrTemporal = array('codigoRecurso' => $codigoRecurso);
                for ($i = 1; $i <= 31; $i++) {
                    $cell = $worksheet->getCellByColumnAndRow($i, $row);
                    $turno = $cell->getValue();
                    $arrTemporal[$i] = $turno;
                }
                $arrCargas[] = $arrTemporal;
            }
        }

        //Validar que el turno exista
        foreach ($arrCargas as $arrCarga) {
            for ($i = 1; $i <= 31; $i++) {
                if (!$this->validarTurno($arrCarga[$i])) {
                    $error = " el turno " . $arrCarga[$i] . " no existe.";
                }
            }
        }

        if ($error != "") {
            $objMensaje->Mensaje('error', "Error al cargar:" . $error, $this);
        } else {
            foreach ($arrCargas as $arrCarga) {
                $arProgramacionImportar = new \Brasa\TurnoBundle\Entity\TurProgramacionImportar();
                $arProgramacionImportar->setCodigoRecursoFk($arrCarga['codigoRecurso']);
                $arProgramacionImportar->setDia1($arrCarga[1]);
                $arProgramacionImportar->setDia2($arrCarga[2]);
                $arProgramacionImportar->setDia3($arrCarga[3]);
                $arProgramacionImportar->setDia4($arrCarga[4]);
                $arProgramacionImportar->setDia5($arrCarga[5]);
                $arProgramacionImportar->setDia6($arrCarga[6]);
                $arProgramacionImportar->setDia7($arrCarga[7]);
                $arProgramacionImportar->setDia8($arrCarga[8]);
                $arProgramacionImportar->setDia9($arrCarga[9]);
                $arProgramacionImportar->setDia10($arrCarga[10]);
                $arProgramacionImportar->setDia11($arrCarga[11]);
                $arProgramacionImportar->setDia12($arrCarga[12]);
                $arProgramacionImportar->setDia13($arrCarga[13]);
                $arProgramacionImportar->setDia14($arrCarga[14]);
                $arProgramacionImportar->setDia15($arrCarga[15]);
                $arProgramacionImportar->setDia16($arrCarga[16]);
                $arProgramacionImportar->setDia17($arrCarga[17]);
                $arProgramacionImportar->setDia18($arrCarga[18]);
                $arProgramacionImportar->setDia19($arrCarga[19]);
                $arProgramacionImportar->setDia20($arrCarga[20]);
                $arProgramacionImportar->setDia21($arrCarga[21]);
                $arProgramacionImportar->setDia22($arrCarga[22]);
                $arProgramacionImportar->setDia23($arrCarga[23]);
                $arProgramacionImportar->setDia24($arrCarga[24]);
                $arProgramacionImportar->setDia25($arrCarga[25]);
                $arProgramacionImportar->setDia26($arrCarga[26]);
                $arProgramacionImportar->setDia27($arrCarga[27]);
                $arProgramacionImportar->setDia28($arrCarga[28]);
                $arProgramacionImportar->setDia29($arrCarga[29]);
                $arProgramacionImportar->setDia30($arrCarga[30]);
                $arProgramacionImportar->setDia31($arrCarga[31]);
                $em->persist($arProgramacionImportar);
            }
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
    }

    private function validarTurno($codigoTurno) {
        $em = $this->getDoctrine()->getManager();
        $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        return $arTurno;
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
        for ($col = 'A'; $col !== 'AG'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO RECURSO')
                ->setCellValue('B1', '1')
                ->setCellValue('C1', '2')
                ->setCellValue('D1', '3')
                ->setCellValue('E1', '4')
                ->setCellValue('F1', '5')
                ->setCellValue('G1', '6')
                ->setCellValue('H1', '7')
                ->setCellValue('I1', '8')
                ->setCellValue('J1', '9')
                ->setCellValue('K1', '10')
                ->setCellValue('L1', '11')
                ->setCellValue('M1', '12')
                ->setCellValue('N1', '13')
                ->setCellValue('O1', '14')
                ->setCellValue('P1', '15')
                ->setCellValue('Q1', '16')
                ->setCellValue('R1', '17')
                ->setCellValue('S1', '18')
                ->setCellValue('T1', '19')
                ->setCellValue('U1', '20')
                ->setCellValue('V1', '21')
                ->setCellValue('W1', '22')
                ->setCellValue('X1', '23')
                ->setCellValue('Y1', '24')
                ->setCellValue('Z1', '25')
                ->setCellValue('AA1', '26')
                ->setCellValue('AB1', '27')
                ->setCellValue('AC1', '28')
                ->setCellValue('AD1', '29')
                ->setCellValue('AE1', '30')
                ->setCellValue('AF1', '31');

        $i = 2;
        $arProgramacionesImportar = new \Brasa\TurnoBundle\Entity\TurProgramacionImportar();
        $arProgramacionesImportar = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->findAll();
        foreach ($arProgramacionesImportar as $arProgramacionImportar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionImportar->getCodigoRecursoFk())
                    ->setCellValue('B' . $i, $arProgramacionImportar->getDia1())
                    ->setCellValue('C' . $i, $arProgramacionImportar->getDia2())
                    ->setCellValue('D' . $i, $arProgramacionImportar->getDia3())
                    ->setCellValue('E' . $i, $arProgramacionImportar->getDia4())
                    ->setCellValue('F' . $i, $arProgramacionImportar->getDia5())
                    ->setCellValue('G' . $i, $arProgramacionImportar->getDia6())
                    ->setCellValue('H' . $i, $arProgramacionImportar->getDia7())
                    ->setCellValue('I' . $i, $arProgramacionImportar->getDia8())
                    ->setCellValue('J' . $i, $arProgramacionImportar->getDia9())
                    ->setCellValue('K' . $i, $arProgramacionImportar->getDia10())
                    ->setCellValue('L' . $i, $arProgramacionImportar->getDia11())
                    ->setCellValue('M' . $i, $arProgramacionImportar->getDia12())
                    ->setCellValue('N' . $i, $arProgramacionImportar->getDia13())
                    ->setCellValue('O' . $i, $arProgramacionImportar->getDia14())
                    ->setCellValue('P' . $i, $arProgramacionImportar->getDia15())
                    ->setCellValue('Q' . $i, $arProgramacionImportar->getDia16())
                    ->setCellValue('R' . $i, $arProgramacionImportar->getDia17())
                    ->setCellValue('S' . $i, $arProgramacionImportar->getDia18())
                    ->setCellValue('T' . $i, $arProgramacionImportar->getDia19())
                    ->setCellValue('U' . $i, $arProgramacionImportar->getDia20())
                    ->setCellValue('V' . $i, $arProgramacionImportar->getDia21())
                    ->setCellValue('W' . $i, $arProgramacionImportar->getDia22())
                    ->setCellValue('X' . $i, $arProgramacionImportar->getDia23())
                    ->setCellValue('Y' . $i, $arProgramacionImportar->getDia24())
                    ->setCellValue('Z' . $i, $arProgramacionImportar->getDia25())
                    ->setCellValue('AA' . $i, $arProgramacionImportar->getDia26())
                    ->setCellValue('AB' . $i, $arProgramacionImportar->getDia27())
                    ->setCellValue('AC' . $i, $arProgramacionImportar->getDia28())
                    ->setCellValue('AD' . $i, $arProgramacionImportar->getDia29())
                    ->setCellValue('AE' . $i, $arProgramacionImportar->getDia30())
                    ->setCellValue('AF' . $i, $arProgramacionImportar->getDia31());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Turnos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Turnos.xlsx"');
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
