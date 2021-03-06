<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionDetalleType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionNotaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CapacitacionesDetallesController extends Controller
{
    var $strDqlLista = "";    

    /**
     * @Route("/rhu/consultas/capacitacion/detalle", name="brs_rhu_consultas_capacitacion_detalle")
     *
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 118)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }                        
        }

        $arCapacitacionesDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Capacitaciones:detalle.html.twig', array(
            'arCapacitacionesDetalles' => $arCapacitacionesDetalles,
            'form' => $form->createView()));
    }  

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionDetalle')->listaConsultaDetalleDql(
            $session->get('filtroTipo'),
            $session->get('filtroTema'),
            $session->get('filtroEstado'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta'),
            $session->get('filtroZona'),
            $session->get('filtroIdentificacion'));
    }    

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCapacitacionTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                    ->orderBy('ct.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCapacitacionTipo", $session->get('filtroTipo'));
        }
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {                
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroIdentificacion', null);
            }          
        }
        $arrayPropiedadesZona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroZona'));
        }
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))    
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))                
            ->add('capacitacionTipoRel', EntityType::class, $arrayPropiedades)
            ->add('zonaRel', EntityType::class, $arrayPropiedadesZona)
            ->add('fechaDesde',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('TxtTema', TextType::class, array('label'  => 'TEMA','data' => $session->get('filtroTema')))
            ->add('estado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'), 'data' => $session->get('filtroEstado')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))            
            ->getForm();
        return $form;
    }
    
    private function filtrar ($form) {
        $session = new session;
        $codigoCapacitacionTipo = "";
        if($form->get('capacitacionTipoRel')->getData()) {
            $codigoCapacitacionTipo = $form->get('capacitacionTipoRel')->getData()->getCodigoCapacitacionTipoPk();
        }
        $session->set('filtroTipo', $codigoCapacitacionTipo);
        $session->set('filtroTema', $form->get('TxtTema')->getData());
        $session->set('filtroEstado', $form->get('estado')->getData());

        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
        $codigoZona = "";
        if($form->get('zonaRel')->getData()) {
            $codigoZona = $form->get('zonaRel')->getData()->getCodigoZonaPk();
        }
        $session->set('filtroZona', $codigoZona);
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
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
                for($col = 'A'; $col !== 'Z'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                }
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'HORA')
                            ->setCellValue('D1', 'DURACION')
                            ->setCellValue('E1', 'CIUDAD')
                            ->setCellValue('F1', 'LUGAR')
                            ->setCellValue('G1', 'ZONA')
                            ->setCellValue('H1', 'TIPO')
                            ->setCellValue('I1', 'TEMA')
                            ->setCellValue('J1', 'METODOLOGIA')
                            ->setCellValue('K1', 'OBJETIVO')
                            ->setCellValue('L1', 'CONTENIDO')
                            ->setCellValue('M1', 'A CAPACITAR')
                            ->setCellValue('N1', 'ASISTIERON')
                            ->setCellValue('O1', 'VR CAPACITACION')
                            ->setCellValue('P1', 'FACILITADOR')
                            ->setCellValue('Q1', 'IDENTIFICACION')
                            ->setCellValue('R1', 'ESTADO ABIERTO')
                            ->setCellValue('S1', 'ASISTENTE')
                            ->setCellValue('T1', 'IDENTIFICACION')
                            ->setCellValue('U1', 'GRUPO PAGO')
                            ->setCellValue('V1', 'CARGO')
                            ->setCellValue('W1', 'PUESTO')
                            ->setCellValue('X1', 'EVALUACION')
                            ->setCellValue('Y1', 'ASISTENCIA');

                $i = 2;
                $query = $em->createQuery($this->strDqlLista);
                $arCapacitacionesDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionDetalle();
                $arCapacitacionesDetalles = $query->getResult();

                foreach ($arCapacitacionesDetalles as $arCapacitacionDetalle) {
                    $strCapacitacionTipo = "";
                    if ($arCapacitacionDetalle->getCapacitacionRel()->getCodigoCapacitacionTipoFk() != null){
                        $strCapacitacionTipo = $arCapacitacionDetalle->getCapacitacionRel()->getCapacitacionTipoRel()->getNombre();
                    }
                    $ciudad = "";
                    if ($arCapacitacionDetalle->getCapacitacionRel()->getCodigoCiudadFk() != null){
                        $ciudad = $arCapacitacionDetalle->getCapacitacionRel()->getCiudadRel()->getNombre();
                    }
                    $strCapacitacionMetodologia = "";
                    if ($arCapacitacionDetalle->getCapacitacionRel()->getCodigoCapacitacionMetodologiaFk() != null){
                        $strCapacitacionMetodologia = $arCapacitacionDetalle->getCapacitacionRel()->getCapacitacionMetodologiaRel()->getNombre();
                    }
                    $estado = "SI";
                    if ($arCapacitacionDetalle->getCapacitacionRel()->getEstado() == 1){
                        $estado = "NO";
                    }
                    $zona = "";
                    if ($arCapacitacionDetalle->getCapacitacionRel()->getCodigoZonaFk() != null){
                        $zona = $arCapacitacionDetalle->getCapacitacionRel()->getZonaRel()->getNombre();
                    }
                    $puesto = "";
                    if ($arCapacitacionDetalle->getEmpleadoRel()->getCodigoPuestoFk() != null){
                        $puesto = $arCapacitacionDetalle->getEmpleadoRel()->getPuestoRel()->getNombre();
                    }
                    $centroCosto = "";
                    if ($arCapacitacionDetalle->getEmpleadoRel()->getCodigoCentroCostoFk() != null){
                        $centroCosto = $arCapacitacionDetalle->getEmpleadoRel()->getCentroCostoRel()->getNombre();
                    }
                    $cargo = "";
                    if ($arCapacitacionDetalle->getEmpleadoRel()->getCodigoCargoFk() != null){
                        $cargo = $arCapacitacionDetalle->getEmpleadoRel()->getCargoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getCodigoCapacitacionPk())
                            ->setCellValue('B' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getFechaCapacitacion()->format('Y-m-d'))
                            ->setCellValue('C' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getFechaCapacitacion()->format('H:i:s'))
                            ->setCellValue('D' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getDuracion())
                            ->setCellValue('E' . $i, $ciudad)
                            ->setCellValue('F' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getLugar())
                            ->setCellValue('G' . $i, $zona)
                            ->setCellValue('H' . $i, $strCapacitacionTipo)
                            ->setCellValue('I' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getTema())
                            ->setCellValue('J' . $i, $strCapacitacionMetodologia)
                            ->setCellValue('K' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getObjetivo())
                            ->setCellValue('L' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getContenido())
                            ->setCellValue('M' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getNumeroPersonasCapacitar())
                            ->setCellValue('N' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getNumeroPersonasAsistieron())
                            ->setCellValue('O' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getVrCapacitacion())
                            ->setCellValue('P' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getFacilitador())
                            ->setCellValue('Q' . $i, $arCapacitacionDetalle->getCapacitacionRel()->getNumeroIdentificacionFacilitador())
                            ->setCellValue('R' . $i, $estado)
                            ->setCellValue('S' . $i, $arCapacitacionDetalle->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('T' . $i, $arCapacitacionDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('U' . $i, $centroCosto)
                            ->setCellValue('V' . $i, $cargo)
                            ->setCellValue('W' . $i, $puesto)
                            ->setCellValue('X' . $i, $arCapacitacionDetalle->getEvaluacion())
                            ->setCellValue('Y' . $i, $arCapacitacionDetalle->getAsistencia());
                    $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('capacitacionDetalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="capacitacionDetalle.xlsx"');
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
