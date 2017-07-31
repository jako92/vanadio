<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Costo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\UtlProgramacion;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Entity\TurProgramacionSimulador;

class SimuladorController extends Controller {
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
     * mes seleccinado para la simulación.
     * @var int
     */
    private $mesSimulacion;
    /**
     * Año de simulación.
     * @var int
     */
    private $anioSimulacion;
    /**
     * Total dias del mes.
     * @var int
     */
    private $totalDiasMes = 0;
    /**
     * Meses del año.
     * @var array
     */
    private $meses = array(
        'Enero'     => 1, 
        'Febrero'   => 2, 
        'Marzo'     => 3, 
        'Abril'     => 4,
        'Mayo'      => 5, 
        'Junio'     => 6, 
        'Julio'     => 7, 
        'Agosto'    => 8, 
        'Septiembre'=> 9, 
        'Octubre'   => 10, 
        'Noviembre' => 11, 
        'Diciembre' => 12
    );
    /**
     * Turnos guardados en el sistema.
     * @var array
     */
    private $turnos = [];
    /**
     * Festivos del mes.
     * @var array
     */
    private $festivos = [
        '2017-07-20',
        '2017-08-07'
    ];
    
    /**
     * Esta función es la acción inicial de la aplicación.
     * @Route("/tur/utilidad/costos/simulador", name="brs_tur_utilidad_costo_simulador")
     */
    public function listarAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $dql = $em->getRepository("BrasaTurnoBundle:TurProgramacionSimulador")
                   ->getDqlConsultaDetalles();
        $arProgramacionSimulador = $em->createQuery($dql);
        $form = $this->getFormularioSimulacion();
        
        # Obtenemos un listado de los días del mes.
        setlocale(LC_ALL, "es_CO.UTF-8");
        $diasMes = [];       
        $arFestivos = $em->getRepository("BrasaGeneralBundle:GenFestivo")
                        ->festivos(date("Y-01-01"), date("Y-12-31"));
        
        foreach($arFestivos AS $festivo) $this->festivos[] = $festivo['fecha']->format("Y-m-d");
        
        UtlProgramacion::Util()->setFestivos($this->festivos);
        
        $this->totalDiasMes = cal_days_in_month(CAL_GREGORIAN, $this->mesSimulacion, $this->anioSimulacion);
        for($i = 1; $i <= $this->totalDiasMes; $i ++){
            $mes = "{$this->anioSimulacion}-{$this->mesSimulacion}-{$i}";
            $diasMes[($i < 10? '0' . $i : $i)] = (Object)[
                'numero'    => $i,
                'nombre'    => strftime("%A", strtotime($mes)),
                'esFestivo' => UtlProgramacion::Util()->esFestivo($mes),
            ];
        }
        
        $form->handleRequest($request);
        
        # Eventos
        if($form->isSubmitted() && $form->isValid()){
            if($form->get("BtnActualizar")->isClicked()) $this->actualizar($request);        
            if($form->get("BtnSimular")->isClicked()) $this->ejecutarSimulacion($request);
            if($form->get("BtnEliminar")->isClicked()) $this->eliminarRecursos($request);            
        }
        
        return $this->render('BrasaTurnoBundle:Utilidades/Costo/Simulador:inicio.html.twig', [
            'arProgramacionSimulador' => $arProgramacionSimulador->getResult(),
            'form' => $form->createView(),
            'diasMes' => $diasMes,
        ]);
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
        $query = $qb->delete("BrasaTurnoBundle:TurProgramacionSimulador", "b")
                    ->where("b.codigoSimulacionPk IN(:ids)")
                    ->setParameter("ids", $recursos)
                    ->getQuery();
        $query->execute();
        return $this->redirectToRoute("brs_tur_utilidad_costo_simulador");
    }
    
    /**
     * Esta función permite actualizar la programación del simulador.
     * @param Request $request
     * @return boolean
     */
    private function actualizar(Request $request){
        $programaciones = $request->get("dias");
        if(count($programaciones) == 0) return false;
        foreach ($programaciones aS $idProgramacion=>$dias){
            $em = $this->getDoctrine()->getManager();
            $arProgramacionSimulacion = $em->getRepository("BrasaTurnoBundle:TurProgramacionSimulador")->find($idProgramacion);
            $contador = 1;
            $this->procesarDetalle($em, $arProgramacionSimulacion, $dias, $contador);
            $em->flush();
        }
        return $this->redirectToRoute('brs_tur_utilidad_costo_simulador');
    }
    
    /**
     * Esta función permite actualizar cada detalle de la programación (setea cada uno de sus días).
     * @param \Doctrine\Common\Persistence\AbstractManagerRegistry $em
     * @param \Brasa\TurnoBundle\Entity\TurProgramacion $arProgramacionSimulacion
     * @param int $dias
     */
    private function procesarDetalle(&$em, &$arProgramacionSimulacion, $dias){
        foreach($dias AS $dia=>$turno){
            if(trim($turno) == "") $turno = null;
            $arProgramacionSimulacion->setDia($dia, $turno);
            $em->persist($arProgramacionSimulacion);
        }
    }
    
    /**
     * Esta función crea el formulario de simulación.
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getFormularioSimulacion(){
        $datos = array(
            'mes' => $this->mesSimulacion = intval(date('m')),
            'anio' => $this->anioSimulacion = intval(date("Y"))
        );
        return $this->createFormBuilder($datos)
                     ->add("mes", ChoiceType::class, array('choices' => $this->meses))
                     ->add("anio", TextType::class)
                     ->add("BtnAgregar", SubmitType::class, array('label' => 'Agregar'))
                     ->add("BtnActualizar", SubmitType::class, array('label' => 'Actualizar'))
                     ->add("BtnEliminar", SubmitType::class, array('label' => 'Eliminar'))
                     ->add("BtnSimular", SubmitType::class, array('label' => 'Simular'))
                     ->getForm();
    }
    
    /**
     * Esta función ejecuta la acción de agregar un recurso a la simulación.
     * @Route("/tur/utilidad/costos/simulador/agregarRecurso", name="brs_tur_utilidad_costo_simulador_agregar_recurso")
     */
    public function agregarRecursoAction(Request $request){
        $form = $this->formularioAgregarRecurso();
        $form->handleRequest($request);
        $this->guardarNuevoRecurso($form, $request);
        return $this->render("BrasaTurnoBundle:Utilidades/Costo/Simulador:agregarRecurso.html.twig", [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Esta función guarda el recurso a la simulación.
     * @param type $form
     * @param type $request
     * @return boolean
     */
    private function guardarNuevoRecurso($form, $request){
        if(!$form->get('BtnAgregar')->isClicked() && $request->get("TxtCodigoRecurso") == "") return false;
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
        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
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