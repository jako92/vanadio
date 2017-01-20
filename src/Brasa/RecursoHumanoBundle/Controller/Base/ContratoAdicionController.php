<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ContratoAdicionController extends Controller
{
    /**
     * @Route("/rhu/contratoadicion/nuevo/{codigoContrato}/{codigoContratoAdicion}", name="brs_rhu_contrato_adicion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoContratoAdicion = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContratoAdicion = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicion();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoContratoAdicion != 0)
        {
            $arContratoAdicion = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoAdicion')->find($codigoContratoAdicion);
            $dateAplicacion = $arContratoAdicion->getFecha();
        }else {
            $dateAplicacion = new \DateTime('now');
        }    
        $form = $this->createFormBuilder()
            ->add('bonificacion', NumberType::class, array('required' => true, 'data' => $arContratoAdicion->getVrBonificacion()))
            ->add('fecha', DateType::class, array('data' => new \DateTime('now')))
            ->add('detalle', TextType::class, array('required' => true, 'data' => $arContratoAdicion->getDetalle()))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();            
            $arContratoAdicion->setContratoRel($arContrato);
            $arContratoAdicion->setEmpleadoRel($arContrato->getEmpleadoRel());
            $arContratoAdicion->setFecha($form->get('fecha')->getData());
            $arContratoAdicion->setVrBonificacion($form->get('bonificacion')->getData());                
            $arContratoAdicion->setCodigoUsuario($arUsuario->getUserName());            
            $arContratoAdicion->setDetalle($form->get('detalle')->getData());            
            $em->persist($arContratoAdicion);                       
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                         
        }
        return $this->render('BrasaRecursoHumanoBundle:ContratoAdicion:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
