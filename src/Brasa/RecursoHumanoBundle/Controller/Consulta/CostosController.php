<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CostosController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/consulta/costo", name="brs_rhu_consulta_costo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 13)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listarCostosGeneral();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrarLista($form);
                    $this->listarCostosGeneral();
                    $this->generarExcel();
                }
                if ($form->get('BtnPDF')->isClicked()) {
                    $this->filtrarLista($form);
                    $this->listarCostosGeneral();
                    $objReporteCostos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCostos();
                    $objReporteCostos->Generar($this, $em, $this->strSqlLista);
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrarLista($form);
                    $this->listarCostosGeneral();
                }
            }
        }
        $arCostos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Costo:lista.html.twig', array(
                    'arCostos' => $arCostos,
                    'form' => $form->createView()
        ));
    }

    private function listarCostosGeneral() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCosto')->listaDql(
                $session->get('filtroRhuAnio'), $session->get('filtroRhuMes')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
           $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            } else {
                $session->set('filtroIdentificacion', null);
                
            }
        }
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
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('TxtAnio', TextType::class, array('data' => $session->get('filtroRhuAnio')))
                ->add('TxtMes', TextType::class, array('data' => $session->get('filtroRhuMes')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnPDF', SubmitType::class, array('label' => 'PDF',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $codigoCentroCosto = "";
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        if ($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroRhuMes', $form->get('TxtMes')->getData());
        $session->set('filtroRhuAnio', $form->get('TxtAnio')->getData());
    }

    private function generarExcel() {
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
        for ($col = 'A'; $col !== 'J'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'F'; $col !== 'J'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'ANIO')
                ->setCellValue('C1', 'MES')
                ->setCellValue('D1', 'IDENTIFICACION')
                ->setCellValue('E1', 'NOMBRE')
                ->setCellValue('F1', 'NOMINA')
                ->setCellValue('G1', 'S_SOCIAL')
                ->setCellValue('H1', 'PRESTACIONES')
                ->setCellValue('I1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCosto();
        $arCostos = $query->getResult();
        foreach ($arCostos as $arCosto) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCosto->getCodigoCostoPk())
                    ->setCellValue('B' . $i, $arCosto->getAnio())
                    ->setCellValue('C' . $i, $arCosto->getMes())
                    ->setCellValue('D' . $i, $arCosto->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arCosto->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arCosto->getVrNomina())
                    ->setCellValue('G' . $i, $arCosto->getVrSeguridadSocial())
                    ->setCellValue('H' . $i, $arCosto->getVrPrestacion())
                    ->setCellValue('I' . $i, $arCosto->getVrTotal());
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

}
