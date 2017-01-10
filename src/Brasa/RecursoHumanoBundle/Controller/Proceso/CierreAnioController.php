<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCierreAnioType;
class CierreAnioController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/proceso/cierre/anio", name="brs_rhu_proceso_cierre_anio")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 62)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        } 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);                       
        $this->listar();
        if($form->isValid()) {

        }
        $arCierresAnios = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:lista.html.twig', array(
            'arCierresAnios' => $arCierresAnios,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/proceso/cierre/anio/nuevo/{codigoCierreAnio}", name="brs_rhu_proceso_cierre_anio_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCierreAnio = 0) {        
        $em = $this->getDoctrine()->getManager();        
        $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();       
        if($codigoCierreAnio != 0) {
            $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoPagoAdicionalPeriodo);
        }
        
        $form = $this->createForm(RhuCierreAnioType::class, $arCierreAnio);                     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCierreAnio = $form->getData();                                                                                                          
            $em->persist($arCierreAnio);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_anio'));                                                                                                                                                                                                             
        }                

        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:nuevo.html.twig', array(
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/rhu/proceso/cierre/anio/cerrar/{codigoCierreAnio}", name="brs_rhu_proceso_cierre_anio_cerrar")
     */
    public function cerrarAction(Request $request, $codigoCierreAnio) {        
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_proceso_cierre_anio_cerrar', array('codigoCierreAnio' => $codigoCierreAnio)))
            ->add('salarioMinimo', NumberType::class)
            ->add('auxilioTransporte', NumberType::class)            
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
        $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);                
        
        if ($form->isValid()) {  
            set_time_limit(0);
            ini_set("memory_limit", -1); 
            $floSalarioMinimo = $form->get('salarioMinimo')->getData();
            $floAuxilioTransporte = $form->get('auxilioTransporte')->getData();            
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $floSalarioMinimoAnterior = $arConfiguracion->getVrSalario();
                        
            $arCierreAnio = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio();
            $arCierreAnio = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->find($codigoCierreAnio);
            $arCierreAnio->setEstadoCerrado(1);
            $arCierreAnio->setFechaAplicacion(new \DateTime('now'));
            $em->persist($arCierreAnio);
            
            $strFechaCambio = $arConfiguracion->getAnioActual()."/12/30";
            $arContratoMinimos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
            $strDql = "SELECT c FROM BrasaRecursoHumanoBundle:RhuContrato c WHERE c.estadoActivo = 1 AND c.VrSalario <= " . $arConfiguracion->getVrSalario();
            $query = $em->createQuery($strDql);
            $arContratoMinimos = $query->getResult();                        
            foreach ($arContratoMinimos as $arContratoMinimo){
                $arCambioSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
                $arCambioSalario->setContratoRel($arContratoMinimo);
                $arCambioSalario->setEmpleadoRel($arContratoMinimo->getEmpleadoRel());
                $arCambioSalario->setFecha(date_create($strFechaCambio));
                $arCambioSalario->setVrSalarioAnterior($floSalarioMinimoAnterior);
                $arCambioSalario->setVrSalarioNuevo($floSalarioMinimo);
                $arCambioSalario->setDetalle('ACTUALIZACION SALARIO MINIMO');
                $em->persist($arCambioSalario);
                $arContratoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContratoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arContratoMinimo->getCodigoContratoPk());
                $arContratoActualizar->setVrSalario($floSalarioMinimo);
                $arContratoActualizar->setVrSalarioPago($floSalarioMinimo);
                if ($arContratoActualizar->getCodigoTipoTiempoFk() == 2){
                    $arContratoActualizar->setVrSalarioPago($floSalarioMinimo / 2);
                }
                $em->persist($arContratoActualizar);
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContratoMinimo->getCodigoEmpleadoFk());
                $arEmpleadoActualizar->setVrSalario($floSalarioMinimo);
                $em->persist($arEmpleadoActualizar);
            }
            $arConfiguracion->setAnioActual($arConfiguracion->getAnioActual() + 1);
            $arConfiguracion->setVrSalario($floSalarioMinimo);
            $arConfiguracion->setVrAuxilioTransporte($floAuxilioTransporte);
            $em->persist($arConfiguracion);
            //nuevo año periodo
            $anioNuevoPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreAnio;
            $anioNuevoPeriodo->setAnio($arConfiguracion->getAnioActual());
            $anioNuevoPeriodo->setEstadoCerrado(0);
            $em->persist($anioNuevoPeriodo);
            $em->flush();                                     
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_anio'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreAnio:cerrar.html.twig', array(
            'arCierreAnio' => $arCierreAnio,
            'form' => $form->createView()
        ));
    }

    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreAnio')->listaDql();
    }

}
