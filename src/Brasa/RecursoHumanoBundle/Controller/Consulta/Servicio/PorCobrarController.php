<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta\Servicio;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PorCobrarController extends Controller {
    var $strSqlServiciosPorCobrarLista = "";

    /**
     * @Route("/rhu/consultas/servicios/cobrar", name="brs_rhu_consultas_servicios_cobrar")
     */
    public function serviciosCobrarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 38)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioServiciosPorCobrarLista();
        $form->handleRequest($request);
        $this->ServiciosPorCobrarListar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnExcelServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $this->generarServiciosPorCobrarExcel();
            }
            if ($form->get('BtnPDFServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $objReporteServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteServiciosPorCobrar();
                $objReporteServiciosPorCobrar->Generar($this, $em, $this->strSqlServiciosPorCobrarLista);
            }
            if ($form->get('BtnFiltrarServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
            }
        }
        $arServiciosPorCobrar = $paginator->paginate($em->createQuery($this->strSqlServiciosPorCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Servicios:porCobrar.html.twig', array(
                    'arServiciosPorCobrar' => $arServiciosPorCobrar,
                    'form' => $form->createView()
        ));
    }

    private function ServiciosPorCobrarListar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlServiciosPorCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->listaServiciosPorCobrarDQL(
                $session->get('filtroCodigoCentroCosto'), $session->get('filtroIdentificacion'), $session->get('filtroDesde'), $session->get('filtroHasta')
        );
    }

    private function formularioServiciosPorCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $arrayPropiedades = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
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
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
                ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
                ->add('TxtIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => DateType::class,)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => DateType::class,)))
                ->add('BtnFiltrarServiciosPorCobrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcelServiciosPorCobrar', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnPDFServiciosPorCobrar', SubmitType::class, array('label' => 'PDF',))
                ->getForm();
        return $form;
    }

    private function filtrarServiciosPorCobrarLista($form) {
        $session = new Session;

        $codigoCentroCosto = "";
        if ($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        //$session->set('filtroDesde', $form->get('fechaDesde')->getData());
        //$session->set('filtroHasta', $form->get('fechaHasta')->getData());

        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null) {
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
    }

    private function generarServiciosPorCobrarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'GRUPO PAGO')
                ->setCellValue('C1', 'IDENTIFICACION')
                ->setCellValue('D1', 'EMPLEADO')
                ->setCellValue('E1', 'BASICO')
                ->setCellValue('F1', 'PRESTACIONAL')
                ->setCellValue('G1', 'NO_PRESTACIONAL')
                ->setCellValue('H1', 'TTE')
                ->setCellValue('I1', 'PENSION')
                ->setCellValue('J1', 'SALUD')
                ->setCellValue('K1', 'RIESGOS')
                ->setCellValue('L1', 'CAJA')
                ->setCellValue('M1', 'SENA')
                ->setCellValue('N1', 'ICBF')
                ->setCellValue('O1', 'PRESTACIONES')
                ->setCellValue('P1', 'VACACIONES')
                ->setCellValue('Q1', 'A_PARAFISCALES')
                ->setCellValue('R1', 'ADMON');

        $i = 2;
        $query = $em->createQuery($this->strSqlServiciosPorCobrarLista);
        $arServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arServiciosPorCobrar = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        for ($col = 'E'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arServiciosPorCobrar as $arServiciosPorCobrar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServiciosPorCobrar->getCodigoServicioCobrarPk())
                    ->setCellValue('B' . $i, $arServiciosPorCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('C' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arServiciosPorCobrar->getVrSalario())
                    ->setCellValue('F' . $i, $arServiciosPorCobrar->getVrPrestacional())
                    ->setCellValue('G' . $i, $arServiciosPorCobrar->getVrNoPrestacional())
                    ->setCellValue('H' . $i, $arServiciosPorCobrar->getVrAuxilioTransporte())
                    ->setCellValue('I' . $i, $arServiciosPorCobrar->getVrPension())
                    ->setCellValue('J' . $i, $arServiciosPorCobrar->getVrSalud())
                    ->setCellValue('K' . $i, $arServiciosPorCobrar->getVrRiesgos())
                    ->setCellValue('L' . $i, $arServiciosPorCobrar->getVrCaja())
                    ->setCellValue('M' . $i, $arServiciosPorCobrar->getVrSena())
                    ->setCellValue('N' . $i, $arServiciosPorCobrar->getVrIcbf())
                    ->setCellValue('O' . $i, $arServiciosPorCobrar->getVrPrestaciones())
                    ->setCellValue('P' . $i, $arServiciosPorCobrar->getVrVacaciones())
                    ->setCellValue('Q' . $i, $arServiciosPorCobrar->getVrAporteParafiscales())
                    ->setCellValue('R' . $i, $arServiciosPorCobrar->getVrAdministracion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ServiciosPorCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaServiciosPorCobrar.xlsx"');
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
