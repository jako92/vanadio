<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracionGeneral controller.
 *
 */
class ConfiguracionGeneralController extends Controller
{
    /**
     * @Route("/gen/configuracion/general/{codigoConfiguracionPk}", name="brs_gen_configuracion_general")
     */
    public function configuracionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 93)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNotificaciones = new \Brasa\GeneralBundle\Entity\GenConfiguracionNotificaciones();
        $arConfiguracionNotificaciones = $em->getRepository('BrasaGeneralBundle:GenConfiguracionNotificaciones')->find(1);
        $arrayPropiedadesCiudad = array(
            'class' => 'BrasaGeneralBundle:GenCiudad',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')                                        
                ->orderBy('c.nombre', 'ASC');},
            'choice_label' => 'nombre',
            'required' => false);                   
        $arrayPropiedadesCiudad['data'] = $em->getReference("BrasaGeneralBundle:GenCiudad", $arConfiguracionGeneral->getCodigoCiudadFk());
        $formConfiguracionNotificaciones = $this->createFormBuilder() 
            ->add('correoTurnoInconsistencia', TextType::class, array('data' => $arConfiguracionNotificaciones->getCorreoTurnoInconsistencia(), 'required' => false))
            ->add('guardar', SubmitType::class, array('label' => 'Actualizar'))            
            ->getForm(); 
        $formConfiguracionNotificaciones->handleRequest($request);
        $formConfiguracionGeneral = $this->createFormBuilder() 
            ->add('nitEmpresa', TextType::class, array('data' => $arConfiguracionGeneral->getNitEmpresa(), 'required' => true))
            ->add('digitoVerificacion', NumberType::class, array('data' => $arConfiguracionGeneral->getDigitoVerificacionEmpresa(), 'required' => true))
            ->add('nombreEmpresa', TextType::class, array('data' => $arConfiguracionGeneral->getNombreEmpresa(), 'required' => true))    
            ->add('sigla', TextType::class, array('data' => $arConfiguracionGeneral->getSigla(), 'required' => true))    
            ->add('telefonoEmpresa', TextType::class, array('data' => $arConfiguracionGeneral->getTelefonoEmpresa(), 'required' => true))
            ->add('direccionEmpresa', TextType::class, array('data' => $arConfiguracionGeneral->getDireccionEmpresa(), 'required' => true))    
            ->add('ciudadRel', EntityType::class, $arrayPropiedadesCiudad)
            ->add('baseRetencionFuente', TextType::class, array('data' => $arConfiguracionGeneral->getBaseRetencionFuente(), 'required' => true))
            ->add('baseRetencionCree', TextType::class, array('data' => $arConfiguracionGeneral->getBaseRetencionCREE(), 'required' => true))    
            ->add('porcentajeRetencionFuente', TextType::class, array('data' => $arConfiguracionGeneral->getPorcentajeRetencionFuente(), 'required' => true))    
            ->add('porcentajeRetencionCree', TextType::class, array('data' => $arConfiguracionGeneral->getPorcentajeRetencionCREE(), 'required' => true))
            ->add('baseRetencionIvaVentas', TextType::class, array('data' => $arConfiguracionGeneral->getBaseRetencionIvaVentas(), 'required' => true))    
            ->add('porcentajeRetencionIvaVentas', TextType::class, array('data' => $arConfiguracionGeneral->getPorcentajeRetencionIvaVentas(), 'required' => true))
            ->add('fechaUltimoCierre', DateType::class, array('data' => $arConfiguracionGeneral->getFechaUltimoCierre(), 'required' => true))
            ->add('nitVentasMostrador', TextType::class, array('data' => $arConfiguracionGeneral->getNitVentasMostrador(), 'required' => true))    
            ->add('rutaTemporal', TextType::class, array('data' => $arConfiguracionGeneral->getRutaTemporal(), 'required' => true))    
            ->add('rutaAlmacenamiento', TextType::class, array('data' => $arConfiguracionGeneral->getRutaAlmacenamiento(), 'required' => true))                
            ->add('rutaImagenes', TextType::class, array('data' => $arConfiguracionGeneral->getRutaImagenes(), 'required' => true))                
            ->add('rutaImagenesVer', TextType::class, array('data' => $arConfiguracionGeneral->getRutaImagenesVer(), 'required' => true))                
            ->add('rutaDirectorio', TextType::class, array('data' => $arConfiguracionGeneral->getRutaDirectorio(), 'required' => true))                                
            ->add('paginaWeb', TextType::class, array('data' => $arConfiguracionGeneral->getPaginaWeb(), 'required' => true))                                                
            ->add('guardar', SubmitType::class, array('label' => 'Actualizar'))            
            ->getForm();
        $formConfiguracionGeneral->handleRequest($request);
        if ($formConfiguracionGeneral->isValid()) {
            if($formConfiguracionGeneral->get('guardar')->isClicked()) {
                $controles = $request->request->get('form');                                    
                $NitEmpresa = $formConfiguracionGeneral->get('nitEmpresa')->getData();
                $NumeroDv = $controles['digitoVerificacion'];
                $NombreEmpresa = $controles['nombreEmpresa'];
                $Sigla = $controles['sigla'];
                $TelefonoEmpresa = $controles['telefonoEmpresa'];
                $DireccionEmpresa = $controles['direccionEmpresa'];
                $Ciudad = $controles['ciudadRel'];
                $BaseRetencionFuente = $controles['baseRetencionFuente'];
                $BaseRetencionCree = $controles['baseRetencionCree'];
                $PorcentajeRetencionFuente = $controles['porcentajeRetencionFuente'];
                $PorcentajeRetencionCree = $controles['porcentajeRetencionCree'];
                $BaseRetencionIvaVentas = $controles['baseRetencionIvaVentas'];
                $PorcentajeRetencionIvaVentas = $controles['porcentajeRetencionIvaVentas'];
                $FechaUltimoCierre = $formConfiguracionGeneral->get('fechaUltimoCierre')->getData();
                $NitVentasMostrador = $controles['nitVentasMostrador'];
                $RutaTemporal = $controles['rutaTemporal'];
                $RutaAlmacenamiento = $controles['rutaAlmacenamiento'];
                $RutaImagenes = $controles['rutaImagenes'];
                $RutaImagenesVer = $controles['rutaImagenesVer'];
                $RutaDirectorio = $controles['rutaDirectorio'];
                $PaginaWeb = $controles['paginaWeb'];
                // guardar la tarea en la base de datos
                $arConfiguracionGeneral->setNitEmpresa($NitEmpresa);
                $arConfiguracionGeneral->setDigitoVerificacionEmpresa($NumeroDv);
                $arConfiguracionGeneral->setNombreEmpresa($NombreEmpresa);
                $arConfiguracionGeneral->setSigla($Sigla);
                $arConfiguracionGeneral->setTelefonoEmpresa($TelefonoEmpresa);
                $arConfiguracionGeneral->setDireccionEmpresa($DireccionEmpresa);
                $arConfiguracionGeneral->setCodigoCiudadFk($Ciudad);
                $arConfiguracionGeneral->setBaseRetencionFuente($BaseRetencionFuente);
                $arConfiguracionGeneral->setBaseRetencionCree($BaseRetencionCree);
                $arConfiguracionGeneral->setPorcentajeRetencionFuente($PorcentajeRetencionFuente);
                $arConfiguracionGeneral->setPorcentajeRetencionCREE($PorcentajeRetencionCree);
                $arConfiguracionGeneral->setBaseRetencionIvaVentas($BaseRetencionIvaVentas);
                $arConfiguracionGeneral->setPorcentajeRetencionIvaVentas($PorcentajeRetencionIvaVentas);
                $arConfiguracionGeneral->setFechaUltimoCierre($FechaUltimoCierre);
                $arConfiguracionGeneral->setNitVentasMostrador($NitVentasMostrador);
                $arConfiguracionGeneral->setRutaTemporal($RutaTemporal);
                $arConfiguracionGeneral->setRutaAlmacenamiento($RutaAlmacenamiento);
                $arConfiguracionGeneral->setRutaImagenes($RutaImagenes);
                $arConfiguracionGeneral->setRutaImagenesVer($RutaImagenesVer);
                $arConfiguracionGeneral->setRutaDirectorio($RutaDirectorio);
                $arConfiguracionGeneral->setPaginaWeb($PaginaWeb);
                $em->persist($arConfiguracionGeneral);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_gen_configuracion_general', array('codigoConfiguracionPk' => 1)));                
            }
        }
        if ($formConfiguracionNotificaciones->isValid()) {            
            $arConfiguracionNotificaciones->setCorreoTurnoInconsistencia($formConfiguracionNotificaciones->get('correoTurnoInconsistencia')->getData());
            $em->persist($arConfiguracionNotificaciones);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_configuracion_general', array('codigoConfiguracionPk' => 1)));                
        }
        return $this->render('BrasaGeneralBundle:Base/ConfiguracionGeneral:Configuracion.html.twig', array(
            'formConfiguracionGeneral' => $formConfiguracionGeneral->createView(),
            'formConfiguracionNotificaciones' => $formConfiguracionNotificaciones->createView(),
        ));
    }

    /**
     * @Route("/general/borrar/bd/", name="brs_gen_borrar_bd")
     */
    public function borrarBDAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createFormBuilder()            
            ->add('BtnCargar', SubmitType::class, array('label'  => 'Borrar'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $fp = fopen("../src/Brasa/GeneralBundle/Resources/sql/datosDemoRecursoHumano.sql", "r");                                
                $strSql = "";
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $strSql = $strSql . $linea;
                    }
                }
                fclose($fp);
                //Turnos
                $em->getConnection()->executeQuery($strSql);
                $fp = fopen("../src/Brasa/GeneralBundle/Resources/sql/datosDemoTurnos.sql", "r");                                
                $strSql = "";
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $strSql = $strSql . $linea;
                    }
                }
                fclose($fp);
                $em->getConnection()->executeQuery($strSql);
                
                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                
            }                                   
        }         
        return $this->render('BrasaGeneralBundle:ConfiguracionGeneral:borrarBD.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
}
