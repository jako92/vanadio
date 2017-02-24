<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\SeguridadSocial;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracion controller.
 *
 */
class ConfiguracionAporteController extends Controller
{
    /**
     * @Route("/rhu/utilidad/seguridadsocial/configuracion/aporte", name="brs_rhu_utilidad_seguridadsocial_configuracion_aporte")
     */
    public function configuracionAporteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracionAporte = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionAporte();
        $arConfiguracionAporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionAporte')->find(1);

        $formConfiguracionAporte = $this->createFormBuilder() 
            ->add('formaPresentacion', TextType::class, array('data' => $arConfiguracionAporte->getFormaPresentacion(),'required' => true))    
            ->add('nombreEmpresa', TextType::class, array('data' => $arConfiguracionAporte->getNombreEmpresa(),'required' => true))
            ->add('tipoIdentificacionEmpresa', TextType::class, array('data' => $arConfiguracionAporte->getTipoIdentificacionEmpresa(),'required' => true))
            ->add('identificacionEmpresa', TextType::class, array('data' => $arConfiguracionAporte->getIdentificacionEmpresa(),'required' => true))
            ->add('digitoVerificacionEmpresa', TextType::class, array('data' => $arConfiguracionAporte->getDigitoVerificacionEmpresa(),'required' => true))
            ->add('codigoEntidadRiesgosProfesionales', TextType::class, array('data' => $arConfiguracionAporte->getCodigoEntidadRiesgosProfesionales(),'required' => true))
            ->add('guardar', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracionAporte->handleRequest($request);
        if ($formConfiguracionAporte->isValid()) {
            $controles = $request->request->get('form');
            $formaPresentacion = $controles['formaPresentacion'];
            $nombreEmpresa = $controles['nombreEmpresa'];
            $tipoIdentificacionEmpresa = $controles['tipoIdentificacionEmpresa'];
            $identificacionEmpresa = $controles['identificacionEmpresa'];
            $digitoverificacionEmpresa = $controles['digitoVerificacionEmpresa'];
            $codigoEntidadRiesgoProfesional = $controles['codigoEntidadRiesgosProfesionales'];
            
            $arConfiguracionAporte->setformaPresentacion($formaPresentacion);
            $arConfiguracionAporte->setnombreEmpresa($nombreEmpresa);
            $arConfiguracionAporte->settipoIdentificacionEmpresa($tipoIdentificacionEmpresa);
            $arConfiguracionAporte->setdigitoverificacionEmpresa($digitoverificacionEmpresa);
            $arConfiguracionAporte->setcodigoEntidadRiesgosProfesionales($codigoEntidadRiesgoProfesional);              
        $em->persist($arConfiguracionAporte);
        $em->flush();
        return $this->redirect($this->generateUrl('brs_rhu_utilidad_seguridad_social_configuracion_aporte', array('codigoConfiguracionAportePk' => 1)));

        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/SeguridadSocial:configuracionAporte.html.twig', array(
            'formConfiguracionAporte' => $formConfiguracionAporte->createView(),
        ));
    }

}
