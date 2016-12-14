<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoEstudioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EstudioController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/rhu/estudio/lista", name="brs_rhu_estudio_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 36, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoEstudio) {
                        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
                        $em->remove($arEstudio);   
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_estudio_lista'));
                }
            }

            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnExcelInforme')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarInformeExcel();
            }
        }

        $arEstudios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:lista.html.twig', array('arEstudios' => $arEstudios, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/estudio/nuevo/{codigoEstudio}", name="brs_rhu_estudio_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEstudio = 0) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        if($codigoEstudio != 0) {
            $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        } else {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arEstudio->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RhuEmpleadoEstudioType::class, $arEstudio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEstudio = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arEstudio->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        if ($codigoEstudio == 0){
                            $arEstudio->setCodigoUsuario($arUsuario->getUserName());
                        }
                        $em->persist($arEstudio);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_estudio_nuevo', array('codigoEstudio' => 0 )));
                        } else {
                            if ($codigoEstudio == 0){
                                return $this->redirect($this->generateUrl('brs_rhu_estudio_detalle', array('codigoEstudio' => $arEstudio->getCodigoEmpleadoEstudioPk())));
                            } else {
                                return $this->redirect($this->generateUrl('brs_rhu_estudio_lista'));
                            }
                            
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:nuevo.html.twig', array(
            'arEstudio' => $arEstudio,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/estudio/detalle/{codigoEstudio}", name="brs_rhu_estudio_detalle")
     */
    public function detalleAction(Request $request, $codigoEstudio) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = $this->get('mensajes_brasa');
        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        $form = $this->formularioDetalle($arEstudio);
        $form->handleRequest($request);
        if($form->isValid()) {
            
            
        }
        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:detalle.html.twig', array(
                    'arEstudio' => $arEstudio,
                    'form' => $form->createView()
                    ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->listaMovimientoDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroNombre'),
                $session->get('filtroEstudio'),
                $session->get('filtroDesde')
                );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedadesEstudio = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroEstudio')) {
            $arrayPropiedadesEstudio['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo", $session->get('filtroEstudio'));
        }
        

        $form = $this->createFormBuilder()
            ->add('empleadoEstudioTipoRel', EntityType::class, $arrayPropiedadesEstudio)
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombre')))    
            ->add('fechaVencimientoCurso',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            //->add('BtnExcelInforme', SubmitType::class, array('label'  => 'Informe'))    
            
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = new session;
        $codigoEmpleadoEstudioTipo = "";
        if($form->get('empleadoEstudioTipoRel')->getData()) {
            $codigoEmpleadoEstudioTipo = $form->get('empleadoEstudioTipoRel')->getData()->getCodigoEmpleadoEstudioTipoPk();
        }        
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEstudio', $codigoEmpleadoEstudioTipo);
        $dateFechaHasta = $form->get('fechaVencimientoCurso')->getData();
        if ($form->get('fechaVencimientoCurso')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaVencimientoCurso')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaHasta->format('Y-m-d'));
        }
    }
    
    private function formularioDetalle($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrBotonGenerar = array('label' => 'Generar solicitud', 'disabled' => false);
        $arrBotonValidado = array('label' => 'Validado', 'disabled' => false);
        $arrBotonNoValidado = array('label' => 'No validado', 'disabled' => false);
        $arrBotonAcreditado = array('label' => 'Acreditado', 'disabled' => false);
        $form = $this->createFormBuilder()
            
                ->getForm();  
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'IDENTIFICACIÓN')
                            ->setCellValue('D1', 'EMPLEADO')
                            ->setCellValue('E1', 'CARGO')
                            ->setCellValue('F1', 'TIPO ESTUDIO')
                            ->setCellValue('G1', 'INSTITUCION')
                            ->setCellValue('H1', 'TITULO')
                            ->setCellValue('I1', 'CIUDAD')
                            ->setCellValue('J1', 'FECHA INICIO')
                            ->setCellValue('K1', 'FECHA TERMINACIÓN')
                            ->setCellValue('L1', 'FECHA VENCIMIENTO CONTROL')
                            ->setCellValue('M1', 'GRADO BACHILLER')
                            ->setCellValue('N1', 'GRADUADO')
                            ->setCellValue('O1', 'NUMERO REGISTRO')
                            ->setCellValue('P1', 'VALIDAR')
                            ->setCellValue('Q1', 'COMENTARIOS');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                $arEstudios = $query->getResult();

                foreach ($arEstudios as $arEstudios) {
                    $fecha = "";
                    if ($arEstudios->getFecha() != null) {
                        $fecha = $arEstudios->getFecha()->format('Y/m/d');
                    }
                    $ciudad = "";
                    if ($arEstudios->getCodigoCiudadFk() != null) {
                        $ciudad = $arEstudios->getCiudadRel()->getNombre();
                    }
                    $fechaInicio = "";
                    if ($arEstudios->getFechaInicio() != null) {
                        $fechaInicio = $arEstudios->getFechaInicio()->format('Y/m/d');
                    }
                    $fechaTerminacion = "";
                    if ($arEstudios->getFechaTerminacion() != null) {
                        $fechaTerminacion = $arEstudios->getFechaTerminacion()->format('Y/m/d');
                    }
                    $fechaInicioAcreditacion = "";
                    if ($arEstudios->getFechaInicioAcreditacion() != null) {
                        $fechaInicioAcreditacion = $arEstudios->getFechaInicioAcreditacion()->format('Y/m/d');
                    }
                    $fechaVencimientoControl = "";
                    if ($arEstudios->getFechaVencimientoCurso() != null) {
                        $fechaVencimientoControl = $arEstudios->getFechaVencimientoCurso()->format('Y/m/d');
                    }
                    $fechaVencimientoAcreditacion = "";
                    if ($arEstudios->getFechaVencimientoAcreditacion() != null) {
                        $fechaVencimientoAcreditacion = $arEstudios->getFechaVencimientoAcreditacion()->format('Y/m/d');
                    }
                    $tipoAcreditacion = "";
                    if ($arEstudios->getCodigoEstudioTipoAcreditacionFk() != null) {
                        $tipoAcreditacion = $arEstudios->getEstudioTipoAcreditacionRel()->getNombre();
                    }
                    $academia = "";
                    if ($arEstudios->getCodigoAcademiaFk() != null) {
                        $academia = $arEstudios->getAcademiaRel()->getNombre();
                    }
                    $estadoInvalidado = "";
                    if ($arEstudios->getCodigoEstudioEstadoInvalidoFk() != null) {
                        $estadoInvalidado = $arEstudios->getEstudioEstadoInvalidoRel()->getNombre();
                    }
                    $gradoBachiller = "";
                    if ($arEstudios->getCodigoGradoBachillerFk() != null) {
                        $gradoBachiller = $arEstudios->getGradoBachillerRel()->getGrado();
                    }
                    $estado = "";
                    if ($arEstudios->getCodigoEstudioEstadoFk() != null) {
                        $estado = $arEstudios->getEstudioEstadoRel()->getNombre();
                    }
                    $graduado = "";
                    if ($arEstudios->getGraduado() == 1){
                        $graduado = "SI";
                    } else {
                        $graduado = "NO";
                    }
                    $fechaEstado = "";
                    if ($arEstudios->getFechaEstado() != null) {
                        $fechaEstado = $arEstudios->getFechaEstado()->format('Y/m/d');
                    }
                    $fechaEstadoInvalido = "";
                    if ($arEstudios->getFechaEstadoInvalido() != null) {
                        $fechaEstadoInvalido = $arEstudios->getFechaEstadoInvalido()->format('Y/m/d');
                    }
                    $cargo = '';
                    if ($arEstudios->getEmpleadoRel()->getCodigoCargoFk() != null){
                        $cargo = $arEstudios->getEmpleadoRel()->getCargoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEstudios->getCodigoEmpleadoEstudioPk())
                            ->setCellValue('B' . $i, $fecha)
                            ->setCellValue('C' . $i, $arEstudios->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arEstudios->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('E' . $i, $cargo)
                            ->setCellValue('F' . $i, $arEstudios->getEmpleadoEstudioTipoRel()->getNombre())
                            ->setCellValue('G' . $i, $arEstudios->getInstitucion())
                            ->setCellValue('H' . $i, $arEstudios->getTitulo())
                            ->setCellValue('I' . $i, $ciudad)
                            ->setCellValue('J' . $i, $fechaInicio)
                            ->setCellValue('K' . $i, $fechaTerminacion)
                            ->setCellValue('L' . $i, $fechaVencimientoControl)
                            ->setCellValue('M' . $i, $gradoBachiller)
                            ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arEstudios->getGraduado()))
                            ->setCellValue('O' . $i, $arEstudios->getNumeroRegistro())
                            ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arEstudios->getValidarVencimiento()))
                            ->setCellValue('Q' . $i, $arEstudios->getComentarios())
                            
                            ;
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Estudios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Estudios.xlsx"');
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
