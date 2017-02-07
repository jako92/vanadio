<?php

namespace Brasa\TurnoBundle\Controller\Consulta;

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

class SoportePagoDetalleController extends Controller {

    var $strListaDql = "";
    var $codigoSoportePago = "";
    var $codigoRecurso = "";

    /**
     * @Route("/tur/consulta/soporte/pago/detalle", name="brs_tur_consulta_soporte_pago_detalle")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 43)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($form->isValid()) {
            $arrControles = $request->request->All();
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
        $arSoportePagoDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Consultas/SoportePago:detalle.html.twig', array(
                    'arSoportePagoDetalles' => $arSoportePagoDetalles,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroSoportePagoDetalleFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroSoportePagoDetalleFechaDesde');
            $strFechaHasta = $session->get('filtroSoportePagoDetalleFechaHasta');
        }
        $this->strListaDql = $em->getRepository('BrasaTurnoBundle:TurSoportePagoDetalle')->listaConsultaDql(
                $this->codigoSoportePago, $session->get('filtroCodigoRecurso'), $strFechaDesde, $strFechaHasta, $session->get('filtroCodigoTurno')
        );
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroSoportePagoDetalleFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroSoportePagoDetalleFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroSoportePagoDetalleFiltrarFecha', $form->get('filtrarFecha')->getData());
        $session->set('filtroCodigoTurno', $form->get('TxtCodigoTurno')->getData());                     
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreRecurso = "";
        if ($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if ($arRecurso) {
                $strNombreRecurso = $arRecurso->getNombreCorto();
            } else {
                $session->set('filtroCodigoRecurso', null);
            }
        }        
        $dateFecha = new \DateTime('now');
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        if ($session->get('filtroSoportePagoDetalleFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroSoportePagoDetalleFechaDesde');
        }
        if ($session->get('filtroSoportePagoDetalleFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroSoportePagoDetalleFechaHasta');
        }
        $dateFechaHasta = date_create($strFechaHasta);
        $dateFechaDesde = date_create($strFechaDesde);
        $form = $this->createFormBuilder()
                ->add('TxtCodigoRecurso', TextType::class, array('data' => $session->get('filtroCodigoRecurso')))
                ->add('TxtNombreRecurso', TextType::class, array('data' => $strNombreRecurso))
                ->add('TxtCodigoTurno', TextType::class, array('data' => $session->get('filtroCodigoTurno')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroSoportePagoDetalleFiltrarFecha')))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
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

        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AB'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIG0')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'CEDULA')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'TURNO')
                ->setCellValue('F1', 'DIAS')
                ->setCellValue('G1', 'DESCANSO')
                ->setCellValue('H1', 'HDS')
                ->setCellValue('I1', 'HD')
                ->setCellValue('J1', 'HN')
                ->setCellValue('K1', 'HFD')
                ->setCellValue('L1', 'HFN')
                ->setCellValue('M1', 'HEOD')
                ->setCellValue('N1', 'HEON')
                ->setCellValue('O1', 'HEFD')
                ->setCellValue('P1', 'HEFN')
                ->setCellValue('Q1', 'H');

        $i = 2;

        $query = $em->createQuery($this->strListaDql);
        $arSoportesPagoDetalle = new \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle();
        $arSoportesPagoDetalle = $query->getResult();

        foreach ($arSoportesPagoDetalle as $arSoportePagoDetalle) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportePagoDetalle->getCodigoSoportePagoDetallePk())
                    ->setCellValue('B' . $i, $arSoportePagoDetalle->getrecursoRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arSoportePagoDetalle->getrecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arSoportePagoDetalle->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arSoportePagoDetalle->getTurnoRel()->getNombre())
                    ->setCellValue('F' . $i, $arSoportePagoDetalle->getDias())
                    ->setCellValue('G' . $i, $arSoportePagoDetalle->getDescanso())
                    ->setCellValue('H' . $i, $arSoportePagoDetalle->getHorasDescanso())
                    ->setCellValue('I' . $i, $arSoportePagoDetalle->getHorasDiurnas())
                    ->setCellValue('J' . $i, $arSoportePagoDetalle->getHorasNocturnas())
                    ->setCellValue('K' . $i, $arSoportePagoDetalle->getHorasFestivasDiurnas())
                    ->setCellValue('L' . $i, $arSoportePagoDetalle->getHorasFestivasNocturnas())
                    ->setCellValue('M' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('N' . $i, $arSoportePagoDetalle->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('O' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('P' . $i, $arSoportePagoDetalle->getHorasExtrasFestivasNocturnas())
                    ->setCellValue('Q' . $i, $arSoportePagoDetalle->getHoras());


            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePagoDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportePagoDetalle.xlsx"');
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
