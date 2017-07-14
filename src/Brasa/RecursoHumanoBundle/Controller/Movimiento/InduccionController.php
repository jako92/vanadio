<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuInduccionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class InduccionController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/induccion/lista", name="brs_rhu_induccion_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 99, 1)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          }
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuInduccion')->eliminarInduccion($arrSeleccionados);
            }
        }

        $arInducciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Induccion:lista.html.twig', array(
                    'arInducciones' => $arInducciones,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/induccion/nuevo/{codigoInduccion}", name="brs_rhu_induccion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoInduccion) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arInduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuInduccion();
        if ($codigoInduccion != 0) {
            $arInduccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuInduccion')->find($codigoInduccion);
        } else {
            $arInduccion->setFecha(new \DateTime('now'));
            $arInduccion->setFechaDesde(new \DateTime('now'));
            $arInduccion->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(RhuInduccionType::class, $arInduccion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arInduccion = $form->getData();
            $arrControles = $request->request->All();
            if ($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if (count($arEmpleado) > 0) {
                    $arInduccion->setEmpleadoRel($arEmpleado);
                    $arInduccion = $form->getData();
                    $em->persist($arInduccion);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_induccion_nuevo', array('codigoInduccion' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_induccion_detalle', array('codigoInduccion' => $arInduccion->getCodigoInduccionPk())));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            } else {
                $objMensaje->Mensaje("error", "Seleccione un empleado");
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Induccion:nuevo.html.twig', array(
                    'arInduccion' => $arInduccion,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/induccion/detalle/{codigoInduccion}", name="brs_rhu_induccion_detalle")
     */
    public function detalleAction(Request $request, $codigoInduccion) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arInduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion();
        $arInduccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuInduccion')->find($codigoInduccion);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Induccion:detalle.html.twig', array(
                    'arInduccion' => $arInduccion));
    }

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $session->get('filtroDesde');
            $strFechaHasta = $session->get('filtroHasta');
        }
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuInduccion')->listaDql(
                $session->get('filtroIdentificacion'), $strFechaDesde, $strFechaHasta);
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            } else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }
        if ($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if ($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('fechaDesde', DateType::class, array('data' => $dateFechaDesde))
                ->add('fechaHasta', DateType::class, array('data' => $dateFechaHasta))
                ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFiltrarFecha'), 'label' => 'Filtrar por fecha'))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroFiltrarFecha', $form->get('filtrarFecha')->getData());
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null) {
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'FECHA DESDE')
                ->setCellValue('C1', 'FECHA HASTA')
                ->setCellValue('D1', 'EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arInducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuInduccion();
        $arInducciones = $query->getResult();

        foreach ($arInducciones as $arInduccion) {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $arInduccion->getCodigoInduccionPk())
            ->setCellValue('B' . $i, $arInduccion->getFechaDesde()->format('Y-m-d'))
            ->setCellValue('C' . $i, $arInduccion->getFechaHasta()->format('Y-m-d'))
            ->setCellValue('D' . $i, $arInduccion->getEmpleadoRel()->getNombreCorto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inducciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Inducciones.xlsx"');
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
