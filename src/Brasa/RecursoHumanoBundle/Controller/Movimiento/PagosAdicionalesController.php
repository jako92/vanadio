<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAdicionalPagoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoAdicionalPeriodoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PagosAdicionalesController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaTiempoSuplementarioMasivo = "";
    var $nombre = "";
    var $identificacion = "";
    var $aplicarDiaLaborado = 2;
    
    /**
     * @Route("/rhu/pagos/adicionales/lista/{modalidad}/{periodo}", name="brs_rhu_pagos_adicionales_lista")
     */
    public function listaAction(Request $request, $modalidad, $periodo) {
        $em = $this->getDoctrine()->getManager();        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $objMensaje = $this->get('mensajes_brasa');
        $session =  new Session;
        $paginator  = $this->get('knp_paginator');        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 10, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar($form, $modalidad, $periodo);
        
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getEstadoInactivo() == 1) {
                            $arPagoAdicional->setEstadoInactivo(0);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        } else {
                            $arPagoAdicional->setEstadoInactivo(1);
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista', array('modalidad' => $modalidad, 'periodo' => $periodo)));
                }
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form, $modalidad, $periodo);
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form, $modalidad, $periodo);
                $this->generarExcel();
            }
        }
        //$this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaDql("");        
        $nombreModalidad = "";
        if($modalidad == 1) {
            $nombreModalidad = "PERMANENTES";
        }
        if($modalidad == 2) {
            $nombreModalidad = "FECHA";
        }        
        $arPagosAdicionales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        //$arCentrosCostos = $paginator->paginate($em->createQuery($session->get('dqlCentroCosto')), $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:lista.html.twig', array(
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'modalidad' => $modalidad,
                    'nombreModalidad' => $nombreModalidad,
                    'periodo' => $periodo,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/rhu/pagos/adicionales/fecha/lista/{modalidad}", name="brs_rhu_pagos_adicionales_lista_fecha")
     */
    public function listaPeriodoAction(Request $request, $modalidad) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new Session;
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 34, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $session->set('filtroRhuPagoAdicionalPeriodoEstadoCerrado', 0);
        $form = $this->formularioPeriodo();
        $form->handleRequest($request);     
        $this->listarPeriodo();        
        if($form->isValid()) {
            if($request->request->get('OpCerrar')) {
                $codigoPagoAdicionalPeriodo = $request->request->get('OpCerrar');
                $arPagoAdicionalPeriodo = NEW \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalPeriodo();
                $arPagoAdicionalPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->find($codigoPagoAdicionalPeriodo);                
                $arPagoAdicionalPeriodo->setEstadoCerrado(1);                
                $em->persist($arPagoAdicionalPeriodo);
                $em->flush();                                                   
                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista_fecha', array('modalidad' => $modalidad)));                
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarListaPeriodo($form);
                $form = $this->formularioPeriodo();
                $this->listarPeriodo();
            }
                         
        }
        $nombreModalidad = "";
        if($modalidad == 1) {
            $nombreModalidad = "PERMANENTES";
        }
        if($modalidad == 2) {
            $nombreModalidad = "FECHA";
        }        
        $arPagosAdicionalesPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:periodo.html.twig', array(
                    'arPagosAdicionalesPeriodos' => $arPagosAdicionalesPeriodos,
                    'modalidad' => $modalidad,
                    'nombreModalidad' => $nombreModalidad,
                    'form' => $form->createView()
                    ));
    }    

    /**
     * @Route("/rhu/movimiento/pago/adicional/periodo/nuevo/{codigoPagoAdicionalPeriodo}", name="brs_rhu_movimiento_pago_adicional_periodo_nuevo")
     */    
    public function nuevoPeriodoAction(Request $request, $codigoPagoAdicionalPeriodo = 0) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arPagoAdicionalPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalPeriodo();       
        if($codigoPagoAdicionalPeriodo != 0) {
            $arPagoAdicionalPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->find($codigoPagoAdicionalPeriodo);
        } else {
            $arPagoAdicionalPeriodo->setFecha(new \DateTime('now'));
        }        

        $form = $this->createForm(RhuPagoAdicionalPeriodoType::class, $arPagoAdicionalPeriodo);                     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arPagoAdicionalPeriodo = $form->getData();                                                                                                          
            $em->persist($arPagoAdicionalPeriodo);
            $em->flush();                
            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista_fecha', array('modalidad' => 2)));                                                                                                                                                                                                             
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:periodoNuevo.html.twig', array(
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/movimiento/pago/adicional/detalle/{codigoPagoAdicional}", name="brs_rhu_movimiento_pago_adicional_detalle")
     */    
    public function detalleAdicionalAction(Request $request, $codigoPagoAdicional) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {           
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:detallePagoAdicional.html.twig', array(
                    'arPagoAdicional' => $arPagoAdicional,
                    'form' => $form->createView()
                    ));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
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
        $arrayPropiedadesConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->orderBy('pc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoConcepto')) {
            $arrayPropiedadesConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $session->get('filtroCodigoPagoConcepto'));
        }
        $strNombreCorto = "";
        if($session->get('filtroNumeroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroNumeroIdentificacion')));
            if($arEmpleado) {
                $session->set('filtroNumeroIdentificacion', $arEmpleado->getNumeroIdentificacion());
                $strNombreCorto = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroNumeroIdentificacion', null);
            }          
        } else {
            $session->set('filtroNumeroIdentificacion', null);
        }       
        
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Numero Identificacion','data' => $session->get('filtroNumeroIdentificacion'), 'required' => false))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'NombreCorto','data' => $strNombreCorto))                    
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
            ->add('pagoConceptoRel', EntityType::class, $arrayPropiedadesConcepto)    
            ->add('BtnRetirarConcepto', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnInactivar', SubmitType::class, array('label'  => 'Inactivar',))            
            ->add('aplicarDiaLaborado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'NO' => '0', 'SI' => '1'), 'data' => $session->get('filtroAplicarDiaLaborado')))                
            ->add('estadoInactivo', ChoiceType::class, array('choices' => array('TODOS' => '2', 'NO' => '0', 'SI' => '1'), 'data' => $session->get('filtroPagoAdicionalEstadoInactivo')))                                
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioPeriodo() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;                 
        $form = $this->createFormBuilder()
            ->add('estadoCerrado', ChoiceType::class, array('choices' => array('SIN CERRAR' => '0', 'CERRADO' => '1', 'TODOS' => '2'), 'data' => $session->get('filtroRhuPagoAdicionalPeriodoEstadoCerrado')))                                
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function listar($form, $modalidad, $periodo) {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaAdicionalesDql(                    
            $session->get('filtroNumeroIdentificacion'),
            $session->get('filtroAplicarDiaLaborado'),        
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroCodigoPagoConcepto'),
            $session->get('filtroPagoAdicionalEstadoInactivo'),
            $modalidad,
            $periodo
            );
    }

    private function listarPeriodo() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->listaDql(                    
            $session->get('filtroRhuPagoAdicionalPeriodoEstadoCerrado'));
    }    
    
    private function filtrarLista($form) {
        
        $session = new Session;
        
        $codigoCentroCosto = '';
        if($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $codigoPagoConcepto = '';
        if($form->get('pagoConceptoRel')->getData()) {
            $codigoPagoConcepto = $form->get('pagoConceptoRel')->getData()->getCodigoPagoConceptoPk();
        }        
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroNumeroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroAplicarDiaLaborado', $form->get('aplicarDiaLaborado')->getData());
        $session->set('filtroCodigoPagoConcepto', $codigoPagoConcepto);
        $session->set('filtroPagoAdicionalEstadoInactivo', $form->get('estadoInactivo')->getData());
    }

    private function filtrarListaPeriodo($form) {        
        $session = new Session;
        $session->set('filtroRhuPagoAdicionalPeriodoEstadoCerrado', $form->get('estadoCerrado')->getData());
    }    
    
    /**
     * @Route("/rhu/pagos/adicionales/detalle/{codigoProgramacionPago}", name="brs_rhu_pagos_adicionales_detalle")
     */ 
    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()
            ->add('BtnRetirarConcepto', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnAplicaDiaLaborado', SubmitType::class, array('label'  => 'Aplicar a dia laborado',))                
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }
            if($form->get('BtnConceptoPermanente')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getPermanente() == 1) {
                            $arPagoAdicional->setPermanente(0);
                        } else {
                            $arPagoAdicional->setPermanente(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }
            if($form->get('BtnAplicaDiaLaborado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                            $arPagoAdicional->setAplicaDiaLaborado(0);
                        } else {
                            $arPagoAdicional->setAplicaDiaLaborado(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }            
        }
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaDql($codigoProgramacionPago);        
        $arPagosAdicionales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:detalle.html.twig', array(
                    'arProgramacionPago' => $arProgramacionPago,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/pagos/adicionales/generarmasivo/lista", name="brs_rhu_pagos_adicionales_generarmasivo_lista")
     */
    public function generarMasivoListaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);

        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();


        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoLista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/pagos/adicionales/generarmasivo/suplementario/detalle/{codigoProgramacionPago}", name="brs_rhu_pagos_adicionales_generarmasivo_suplementario_detalle")
     */
    public function generarMasivoSuplementarioDetalleAction(Request $request, $codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();        
        $session = new Session;
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));        
        $arrayPropiedadesDepartamentoEmpresa = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoDepartamentoEmpresa')) {
            $arrayPropiedadesDepartamentoEmpresa['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa", $session->get('filtroCodigoDepartamentoEmpresa'));
        }
        $form = $this->createFormBuilder()
            ->add('departamentoEmpresaRel', EntityType::class, $arrayPropiedadesDepartamentoEmpresa)
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                                
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))    
            ->add('BtnEliminarExtra', SubmitType::class, array('label'  => 'Eliminar extra'))                                
            ->getForm();
        $form->handleRequest($request);
        $this->listarTiempoSuplementarioMasivo($arProgramacionPago);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnEliminarExtra')->isClicked()) {
                if ($arProgramacionPago->getEstadoPagado() == 0 && $arProgramacionPago->getEstadoGenerado() == 0){
                    $strSql = "UPDATE rhu_programacion_pago_detalle SET horas_extras_ordinarias_diurnas = 0, horas_extras_ordinarias_nocturnas = 0, horas_extras_festivas_diurnas = 0, horas_extras_festivas_nocturnas = 0, horas_recargo_nocturno = 0, horas_recargo_festivo_diurno = 0, horas_recargo_festivo_nocturno = 0 WHERE codigo_programacion_pago_fk = " . $codigoProgramacionPago;           
                    $em->getConnection()->executeQuery($strSql);  
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_generarmasivo_suplementario_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));                                                
                }
            }            
            if($form->get('BtnGuardar')->isClicked()) {
                if ($arProgramacionPago->getEstadoPagado() == 0){
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($intCodigo);
                        if(count($arProgramacionPagoDetalle) > 0) {
                            if($arrControles['TxtHN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHFD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHFD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasFestivasDiurnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHFN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHFN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasFestivasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEOD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEOD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasOrdinariasDiurnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEON'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEON'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasOrdinariasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEFD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEFD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasFestivasDiurnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHEFN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHEFN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasExtrasFestivasNocturnas($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHRN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHRN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasRecargoNocturno($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHRFD'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHRFD'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasRecargoFestivoDiurno($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                            if($arrControles['TxtHRFN'.$intCodigo] != "" ) {
                                $intHoras = $arrControles['TxtHRFN'.$intCodigo];
                                $arProgramacionPagoDetalle->setHorasRecargoFestivoNocturno($intHoras);
                                $em->persist($arProgramacionPagoDetalle);
                            }
                        }
                        $intIndice++;
                    }
                } else {
                    $objMensaje->Mensaje("error", "La programacion esta pagada, no se puede modificar el tiempo suplementario!");
                }    
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_generarmasivo_suplementario_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));                                                
                    //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarListaTiempoSuplementirioMasivo($form, $request);
                $this->listarTiempoSuplementarioMasivo($arProgramacionPago);
            }
        }
        
        $arProgramacionPagoDetalle = $paginator->paginate($arProgramacionPagoDetalle, $request->query->get('page', 1), 30);                               
        //$arEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaTiempoSuplementarioMasivo), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoSuplementarioDetalle.html.twig', array(
            'arProgramacionPagoDetalle' => $arProgramacionPagoDetalle,
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()
            ));
    }
    
    private function listarTiempoSuplementarioMasivo($ar) {
        $em = $this->getDoctrine()->getManager();                
        $session = new Session;
        $this->strDqlListaTiempoSuplementarioMasivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaTiempoSuplementarioMasivoDql(
                    '',
                    $ar->getCodigoCentroCostoFk(),
                    1,
                    $session->get('filtroCodigoDepartamentoEmpresa')
                    );  
    }         
    
    private function filtrarListaTiempoSuplementirioMasivo($form, Request $request) {
        $session = new Session;        
        $codigoDepartamentoEmpresa = '';
        if($form->get('departamentoEmpresaRel')->getData()) {
            $codigoDepartamentoEmpresa = $form->get('departamentoEmpresaRel')->getData()->getCodigoDepartamentoEmpresaPk();
        }
        $session->set('filtroCodigoDepartamentoEmpresa', $codigoDepartamentoEmpresa);
        //$session->set('filtroCodigoDepartamentoEmpresa', $form->get('departamentoEmpresaRel')->getData());
    }
    
    
    public function generarMasivoValorDetalleAction(Request $request, $codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');

        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL('', $codigoCentroCosto, 1));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();

        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoSuplementarioDetalle.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CONCEPTO')
                    ->setCellValue('C1', 'DETALLE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CANTIDAD')
                    ->setCellValue('H1', 'VALOR')                    
                    ->setCellValue('I1', 'PER')
                    ->setCellValue('J1', 'ACT')
                    ->setCellValue('K1', 'ADL');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $query->getResult();
        foreach ($arPagoAdicional as $arPagoAdicional) {
            if ($arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk() == null){
                $srtCentroCosto = "";
            } else {
                $srtCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCentroCostoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPagoAdicional->getCodigoPagoAdicionalPk())    
                ->setCellValue('B' . $i, $arPagoAdicional->getPagoConceptoRel()->getNombre())
                ->setCellValue('C' . $i, $arPagoAdicional->getDetalle())
                ->setCellValue('D' . $i, $srtCentroCosto)    
                ->setCellValue('E' . $i, $arPagoAdicional->getEmpleadoRel()->getNumeroIdentificacion())                        
                ->setCellValue('F' . $i, $arPagoAdicional->getEmpleadoRel()->getNombreCorto())                    
                ->setCellValue('G' . $i, $arPagoAdicional->getCantidad())                    
                ->setCellValue('H' . $i, $arPagoAdicional->getValor())
                ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getPermanente()))
                ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getEstadoInactivo()))
                ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getAplicaDiaLaborado()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagosAdicionales');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosAdicionales.xlsx"');
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