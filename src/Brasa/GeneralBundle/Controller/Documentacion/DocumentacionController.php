<?php

namespace Brasa\GeneralBundle\Controller\Documentacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\DoctrineBundle\ConnectionFactory;

class DocumentacionController extends Controller
{           
    /**
     * @Route("/general/documentacion/", name="brasa_general_documentacion")
     */
    public function indexAction() {
    
        return $this->render('BrasaGeneralBundle:Documentacion:documentacion.html.twig');
    }                     
    
}
