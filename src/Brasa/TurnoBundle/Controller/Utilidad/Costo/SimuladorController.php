<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Costo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Brasa\TurnoBundle\UtlProgramacion;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Entity\TurProgramacionSimulador;

class SimuladorController extends Controller {
    private $strDqlLista;
    private $strCodigo = "";
    private $strNombre = "";
    /**
     * Fecha inicial para la simulación.
     * @var string
     */
    private $fechaInicial;
    /**
     * Fecha final para la simulación.
     * @var string
     */
    private $fechaFinal;
    /**
     * Total dias del mes.
     * @var int
     */
    private $totalDiasMes = 0;
    /**
     * Turnos guardados en el sistema.
     * @var array
     */
    private $turnos = array();
    /**
     * Festivos del mes.
     * @var array
     */
    private $festivos = array();
    
    public function __construct() {
        
    }
    
    /**
     * Esta función es la acción inicial de la aplicación.
     * @Route("/tur/utilidad/costos/simulador/{simulacionId}", defaults={"simulacionId" = null},  name="brs_tur_utilidad_costo_simulador")
     */
    public function listarAction(Request $request, $simulacionId = null){
        $em = $this->getDoctrine()->getManager();
        UtlProgramacion::Util()->setEntityManager($em);
        $dql = $em->getRepository("BrasaTurnoBundle:TurProgramacionSimulador")
                   ->getDqlConsultaDetalles();
        $arProgramacionSimulador = $em->createQuery($dql);
        $form = $this->getFormularioSimulacion();
        $form->handleRequest($request);
        if($form->get('hasta')->getData()->format("y-m-d") != ""){
            $this->fechaInicial = $form->get('desde')->getData()->format("Y-m-d");
            $this->fechaFinal = $form->get('hasta')->getData()->format("Y-m-d");
        }
        # Obtenemos un listado de los días del mes.
        $diasMes = [];        
        UtlProgramacion::Util()->setFestivos($this->festivos);        
        $this->totalDiasMes = UtlProgramacion::Util()->getDiferencia($this->fechaInicial, $this->fechaFinal, UtlProgramacion::DIA);        
        setlocale(LC_ALL, "es_CO.UTF-8");
        
        $fecha = $this->fechaInicial;
        for($i = 1; $i <= $this->totalDiasMes; $i ++){
            $dia = intval(UtlProgramacion::Util()->getFormato($fecha, "d"));
            $diasMes[$i] = (Object)[
                'fecha'     => $fecha,
                'numero'    =>  $dia,
                'nombre'    => strftime("%A", strtotime($fecha)),
                'esFestivo' => UtlProgramacion::Util()->esFestivo($fecha),
            ];
            $fecha = UtlProgramacion::Util()->sumarAFecha($this->fechaInicial, UtlProgramacion::DIA, $i);
        }
        
        # Eventos
        if($form->isSubmitted() && $form->isValid()){
            if($form->get("BtnActualizar")->isClicked()) { $this->actualizar($request); }
            if($form->get("BtnSimular")->isClicked()) { $this->ejecutarSimulacion($request, $form); }
            if($form->get("BtnEliminar")->isClicked()) { $this->eliminarRecursos($request); }
        }
        
        $arrDetalles = array();
        if($request->request->has("BtnVerDetalle")){
            $simulacionId = $request->request->get("BtnVerDetalle");
            $arrDetalles = array(
                'horas' => array(), 
                'totales' => array('tot_diu' => 0, 'tot_noc' => 0, 'tot_diu_ext' => 0, 'tot_noc_ext' => 0, 'tot_fes_diu' => 0, 'tot_fes_noc' => 0, 'tot_fes_diu_ext' => 0, 'tot_fes_noc_ext' => 0)
            );
            
            $arDetalles = $em->getRepository("BrasaTurnoBundle:TurProgramacionSimuladorDetalle")
                             ->createQueryBuilder("t")
                             ->where("t.codigoSimulacionFk = :codigoSimulacionFk")
                             ->andWhere("t.fecha >= :fechaInicial")
                             ->andWhere("t.fecha <= :fechaFinal")
                             ->setParameter("codigoSimulacionFk", $simulacionId)
                             ->setParameter("fechaInicial", $this->fechaInicial)
                             ->setParameter("fechaFinal", $this->fechaFinal)
                             ->getQuery()->getResult();
            
            foreach($arDetalles AS $arDetalle){
                $arrDetalles['horas'][] = array(
                    'esFestivo' => UtlProgramacion::Util()->esFestivo($arDetalle->getFecha()),
                    'fecha' => $arDetalle->getFecha(),
                    'ordDiurnas' => $arDetalle->getOrdDiurnas(),
                    'ordDiuExtras' => $arDetalle->getOrdDiuExtras(),
                    'ordNocturnas' => $arDetalle->getOrdNocturnas(),
                    'ordNocExtras' => $arDetalle->getOrdNocExtras(),
                    'fesDiurnas' => $arDetalle->getFesDiurnas(),
                    'fesDiuExtras' => $arDetalle->getFesDiuExtras(),
                    'fesNocturnas' => $arDetalle->getFesNocturnas(),
                    'fesNocExtras' => $arDetalle->getFesNocExtras(),
                );
                $arrDetalles['totales']['tot_diu'] += $arDetalle->getOrdDiurnas();
                $arrDetalles['totales']['tot_diu_ext'] += $arDetalle->getOrdDiuExtras();
                $arrDetalles['totales']['tot_noc'] += $arDetalle->getOrdNocturnas();
                $arrDetalles['totales']['tot_noc_ext'] += $arDetalle->getOrdNocExtras();
                $arrDetalles['totales']['tot_fes_diu'] += $arDetalle->getFesDiurnas();
                $arrDetalles['totales']['tot_fes_diu_ext'] += $arDetalle->getFesDiuExtras();
                $arrDetalles['totales']['tot_fes_noc'] += $arDetalle->getFesNocturnas();
                $arrDetalles['totales']['tot_fes_noc_ext'] += $arDetalle->getFesNocExtras();
            }
        }
        
        return $this->render('BrasaTurnoBundle:Utilidades/Costo/Simulador:inicio.html.twig', [
            'arProgramacionSimulador' => $arProgramacionSimulador->getResult(),
            'form'      => $form->createView(),
            'diasMes'   => $diasMes,
            'arrDetalles'  => $arrDetalles,
            'mostrarModal' => count($arrDetalles) > 0,
        ]);
    }
    
    /**
     * Esta función permite ejecutar el proceso de simulación.
     * @param Request $request
     * @param \Symfony\Component\Form\Form $form
     */
    private function ejecutarSimulacion(Request $request,  $form){        
        $recursos = $request->get("dias");        
        $desde = $form->get("desde")->getData()->format("Y-m-d");
        $hasta = $form->get("hasta")->getData()->format("Y-m-d");
        $fechaInicial = UtlProgramacion::Util()->getFormato($desde, "Y-m");
        $diaInicial = intval(UtlProgramacion::Util()->getFormato($desde, "d"));
        $diaFinal = intval(UtlProgramacion::Util()->getFormato($hasta, "d"));
        $dias = UtlProgramacion::util()->getDiferencia($desde, $hasta, UtlProgramacion::DIA);
        $em = $this->getDoctrine()->getManager();
        $turnos = $em->getRepository("BrasaTurnoBundle:TurTurno")->getInformacionTurnos();
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("DELETE FROM BrasaTurnoBundle:TurProgramacionSimuladorDetalle");
        $query->execute();
        if($recursos == null){ return false; }
        foreach($recursos AS $idRecurso=>$diasPeriodo){
            $datos = ['ordDiurnas' => 0, 'ordNocturnas' => 0, 'ordDiuExt' => 0, 'ordNocExt' => 0, 'fesDiurnas' => 0, 'fesNocturnas' => 0, 'fesDiuExt' => 0, 'fesNocExt' => 0 ];
            foreach($diasPeriodo AS $fecha=>$turnoDia){
                if(trim($turnoDia) == "" && !isset($turnos[$turnoDia])){ continue; }
                $turno = (Object) $turnos[$turnoDia];
                $horas = UtlProgramacion::Util()->calcularHoras($fecha, $turno->desde, $turno->hasta);
                UtlProgramacion::util()->sumarHoras($horas, $datos);
                UtlProgramacion::Util()->limpiar();
                $arProgDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionSimuladorDetalle();
                $arProgDetalle->setCodigoSimulacionFk($idRecurso);
                $arProgDetalle->setFecha(new \DateTime(date("Y-m-d H:i:s", strtotime($fecha))));
                $arProgDetalle->setOrdDiurnas($horas['ordDiurnas']);
                $arProgDetalle->setOrdNocturnas($horas['ordNocturnas']);
                $arProgDetalle->setOrdDiuExtras($horas['ordDiuExt']);
                $arProgDetalle->setOrdNocExtras($horas['ordNocExt']);
                $arProgDetalle->setFesDiurnas($horas['fesDiurnas']);
                $arProgDetalle->setFesNocturnas($horas['fesNocturnas']);
                $arProgDetalle->setFesDiuExtras($horas['fesDiuExt']);
                $arProgDetalle->setFesNocExtras($horas['fesNocExt']);
                $em->persist($arProgDetalle);
                $em->flush();
           }
        }        
    }   
    
    /**
     * Esta función permite eliminar los recursos seleccionados por el formulario.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function eliminarRecursos(Request $request){
        $recursos = $request->get("recursosABorrar");
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        # Eliminamos los detalles
        $query = $qb->delete("BrasaTurnoBundle:TurProgramacionSimuladorDetalle", "b")
                    ->where("b.codigoSimulacionFk IN(:ids)")
                    ->setParameter("ids", $recursos)
                    ->getQuery();
        $query->execute();
        # Eliminamos la simulación
        $query = $qb->delete("BrasaTurnoBundle:TurProgramacionSimulador", "b")
                    ->where("b.codigoSimulacionPk IN(:ids)")
                    ->setParameter("ids", $recursos)
                    ->getQuery();
        $query->execute();
        return $this->get('router')->generate('brs_tur_utilidad_costo_simulador');
    }
    
    /**
     * Esta función permite actualizar la programación del simulador.
     * @param Request $request
     * @return boolean
     */
    private function actualizar(Request $request){
        $objMessage = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $programaciones = $request->get("dias");
        if(count($programaciones) == 0) return false;        
        $em = $this->getDoctrine()->getManager();
        $this->turnos = $em->getRepository("BrasaTurnoBundle:TurTurno")->getInformacionTurnos();
        $turnosNoEncontrados = array();
        foreach ($programaciones aS $idProgramacion=>$dias){
            $arProgramacionSimulacion = $em->getRepository("BrasaTurnoBundle:TurProgramacionSimulador")->find($idProgramacion);
            $contador = 1;
            $this->procesarDetalle($em, $arProgramacionSimulacion, $turnosNoEncontrados, $dias);
            $em->flush();
        }
        if(!is_null($turnosNoEncontrados)){
            $objMessage->Mensaje("error", "Los siguientes turnos no están registrados: " . implode(", ", $turnosNoEncontrados));
        }
        return $this->redirectToRoute('brs_tur_utilidad_costo_simulador');
    }
    
    /**
     * 
     * @param TurProgramacionSimulador $arProgramadionSimulador Entidad
     * @param int $dia
     * @param string $valor
     * @return boolean
     */
    private function setDiaSimulacion(&$arProgramadionSimulador, $dia, $valor) {           
        if(!method_exists($arProgramadionSimulador, "setDia{$dia}")) {
            return false;
        }        
        # Está función invoca la función del día deseado.
        call_user_func_array(array($arProgramadionSimulador, "setDia{$dia}"), array($valor));
    }
    
    /**
     * Esta función permite actualizar cada detalle de la programación (setea cada uno de sus días).
     * @param \Doctrine\Common\Persistence\AbstractManagerRegistry $em
     * @param \Brasa\TurnoBundle\Entity\TurProgramacion $arProgramacionSimulacion
     * @param int $dias
     */
    private function procesarDetalle(&$em, &$arProgramacionSimulacion, &$turnosNoEncontrados, $dias){
        foreach($dias AS $dia=>$codigoTurno){
            if(trim($codigoTurno) == "") {
                $codigoTurno = null;
            }
            if(!isset($this->turnos[$codigoTurno])){ 
                if($codigoTurno != null && !in_array($codigoTurno, $turnosNoEncontrados)) { $turnosNoEncontrados[] = $codigoTurno; }
                continue;
            }
            $intDia = intval(UtlProgramacion::Util()->getFormato($dia, "d"));
            $this->setDiaSimulacion($arProgramacionSimulacion, $intDia, $codigoTurno);
        }
        $em->persist($arProgramacionSimulacion);
    }
    
    /**
     * Esta función crea el formulario de simulación.
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getFormularioSimulacion(){
        $this->fechaInicial = date('Y-m-01');
        $this->fechaFinal = date("Y-m-t");
        $rango = range(date("Y"), date("Y") + 2);
        return $this->createFormBuilder()
                     ->add("desde", DateType::class, array('data' => new \DateTime($this->fechaInicial), 'years' => $rango, 'format' => 'yyyyMMdd'))
                     ->add("hasta", DateType::class, array('data' => new \DateTime($this->fechaFinal), 'years' => $rango, 'format' => 'yyyyMMdd'))
                     ->add("BtnAgregar", SubmitType::class, array('label' => 'Agregar'))
                     ->add("BtnActualizar", SubmitType::class, array('label' => 'Actualizar'))
                     ->add("BtnEliminar", SubmitType::class, array('label' => 'Eliminar'))
                     ->add("BtnSimular", SubmitType::class, array('label' => 'Simular'))
                     ->getForm();
    }
    
    /**
     * Esta función ejecuta la acción de agregar un recurso a la simulación.
     * @Route("/tur/utilidad/costos/simulador/recurso/agregar", name="brs_tur_utilidad_costo_simulador_agregar")
     */
    public function agregarRecursoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        
        if ($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->lista();
            }
        }
        
        $this->guardarNuevoRecurso($form, $request);
        
        $arRecurso = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render("BrasaTurnoBundle:Utilidades/Costo/Simulador:agregarRecurso.html.twig", [
            'form' => $form->createView(),
            'arRecursos' => $arRecurso,
            'campoCodigo' => '',
            'campoNombre' => '',
        ]);
    }
    
    /**
     * Esta función permite obtener el forulario de buscar recurso.
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function formularioLista() {
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $this->strCodigo))
            ->add('inactivos', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroTurnoRecursoInactivo')))
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroTurnoNumeroIdentificacion')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    /**
     * Esta función permite obtener el dql para filtrar los registros.
     */
    public function lista() {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurRecurso')->buscarDQL(
                $this->strNombre,
                $this->strCodigo,
                "",
                $session->get('filtroTurnoNumeroIdentificacion'),
                "", "", $session->get('filtroTurnoRecursoInactivo')
            );
    }
    
    /**
     * Esta función aplica los filtros a la lista.
     * @param type $form
     */
    private function filtrarLista($form) {
        $session = new Session();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $session->set('filtroTurnoRecursoInactivo', $form->get('inactivos')->getData());
        $session->set('filtroTurnoNumeroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
    }
    
    /**
     * Esta función guarda el recurso a la simulación.
     * @param type $form
     * @param type $request
     * @return boolean
     */
    private function guardarNuevoRecurso($form, $request){
        if (!$form->has('BtnAgregar') && $request->get("TxtCodigoRecurso") == "") {
            return false;
        }
        if($this->validarExistencia($request->get("TxtCodigoRecurso"))){
            echo "<script languaje='javascript' type='text/javascript'>alert('Este recurso ya se encuentra agregado.');</script>";
            return false;
        }
        $em = $this->getDoctrine()->getManager();
        $detalle = new TurProgramacionSimulador();
        $arRecurso = $em->getRepository("BrasaTurnoBundle:TurRecurso")->find($request->get('TxtCodigoRecurso'));
        $detalle->setRecursoRel($arRecurso);
        $em->persist($detalle);
        $em->flush();
        $url = $this->generateUrl('brs_tur_utilidad_costo_simulador');
        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.href= '{$url}';</script>";
        exit();
    }
    /**
     * Esta función valida la existencia de un recurso en la simulación.
     * @param type $codigoRecurso
     * @return type
     */
    private function validarExistencia($codigoRecurso){
        $em = $this->getDoctrine()->getManager();
        $arProgramacionSimulador = $em->getRepository("BrasaTurnoBundle:TurProgramacionSimulador")->findOneBy(array(
            'codigoRecursoFk' => $codigoRecurso,
        ));        
        return count($arProgramacionSimulador) > 0;
    }
   
    /**
     * Esta función construye el formulario de agregar recurso.
     * @return \Symfony\Component\Form\FormBuilder
     */
    private function formularioAgregarRecurso() {        
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))
                ->add('BtnAgregar', SubmitType::class, array('label' => 'Agregar'))
                ->getForm();
        return $form;
    }
}