<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\DoctrineBundle\ConnectionFactory;

class DefaultController extends Controller
{           
    /**
     * @Route("/", name="brasa_general_inicio")
     */
    public function indexAction() {
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();   
        /*$arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();        
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        if($arConfiguracion->getInhabilitado() == 1) {           
            return $this->redirect($this->generateUrl('logout'));
        }
        $fecha = new \DateTime('now');
        $fechaVenceServicio = $arConfiguracion->getFechaHastaServicio();
        $diff = $fecha->diff($fechaVenceServicio);
        $dias = $diff->format('%r%a');
        if($dias <= 3 ) {
            $objMensaje->Mensaje("error", "El servicio de soporte, mantenimiento y actualizacion vence en " . $dias . " dias, si el contrato es de tipo arrendamiento se suspende totalmente el acceso");
        }*/
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $this->getUser();
        if ($arUsuario->getCambiarClave()){
            return $this->redirectToRoute('brs_seg_user_usuario_cambiar_clave', array('codigoUsuario' => $arUsuario->getId()));
        }
        return $this->render('BrasaGeneralBundle:Default:index.html.twig');
    }                     
    
}
