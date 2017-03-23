<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PagosAdicionalesAgregarController extends Controller
{
    /**
     * @Route("/rhu/pagos/adicionales/agregar/valoradicional/{tipo}/{modalidad}/{periodo}", name="brs_rhu_pagos_adicionales_agregar_valoradicional")
     */
    public function valorAdicionalAction(Request $request, $tipo, $modalidad, $periodo) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $intTipoAdicional = $tipo;
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('TxtValor', NumberType::class, array('required' => true))
            ->add('TxtHoras', NumberType::class, array('required' => true))
            ->add('TxtDetalle', TextType::class, array('required' => false))            
            ->add('aplicarDiaLaborado', CheckboxType::class, array('required'  => false))                                            
            ->add('aplicarDiaLaboradoSinDescanso', CheckboxType::class, array('required'  => false))
            ->add('liquidarHorasSalario', CheckboxType::class, array('required'  => false))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', SubmitType::class, array('label'  => 'Guardar y nuevo',))
            ->getForm();
                    
        $form->handleRequest($request);
        if($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
            if ($arrControles['form_txtNumeroIdentificacion'] == ""){
                $objMensaje->Mensaje("error", "Digite el número de identificación");
            }else {
                if ($arEmpleado == null){
                    $objMensaje->Mensaje("error", "El empleado no existe");
                } else {                    
                    if ($arEmpleado->getCodigoContratoActivoFk() != null){
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                    }else {                       
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if ($arContrato == null){
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato");
                    } else {
                        if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                            $boolError = FALSE;
                            $floSalario = $arContrato->getVrSalario();
                            $floVrDia = ($floSalario / 30);                            
                            $valorHora = $floVrDia / 8;
                            $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                            $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                            if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                                if($arEmpleado->getCodigoContratoActivoFk()) {
                                    $codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                                } else {
                                    $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                                }
                                $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);                                
                                $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arContrato->getCodigoCentroCostoFk());        
                                $strPeriodoPago = $arCentroCosto->getPeriodoPagoRel()->getNombre();
                                if ($strPeriodoPago == "MENSUAL"){
                                    $intDias = 30;
                                }
                                if ($strPeriodoPago == "QUINCENAL"){
                                    $intDias = 15;
                                }
                                if ($strPeriodoPago == "CATORCENAL"){
                                    $intDias = 14;
                                }
                                if ($strPeriodoPago == "DECADAL"){
                                    $intDias = 10;
                                }
                                if ($strPeriodoPago == "SEMANAL"){
                                    $intDias = 7;
                                }                                                                   
                            }
                            if($boolError == FALSE) {
                                $valor = $form->get('TxtValor')->getData();
                                if($form->get('liquidarHorasSalario')->getData()) {
                                    if($form->get('TxtHoras')->getData() > 0) {
                                        $valor = $form->get('TxtHoras')->getData() * $valorHora;
                                        $valor = round($valor);
                                    }
                                }
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                     
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setValor($valor);  
                                $arPagoAdicional->setHoras($form->get('TxtHoras')->getData()); 
                                $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                                $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                                $arPagoAdicional->setTipoAdicional($tipo);                                
                                $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicarDiaLaborado')->getData());
                                $arPagoAdicional->setAplicaDiaLaboradoSinDescanso($form->get('aplicarDiaLaboradoSinDescanso')->getData());
                                $arPagoAdicional->setLiquidarHorasSalario($form->get('liquidarHorasSalario')->getData());                                
                                $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());
                                $arPagoAdicional->setModalidad($modalidad);
                                if($periodo != 0) {
                                    $arPagoAdicionalPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalPeriodo();                                    
                                    $arPagoAdicionalPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->find($periodo);
                                    if($arPagoAdicionalPeriodo) {
                                        $arPagoAdicional->setCodigoPeriodoFk($periodo);   
                                        $arPagoAdicional->setFecha($arPagoAdicionalPeriodo->getFecha());
                                    }                                                                                                            
                                } else {
                                    $arPagoAdicional->setPermanente(1);
                                }
                                $arPagoAdicional->setFechaCreacion(new \DateTime('now'));
                                $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                                $em->persist($arPagoAdicional);                                                        
                                $em->flush();
                            }
                            if($form->get('BtnGuardaryNuevo')->isClicked()) {
                                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo, 'modalidad' => $modalidad, 'periodo' => $periodo) ));
                            } else {
                                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                            }
                        } else {
                            $objMensaje->Mensaje("error", "Debe ingresar un valor");
                        }
                    }                                                                                                                                                      
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarValorAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo,
            'modalidad' => $modalidad,
            'periodo' => $periodo
            ));
    }
    
    /**
     * @Route("/rhu/pagos/adicionales/agregar/valoradicionaleditar/{tipo}/{codigoPagoAdicional}/{modalidad}/{periodo}", name="brs_rhu_pagos_adicionales_agregar_valoradicionaleditar")
     */
    public function valorAdicionalEditarAction(Request $request, $tipo, $codigoPagoAdicional, $modalidad, $periodo) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($arPagoAdicional->getCodigoPagoConceptoFk());
        $codigoAdicionalDetalle = $arPagoConcepto->getTipoAdicional();
        if ($arPagoAdicional->getAplicaDiaLaborado() == 1){
            $intAplicaDiaLaborado = "SI";
        } else {
            $intAplicaDiaLaborado = "NO";
        }
        if ($arPagoAdicional->getAplicaDiaLaborado() == 1){
            $intAplicaDiaLaboradoSinDescanso = "SI";
        } else {
            $intAplicaDiaLaboradoSinDescanso = "NO";
        }                
        $codigoEmpleado = $arPagoAdicional->getCodigoEmpleadoFk();
        $codigoCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk();        
        $codigoPagoConcepto = $arPagoAdicional->getCodigoPagoConceptoFk();
        $pagoConcepto = $arPagoAdicional->getPagoConceptoRel()->getNombre();
        $intTipoAdicional = $tipo;
        $aplicaDiaLaborado = $arPagoAdicional->getAplicaDiaLaborado();
        $aplicaDiaLaboradoSinDescanso = $arPagoAdicional->getAplicaDiaLaboradoSinDescanso();        
        $liquidarHorasSalario = $arPagoAdicional->getLiquidarHorasSalario();        
        $arrayPropiedadesPagoConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use ($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true,
                'data' => ""
            ); 
        if($codigoPagoConcepto) {
            $arrayPropiedadesPagoConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $codigoPagoConcepto);
        }                    
        
        $form = $this->createFormBuilder() 
            ->add('empleadoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoEmpleado) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoEmpleadoPk = :empleado')
                    ->setParameter('empleado', $codigoEmpleado)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))    
            ->add('pagoConceptoRel', EntityType::class, $arrayPropiedadesPagoConcepto)               
            ->add('TxtValor', NumberType::class, array('required' => true, 'data' => $arPagoAdicional->getValor()))                             
            ->add('TxtHoras', NumberType::class, array('required' => true, 'data' => $arPagoAdicional->getHoras()))                            
            ->add('TxtDetalle', TextType::class, array('required' => false, 'data' => $arPagoAdicional->getDetalle()))
            ->add('aplicarDiaLaborado', CheckboxType::class, array('required' => false, 'data' => $aplicaDiaLaborado))                
            ->add('aplicarDiaLaboradoSinDescanso', CheckboxType::class, array('required' => false, 'data' => $aplicaDiaLaboradoSinDescanso))                                                        
            ->add('liquidarHorasSalario', CheckboxType::class, array('required'  => false, 'data' => $liquidarHorasSalario))            
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', SubmitType::class, array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        
        if ($codigoAdicionalDetalle == 0){
            $objMensaje->Mensaje("error", "El tipo de adicional al pago para el item " . $arPagoConcepto->getNombre() . " en la tabla pago concepto no debe estar en cero (0), 1: bonificación, 2:descuento, 3: comisión, 4: tiempo suplementario!");
        }
        if($form->isValid()) {
            $arrControles = $request->request->All();
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPagoAdicional->getCodigoEmpleadoFk());        
            if ($arEmpleado->getCodigoContratoActivoFk() != null){
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
            }else {                       
                $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
            }            
            if ($codigoCentroCosto == null){                
                $codigoCentroCosto = $arContrato->getCodigoCentroCostoFk();
            }
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $boolError = FALSE;
                    $floSalario = $arContrato->getVrSalario();
                    $floVrDia = ($floSalario / 30);
                    $valorHora = $floVrDia / 8;
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);        
                        $strPeriodoPago = $arCentroCosto->getPeriodoPagoRel()->getNombre();
                        if ($strPeriodoPago == "MENSUAL"){
                            $intDias = 30;
                        }
                        if ($strPeriodoPago == "QUINCENAL"){
                            $intDias = 15;
                        }
                        if ($strPeriodoPago == "CATORCENAL"){
                            $intDias = 14;
                        }
                        if ($strPeriodoPago == "DECADAL"){
                            $intDias = 10;
                        }
                        if ($strPeriodoPago == "SEMANAL"){
                            $intDias = 7;
                        }
                        $floSalarioEmpleado = $floVrDia * $intDias;
                        $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                        $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arPagoAdicional->getCodigoEmpleadoFk(), 0);                                
                        $floBonificacion = $form->get('TxtValor')->getData();
                        $floBonificacionTotal = $floBonificacionNoPrestacional+ $floBonificacion;                                                                       
                    }
                    if($boolError == FALSE) {
                        $valor = $form->get('TxtValor')->getData();
                        if($form->get('liquidarHorasSalario')->getData()) {
                            if($form->get('TxtHoras')->getData() > 0) {
                                $valor = $form->get('TxtHoras')->getData() * $valorHora;
                                $valor = round($valor);
                            }
                        }                        
                        $arPagoAdicional->setEmpleadoRel($arEmpleado);
                        $arPagoAdicional->setValor($valor); 
                        $arPagoAdicional->setHoras($form->get('TxtHoras')->getData()); 
                        $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                        $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                        $arPagoAdicional->setTipoAdicional($tipo);                                                
                        $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicarDiaLaborado')->getData());
                        $arPagoAdicional->setAplicaDiaLaboradoSinDescanso($form->get('aplicarDiaLaboradoSinDescanso')->getData());
                        $arPagoAdicional->setLiquidarHorasSalario($form->get('liquidarHorasSalario')->getData());
                        $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        $arPagoAdicional->setCodigoUsuarioUltimaEdicion($arUsuario->getUserName());
                        $em->persist($arPagoAdicional);                                                        
                        $em->flush();
                        if($form->get('BtnGuardaryNuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo, 'modalidad' => $modalidad) ));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                        }
                    } 
                    
                }                                                                                                                                                                          
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:editarAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo,
            'modalidad' => $modalidad,
            'periodo' => $periodo
            ));
    }
    
    
}
