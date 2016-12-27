<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrasladoSaludController extends Controller
{
    /**
     * @Route("/rhu/traslado/salud/nuevo/{codigoContrato}/{codigoTrasladoSalud}", name="brs_rhu_traslado_salud_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoTrasladoSalud = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arTrasladoSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoTrasladoSalud != 0)
        {
            $arTrasladoSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoSalud')->find($codigoTrasladoSalud);
        }
        if ($arContrato->getEstadoActivo()== 0){
            $objMensaje->Mensaje("error", "No tiene contrato activo");
        }
        $form = $this->createFormBuilder()
            ->add('entidadSaludNuevaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('fechaAplicacion', DateType::class, array('data' => new \DateTime('now')))
            ->add('fechaFosyga', DateType::class, array('data' => new \DateTime('now')))                                
            ->add('tipo', ChoiceType::class, array('choices' => array('TRASLADO' => '1', 'CAMBIO' => '2')))                                
            ->add('detalle', TextType::class, array('required' => true))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if ($arContrato->getEstadoActivo()== 0){
                $objMensaje->Mensaje("error", "No tiene contrato activo");
            }else{
                $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($arContrato->getCodigoEntidadSaludFk());
                $arTrasladoSalud->setContratoRel($arContrato);
                $arTrasladoSalud->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arTrasladoSalud->setFecha($form->get('fechaAplicacion')->getData());
                $arTrasladoSalud->setEntidadSaludNuevaRel($form->get('entidadSaludNuevaRel')->getData());
                $arTrasladoSalud->setEntidadSaludAnteriorRel($arEntidadSalud);
                $arTrasladoSalud->setDetalle($form->get('detalle')->getData());
                $arTrasladoSalud->setTipo($form->get('tipo')->getData());
                $arTrasladoSalud->setFechaFosyga($form->get('fechaFosyga')->getData());
                if ($form->get('tipo')->getData() == 1){
                    $arTrasladoSalud->setEstadoAfiliado(1);
                }
                $arContrato->setEntidadSaludRel($form->get('entidadSaludNuevaRel')->getData());
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getEmpleadoRel());
                if ($arEmpleadoActualizar->getCodigoCentroCostoFk() <> null){
                    $arEmpleadoActualizar->setEntidadSaludRel($form->get('entidadSaludNuevaRel')->getData());
                    $em->persist($arEmpleadoActualizar);
                }
                $em->persist($arContrato);
                $em->persist($arTrasladoSalud);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:TrasladoSalud:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));
    }
    
    /**
     * @Route("/rhu/traslado/salud/editar/{codigoContrato}/{codigoTrasladoSalud}", name="brs_rhu_traslado_salud_editar")
     */
    public function editarAction(Request $request, $codigoContrato, $codigoTrasladoSalud = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $arTrasladoSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud();
        $arTrasladoSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoSalud')->find($codigoTrasladoSalud);
        $estadoAfiliado = $arTrasladoSalud->getEstadoAfiliado(); 
        if ($estadoAfiliado == 1){
            $nombreEstadoAfiliado = "CERRADO";
        } else {
            $nombreEstadoAfiliado = "ABIERTO";
        }
        $form = $this->createFormBuilder()    
            ->setAction($this->generateUrl('brs_rhu_traslado_salud_editar', array('codigoContrato' => $codigoContrato, 'codigoTrasladoSalud' => $codigoTrasladoSalud)))
            ->add('fechaCambioAfiliacion', DateType::class, array('data' => new \DateTime('now')))
            ->add('estadoAfiliado', ChoiceType::class, array('choices' => array($nombreEstadoAfiliado => $estadoAfiliado, 'CERRADO' => '1', 'ABIERTO' => '0')))                                                
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if ($arContrato->getEstadoActivo()== 0){
                $objMensaje->Mensaje("error", "No tiene contrato activo");
            }else{
                
                $arTrasladoSalud->setFechaCambioAfiliacion($form->get('fechaCambioAfiliacion')->getData());
                $arTrasladoSalud->setEstadoAfiliado($form->get('estadoAfiliado')->getData());
                $em->persist($arTrasladoSalud);
                $em->flush();
                //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
                return $this->redirect($this->generateUrl('brs_rhu_base_contratos_detalles', array('codigoContrato' => $codigoContrato)));
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:TrasladoSalud:editar.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));
    }

}
