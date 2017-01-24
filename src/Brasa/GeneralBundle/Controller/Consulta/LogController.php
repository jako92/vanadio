<?php

namespace Brasa\GeneralBundle\Controller\Consulta;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class LogController extends Controller
{
    
    /**
     * @Route("/gen/consulta/log/{codigoUsuario}/{codigoDocumento}/{id}", name="brs_gen_consulta_log")
     */     
    public function listaAction(Request $request, $codigoUsuario , $codigoDocumento, $id) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $query = $em->createQuery($em->getRepository('BrasaGeneralBundle:GenLog')->listaDql($codigoUsuario, $codigoDocumento, $id));        
        $arLogs = $paginator->paginate($query, $request->query->get('page', 1), 50);                               
        return $this->render('BrasaGeneralBundle:Consulta/Log:lista.html.twig', array(
            'arLogs' => $arLogs
            ));
    }              
    
}
