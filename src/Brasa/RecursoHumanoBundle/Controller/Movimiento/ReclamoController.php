<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuReclamoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReclamoController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/reclamo/", name="brs_rhu_movimiento_reclamo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 12, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }*/
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnExcelInforme')->isClicked()) {
                $this->generarInformeExcel();
            }*/
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoReclamo) {
                        $arReclamo = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
                        $arReclamo = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find($codigoReclamo);
                        if($arReclamo->getEstadoCerrado() == 0) {
                            $em->remove($arReclamo);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo'));
                }
            }

        }
        $arReclamos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Reclamo:lista.html.twig', array(
            'arReclamos' => $arReclamos,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/reclamo/nuevo/{codigoReclamo}", name="brs_rhu_movimiento_reclamo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoReclamo = 0) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arReclamo = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
        if($codigoReclamo != 0) {
            $arReclamo = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find($codigoReclamo);
        } else {
            $arReclamo->setFecha(new \DateTime('now'));
            $arReclamo->setFechaRegistro(new \DateTime('now'));            
            $arReclamo->setFechaCierre(new \DateTime('now'));
        }

        $form = $this->createForm(RhuReclamoType::class, $arReclamo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arReclamo = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arReclamo->setEmpleadoRel($arEmpleado);
                    if($codigoReclamo == 0) {
                        $arReclamo->setCodigoUsuario($arUsuario->getUserName());
                    }
                    $em->persist($arReclamo);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo_nuevo', array('codigoReclamo' => 0)));
                    } else {                        
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo_detalle', array('codigoReclamo' => $arReclamo->getCodigoReclamoPk())));
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Reclamo:nuevo.html.twig', array(
            'arReclamo' => $arReclamo,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/reclamo/detalle/{codigoReclamo}", name="brs_rhu_movimiento_reclamo_detalle")
     */
    public function detalleAction(Request $request, $codigoReclamo) {
        $em = $this->getDoctrine()->getManager();        
        $objMensaje = $this->get('mensajes_brasa');
        $arReclamo = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
        $arReclamo = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find($codigoReclamo);
        $form = $this->formularioDetalle($arReclamo);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleReclamo = new \Brasa\RecursoHumanoBundle\Formatos\Reclamo();
                $objFormatoDetalleReclamo->Generar($em, $codigoReclamo);
            }                
            if($form->get('BtnCerrar')->isClicked()) {
                if($arReclamo->getEstadoCerrado() == 0) {
                    $arReclamo->setEstadoCerrado(1);
                    $arReclamo->setFechaCierre(new \DateTime('now'));
                    $em->persist($arReclamo);
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_reclamo_detalle', array('codigoReclamo' => $codigoReclamo)));
            }            
        }
        $arReclamo = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find($codigoReclamo);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Reclamo:detalle.html.twig', array(
                    'arReclamo' => $arReclamo,
                    'form' => $form->createView()
                    ));
    }    
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            }  else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroRhuReclamoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroRhuReclamoFechaDesde');
        }
        if($session->get('filtroRhuReclamoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroRhuReclamoFechaHasta');
        }
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoCerrado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'CERRADO' => '1', 'SIN CERRAR' => '0'), 'data' => $session->get('filtroRhuReclamoEstadoCerrado')))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroRhuReclamoFiltrarFecha')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);        
        if($ar->getEstadoCerrado() == 1) {
            $arrBotonCerrar['disabled'] = true;
        }        
        $form = $this->createFormBuilder()
                ->add('BtnCerrar', SubmitType::class, $arrBotonCerrar)
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->getForm();  
        return $form;
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroRhuReclamoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroRhuReclamoFechaDesde');
            $strFechaHasta = $session->get('filtroRhuReclamoFechaHasta');
        }
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->listaDQL(
                $session->get('filtroRhuCodigoEmpleado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroRhuReclamoEstadoCerrado'));
    }

    private function filtrarLista($form) {
        $session = new session;       
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuReclamoEstadoCerrado', $form->get('estadoCerrado')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroRhuReclamoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroRhuReclamoFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroRhuReclamoFiltrarFecha', $form->get('filtrarFecha')->getData());
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
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
        for($col = 'A'; $col !== 'O'; $col++) {            
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'DOCUMENTO')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'CIERRE')
                    ->setCellValue('F1', 'CERRADO');
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arReclamoes = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();
        $arReclamoes = $query->getResult();
        foreach ($arReclamoes as $arReclamo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arReclamo->getCodigoReclamoPk())
                    ->setCellValue('B' . $i, $arReclamo->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('C' . $i, $arReclamo->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arReclamo->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arReclamo->getFechaCierre()->format('Y/m/d'))                    
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arReclamo->getEstadoCerrado()));           
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Reclamos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reclamos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
}
