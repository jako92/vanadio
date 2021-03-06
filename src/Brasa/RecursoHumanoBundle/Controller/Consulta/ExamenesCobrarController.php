<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ExamenesCobrarController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/consultas/examen/cobrar", name="brs_rhu_consultas_examen_cobrar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 32)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arExamenes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Examen:porCobrar.html.twig', array(
                    'arExamenes' => $arExamenes,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroExamenFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('fechaDesde');
            $strFechaHasta = $session->get('fechaHasta');
        }
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->pendienteCobrarConsulta(
                $session->get('filtroIdentificacion'),
                $strFechaDesde, 
                $strFechaHasta,
                $session->get('filtroCodigoExamenClase')
                );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('fechaDesde') != "") {
            $strFechaDesde = $session->get('fechaDesde');
        }
        if ($session->get('fechaHasta') != "") {
            $strFechaHasta = $session->get('fechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        
        $arrayPropiedadesCentroCosto = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuExamenClase',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ec')
                                ->orderBy('ec.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoExamenClase')) {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuExamenClase", $session->get('filtroCodigoExamenClase'));
        }

        $form = $this->createFormBuilder()
                ->add('examenClaseRel', EntityType::class, $arrayPropiedadesCentroCosto)
                ->add('TxtIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroExamenFiltrarFecha')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $codigoExamenClase = '';
        if ($form->get('examenClaseRel')->getData()) {
            $codigoExamenClase = $form->get('examenClaseRel')->getData()->getCodigoExamenClasePk();
        }
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('fechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('fechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroExamenFiltrarFecha', $form->get('filtrarFecha')->getData());
        $session->set('filtroCodigoExamenClase', $codigoExamenClase);
    }

    private function generarExcel() {
        ob_clean();
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
                ->setCellValue('B1', 'CENTRO COSTO')
                ->setCellValue('C1', 'IDENTIFICACIÓN')
                ->setCellValue('D1', 'NOMBRE')
                ->setCellValue('E1', 'CARGO')
                ->setCellValue('F1', 'FECHA')
                ->setCellValue('G1', 'EXAMEN')
                ->setCellValue('H1', 'ENTIDAD EXAMEN')
                ->setCellValue('I1', 'CIUDAD')
                ->setCellValue('J1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arExamenesPendientePago = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenesPendientePago = $query->getResult();
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        for ($col = 'j'; $col !== 'k'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('rigth');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        foreach ($arExamenesPendientePago as $arExamenPendientePago) {
            if ($arExamenPendientePago->getCentroCostoRel()) {
                $nombreCentroCosto = $arExamenPendientePago->getCentroCostoRel()->getNombre();
            } else {
                $nombreCentroCosto = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arExamenPendientePago->getCodigoExamenPk())
                    ->setCellValue('B' . $i, $nombreCentroCosto)
                    ->setCellValue('C' . $i, $arExamenPendientePago->getIdentificacion())
                    ->setCellValue('D' . $i, $arExamenPendientePago->getNombreCorto())
                    ->setCellValue('E' . $i, $arExamenPendientePago->getCargoRel()->getNombre())
                    ->setCellValue('F' . $i, $arExamenPendientePago->getFecha()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arExamenPendientePago->getExamenClaseRel()->getNombre())
                    ->setCellValue('H' . $i, $arExamenPendientePago->getEntidadExamenRel()->getNombre())
                    ->setCellValue('I' . $i, $arExamenPendientePago->getCiudadRel()->getNombre())
                    ->setCellValue('J' . $i, $arExamenPendientePago->getVrTotal());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagoExamenPendiente');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagoExamenPendiente.xlsx"');
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
