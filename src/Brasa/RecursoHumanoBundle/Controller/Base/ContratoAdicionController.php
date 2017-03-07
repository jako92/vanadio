<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoAdicionType;
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
            $dato = "SI";
        }else {
            $dateAplicacion = new \DateTime('now');
            $arContratoAdicion->setFecha($dateAplicacion);
            $dato = "NO";
        }            
        $form = $this->createForm(RhuContratoAdicionType::class, $arContratoAdicion); 
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $arContratoAdicionTipo = $form->get('contratoAdicionTipoRel')->getData();
            if ($codigoContratoAdicion == 0){
                $arContenidoFormato = $em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find($arContratoAdicionTipo->getCodigoContenidoFormatoFk());
                $contenido = $arContenidoFormato->getContenido();
            } else {
                if ($arContratoAdicion->getCodigoContratoAdicionTipoFk() != $arContratoAdicionTipo->getCodigoContratoAdicionTipoPk()){
                    $arTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoAdicionTipo')->find($arContratoAdicion->getCodigoContratoAdicionTipoFk());
                    $arContratoAdicion->setContratoAdicionTipoRel($arTipo);
                }
                $contenido = $form->get('contenido')->getData();                
            }
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();            
            $arContratoAdicion->setContratoRel($arContrato);            
            $arContratoAdicion->setFecha($form->get('fecha')->getData());            
            $arContratoAdicion->setCodigoUsuario($arUsuario->getUserName());            
            $arContratoAdicion->setContenido($contenido);
            $em->persist($arContratoAdicion);                       
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                         
        }
        return $this->render('BrasaRecursoHumanoBundle:ContratoAdicion:nuevo.html.twig', array(
            'form' => $form->createView(),
            'edicion' => $dato
        ));
    }

}
