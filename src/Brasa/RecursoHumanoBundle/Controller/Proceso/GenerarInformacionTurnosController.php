<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GenerarInformacionTurnosController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/rhu/proceso/generar/informacion/turno", name="brs_rhu_proceso_generar_informacion_turno")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if ($form->get('BtnGenerar')->isClicked()) { 
                
                set_time_limit(0);
                ini_set("memory_limit", -1); 
                $anio = $form->get('TxtAnio')->getData();
                $mes = $form->get('TxtMes')->getData();
                $arCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCosto();
                $arCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCosto')->findBy(array('anio' => $anio, 'mes' => $mes));
                foreach ($arCostos as $arCosto) {
                    $arEmpleadoCentroCostoMes = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
                    $arEmpleadoCentroCostoMes = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoCentroCosto')->findOneBy(array('codigoEmpleadoFk' => $arCosto->getCodigoEmpleadoFk(), 'anio' => $anio, 'mes' => $mes), array('participacion' => 'DESC'));                    
                    if($arEmpleadoCentroCostoMes) {
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();                                                
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arCosto->getCodigoEmpleadoFk());
                        if($arEmpleadoCentroCostoMes->getCodigoPuestoFk()) {
                            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($arEmpleadoCentroCostoMes->getCodigoPuestoFk());
                            if($arPuesto) {
                                $arEmpleado->setPuestoRel($arPuesto);
                                $arEmpleado->setCodigoClienteTurnoFk($arPuesto->getCodigoClienteFk());
                            }
                        }
                        if($arEmpleado->getEmpleadoTipoRel()->getOperativo() == 1 && $arEmpleado->getCentroCostoFijo() == 0) {
                            if($arEmpleadoCentroCostoMes->getCodigoCentroCostoFk()) {
                                $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($arEmpleadoCentroCostoMes->getCodigoCentroCostoFk());
                                if($arCentroCosto) {
                                    $arEmpleado->setPuestoRel($arPuesto);
                                }
                            }                            
                        }
                        $em->persist($arEmpleado);
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_proceso_generar_informacion_turno'));            
                
            }            
        }       
                
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarInformacionTurnos:lista.html.twig', array(
            'form' => $form->createView()));
    }          
    
    private function formularioLista() {
        $session = $this->get('session');
        $form = $this->createFormBuilder()
            ->add('TxtAnio', TextType::class, array('data' => $session->get('filtroRhuAnio')), 'required')
            ->add('TxtMes', TextType::class, array('data' => $session->get('filtroRhuMes')), 'required')                
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pendientesGenerarServicioDql();  
    }         
    
    
    
}
