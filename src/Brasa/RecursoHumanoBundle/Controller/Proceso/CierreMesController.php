<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCierreMesType;

class CierreMesController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/proceso/cierre/mes", name="brs_rhu_proceso_cierre_mes")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 62)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/ 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);                       
        $this->listar();
        if($form->isValid()) {

        }
        $arCierresMes = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreMes:lista.html.twig', array(
            'arCierresMes' => $arCierresMes,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/proceso/cierre/mes/nuevo/{codigoCierreMes}", name="brs_rhu_proceso_cierre_mes_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCierreMes = 0) {        
        $em = $this->getDoctrine()->getManager();        
        $arCierreMes = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreMes();       
        if($codigoCierreMes != 0) {
            $arCierreMes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->find($codigoCierreMes);
        }
        
        $form = $this->createForm(RhuCierreMesType::class, $arCierreMes);                     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCierreMes = $form->getData();                                                                                                          
            $em->persist($arCierreMes);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_mes'));                                                                                                                                                                                                             
        }                

        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreMes:nuevo.html.twig', array(
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/rhu/proceso/cierre/mes/cerrar/{codigoCierreMes}", name="brs_rhu_proceso_cierre_mes_cerrar")
     */
    public function cerrarAction(Request $request, $codigoCierreMes) {        
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_proceso_cierre_mes_cerrar', array('codigoCierreMes' => $codigoCierreMes)))
            ->add('salarioMinimo', NumberType::class)
            ->add('auxilioTransporte', NumberType::class)            
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arCierreMes = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreMes();
        $arCierreMes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->find($codigoCierreMes);                
        
        if ($form->isValid()) {  
            set_time_limit(0);
            ini_set("memory_limit", -1); 
            $floSalarioMinimo = $form->get('salarioMinimo')->getData();
            $floAuxilioTransporte = $form->get('auxilioTransporte')->getData();            
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            $floSalarioMinimoAnterior = $arConfiguracion->getVrSalario();
                        
            $arCierreMes = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreMes();
            $arCierreMes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->find($codigoCierreMes);
            $arCierreMes->setEstadoCerrado(1);
            $arCierreMes->setFechaAplicacion(new \DateTime('now'));
            $em->persist($arCierreMes);
            
            $strFechaCambio = $arConfiguracion->getMesActual()."/12/30";
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
            $arConfiguracion->setMesActual($arConfiguracion->getMesActual() + 1);
            $arConfiguracion->setVrSalario($floSalarioMinimo);
            $arConfiguracion->setVrAuxilioTransporte($floAuxilioTransporte);
            $em->persist($arConfiguracion);
            //nuevo año periodo
            $mesNuevoPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuCierreMes;
            $mesNuevoPeriodo->setMes($arConfiguracion->getMesActual());
            $mesNuevoPeriodo->setEstadoCerrado(0);
            $em->persist($mesNuevoPeriodo);
            $em->flush();                                     
            return $this->redirect($this->generateUrl('brs_rhu_proceso_cierre_mes'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/CierreMes:cerrar.html.twig', array(
            'arCierreMes' => $arCierreMes,
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
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCierreMes')->listaDql();
    }

}
