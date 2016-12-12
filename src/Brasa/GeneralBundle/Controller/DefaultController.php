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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();   
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        if($arConfiguracion->getInhabilitado() == 1) {           
            return $this->redirect($this->generateUrl('logout'));
        }
        return $this->render('BrasaGeneralBundle:Default:index.html.twig');
    }
    
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        //$arUsuario = $this->get('security.context')->getToken()->getUser();
        //$strUsuario = $arUsuario->getNombreCorto();
        //$destinatario = $this->contenedor->getParameter('contact_email');
        //$obj = new \Brasa\GeneralBundle\MisClases\CambiarBD();
        //$obj->setUpAppConnection($this);
        //\Brasa\GeneralBundle\MisClases\CambiarBD::setUpAppConnection();
        $arModulo = new \Brasa\GeneralBundle\Entity\GenModulo();
        $arModulo = $em->getRepository('BrasaGeneralBundle:GenModulo')->find(1);
        return $this->render('BrasaGeneralBundle:plantillas:menu.html.twig', array(
            'arModulo' => $arModulo
        ));
    }                  
    
}
