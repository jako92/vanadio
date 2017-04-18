<?php
namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenControlType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenDetalleType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenRestriccionMedicaAgregarType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenRestriccionMedicaEditarType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ExamenController extends Controller
{
    var $strListaDql = "";

    /**
     * @Route("/rhu/examen/listar", name="brs_rhu_examen_listar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 5, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->eliminarExamen($arrSeleccionados);
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arExamenes = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:lista.html.twig', array('arExamenes' => $arExamenes, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/examen/nuevo/{codigoExamen}", name="brs_rhu_examen_nuevo")
     */
    public function nuevoAction(Request $request, $codigoExamen) {
        
        $em = $this->getDoctrine()->getManager();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        if($codigoExamen != 0) {
            $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        }else{
            $arExamen->setFecha(new \DateTime('now'));
            $arExamen->setControlPago($arConfiguracion->getControlPago());
        }
        $form = $this->createForm(RhuExamenType::class, $arExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arExamen = $form->getData();
            if($arExamen->getClienteRel()) {
                $arExamen->setCobro($arExamen->getClienteRel()->getCobroExamen());
            } else {
                $arExamen->setCobro('N');
            }
            if($codigoExamen == 0) {
                $arExamen->setCodigoUsuario($arUsuario->getUserName());
                if($arExamen->getExamenClaseRel()->getCodigoExamenClasePk() == 1 && $codigoExamen == 0) {
                    $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
                    $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findBy(array('ingreso' => 1));
                    foreach ($arExamenTipos as $arExamenTipo) {
                        $arExamenListaPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findOneBy(array('codigoEntidadExamenFk' => $arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), 'codigoExamenTipoFk' => $arExamenTipo->getCodigoExamenTipoPk()));
                        if($arExamenListaPrecio) {
                            $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                            $arExamenDetalle->setExamenRel($arExamen);
                            $arExamenDetalle->setExamenTipoRel($arExamenTipo);                            
                            $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getPrecio());
                            $arExamenDetalle->setFechaVence(new \DateTime('now'));
                            $arExamenDetalle->setFechaExamen(new \DateTime('now'));
                            $em->persist($arExamenDetalle);                            
                        }
                    }
                }
                $arCargo = $form->get('cargoRel')->getData();
                $arExamenCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo();
                $arExamenCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenCargo')->findBy(array('codigoCargoFk' => $arCargo->getCodigoCargoPk()));
                foreach ($arExamenCargo as $arExamenCargo) {
                    $arExamenListaPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findOneBy(array('codigoEntidadExamenFk' => $arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), 'codigoExamenTipoFk' => $arExamenCargo->getCodigoExamenTipoFk()));
                    if($arExamenListaPrecio) {                    
                        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                        $arExamenDetalle->setExamenRel($arExamen);
                        $arExamenDetalle->setExamenTipoRel($arExamenCargo->getExamenTipoRel());                        
                        $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getPrecio());
                        $arExamenDetalle->setFechaVence(new \DateTime('now'));
                        $arExamenDetalle->setFechaExamen(new \DateTime('now'));
                        $em->persist($arExamenDetalle);                        
                    }
                }
            }

            $em->persist($arExamen);
            $em->flush();
            $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($arExamen->getCodigoExamenPk());

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_examen_nuevo', array('codigoExamen' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $arExamen->getCodigoExamenPk())));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:nuevo.html.twig', array(
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/examen/nuevo/control/{codigoExamen}", name="brs_rhu_examen_nuevo_control")
     */
    public function nuevoControlAction(Request $request, $codigoExamen) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        if($codigoExamen != 0) {
            $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        }else{
            $arExamen->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RhuExamenControlType::class, $arExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arExamen = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arExamen->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        $arExamen->setIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arExamen->setNombreCorto($arEmpleado->getNombreCorto());                        
                        $arExamen->setCiudadRel($arEmpleado->getCiudadRel());
                        $arExamen->setEmpleadoRel($arEmpleado);
                        $arExamen->setControl(1);
                        $arExamen->setCodigoSexoFk($arEmpleado->getCodigoSexoFk());
                        $em->persist($arExamen);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_examen_nuevo_control', array('codigoExamen' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $arExamen->getCodigoExamenPk())));
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo");
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:nuevoControl.html.twig', array(
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/examen/detalle/{codigoExamen}", name="brs_rhu_examen_detalle")
     */
    public function detalleAction(Request $request, $codigoExamen) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = $this->get('mensajes_brasa');
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $form = $this->formularioDetalle($arExamen);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->numeroRegistros($codigoExamen) > 0) {
                        $arExamen->setEstadoAutorizado(1);
                        $em->persist($arExamen);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen');
                    }
                }
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 1) {
                    $arExamen->setEstadoAutorizado(0);
                    $em->persist($arExamen);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                }
            }
            if ($form->get('BtnAprobar')->isClicked()) {
               if($arExamen->getEstadoAutorizado() == 1) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->aprobarExamen($codigoExamen);
                    if($strRespuesta == ''){
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                    }else {
                      $objMensaje->Mensaje('error', $strRespuesta, $this);
                    }
               }
            }

            if($form->get('BtnImprimir')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\RecursoHumanoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($em, $codigoExamen);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada");
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($codigoExamen);
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                }
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 1) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->aprobarDetallesSeleccionados($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                }
            }
            if($form->get('BtnCerrarDetalle')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->cerrarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
            }
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtPrecio'.$intCodigo] != "" && $arrControles['TxtPrecio'.$intCodigo] != NULL) {
                            $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                            $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->find($intCodigo);
                            $floPrecio = $arrControles['TxtPrecio'.$intCodigo];
                            $arExamenDetalle->setValidarVencimiento($arrControles['cboValidarVencimiento'.$intCodigo]);
                            $arExamenDetalle->setFechaVence(date_create($arrControles['TxtVence'.$intCodigo]));
                            $arExamenDetalle->setFechaExamen(date_create($arrControles['TxtFechaExamen'.$intCodigo]));
                            $arExamenDetalle->setVrPrecio($floPrecio);
                            $em->persist($arExamenDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($codigoExamen);
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                }
            }
            if($form->get('BtnEliminarRestriccion')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 1) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarRestriccion');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoExamenRestriccionMedica) {
                            $arExamenRestriccionMedica = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica();
                            $arExamenRestriccionMedica = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedica')->find($codigoExamenRestriccionMedica);
                            $arExamenRestriccionMedicaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
                            $arExamenRestriccionMedicaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle')->findBy(array('codigoExamenRestriccionMedicaFk' => $arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk()));
                            foreach ($arExamenRestriccionMedicaDetalles as $codigoExamenRestriccionMedicaDetalle) {
                                $arExamenRestriccionMedicaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
                                $arExamenRestriccionMedicaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle')->find($codigoExamenRestriccionMedicaDetalle);
                                $em->remove($arExamenRestriccionMedicaDetalle);
                            }
                            $em->remove($arExamenRestriccionMedica);
                        }
                        $em->flush();

                    }
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
                }
            }
        }


        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array ('codigoExamenFk' => $codigoExamen));
        $arExamenRestriccionesMedicas = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica();
        $arExamenRestriccionesMedicas = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedica')->findBy(array ('codigoExamenFk' => $codigoExamen));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalle.html.twig', array(
                    'arExamen' => $arExamen,
                    'arExamenDetalle' => $arExamenDetalle,
                    'arExamenRestriccionesMedicas' => $arExamenRestriccionesMedicas,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/examen/detalle/nuevo/{codigoExamen}", name="brs_rhu_examen_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $codigoExamen) {        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);        
        $arExamenListaPrecios = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->findBy(array('codigoEntidadExamenFk' => $arExamen->getCodigoEntidadExamenFk()));

        $form = $this->createFormBuilder()
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                if ($arExamen->getEstadoAutorizado() == 0){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arExamenListaPrecio = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenListaPrecio();                            
                            $arExamenListaPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->find($codigo);
                            $arExamenDetalleValidar = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                            $arExamenDetalleValidar = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen, 'codigoExamenTipoFk' => $arExamenListaPrecio->getCodigoExamenTipoFk()));
                            if(!$arExamenDetalleValidar) {
                                $arExamenTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($arExamenListaPrecio->getCodigoExamenTipoFk());
                                $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                                $arExamenDetalle->setExamenTipoRel($arExamenTipo);
                                $arExamenDetalle->setExamenRel($arExamen);
                                $arExamenDetalle->setFechaExamen($arExamen->getFecha());
                                $arExamenDetalle->setFechaVence($arExamen->getFecha());                            
                                $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getPrecio());
                                $em->persist($arExamenDetalle);                                
                            }
                        }
                        $em->flush();
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($codigoExamen);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                    else {
                        $objMensaje->Mensaje("error", "No selecciono ningun dato para grabar");
                    }
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalleNuevo.html.twig', array(
            'arExamenListaPrecios' => $arExamenListaPrecios,
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/examen/detalle/nuevo/comentario/{codigoExamenDetalle}", name="brs_rhu_examen_detalle_nuevo_comentario")
     */
    public function detalleNuevoComentarioAction(Request $request, $codigoExamenDetalle) {
        
        $em = $this->getDoctrine()->getManager();
        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->find($codigoExamenDetalle);
        $form = $this->createForm(RhuExamenDetalleType::class, $arExamenDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arExamenDetalle = $form->getData();
            $em->persist($arExamenDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalleNuevoComentario.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/examen/restriccion/medica/agregar/{codigoExamen}/{codigoRestriccionMedica}", name="brs_rhu_examen_restriccion_medica_agregar")
     */
    public function agregarRestriccionMedicaAction(Request $request, $codigoExamen,$codigoRestriccionMedica) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $arExamenRestriccionMedica = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica();
        $arExamenRestriccionMedicaTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaTipo();
        $arExamenRestriccionMedicaTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaTipo')->findAll();
        $form = $this->createForm(RhuExamenRestriccionMedicaAgregarType::class, $arExamenRestriccionMedica);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arExamen->getEstadoAutorizado() == 1){
                $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                $arExamenRestriccionMedica = $form->getData();
                $arExamenRestriccionMedica->setExamenRel($arExamen);
                $arExamenRestriccionMedica->setFecha(new \DateTime('now'));
                $arExamenRestriccionMedica->setCodigoUsuario($arUsuario->getUserName());             
                //Fecha Vencimiento
                $fechaExamen = $arExamen->getFecha()->format('Y-m-d');
                $dias = $arExamenRestriccionMedica->getDias();
                $dateFechaVence = date('Y-m-d', strtotime("$fechaExamen + $dias day"));
                $arExamenRestriccionMedica->setFechaVence(new \DateTime($dateFechaVence));
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoExamenRestriccionMedicaDetalle) {
                            $arExamenRestriccionMedicaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
                            $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaDetalleRel($arExamenRestriccionMedica);
                            $arExamenRestriccionMedicaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaTipo;
                            $arExamenRestriccionMedicaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaTipo')->find($codigoExamenRestriccionMedicaDetalle);
                            $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaTipoRel($arExamenRestriccionMedicaTipo);
                            $em->persist($arExamenRestriccionMedicaDetalle);
                        }
                        $em->persist($arExamenRestriccionMedica);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                    else {
                        $objMensaje->Mensaje("error", "Debe selecionar al menos un tipo de restricción medica");
                    }

            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
              }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:agregarRestriccion.html.twig', array(
            'form' => $form->createView(),
            'arExamenRestriccionMedicaTipos' => $arExamenRestriccionMedicaTipos
            ));
    }

    /**
     * @Route("/rhu/examen/restriccion/medica/editar/{codigoExamen}/{codigoRestriccionMedica}", name="brs_rhu_examen_restriccion_medica_editar")
     */
    public function editarRestriccionMedicaAction(Request $request, $codigoExamen,$codigoRestriccionMedica) {
        $em = $this->getDoctrine()->getManager();
        
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $arExamenRestriccionMedica = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica();
        $arExamenRestriccionMedicaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
        if($codigoRestriccionMedica != 0) {
            $arExamenRestriccionMedica = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedica')->find($codigoRestriccionMedica);
            $arExamenRestriccionMedicaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle')->findBy(array('codigoExamenRestriccionMedicaFk' => $arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk()));
            $arExamenRestriccionMedicaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle')->tiposRestriccionesMedicas($arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk());
        }

        $form = $this->createForm(RhuExamenRestriccionMedicaEditarType::class, $arExamenRestriccionMedica);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arExamen->getEstadoAutorizado() == 1){
                if ($form->get('BtnGuardar')->isClicked()) {
                    $arExamenRestriccionMedica = $form->getData();
                    $arExamenRestriccionMedica->setExamenRel($arExamen);
                    $em->persist($arExamenRestriccionMedica);
                    $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                    $arExamenRestriccionMedica = $form->getData();
                    $arExamenRestriccionMedica->setExamenRel($arExamen);
                    $arExamenRestriccionMedica->setFecha(new \DateTime('now'));
                    $arExamenRestriccionMedica->setCodigoUsuario($arUsuario->getUserName());
                    //Fecha Vencimiento
                    $fechaExamen = $arExamen->getFecha()->format('Y-m-d');
                    $dias = $arExamenRestriccionMedica->getDias();
                    $dateFechaVence = date('Y-m-d', strtotime("$fechaExamen + $dias day"));
                    $arExamenRestriccionMedica->setFechaVence(new \DateTime($dateFechaVence));
                    $arrSeleccionados = $request->request->get('ChkSeleccionarNuevo');
                        if(count($arrSeleccionados) > 0) {
                            foreach ($arrSeleccionados AS $codigoExamenRestriccionMedicaDetalle) {
                                $arExamenRestriccionMedicaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
                                $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaDetalleRel($arExamenRestriccionMedica);
                                $arExamenRestriccionMedicaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaTipo;
                                $arExamenRestriccionMedicaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaTipo')->find($codigoExamenRestriccionMedicaDetalle);
                                $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaTipoRel($arExamenRestriccionMedicaTipo);
                                $em->persist($arExamenRestriccionMedicaDetalle);
                            }
                            $em->persist($arExamenRestriccionMedica);
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                if ($form->get('BtnEliminar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarDetalle');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoExamenRestriccionMedicaDetalle) {
                            $arExamenRestriccionMedicaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
                            $arExamenRestriccionMedicaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle')->find($codigoExamenRestriccionMedicaDetalle);
                            $em->remove($arExamenRestriccionMedicaDetalle);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('brs_rhu_examen_restriccion_medica_editar', array('codigoExamen' => $codigoExamen, 'codigoRestriccionMedica' => $codigoRestriccionMedica)));
                    }
                }
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
              }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:editarRestriccion.html.twig', array(
            'form' => $form->createView(),
            'arExamenRestriccionMedicaDetalles' => $arExamenRestriccionMedicaDetalles,
            'arExamenRestriccionMedicaTipos' => $arExamenRestriccionMedicaTipo,
            ));
    }

    /**
     * @Route("/rhu/examen/restriccion/medica/detalle/{codigoRestriccionMedica}", name="brs_rhu_examen_restriccion_medica_detalle")
     */
    public function detalleRestriccionMedicaAction(Request $request, $codigoRestriccionMedica) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = $this->get('mensajes_brasa');
        $arExamenRestriccionMedica = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica();
        $arExamenRestriccionMedica = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedica')->find($codigoRestriccionMedica);
        $arExamenRestriccionMedicaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedicaDetalle();
        $arExamenRestriccionMedicaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle')->findBy(array('codigoExamenRestriccionMedicaFk' => $arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk()));
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {

            if($form->get('BtnImprimir')->isClicked()) {

                $objExamenRestriccionMedica = new \Brasa\RecursoHumanoBundle\Formatos\FormatoExamenRestriccionMedica();
                $objExamenRestriccionMedica->Generar($em, $codigoRestriccionMedica,$arExamenRestriccionMedica,$arExamenRestriccionMedicaDetalle);

            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalleRestriccion.html.twig', array(
                    'arExamenRestriccionMedica' => $arExamenRestriccionMedica,
                    'arExamenRestriccionMedicaDetalle' => $arExamenRestriccionMedicaDetalle,
                    'form' => $form->createView()
                    ));
    }

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->listaDQL(
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroRhuExamenEstadoAprobado'),
                $session->get('filtroControlPago'),
                $session->get('filtroRhuExamenEstadoAutorizado')
                );
    }

    private function filtrar ($form) {
        $session = new session;       
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroRhuExamenEstadoAutorizado', $form->get('estadoAutorizado')->getData());
        $session->set('filtroRhuExamenEstadoAprobado', $form->get('estadoAprobado')->getData());
        $session->set('filtroControlPago', $form->get('controlPago')->getData());
    }

    private function formularioFiltro() {
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
        
        $form = $this->createFormBuilder()            
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'AUTORIZADOS' => '1', 'SIN AUTORIZAR' => '0'), 'data' => $session->get('filtroRhuExamenEstadoAutorizado')))
            ->add('estadoAprobado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'APROBADOS' => '1', 'SIN APROBAR' => '0'), 'data' => $session->get('filtroRhuExamenEstadoAprobado')))
            ->add('controlPago', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'), 'data' => $session->get('filtroControlPago')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonActualizarDetalle = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonAprobarDetalle = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonCerrarDetalle = array('label' => 'Cerrar', 'disabled' => true);
        $arrBotonEliminarRestriccion = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonActualizarDetalle['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobarDetalle['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            $arrBotonEliminarRestriccion['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobarDetalle['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            $arrBotonCerrarDetalle['disabled'] = false;
            $arrBotonEliminarRestriccion['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)
                    ->add('BtnAprobar', SubmitType::class, $arrBotonAprobar)
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnEliminarDetalle', SubmitType::class, $arrBotonEliminarDetalle)
                    ->add('BtnActualizarDetalle', SubmitType::class, $arrBotonActualizarDetalle)
                    ->add('BtnAprobarDetalle', SubmitType::class, $arrBotonAprobarDetalle)
                    ->add('BtnCerrarDetalle', SubmitType::class, $arrBotonCerrarDetalle)
                    ->add('BtnEliminarRestriccion', SubmitType::class, $arrBotonEliminarRestriccion)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        $em = $this->getDoctrine()->getManager();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'NOMBRES Y APELLIDOS')
                    ->setCellValue('D1', 'EDAD')
                    ->setCellValue('E1', 'SEXO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', '')
                    ->setCellValue('H1', 'PAGA EMPLEADO')
                    ->setCellValue('I1', 'ENTIDAD / LABORATORIO')
                    ->setCellValue('J1', 'CIUDAD')
                    ->setCellValue('K1', 'FECHA EXAMEN')
                    ->setCellValue('L1', 'AÑO EXAMEN')
                    ->setCellValue('M1', 'MES EXAMEN')
                    ->setCellValue('N1', 'DIA EXAMEN')
                    ->setCellValue('O1', 'TIPO EXAMEN')
                    ->setCellValue('P1', 'TOTAL')
                    ->setCellValue('Q1', 'APROBADO')
                    ->setCellValue('R1', 'COMENTARIOS GENERALES');

        $i = 2;

        $query = $em->createQuery($this->strListaDql);
                $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
                $arExamenes = $query->getResult();

        foreach ($arExamenes as $arExamen) {
            $strNombreEntidad = "SIN ENTIDAD";
            if($arExamen->getEntidadExamenRel()) {
                $strNombreEntidad = $arExamen->getEntidadExamenRel()->getNombre();
            }

            //Calculo edad
            /*$varFechaNacimientoAnio = $arExamen->getFechaNacimiento()->format('Y');
            $varFechaNacimientoMes =  $arExamen->getFechaNacimiento()->format('m');
            $varMesActual = date('m');
            if ($varMesActual >= $varFechaNacimientoMes){
                $varEdad = date('Y') - $varFechaNacimientoAnio;
            } else {
                $varEdad = date('Y') - $varFechaNacimientoAnio -1;
            }*/
            $varEdad = "";
            //Fin calculo edad
            $arDetalleExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $arExamen->getCodigoExamenPk()));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arExamen->getCodigoExamenPk())
                    ->setCellValue('B' . $i, $arExamen->getIdentificacion())
                    ->setCellValue('C' . $i, $arExamen->getNombreCorto())
                    ->setCellValue('D' . $i, $varEdad)
                    ->setCellValue('E' . $i, $arExamen->getCodigoSexoFk())
                    ->setCellValue('F' . $i, $arExamen->getCargoRel()->getNombre())
                    ->setCellValue('G' . $i, "")
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arExamen->getControlPago()))
                    ->setCellValue('I' . $i, $strNombreEntidad)
                    ->setCellValue('J' . $i, $arExamen->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arExamen->getFecha())
                    ->setCellValue('L' . $i, $arExamen->getFecha()->format('Y'))
                    ->setCellValue('M' . $i, $arExamen->getFecha()->format('m'))
                    ->setCellValue('N' . $i, $arExamen->getFecha()->format('d'))
                    ->setCellValue('O' . $i, $arExamen->getExamenClaseRel()->getNombre())
                    ->setCellValue('P' . $i, $arExamen->getVrTotal())
                    ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arExamen->getEstadoAprobado()))
                    ->setCellValue('R' . $i, $arExamen->getComentarios());
                    $array = array();
                    foreach ($arDetalleExamen as $arDetalleExamen){
                        $array[] = $arDetalleExamen->getCodigoExamenTipoFk();
                        $array[] = $arDetalleExamen->getEstadoAprobado();
                        $array[] = $arDetalleExamen->getComentarios();
                    }


                    foreach ($array as $posicion=>$jugador){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('R' . $i, $jugador)
                            ->setCellValue('S' . $i, $jugador)
                            ->setCellValue('T' . $i, $jugador)
                            ->setCellValue('U' . $i, $jugador)
                            ->setCellValue('V' . $i, $jugador);
                    }

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Examen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Examenes.xlsx"');
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