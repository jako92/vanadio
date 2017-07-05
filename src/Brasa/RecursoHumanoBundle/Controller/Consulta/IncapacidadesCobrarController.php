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

class IncapacidadesCobrarController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/consultas/Incapacidades/Cobrar", name="brs_rhu_consultas_incapacidades_cobrar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /*if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 32)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }*/
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
        $arIncapacidadesCobrar = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/IncapacidadesCobrar:lista.html.twig', array(
                    'arIncapacidadesCobrar' => $arIncapacidadesCobrar,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroIncapacidadesFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroDesde');
            $strFechaHasta = $session->get('filtroHasta');
        }
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesCobrarDQL(
                $session->get('filtroCentroCosto'),
                $session->get('filtroIdentificacion'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroEntidadSalud'));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        if ($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if ($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""))
                ->add('entidadSaludRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroIncapacidadesFiltrarFecha')))
                ->add('TxtIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $codigoCentroCosto = "";
        if ($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $codigoEntidadSalud = "";
        if ($form->get('entidadSaludRel')->getData()) {
            $codigoEntidadSalud = $form->get('entidadSaludRel')->getData()->getCodigoEntidadSaludPk();
        }
        $session->set('filtroCentroCosto', $codigoCentroCosto);
        $session->set('filtroEntidadSalud', $codigoEntidadSalud);
        $session->set('filtroIncapacidadesFiltrarFecha', $form->get('filtrarFecha')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroIncapacidadesFiltrarFecha', $form->get('filtrarFecha')->getData());
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
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'CENTRO COSTO')
                ->setCellValue('D1', 'IDENTIFICACIÓN')
                ->setCellValue('E1', 'NOMBRE')
                ->setCellValue('F1', 'DIAS')
                ->setCellValue('G1', 'FECHA DESDE')
                ->setCellValue('H1', 'FECHA HASTA')
                ->setCellValue('I1', 'ENTIDAD EXAMEN')
                ->setCellValue('J1', 'CIUDAD')
                ->setCellValue('K1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arIncapacidadesPendientePago = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidadesPendientePago = $query->getResult();
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
        foreach ($arIncapacidadesPendientePago as $arIncapacidadPendientePago) {
            if ($arIncapacidadPendientePago->getCentroCostoRel()) {
                $nombreCentroCosto = $arIncapacidadPendientePago->getCentroCostoRel()->getNombre();
            } else {
                $nombreCentroCosto = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidadPendientePago->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidadPendientePago->getNumero())
                    ->setCellValue('C' . $i, $nombreCentroCosto)
                    ->setCellValue('D' . $i, $arIncapacidadPendientePago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arIncapacidadPendientePago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arIncapacidadPendientePago->getDiasCobro())
                    ->setCellValue('G' . $i, $arIncapacidadPendientePago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('H' . $i, $arIncapacidadPendientePago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('I' . $i, $arIncapacidadPendientePago->getEntidadSaludRel()->getNombre())
                    ->setCellValue('J' . $i, $arIncapacidadPendientePago->getEmpleadoRel()->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arIncapacidadPendientePago->getVrCobro());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IncapacidadPendientes');
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
