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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConsultaEmpleadoController extends Controller
{
    var $strDql = "";   

    /**
     * @Route("/rhu/consulta/empleado", name="brs_rhu_consulta_empleado")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 97)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $session = new Session;
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->EmpleadoListar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
                $this->generarEmpleadoExcel();
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strSqlEmpleadosLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:listaGeneral.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }    

    /**
     * @Route("/rhu/consulta/empleado/detalle/{codigoEmpleado}", name="brs_rhu_consulta_empleado_detalle")
     */
    public function detalleAction(Request $request, $codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEmpleadoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        if($form->isValid()) {
        }
        //$arIncapacidades = $paginator->paginate($arIncapacidades, $this->get('request')->query->get('page', 1),5);
        $arIncapacidades = $paginator->paginate($arIncapacidades, $request->query->getInt('page', 1),5);
        //$arVacaciones = $paginator->paginate($arVacaciones, $this->get('request')->query->get('page', 1),5);
        $arVacaciones = $paginator->paginate($arVacaciones, $request->query->getInt('page', 1),5);
        //$arLicencias = $paginator->paginate($arLicencias, $this->get('request')->query->get('page', 1),5);
        $arLicencias = $paginator->paginate($arLicencias, $request->query->getInt('page', 1),5);
        //$arContratos = $paginator->paginate($arContratos, $this->get('request')->query->get('page', 1),5);
        $arContratos = $paginator->paginate($arContratos, $request->query->getInt('page', 1),5);
        //$arCreditos = $paginator->paginate($arCreditos, $this->get('request')->query->get('page', 1),5);
        $arCreditos = $paginator->paginate($arCreditos, $request->query->getInt('page', 1),5);
        //$arDisciplinarios = $paginator->paginate($arDisciplinarios, $this->get('request')->query->get('page', 1),5);
        $arDisciplinarios = $paginator->paginate($arDisciplinarios, $request->query->getInt('page', 1),5);
        //$arEmpleadoEstudios = $paginator->paginate($arEmpleadoEstudios, $this->get('request')->query->get('page', 1),6);
        $arEmpleadoEstudios = $paginator->paginate($arEmpleadoEstudios, $request->query->getInt('page', 1),5);
        //$arEmpleadoFamilia = $paginator->paginate($arEmpleadoFamilia, $this->get('request')->query->get('page', 1),8);
        $arEmpleadoFamilia = $paginator->paginate($arEmpleadoFamilia, $request->query->getInt('page', 1),5);
        //$arEmpleadoDotacion = $paginator->paginate($arEmpleadoDotacion, $this->get('request')->query->get('page', 1),8);
        $arEmpleadoDotacion = $paginator->paginate($arEmpleadoDotacion, $request->query->getInt('page', 1),5);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:detalleGeneral.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arIncapacidades' => $arIncapacidades,
                    'arVacaciones' => $arVacaciones,
                    'arLicencias' => $arLicencias,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'arDisciplinarios' => $arDisciplinarios,
                    'arEmpleadoEstudios' => $arEmpleadoEstudios,
                    'arEmpleadoFamilia' => $arEmpleadoFamilia,
                    'arEmpleadoDotacion' => $arEmpleadoDotacion,
                    'form' => $form->createView()
                    ));
    }  

    private function EmpleadoListar() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $this->strSqlEmpleadosLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),
                $session->get('filtroIdentificacion'),
                "",
                $session->get('filtroEmpleadoContratado')
                );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
            ->add('estadoActivo', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'ACTIVOS' => '1', 'INACTIVOS' => '0')))
            ->add('estadoContratado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'SI' => '1', 'NO' => '0')))    
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }    
    
    private function filtrarEmpleadoLista($form) {
        $session = new Session;
        $codigoCentroCosto = "";
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();    
        }        
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEmpleadoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroEmpleadoContratado', $form->get('estadoContratado')->getData());
    }  

    private function generarEmpleadoExcel() {
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
        for($col = 'A'; $col !== 'AV'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        } 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'CIUDAD EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('F1', 'FECHA EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('G1', 'LIBRETA MILITAR')
                    ->setCellValue('H1', 'CENTRO COSTO')
                    ->setCellValue('I1', 'NOMBRE')
                    ->setCellValue('J1', 'TELÉFONO')
                    ->setCellValue('K1', 'CELULAR')
                    ->setCellValue('L1', 'DIRECCIÓN')
                    ->setCellValue('M1', 'BARRIO')
                    ->setCellValue('N1', 'CIUDAD RESIDENCIA')
                    ->setCellValue('O1', 'RH')
                    ->setCellValue('P1', 'SEXO')
                    ->setCellValue('Q1', 'CORREO')
                    ->setCellValue('R1', 'FECHA NACIMIENTO')
                    ->setCellValue('S1', 'CIUDAD DE NACIMIENTO')
                    ->setCellValue('T1', 'ESTADO CIVIL')
                    ->setCellValue('U1', 'PADRE DE FAMILIA')
                    ->setCellValue('V1', 'CABEZA DE HOGAR')
                    ->setCellValue('W1', 'NIVEL DE ESTUDIO')
                    ->setCellValue('X1', 'ENTIDAD SALUD')
                    ->setCellValue('Y1', 'ENTIDAD PENSION')
                    ->setCellValue('Z1', 'ENTIDAD CAJA DE COMPESACIÓN')
                    ->setCellValue('AA1', 'ENTIDAD CESANTIAS')
                    ->setCellValue('AB1', 'CLASIFICACIÓN DE RIESGO')
                    ->setCellValue('AC1', 'CUENTA BANCARIA')
                    ->setCellValue('AD1', 'BANCO')
                    ->setCellValue('AE1', 'FECHA CONTRATO')
                    ->setCellValue('AF1', 'FECHA FINALIZA CONTRATO')
                    ->setCellValue('AG1', 'CARGO')
                    ->setCellValue('AH1', 'DESCRIPCIÓN CARGO')
                    ->setCellValue('AI1', 'TIPO PENSIÓN')
                    ->setCellValue('AJ1', 'TIPO COTIZANTE')
                    ->setCellValue('AK1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('AL1', 'ESTADO ACTIVO')
                    ->setCellValue('AM1', 'ESTADO CONTRATO')
                    ->setCellValue('AN1', 'CODIGO CONTRATO')
                    ->setCellValue('AO1', 'TALLA CAMISA')
                    ->setCellValue('AP1', 'TALLA JEANS')
                    ->setCellValue('AQ1', 'TALLA CALZADO')
                    ->setCellValue('AR1', 'DEPARTAMENTO')
                    ->setCellValue('AS1', 'HORARIO')
                    ->setCellValue('AT1', 'DISCAPACIDAD')
                    ->setCellValue('AU1', 'ZONA')
                    ->setCellValue('AV1', 'SUBZONA')
                    ->setCellValue('AW1', 'TIPO');

        $i = 2;
        $query = $em->createQuery($this->strSqlEmpleadosLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AW'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        foreach ($arEmpleados as $arEmpleado) {
            if ($arEmpleado->getCodigoCentroCostoFk() == null){
                $centroCosto = "";
            }else{
                $centroCosto = $arEmpleado->getCentroCostoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoClasificacionRiesgoFk() == null){
                $clasificacionRiesgo = "";
            }else{
                $clasificacionRiesgo = $arEmpleado->getClasificacionRiesgoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCargoFk() == null){
                $cargo = "";
            }else{
                $cargo = $arEmpleado->getCargoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoPensionFk() == null){
                $tipoPension = "";
            }else{
                $tipoPension = $arEmpleado->getTipoPensionRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoCotizanteFk() == null){
                $tipoCotizante = "";
            }else{
                $tipoCotizante = $arEmpleado->getSsoTipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoSubtipoCotizanteFk() == null){
                $subtipoCotizante = "";
            }else{
                $subtipoCotizante = $arEmpleado->getSsoSubtipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadSaludFk() == null){
                $entidadSalud = "";
            }else{
                $entidadSalud = $arEmpleado->getEntidadSaludRel()->getNombre();
            }
            
            if ($arEmpleado->getCodigoEntidadPensionFk() == null){
                $entidadPension = "";
            }else{
                $entidadPension = $arEmpleado->getEntidadPensionRel()->getNombre();
            }
            
            if ($arEmpleado->getCodigoEntidadCajaFk() == null){
                $entidadCaja = "";
            }else{
                $entidadCaja = $arEmpleado->getEntidadCajaRel()->getNombre();
            }        
            if ($arEmpleado->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            }else{
                $sexo = "FEMENINO";
            }
            if ($arEmpleado->getPadreFamilia() == 0){
                $padreFamilia = "NO";
            }else{
                $padreFamilia = "SI";
            }
            if ($arEmpleado->getCabezaHogar() == 0){
                $cabezaHogar = "NO";
            }else{
                $cabezaHogar = "SI";
            }
            if ($arEmpleado->getEstadoActivo() == 0){
                $estadoActivo = "NO";
            }else{
                $estadoActivo = "SI";
            }
            if ($arEmpleado->getDiscapacidad() == 0){
                $discapacidad = "NO";
            }else{
                $discapacidad = "SI";
            }
            if ($arEmpleado->getEstadoContratoActivo() == 0){
                $estadoContratoActivo = "NO VIGENTE";
            }else{
                $estadoContratoActivo = "VIGENTE";
            }
            if ($arEmpleado->getCodigoDepartamentoEmpresaFk() == null){
                $departamentoEmpresa = "";
            }else{
                $departamentoEmpresa = $arEmpleado->getDepartamentoEmpresaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoHorarioFk() == null){
                $horario = "";
            }else{
                $horario = $arEmpleado->getHorarioRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEmpleadoEstudioTipoFk() == null){
                $empleadoEstudioTipo = "";
            }else{
                $empleadoEstudioTipo = $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadCesantiaFk() == null){
                $entidadCesantia = "";
            }else{
                $entidadCesantia = $arEmpleado->getEntidadCesantiaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCiudadExpedicionFk() != null){
                $ciudadExpedicion = $arEmpleado->getciudadExpedicionRel()->getNombre();
            } else {
                $ciudadExpedicion = "";
            }
            if ($arEmpleado->getCodigoCiudadNacimientoFk() != null){
                $ciudadNacimiento = $arEmpleado->getCiudadNacimientoRel()->getNombre();
            } else {
                $ciudadNacimiento = "";
            }
            if ($arEmpleado->getCodigoRhPk() != null){
                $rh = $arEmpleado->getRhRel()->getTipo();
            } else {
                $rh = "";
            }
            if ($arEmpleado->getCodigoBancoFk() != null){
                $banco = $arEmpleado->getBancoRel()->getNombre();
            } else {
                $banco = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arEmpleado->getDigitoVerificacion())
                    ->setCellValue('E' . $i, $ciudadExpedicion)
                    ->setCellValue('F' . $i, $arEmpleado->getFechaExpedicionIdentificacion())
                    ->setCellValue('G' . $i, $arEmpleado->getLibretaMilitar())
                    ->setCellValue('H' . $i, $centroCosto)
                    ->setCellValue('I' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('J' . $i, $arEmpleado->getTelefono())
                    ->setCellValue('K' . $i, $arEmpleado->getCelular())
                    ->setCellValue('L' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('M' . $i, $arEmpleado->getBarrio())
                    ->setCellValue('N' . $i, $arEmpleado->getciudadRel()->getNombre())
                    ->setCellValue('O' . $i, $rh)
                    ->setCellValue('P' . $i, $sexo)
                    ->setCellValue('Q' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('R' . $i, $arEmpleado->getFechaNacimiento())
                    ->setCellValue('S' . $i, $ciudadNacimiento)
                    ->setCellValue('T' . $i, $arEmpleado->getEstadoCivilRel()->getNombre())
                    ->setCellValue('U' . $i, $padreFamilia)
                    ->setCellValue('V' . $i, $cabezaHogar)
                    ->setCellValue('W' . $i, $empleadoEstudioTipo)
                    ->setCellValue('X' . $i, $entidadSalud)
                    ->setCellValue('Y' . $i, $entidadPension)
                    ->setCellValue('Z' . $i, $entidadCaja)
                    ->setCellValue('AA' . $i, $entidadCesantia)
                    ->setCellValue('AB' . $i, $clasificacionRiesgo)
                    ->setCellValue('AC' . $i, $arEmpleado->getCuenta())
                    ->setCellValue('AD' . $i, $banco)
                    ->setCellValue('AE' . $i, $arEmpleado->getFechaContrato())
                    ->setCellValue('AF' . $i, $arEmpleado->getFechaFinalizaContrato())
                    ->setCellValue('AG' . $i, $cargo)
                    ->setCellValue('AH' . $i, $arEmpleado->getCargoDescripcion())
                    ->setCellValue('AI' . $i, $tipoPension)
                    ->setCellValue('AJ' . $i, $tipoCotizante)
                    ->setCellValue('AK' . $i, $subtipoCotizante)
                    ->setCellValue('AL' . $i, $estadoActivo)
                    ->setCellValue('AM' . $i, $estadoContratoActivo)
                    ->setCellValue('AN' . $i, $arEmpleado->getCodigoContratoActivoFk())
                    ->setCellValue('AO' . $i, $arEmpleado->getCamisa())
                    ->setCellValue('AP' . $i, $arEmpleado->getJeans())
                    ->setCellValue('AQ' . $i, $arEmpleado->getCalzado())
                    ->setCellValue('AR' . $i, $departamentoEmpresa)
                    ->setCellValue('AS' . $i, $horario)
                    ->setCellValue('AT' . $i, $discapacidad);
            if($arEmpleado->getCodigoZonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU' . $i, $arEmpleado->getZonaRel()->getNombre()); 
            }
            if($arEmpleado->getCodigoSubzonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV' . $i, $arEmpleado->getSubzonaRel()->getNombre()); 
            }
            if($arEmpleado->getCodigoEmpleadoTipoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW' . $i, $arEmpleado->getEmpleadoTipoRel()->getNombre()); 
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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
