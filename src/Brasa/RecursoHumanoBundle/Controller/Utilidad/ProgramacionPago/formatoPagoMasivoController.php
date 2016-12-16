<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\ProgramacionPago;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class formatoPagoMasivoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/programacion/pago/comprobante/masivo/{codigoProgramacionPago}", name="brs_rhu_utilidades_programacion_pago_comprobante_masivo")
     */         
    public function listaAction(Request $request, $codigoProgramacionPago = "") {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 75)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {
                //if ($form->get('masivo')->getData() == true){
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                $arZona = $form->get('zonaRel')->getData();
                if($arZona) {
                    $codigoZona = $arZona->getCodigoZonaPk();
                } else {
                    $codigoZona = "";
                }
                $arSubzona = $form->get('subzonaRel')->getData();
                if($arSubzona) {
                    $codigoSubzona = $arSubzona->getCodigoSubzonaPk();
                } else {
                    $codigoSubzona = "";
                }    
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $codigoFormato = $arConfiguracion->getCodigoFormatoPago();
                if($codigoFormato <= 1) {
                    $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\PagoMasivo1();
                    $objFormatoPago->Generar($em, $form->get('numero')->getData(), "", "", $codigoZona, $codigoSubzona, $form->get('porFecha')->getData(), $fechaDesde->format('Y-m-d'), $fechaHasta->format('Y-m-d'), $form->get('dato')->getData());
                }
                if($codigoFormato == 2) {
                    $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\PagoMasivo2();
                    $objFormatoPago->Generar($em, $form->get('numero')->getData(), "", "", $codigoZona, $codigoSubzona, $form->get('porFecha')->getData(), $fechaDesde->format('Y-m-d'), $fechaHasta->format('Y-m-d'), $form->get('dato')->getData());
                }                                 
            }            
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/ProgramacionesPago:comprobanteMasivo.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {  
        $em = $this->getDoctrine()->getManager();  
        $session = new Session;
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
        if($session->get('filtroRhuCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroRhuCodigoZona'));
        }
        $arrayPropiedadesSubzona = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroRhuCodigoSubzona')) {
            $arrayPropiedadesSubzona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuSubzona", $session->get('filtroRhuCodigoSubzona'));
        }        
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroFormatoMasivoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroFormatoMasivoFechaHasta');
        }
        if($session->get('filtroFormatoMasivoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroFormatoMasivoFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);        
        $form = $this->createFormBuilder()  
            ->add('zonaRel', EntityType::class, $arrayPropiedadesZona)                
            ->add('subzonaRel', EntityType::class, $arrayPropiedadesSubzona)                
            ->add('numero',TextType::class, array('required'  => false, 'data' => ""))
            ->add('dato',TextType::class, array('required'  => false, 'data' => ""))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                                
            ->add('porFecha', CheckboxType::class, array('required'  => false, 'data' => true))
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))    
            ->getForm();        
        return $form;
    }                 
    
    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false, $archivo = "") {

        /* Declara el handle del objeto */
        if (!$handle) {
            $handle = new \ZipArchive();
            if ($handle->open($zip_salida, ZipArchive::CREATE) === false) {
                return false; /* Imposible crear el archivo ZIP */
            }
        }

        /* Procesa directorio */
        if (is_dir($ruta)) {
            /* Aseguramos que sea un directorio sin carácteres corruptos */
            $ruta = dirname($ruta . '/arch.ext');
            $handle->addEmptyDir($ruta); /* Agrega el directorio comprimido */            
            $dir = opendir($ruta);            
            while ($current = readdir($dir)){
                if( $current != "." && $current != "..") {
                    $this->comprimir($ruta . "/" . $current, $zip_salida, $handle, true, $current); /* Comprime el subdirectorio o archivo */
                }
            }            
            //foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
                //$this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            //}

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta, $archivo);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

}
