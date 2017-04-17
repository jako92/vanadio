<?php

namespace Brasa\TurnoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurControlPuestoType;
use Brasa\TurnoBundle\Form\Type\TurControlPuestoDetalleType;
use Brasa\TurnoBundle\Form\Type\TurControlPuestoDetalleCompuestoType;
use PHPExcel_Style_Border;

class ControlPuestoController extends Controller {

    var $strListaDql = "";
    var $strListaDqlRecurso = "";
    var $codigoRecurso = "";
    var $identificacion = "";
    var $nombreRecurso = "";

    /**
     * @Route("/tur/movimiento/control/puesto", name="brs_tur_movimiento_control_puesto")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 140, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($request->request->get('OpGenerar')) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);
                    $codigoControlPuesto = $request->request->get('OpGenerar');
                    $arControlPuesto = $em->getRepository('BrasaTurnoBundle:TurControlPuesto')->find($codigoControlPuesto);
                    $arPuestos = new \Brasa\TurnoBundle\Entity\TurPuesto();
                    $arPuestos = $em->getRepository('BrasaTurnoBundle:TurPuesto')->findBy(array('controlPuesto' => 1));
                    foreach ($arPuestos as $arPuesto) {
                        $arControlPuestoDetalle = new \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle();
                        $arControlPuestoDetalle->setControlPuestoRel($arControlPuesto);
                        $arControlPuestoDetalle->setPuestoRel($arPuesto);
                        $arControlPuestoDetalle->setNumeroComunicacion($arPuesto->getNumeroComunicacion());
                        $em->persist($arControlPuestoDetalle);
                    }
                    $arControlPuesto->setEstadoGenerado(1);
                    $em->persist($arControlPuesto);
                    $em->flush();
                }

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
        }
        $arControlPuestos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Movimientos/ControlPuesto:lista.html.twig', array(
                    'arControlPuestos' => $arControlPuestos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/control/puesto/nuevo/{codigoControlPuesto}", name="brs_tur_movimiento_control_puesto_nuevo")
     */
    public function nuevoAction(Request $request, $codigoControlPuesto) {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arControlPuesto = new \Brasa\TurnoBundle\Entity\TurControlPuesto();
        if ($codigoControlPuesto == 0) {
            $arControlPuesto->setFecha(new \DateTime('now'));
        } else {
            $arControlPuesto = $em->getRepository('BrasaTurnoBundle:TurControlPuesto')->find($codigoControlPuesto);
        }
        $form = $this->createForm(TurControlPuestoType::class, $arControlPuesto);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arControlPuesto = $form->getData();
                $arUsuario = $this->getUser();
                $arControlPuesto->setUsuario($arUsuario->getUserName());
                $em->persist($arControlPuesto);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_movimiento_control_puesto'));
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/ControlPuesto:nuevo.html.twig', array(
                    'arControlPuesto' => $arControlPuesto,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/movimiento/control/puesto/detalle/{codigoControlPuesto}", name="brs_tur_movimiento_control_puesto_detalle")
     */
    public function detalleAction(Request $request, $codigoControlPuesto) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arControlPuesto = new \Brasa\TurnoBundle\Entity\TurControlPuesto();
        $arControlPuesto = $em->getRepository('BrasaTurnoBundle:TurControlPuesto')->find($codigoControlPuesto);
        $form = $this->formularioDetalle($arControlPuesto);
        $form->handleRequest($request);
        if($form->isSubmitted()){
        if ($form->isValid()) {
            if ($request->request->get('OpCerrar')) {
                $codigoControlPuestoDetalle = $request->request->get('OpCerrar');
                $arControlPuestoDetalle = new \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle();
                $arControlPuestoDetalle = $em->getRepository('BrasaTurnoBundle:TurControlPuestoDetalle')->find($codigoControlPuestoDetalle);
                $arControlPuestoDetalle->setFecha(new \DateTime('now'));
                $arControlPuestoDetalle->setEstadoCerrado(1);
                $em->persist($arControlPuestoDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_movimiento_control_puesto_detalle', array('codigoControlPuesto' => $codigoControlPuesto, 'codigoControlPuestoDetalle' => $codigoControlPuestoDetalle)));
            }

            if ($form->get('BtnImprimir')->isClicked()) {
                $objFormatoControlPuesto = new \Brasa\TurnoBundle\Formatos\ControlPuesto();
                $objFormatoControlPuesto->Generar($em, $codigoControlPuesto);
            }


            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurControlPuestoDetalle')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_control_puesto_detalle', array('codigoControlPuesto' => $codigoControlPuesto)));
            }
        }
    }
        $dql = $em->getRepository('BrasaTurnoBundle:TurControlPuestoDetalle')->listaDql($codigoControlPuesto);
        $arControlPuestoDetalle = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 150);
        return $this->render('BrasaTurnoBundle:Movimientos/ControlPuesto:detalle.html.twig', array(
                    'arControlPuesto' => $arControlPuesto,
                    'arControlPuestoDetalle' => $arControlPuestoDetalle,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/tur/movimiento/control/puesto/detalle/novedad/{codigoControlPuestoDetalle}", name="brs_tur_movimiento_control_puesto_detalle_novedad")
     */
    public function detalleNovedadAction(Request $request, $codigoControlPuestoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $arControlPuestoDetalle = new \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle();
        $arControlPuestoDetalle = $em->getRepository('BrasaTurnoBundle:TurControlPuestoDetalle')->find($codigoControlPuestoDetalle);
        $form = $this->createFormBuilder()
                ->setAction($this->generateUrl('brs_tur_movimiento_control_puesto_detalle_novedad', array('codigoControlPuestoDetalle' => $codigoControlPuestoDetalle)))
                ->add('numeroComunicacion', TextType::class, array('required' => false, 'data' => $arControlPuestoDetalle->getNumeroComunicacion()))
                ->add('novedad', TextareaType::class, array('required' => false, 'data' => $arControlPuestoDetalle->getNovedad()))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $novedad = $form->get('novedad')->getData();
                $numeroComunicacion = $form->get('numeroComunicacion')->getData();
                $arControlPuestoDetalle->setNovedad($novedad);
                $arControlPuestoDetalle->setNumeroComunicacion($numeroComunicacion);
                if($novedad != "") {
                    $arControlPuestoDetalle->setEstadoNovedad(1);
                } else {
                    $arControlPuestoDetalle->setEstadoNovedad(0);
                }
                $em->persist($arControlPuestoDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_movimiento_control_puesto_detalle', array('codigoControlPuesto' => $arControlPuestoDetalle->getCodigoControlPuestoFk())));
            }
        }
    }
        return $this->render('BrasaTurnoBundle:Movimientos/ControlPuesto:novedad.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/tur/movimiento/control/puesto/ver/puesto/{codigoControlPuestoDetalle}", name="brs_tur_movimiento_control_puesto_ver_puesto")
     */
    public function verPuestoAction(Request $request, $codigoControlPuestoDetalle) {
        $em = $this->getDoctrine()->getManager();
        $arControlPuestoDetalle = new \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle();
        $arControlPuestoDetalle = $em->getRepository('BrasaTurnoBundle:TurControlPuestoDetalle')->find($codigoControlPuestoDetalle);
        $form = $this->createFormBuilder()
                ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if ($form->isValid()) {
            }
        }
        $arProgramacionDetalles = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalles = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoPuestoFk' => $arControlPuestoDetalle->getCodigoPuestoFk(), 'anio' => $arControlPuestoDetalle->getControlPuestoRel()->getFecha()->format('Y'), 'mes' => $arControlPuestoDetalle->getControlPuestoRel()->getFecha()->format('m')));
        return $this->render('BrasaTurnoBundle:Movimientos/ControlPuesto:verPuesto.html.twig', array(
                    'arProgramacionDetalles' => $arProgramacionDetalles,
                    'form' => $form->createView()
        ));
    }    
    
    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql = $em->getRepository('BrasaTurnoBundle:TurControlPuesto')->listaDQL();
    }

    private function filtrar($form) {
        
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $form = $this->createFormBuilder()
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $form = $this->createFormBuilder()
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                ->getForm();
        return $form;
    }

    private function generarExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'M'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'TIPO')
                ->setCellValue('C1', 'NÚMERO')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'AÑO')
                ->setCellValue('F1', 'MES')
                ->setCellValue('G1', 'CLIENTE')
                ->setCellValue('H1', 'SECTOR')
                ->setCellValue('I1', 'AUT')
                ->setCellValue('J1', 'PRO')
                ->setCellValue('K1', 'FAC')
                ->setCellValue('L1', 'ANU')
                ->setCellValue('M1', 'HORAS')
                ->setCellValue('N1', 'H.DIURNAS')
                ->setCellValue('O1', 'H.NOCTURNAS')
                ->setCellValue('P1', 'P.MINIMO')
                ->setCellValue('Q1', 'P.AJUSTADO')
                ->setCellValue('R1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arControlPuestos = new \Brasa\TurnoBundle\Entity\TurControlPuesto();
        $arControlPuestos = $query->getResult();

        foreach ($arControlPuestos as $arControlPuesto) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arControlPuesto->getCodigoControlPuestoPk())
                    ->setCellValue('B' . $i, $arControlPuesto->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arControlPuesto->getNumero())
                    ->setCellValue('D' . $i, $arControlPuesto->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arControlPuesto->getFechaProgramacion()->format('Y'))
                    ->setCellValue('F' . $i, $arControlPuesto->getFechaProgramacion()->format('F'))
                    ->setCellValue('G' . $i, $arControlPuesto->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arControlPuesto->getSectorRel()->getNombre())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arControlPuesto->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arControlPuesto->getEstadoProgramado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arControlPuesto->getEstadoFacturado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arControlPuesto->getEstadoAnulado()))
                    ->setCellValue('M' . $i, $arControlPuesto->getHoras())
                    ->setCellValue('N' . $i, $arControlPuesto->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arControlPuesto->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arControlPuesto->getVrTotalPrecioMinimo())
                    ->setCellValue('Q' . $i, $arControlPuesto->getVrTotalPrecioAjustado())
                    ->setCellValue('R' . $i, $arControlPuesto->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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
