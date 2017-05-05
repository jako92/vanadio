<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuVacacionType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VacacionesController extends Controller
{
    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/vacacion/", name="brs_rhu_movimiento_vacacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 14, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();

        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoVacacion) {
                            $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                            $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
                            if ($arVacaciones->getEstadoAutorizado() == 1 ) {
                                $objMensaje->Mensaje("error", "No se puede eliminar el registro, esta autorizado!");
                            }
                            else {
                                if ($arVacaciones->getEstadoPagoGenerado() == 1 ) {
                                    $objMensaje->Mensaje("error", "No se puede eliminar el registro, ya fue pagada!");
                                } else {
                                    $em->remove($arVacaciones);
                                    $em->flush();
                                }

                            }
                        }
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el registro, tiene detalles relacionados');
                      }
                }
                $this->filtrarLista($form);
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arVacaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:lista.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/nuevo/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoVacacion = 0) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        if($codigoVacacion != 0) {
            $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        } else {
            $arVacacion->setFecha(new \DateTime('now'));
            $arVacacion->setFechaDesdeDisfrute(new \DateTime('now'));
            $arVacacion->setFechaHastaDisfrute(new \DateTime('now'));
            $arVacacion->setFechaInicioLabor(new \DateTime('now'));
        }
        $form = $this->createForm(RhuVacacionType::class, $arVacacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arVacacion = $form->getData();
            if($form->get('guardar')->isClicked()) {
                if($arrControles['form_txtNumeroIdentificacion'] != '') {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                    if(count($arEmpleado) > 0) {
                        $arVacacion->setEmpleadoRel($arEmpleado);
                        if($arEmpleado->getCodigoContratoActivoFk() != '') {
                            if ($form->get('fechaDesdeDisfrute')->getData() >  $form->get('fechaHastaDisfrute')->getData()){
                                $objMensaje->Mensaje("error", "La fecha desde no debe ser mayor a la fecha hasta");
                            } else {
                                if ($form->get('diasDisfrutados')->getData() == 0 && $form->get('diasPagados')->getData() == 0){
                                    $objMensaje->Mensaje("error", "Los dias pagados o los dias disfrutados, no pueden estar en ceros");
                                } else {
                                    $arVacacion->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                                    $arVacacion->setContratoRel($arContrato);
                                    $fechaDesdePeriodo = $arContrato->getFechaUltimoPagoVacaciones();
                                    if ($fechaDesdePeriodo == null){
                                        $fechaDesdePeriodo = $arContrato->getFechaDesde();
                                    }
                                    $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta(360, $fechaDesdePeriodo);
                                    $intDias = ($arVacacion->getDiasDisfrutados() + $arVacacion->getDiasPagados()) * 24;

                                    $strFechaDesde = $fechaDesdePeriodo->format('Y-m-d');
                                    $strFechaDesde = strtotime ( '+1 day' , strtotime ( $strFechaDesde ) ) ;
                                    $strFechaDesde = date ( 'Y-m-d' , $strFechaDesde );
                                    $fechaDesdePeriodo = date_create($strFechaDesde);

                                    $fechaHastaPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestacionesHasta($intDias+1, $fechaDesdePeriodo);
                                    $arVacacion->setFechaDesdePeriodo($fechaDesdePeriodo);
                                    $arVacacion->setFechaHastaPeriodo($fechaHastaPeriodo);
                                    $intDiasDevolver = $arVacacion->getDiasPagados();
                                    if($arVacacion->getDiasDisfrutados() > 0){
                                        $intDias = $arVacacion->getFechaDesdeDisfrute()->diff($arVacacion->getFechaHastaDisfrute());
                                        $intDias = $intDias->format('%a');
                                        $intDiasDevolver += $intDias + 1;
                                    }
                                    $arVacacion->setDiasVacaciones($intDiasDevolver);
                                    if($codigoVacacion == 0) {
                                        $arVacacion->setCodigoUsuario($arUsuario->getUserName());
                                    }
                                    $intDiasDevolver = 0;
                                    if($arVacacion->getDiasDisfrutados() > 0) {
                                        $intDias = $arVacacion->getFechaDesdeDisfrute()->diff($arVacacion->getFechaHastaDisfrute());
                                        $intDias = $intDias->format('%a');
                                        $intDiasDevolver += $intDias + 1;
                                    }
                                    $arVacacion->setDiasDisfrutadosReales($intDiasDevolver);

                                    $em->persist($arVacacion);

                                    //Calcular deducciones credito
                                    if($codigoVacacion == 0) {
                                        $floVrDeducciones = 0;
                                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arEmpleado->getCodigoEmpleadoPk());
                                        foreach ($arCreditos as $arCredito) {
                                            $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                                            $arVacacionAdicional->setCreditoRel($arCredito);
                                            $arVacacionAdicional->setVacacionRel($arVacacion);
                                            $arVacacionAdicional->setVrDeduccion($arCredito->getVrCuota());
                                            $arVacacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());
                                            $em->persist($arVacacionAdicional);
                                            $floVrDeducciones += $arCredito->getVrCuota();
                                        }
                                    }

                                    $em->flush();
                                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion'));
                                }
                            }
                        } else {
                            $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no existe");
                    }
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:nuevo.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle")
     */
    public function detalleAction(Request $request, $codigoVacacion) {
        $em = $this->getDoctrine()->getManager();
        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $form = $this->formularioDetalle($arVacacion);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arVacacion->getEstadoAutorizado() == 0) {
                    $arVacacion->setEstadoAutorizado(1);
                    $em->persist($arVacacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arVacacion->getEstadoAutorizado() == 1) {
                    $arVacacion->setEstadoAutorizado(0);
                    $em->persist($arVacacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleVacaciones = new \Brasa\RecursoHumanoBundle\Formatos\FormatoVacaciones();
                $objFormatoDetalleVacaciones->Generar($em, $codigoVacacion);
            }
            if($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
            if($form->get('BtnGenerarPago')->isClicked()) {
                if($arVacacion->getEstadoAutorizado() == 1) {
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato =  $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arVacacion->getCodigoContratoFk());
                    $respuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->pagar($codigoVacacion);
                    if ($respuesta == ''){
                        $arContrato->setFechaUltimoPagoVacaciones($arVacacion->getFechaHastaPeriodo());
                        $arVacacion->setEstadoPagoGenerado(1);
                        $em->persist($arContrato);
                        $em->persist($arVacacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                    } else {
                        $objMensaje->Mensaje("error", $respuesta);
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
                    }
                } else {
                    $objMensaje->Mensaje("error", "No esta autorizado, no se puede generar pago");
                }
            }
            if($form->get('BtnEliminarAdicional')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                        $arVacacionAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->find($codigo);
                        $em->remove($arVacacionAdicional);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_vacacion_detalle', array('codigoVacacion' => $codigoVacacion)));
            }   
            if($form->get('BtnCartaAnuncio')->isClicked()) {
                $formato = new \Brasa\RecursoHumanoBundle\Formatos\VacacionAnuncio();
                $formato->Generar($em, $codigoVacacion, $this->get('security.token_storage')->getToken()->getUser());
            }            
        }

        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionAdicional')->listaDql($codigoVacacion);
        $arVacacionAdicionales = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detalle.html.twig', array(
                    'arVacaciones' => $arVacacion,
                    'arVacacionAdicionales' => $arVacacionAdicionales,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/credito/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle_credito")
     */
    public function detalleCreditoAction(Request $request, $codigoVacacion) {
        
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->pendientes($arVacacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        $valor = 0;
                        if($arrControles['TxtValor'.$codigoCredito] != '') {
                            $valor = $arrControles['TxtValor'.$codigoCredito];
                        }
                        $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                        $arVacacionAdicional->setCreditoRel($arCredito);
                        $arVacacionAdicional->setVacacionRel($arVacacion);
                        $arVacacionAdicional->setVrDeduccion($valor);
                        $arVacacionAdicional->setPagoConceptoRel($arCredito->getCreditoTipoRel()->getPagoConceptoRel());
                        $em->persist($arVacacionAdicional);
                        $floVrDeducciones += $valor;
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                }

            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detallenuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/descuento/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle_descuento")
     */
    public function detalleDescuentoAction(Request $request, $codigoVacacion) {
        
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', 2)
                    ->orderBy('pc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('TxtValor', NumberType::class, array('required' => true))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                $arVacacionAdicional->setVacacionRel($arVacacion);
                $arVacacionAdicional->setPagoConceptoRel($arPagoConcepto);
                $arVacacionAdicional->setVrDeduccion($form->get('TxtValor')->getData());
                $em->persist($arVacacionAdicional);
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detalleNuevoDescuento.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/detalle/bonificacion/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_detalle_bonificacion")
     */
    public function detalleBonificacionAction(Request $request, $codigoVacacion) {
        
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $arPagoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('tipoAdicional' => 1));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($codigo);
                        $valor = 0;
                        if($arrControles['TxtValor'.$codigo] != '') {
                            $valor = $arrControles['TxtValor'.$codigo];
                        }
                        $arVacacionAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionAdicional();
                        $arVacacionAdicional->setPagoConceptoRel($arPagoConcepto);
                        $arVacacionAdicional->setVacacionRel($arVacacion);
                        $arVacacionAdicional->setVrBonificacion($valor);
                        $em->persist($arVacacionAdicional);
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($arVacacion->getCodigoVacacionPk());
                }

            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:detalleBonificacionNuevo.html.twig', array(
            'arPagoConceptos' => $arPagoConceptos,
            'arVacacion' => $arVacacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/vacacion/modificar/{codigoVacacion}", name="brs_rhu_movimiento_vacacion_modificar")
     */
    public function modificarInformacionAction(Request $request, $codigoVacacion) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),112)){
            $objMensaje->Mensaje("error", "No tiene permisos para modificar la vacacion, comuniquese con el administrador");
        }
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        $form = $this->createFormBuilder()
            //->setAction($this->generateUrl('brs_rhu_movimiento_vacacion_modificar', array('codigoVacacion' => $codigoVacacion)))
            ->add('fechaDesdeDisfrute', DateType::class, array('label'  => 'Fecha desde', 'data' => $arVacacion->getFechaDesdeDisfrute()))
            ->add('fechaHastaDisfrute', DateType::class, array('label'  => 'Fecha hasta', 'data' => $arVacacion->getFechaHastaDisfrute()))
            ->add('vrSalud', NumberType::class, array('data' =>$arVacacion->getVrSalud() ,'required' => false))
            ->add('vrPension', NumberType::class, array('data' =>$arVacacion->getVrPension() ,'required' => false))            
            ->add('vrVacacion', NumberType::class, array('data' =>$arVacacion->getVrVacacion() ,'required' => false))                    
            ->add('diasDisfrute', NumberType::class, array('data' =>$arVacacion->getDiasDisfrutados() ,'required' => false))        
            ->add('diasPagados', NumberType::class, array('data' =>$arVacacion->getDiasPagados() ,'required' => false))            
            ->add('vrSalarioPromedio', NumberType::class, array('data' =>$arVacacion->getVrSalarioPromedio() ,'required' => false))            
            ->add('totalVacaciones', NumberType::class, array('data' =>$arVacacion->getVrVacacionBruto() ,'required' => false))                
            ->add('vrRecargoNocturno', NumberType::class, array('data' =>$arVacacion->getVrPromedioRecargoNocturno() ,'required' => false))                            
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),112)){
                $objMensaje->Mensaje("error", "No tiene permisos para modificar la vacacion, comuniquese con el administrador");
            } else {
                $fechaDesdeDisfrute = $form->get('fechaDesdeDisfrute')->getData();
                $fechaHastaDisfrute = $form->get('fechaHastaDisfrute')->getData();
                $vrSalud = $form->get('vrSalud')->getData();
                $vrPension = $form->get('vrPension')->getData();            
                $vrVacacion = $form->get('vrVacacion')->getData();            
                $diasDisfrute = $form->get('diasDisfrute')->getData();
                $diasPagados = $form->get('diasPagados')->getData();
                $vrSalarioPromedio = $form->get('vrSalarioPromedio')->getData();
                $totalVacaciones = $form->get('totalVacaciones')->getData();
                $vrRecargoNocuturno = $form->get('vrRecargoNocturno')->getData();

                $arVacacion->setFechaDesdeDisfrute($fechaDesdeDisfrute);
                $arVacacion->setFechaHastaDisfrute($fechaHastaDisfrute);
                $arVacacion->setVrSalud($vrSalud);
                $arVacacion->setVrPension($vrPension);
                $arVacacion->setVrVacacion($vrVacacion);
                $arVacacion->setDiasDisfrutados($diasDisfrute);
                $arVacacion->setDiasPagados($diasPagados);
                $arVacacion->setVrSalarioPromedio($vrSalarioPromedio);
                $arVacacion->setVrVacacionBruto($totalVacaciones);
                $arVacacion->setVrPromedioRecargoNocturno($vrRecargoNocuturno);

                $em->persist($arVacacion);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Vacaciones:modificar.html.twig', array(
            'arVacacion' => $arVacacion,
            'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->listaVacacionesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroPagado'),
                    $session->get('filtroAutorizado')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            }  else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);
        }
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('estadoPagado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'),'data' => $session->get('filtroPagado')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'),'data' => $session->get('filtroAutorizado')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new session;
        $codigoCentroCosto = '';
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroPagado', $form->get('estadoPagado')->getData());
        $session->set('filtroAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
    }

    private function formularioDetalle($arVacacion) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonCartaAnuncio = array('label' => 'Imprimir carta anuncio', 'disabled' => false);
        $arrBotonGenerarPago = array('label' => 'Generar pago', 'disabled' => false);
        $arrBotonLiquidar = array('label' => 'Liquidar', 'disabled' => false);
        $arrBotonEliminarAdicional = array('label'  => 'Eliminar', 'disabled' => false);

        if($arVacacion->getEstadoAutorizado() == 1) {
            $arrBotonLiquidar['disabled'] = true;
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarAdicional['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        if($arVacacion->getEstadoPagoGenerado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonGenerarPago['disabled'] = true;
            $arrBotonLiquidar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnGenerarPago', SubmitType::class, $arrBotonGenerarPago)
                    ->add('BtnLiquidar', SubmitType::class, $arrBotonLiquidar)
                    ->add('BtnEliminarAdicional', SubmitType::class, $arrBotonEliminarAdicional)
                    ->add('BtnCartaAnuncio', SubmitType::class, $arrBotonCartaAnuncio)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
        $session = new session;
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                    ->setLastModifiedBy("EMPRESA")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                for($col = 'A'; $col !== 'V'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                }
                for($col = 'K'; $col !== 'Q'; $col++) {
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'ID')
                            ->setCellValue('B1', 'FECHA')    
                            ->setCellValue('C1', 'COD')                        
                            ->setCellValue('D1', 'DOCUMENTO')
                            ->setCellValue('E1', 'EMPLEADO')
                            ->setCellValue('F1', 'GRUPO PAGO')
                            ->setCellValue('G1', 'D.DIS')
                            ->setCellValue('H1', 'D.DIS.REALES')
                            ->setCellValue('I1', 'D.PAG')
                            ->setCellValue('J1', 'DIAS')
                            ->setCellValue('K1', 'VR_VACACIONES')
                            ->setCellValue('L1', 'VR_SALUD')
                            ->setCellValue('M1', 'VR_PENSION')
                            ->setCellValue('N1', 'VR_DEDUCCIONES')                            
                            ->setCellValue('O1', 'VR_BONIFICACIONES')
                            ->setCellValue('P1', 'VR_PAGAR')
                            ->setCellValue('Q1', 'PAGADO')
                            ->setCellValue('R1', 'DESDE')
                            ->setCellValue('S1', 'HASTA')
                            ->setCellValue('T1', 'P_DESDE')
                            ->setCellValue('U1', 'P_HASTA');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                $arVacaciones = $query->getResult();

                foreach ($arVacaciones as $arVacacion) {
                    if ($arVacacion->getEstadoPagado() == 1) {
                        $Estado = "SI";
                    } else {
                        $Estado = "NO";
                    }
                    if ($arVacacion->getCodigoCentroCostoFk() == null) {
                    $centroCosto = "";
                    } else {
                    $centroCosto = $arVacacion->getCentroCostoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVacacion->getCodigoVacacionPk())
                            ->setCellValue('B' . $i, $arVacacion->getFecha()->format('Y/m/d'))                            
                            ->setCellValue('C' . $i, $arVacacion->getCodigoEmpleadoFk())
                            ->setCellValue('D' . $i, $arVacacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arVacacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $centroCosto)
                            ->setCellValue('G' . $i, $arVacacion->getDiasDisfrutados())
                            ->setCellValue('H' . $i, $arVacacion->getDiasDisfrutadosReales())
                            ->setCellValue('I' . $i, $arVacacion->getDiasPagados())
                            ->setCellValue('J' . $i, $arVacacion->getDiasVacaciones())
                            ->setCellValue('K' . $i, round($arVacacion->getVrVacacionBruto()))
                            ->setCellValue('L' . $i, round($arVacacion->getVrPension()))
                            ->setCellValue('M' . $i, round($arVacacion->getVrSalud()))
                            ->setCellValue('N' . $i, round($arVacacion->getVrDeduccion()))
                            ->setCellValue('O' . $i, round($arVacacion->getVrBonificacion()))
                            ->setCellValue('P' . $i, round($arVacacion->getVrVacacion()))
                            ->setCellValue('Q' . $i, $Estado)
                            ->setCellValue('R' . $i, $arVacacion->getFechaDesdeDisfrute()->format('Y/m/d'))
                            ->setCellValue('S' . $i, $arVacacion->getFechaHastaDisfrute()->format('Y/m/d'))
                            ->setCellValue('T' . $i, $arVacacion->getFechaDesdePeriodo()->format('Y/m/d'))
                            ->setCellValue('U' . $i, $arVacacion->getFechaHastaPeriodo()->format('Y/m/d'));
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Vacaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Vacaciones.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0
                $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save('php://output');
                exit;
            }

}
