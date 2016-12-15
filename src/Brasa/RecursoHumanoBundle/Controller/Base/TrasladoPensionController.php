<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrasladoPensionController extends Controller
{
    /**
     * @Route("/rhu/traslado/pension/nuevo/{codigoContrato}/{codigoTrasladoPension}", name="brs_rhu_traslado_pension_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoTrasladoPension = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arTrasladoPension = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoTrasladoPension != 0)
        {
            $codigoTrasladoPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoPension')->find($codigoTrasladoPension);
        }
        if ($arContrato->getEstadoActivo()== 0){
            $objMensaje->Mensaje("error", "No tiene contrato activo");
        }
        $form = $this->createFormBuilder()
            ->add('entidadPensionNuevaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ep')
                    ->orderBy('ep.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('fechaAplicacion', DateType::class, array('data' => new \DateTime('now')))
            ->add('fechaFosyga', DateType::class, array('data' => new \DateTime('now')))                
            ->add('detalle', TextType::class, array('required' => true))
            ->add('tipo', ChoiceType::class, array('choices' => array('TRASLADO' => '1', 'CAMBIO' => '2')))                                                
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if ($arContrato->getEstadoActivo()== 0){
                $objMensaje->Mensaje("error", "No tiene contrato activo");
            }else{
                $arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
                $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($arContrato->getCodigoEntidadPensionFk());
                $arTrasladoPension->setContratoRel($arContrato);
                $arTrasladoPension->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arTrasladoPension->setFecha($form->get('fechaAplicacion')->getData());
                $arTrasladoPension->setEntidadPensionNuevaRel($form->get('entidadPensionNuevaRel')->getData());
                $arTrasladoPension->setEntidadPensionAnteriorRel($arEntidadPension);
                $arTrasladoPension->setDetalle($form->get('detalle')->getData());
                $arTrasladoPension->setTipo($form->get('tipo')->getData());
                $arTrasladoPension->setFechaFosyga($form->get('fechaFosyga')->getData());
                if ($form->get('tipo')->getData() == 1){
                    $arTrasladoPension->setEstadoAfiliado(1);
                }
                $arContrato->setEntidadPensionRel($form->get('entidadPensionNuevaRel')->getData());
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getEmpleadoRel());
                if ($arEmpleadoActualizar->getCodigoCentroCostoFk() <> null){
                    $arEmpleadoActualizar->setEntidadPensionRel($form->get('entidadPensionNuevaRel')->getData());
                    $em->persist($arEmpleadoActualizar);
                }
                $em->persist($arContrato);
                $em->persist($arTrasladoPension);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:TrasladoPension:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));
    }
    
    /**
     * @Route("/rhu/traslado/pension/editar/{codigoContrato}/{codigoTrasladoPension}", name="brs_rhu_traslado_pension_editar")
     */
    public function editarAction(Request $request, $codigoContrato, $codigoTrasladoPension = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        $arTrasladoPension = new \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension();
        $arTrasladoPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuTrasladoPension')->find($codigoTrasladoPension);
        $estadoAfiliado = $arTrasladoPension->getEstadoAfiliado(); 
        if ($estadoAfiliado == 1){
            $nombreEstadoAfiliado = "CERRADO";
        } else {
            $nombreEstadoAfiliado = "ABIERTO";
        }
        $form = $this->createFormBuilder()    
            ->setAction($this->generateUrl('brs_rhu_traslado_pension_editar', array('codigoContrato' => $codigoContrato, 'codigoTrasladoPension' => $codigoTrasladoPension)))
            ->add('fechaCambioAfiliacion', DateType::class, array('data' => new \DateTime('now')))
            ->add('estadoAfiliado', ChoiceType::class, array('choices' => array($estadoAfiliado => $nombreEstadoAfiliado, '1' => 'CERRADO', '0' => 'ABIERTO')))                                                
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            if ($arContrato->getEstadoActivo()== 0){
                $objMensaje->Mensaje("error", "No tiene contrato activo");
            }else{
                
                $arTrasladoPension->setFechaCambioAfiliacion($form->get('fechaCambioAfiliacion')->getData());
                $arTrasladoPension->setEstadoAfiliado($form->get('estadoAfiliado')->getData());
                $em->persist($arTrasladoPension);
                $em->flush();
                //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
                return $this->redirect($this->generateUrl('brs_rhu_base_contratos_detalles', array('codigoContrato' => $codigoContrato)));
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:TrasladoPension:editar.html.twig', array(
            'form' => $form->createView(),
            'arContrato' => $arContrato,
        ));
    }

}
