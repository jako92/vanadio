<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CartaLaboralController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/carta/laboral", name="brs_rhu_utilidades_carta_laboral")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 83)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {            
            if($request->request->get('OpImprimir')) {
                $codigoContrato = $request->request->get('OpImprimir');
                $arUsuario = $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                $arUsuario = $arUsuario->getUserName();
                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $codigoCartaTipo = $arConfiguracion->getCodigoFormatoCarta();
                if ($codigoCartaTipo == 0){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                    $objFormatoCarta->Generar($this, $em, $arUsuario,  '', 6, date('Y-m-d'), "", $codigoContrato,"","","","","","","");
                }
                if ($codigoCartaTipo == 1){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta1teg();
                    $objFormatoCarta->Generar($this, $em, $arUsuario,  '', 6, date('Y-m-d'), "", $codigoContrato,"","","","","","","");
                }
                if ($codigoCartaTipo == 2){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaPlazas();
                    $objFormatoCarta->Generar($this, $em, $arUsuario,  '', 6, date('Y-m-d'), "", $codigoContrato,"","","","","","","");
                }
                if ($codigoCartaTipo == 3){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaEurovic();
                    $objFormatoCarta->Generar($this, $em, $arUsuario,  '', 6, date('Y-m-d'), "", $codigoContrato,"","","","","","","");
                }
                if ($codigoCartaTipo == 4){
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaGr();
                    $objFormatoCarta->Generar($this, $em, $arUsuario,  '', 6, date('Y-m-d'), "", $codigoContrato,"","","","","","","");
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            } 
        }       
                
        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Cartas/CartaLaboral:lista.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }         
    
    /**
     * @Route("/rhu/utilidades/carta/laboralparametros/{codigoContrato}", name="brs_rhu_utilidades_carta_laboralparametros")
     */
    public function cartarLaboralParametrosAction(Request $request, $codigoContrato) {       
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        //Inicio promedio mensual
        $intPeriodo = 0;
        $strPeriodo = $arContrato->getCentroCostoRel()->getPeriodoPagoRel()->getNombre();
        if ($strPeriodo == "SEMANAL"){
            $intPeriodo = 4;
        }
        if ($strPeriodo == "DECADAL"){
            $intPeriodo = 3;
        }
        if ($strPeriodo == "CATORCENAL"){
            $intPeriodo = 2;
        }
        if ($strPeriodo == "QUINCENAL"){
            $intPeriodo = 2;
        }
        if ($strPeriodo == "MENSUAL"){
            $intPeriodo = 1;
        }
        $arSuplementario = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->tiempoSuplementarioCartaLaboral($intPeriodo, $arContrato->getCodigoContratoPk());            
        $floPromedioSalario = $arSuplementario;
        $arNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->noPrestacionalCartaLaboral($intPeriodo, $arContrato->getCodigoContratoPk());            
        $floNoPrestacional = $arNoPrestacional;
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_utilidades_carta_laboralparametros', array('codigoContrato' => $codigoContrato)))                        
            ->add('salario', TextType::class, array('required'  => false, 'data' => true))                 
            ->add('promedioIbp', CheckboxType::class, array('required'  => false, 'data' => false))                 
            ->add('promedioNoPrestacional', CheckboxType::class, array('required'  => false, 'data' => false))                 
            ->add('salarioSugerido', TextType::class, array('required' => false))
            ->add('promedioIbpSugerido', TextType::class, array('required' => false))
            ->add('promedioNoPrestacionalSugerido', TextType::class, array('required' => false))
            ->add('destinatario', TextType::class, array('required' => false))
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
           
        if ($form->isValid()) {
            $usuarioCarta = $usuarioCarta = $this->get('security.token_storage')->getToken()->getUser();
            $usuarioCarta = $usuarioCarta->getNombreCorto();
            $arUsuario = $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arUsuario = $arUsuario->getUserName();
            $codigoCartaTipo = 5;
            $salario = $form->get('salario')->getData();
            $promedioIbp = $form->get('promedioIbp')->getData();
            $promedioNoPrestacional = $form->get('promedioNoPrestacional')->getData();
            $salarioSugerido = $form->get('salarioSugerido')->getData();
            $promedioIbpSugerido = $form->get('promedioIbpSugerido')->getData();
            $promedioNoPrestacionalSugerido = $form->get('promedioNoPrestacionalSugerido')->getData();
            $destinatario = $form->get('destinatario')->getData();  
            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            if ($arConfiguracion->getCodigoFormatoCarta() == 0){
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                $objFormatoCarta->Generar($this, $em, $arUsuario, $usuarioCarta,  $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,$salario,$promedioIbp,$promedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido,$destinatario);
            }
            if ($arConfiguracion->getCodigoFormatoCarta() == 1){
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta1teg();
                $objFormatoCarta->Generar($this, $em, $arUsuario, $usuarioCarta, $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,$salario,$promedioIbp,$promedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido,$destinatario);
            }
            if ($arConfiguracion->getCodigoFormatoCarta() == 3){
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaEurovic();
                $objFormatoCarta->Generar($this, $em, $arUsuario, $usuarioCarta,  $codigoCartaTipo, date('Y-m-d'), "", $codigoContrato,$salario,$promedioIbp,$promedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido,$destinatario);
            }
            if ($arConfiguracion->getCodigoFormatoCarta() == 4){
                $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCartaGr();
                $objFormatoCarta->Generar($this, $em, $arUsuario, $usuarioCarta, $codigoCartaTipo, date('Y-m-d'), "",  $codigoContrato,$salario,$promedioIbp,$promedioNoPrestacional,$salarioSugerido,$promedioIbpSugerido,$promedioNoPrestacionalSugerido,$destinatario);
            }
            //return $this->redirect($this->generateUrl('brs_rhu_utilidades_carta_laboral'));
            
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Carta:CartaLaboralParametros.html.twig', array(
            'arContrato' => $arContrato,
            'promedioIbp' => $floPromedioSalario,
            'promedioNoPrestacional' => $floNoPrestacional,
            'form' => $form->createView()
        ));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
        $arrayPropiedadesCentroCosto = array(
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
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', EntityType::class, $arrayPropiedadesCentroCosto)
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                            
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                                            
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = new Session;
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosCartaLaboralDQL(
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroIdentificacion')
            );  
    }         
    
    private function filtrarLista($form, Request $request) {
        $session = new Session;        
        $codigoCentroCosto = "";
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();    
        }        
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);                        
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }         
    
}
