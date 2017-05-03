<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuIncapacidadType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class IncapacidadController extends Controller {

    var $strSqlLista = "";

    /**
     * @Route("/rhu/movimiento/incapacidad/", name="brs_rhu_movimiento_incapacidad")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 12, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }

            if ($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoIncapacidades = new \Brasa\RecursoHumanoBundle\Formatos\FormatoIncapacidad();
                $objFormatoIncapacidades->Generar($em, $this->strSqlLista);
            }

            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    try {
                        foreach ($arrSeleccionados AS $codigoIncapacidad) {
                            $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                            $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                            $em->remove($arIncapacidad);
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad'));
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar la incapacidad porque esta siendo utilizada en una nomina', $this);
                    }
                }
            }
            if ($form->get('BtnLegalizar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $arIncapacidad->setEstadoLegalizado(1);
                        $em->persist($arIncapacidad);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad'));
                }
            }
        }
        $arIncapacidades = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:lista.html.twig', array(
                    'arIncapacidades' => $arIncapacidades,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/movimiento/incapacidad/nuevo/{codigoIncapacidad}", name="brs_rhu_movimiento_incapacidad_nuevo")
     */
    public function nuevoAction(Request $request, $codigoIncapacidad = 0) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        if ($codigoIncapacidad != 0) {
            $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
        } else {
            $arIncapacidad->setEstadoCobrar(true);
            $arIncapacidad->setFecha(new \DateTime('now'));
            $arIncapacidad->setFechaDesde(new \DateTime('now'));
            $arIncapacidad->setFechaHasta(new \DateTime('now'));
        }

        $form = $this->createForm(RhuIncapacidadType::class, $arIncapacidad);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arIncapacidad = $form->getData();
            $arrControles = $request->request->All();
            if ($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if (count($arEmpleado) > 0) {
                    $arIncapacidad->setEmpleadoRel($arEmpleado);
                    if ($arrControles['form_txtCodigoIncapacidadDiagnostico'] != '') {
                        $arIncapacidadDiagnostico = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico();
                        $arIncapacidadDiagnostico = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico')->findOneBy(array('codigo' => $arrControles['form_txtCodigoIncapacidadDiagnostico']));
                        if (count($arIncapacidadDiagnostico) > 0) {
                            $arIncapacidad->setIncapacidadDiagnosticoRel($arIncapacidadDiagnostico);
                            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                            if ($arIncapacidad->getFechaDesde() <= $arIncapacidad->getFechaHasta()) {
                                $diasIncapacidad = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
                                $diasIncapacidad = $diasIncapacidad->format('%a');
                                $diasIncapacidad = $diasIncapacidad + 1;
                                if ($diasIncapacidad < 180) {
                                    if ($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arIncapacidad->getCodigoIncapacidadPk())) {
                                        if ($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), "")) {
                                            if ($arIncapacidad->getFechaDesde() >= $arEmpleado->getFechaContrato()) {
                                                $intDias = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
                                                $intDias = $intDias->format('%a');
                                                $intDias = $intDias + 1;
                                                $intDiasCobro = $this->diasCobro($intDias, $arIncapacidad->getEstadoProrroga(), $arIncapacidad->getIncapacidadTipoRel()->getTipo());
                                                $arIncapacidad->setDiasCobro($intDiasCobro);
                                                $arIncapacidad->setCantidad($intDias);
                                                $arIncapacidad->setEntidadSaludRel($arEmpleado->getEntidadSaludRel());
                                                $floVrIncapacidad = 0;
                                                $douVrDia = $arEmpleado->getVrSalario() / 30;
                                                $douVrDiaSalarioMinimo = $arConfiguracion->getVrSalario() / 30;
                                                $douPorcentajePago = $arIncapacidad->getIncapacidadTipoRel()->getPagoConceptoRel()->getPorPorcentaje();
                                                $arIncapacidad->setPorcentajePago($douPorcentajePago);
                                                if ($arIncapacidad->getIncapacidadTipoRel()->getTipo() == 1) {
                                                    if ($arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario()) {
                                                        $floVrIncapacidad = $intDiasCobro * $douVrDia;
                                                    }
                                                    if ($arEmpleado->getVrSalario() > $arConfiguracion->getVrSalario() && $arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario() * 1.5) {
                                                        $floVrIncapacidad = $intDiasCobro * $douVrDiaSalarioMinimo;
                                                    }
                                                    if ($arEmpleado->getVrSalario() > ($arConfiguracion->getVrSalario() * 1.5)) {
                                                        $floVrIncapacidad = $intDiasCobro * $douVrDia;
                                                        $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago) / 100;
                                                    }
                                                } else {
                                                    $floVrIncapacidad = $intDiasCobro * $douVrDia;
                                                    $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago) / 100;                                                    
                                                }
                                                $arIncapacidad->setVrCobro($floVrIncapacidad);
                                                $arIncapacidad->setVrIncapacidad($floVrIncapacidad);
                                                $arIncapacidad->setVrSaldo($floVrIncapacidad);
                                                $arIncapacidad->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                                if ($codigoIncapacidad == 0) {
                                                    $arIncapacidad->setCodigoUsuario($arUsuario->getUserName());
                                                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                                    if ($arEmpleado->getCodigoContratoActivoFk() != '') {
                                                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                                                    } else {
                                                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                                                    }
                                                    $arIncapacidad->setContratoRel($arContrato);
                                                }
                                                $em->persist($arIncapacidad);
                                                $em->flush();
                                                if ($codigoIncapacidad == 0) {
                                                    $em->getRepository('BrasaGeneralBundle:GenLog')->crearLog($arUsuario->getId(), 12, 1, $arIncapacidad->getCodigoIncapacidadPk());
                                                } else {
                                                    $em->getRepository('BrasaGeneralBundle:GenLog')->crearLog($arUsuario->getId(), 12, 2, $arIncapacidad->getCodigoIncapacidadPk());
                                                }
                                                if ($form->get('guardarnuevo')->isClicked()) {
                                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad_nuevo', array('codigoIncapacidad' => 0)));
                                                } else {
                                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad'));
                                                }
                                            } else {
                                                $objMensaje->Mensaje("error", "No puede ingresar novedades antes de la fecha de inicio del contrato");
                                            }
                                        } else {
                                            $objMensaje->Mensaje("error", "Existe una licencia en este periodo de fechas");
                                        }
                                    } else {
                                        $objMensaje->Mensaje("error", "Existe otra incapaciad del empleado en esta fecha");
                                    }
                                } else {
                                    $objMensaje->Mensaje("error", "La incapacidad no debe ser mayor 180 dias");
                                }
                            } else {
                                $objMensaje->Mensaje("error", "La fecha desde debe ser inferior o igual a la fecha hasta de la incapacidad");
                            }
                        } else {
                            $objMensaje->Mensaje("error", "El diagnostico no existe");
                        }
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe");
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:nuevo.html.twig', array(
                    'arIncapacidad' => $arIncapacidad,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/incapacidad/detalle/{codigoIncapacidad}", name="brs_rhu_movimiento_incapacidad_detalle")
     */
    public function detalleAction(Request $request, $codigoIncapacidad) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
        $form = $this->formularioDetalle($arIncapacidad);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:detalle.html.twig', array(
                    'arIncapacidad' => $arIncapacidad,
                    'form' => $form->createView()
        ));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreEmpleado = "";
        if ($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if ($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            } else {
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
                                ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $arrayPropiedadesIncapacidadTipo = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuIncapacidadTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('it')
                                ->orderBy('it.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroRhuIncapacidadTipo')) {
            $arrayPropiedadesIncapacidadTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuIncapacidadTipo", $session->get('filtroRhuIncapacidadTipo'));
        }
        $form = $this->createFormBuilder()
                ->add('txtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('txtNombreCorto', TextType::class, array('label' => 'Nombre', 'data' => $strNombreEmpleado))
                ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
                ->add('incapacidadTipoRel', EntityType::class, $arrayPropiedadesIncapacidadTipo)
                ->add('TxtNumeroEps', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIncapacidadNumeroEps')))
                ->add('estadoTranscripcion', ChoiceType::class, array('choices' => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'), 'data' => $session->get('filtroIncapacidadEstadoTranscripcion')))
                ->add('estadoLegalizado', ChoiceType::class, array('choices' => array('TODOS' => 'TODOS', 'LEGALIZADA' => '1', 'SIN LEGALIZAR' => '0'), 'data' => $session->get('filtroIncapacidadEstadoLegalizado')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnPdf', SubmitType::class, array('label' => 'PDF',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnLegalizar', SubmitType::class, array('label' => 'Legalizar',))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $form = $this->createFormBuilder()
                ->getForm();
        return $form;
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaDQL(
                "", $session->get('filtroCodigoCentroCosto'), $session->get('filtroIncapacidadEstadoTranscripcion'), $session->get('filtroIdentificacion'), $session->get('filtroIncapacidadNumeroEps'), $session->get('filtroRhuIncapacidadTipo'), $session->get('filtroIncapacidadEstadoLegalizado')
        );
    }

    private function filtrarLista($form) {
        $session = new session;
        $codigoCentroCosto = '';
        if ($form->get('centroCostoRel')->getData()) {
            $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
        }
        $codigoIncapacidadTipo = '';
        if ($form->get('incapacidadTipoRel')->getData()) {
            $codigoIncapacidadTipo = $form->get('incapacidadTipoRel')->getData()->getCodigoIncapacidadTipoPk();
        }
        $session->set('filtroCodigoCentroCosto', $codigoCentroCosto);
        $session->set('filtroRhuIncapacidadTipo', $codigoIncapacidadTipo);
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroIncapacidadNumeroEps', $form->get('TxtNumeroEps')->getData());
        $session->set('filtroIncapacidadEstadoTranscripcion', $form->get('estadoTranscripcion')->getData());
        $session->set('filtroIncapacidadEstadoLegalizado', $form->get('estadoLegalizado')->getData());
    }

    private function generarExcel() {
        $objFuncinoes = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for ($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'NÚMERO')
                ->setCellValue('C1', 'EPS')
                ->setCellValue('D1', 'TIPO')
                ->setCellValue('E1', 'DOCUMENTO')
                ->setCellValue('F1', 'NOMBRE')
                ->setCellValue('G1', 'CENTRO COSTO')
                ->setCellValue('H1', 'ZONA')
                ->setCellValue('I1', 'SUB ZONA')
                ->setCellValue('J1', 'DESDE')
                ->setCellValue('K1', 'HASTA')
                ->setCellValue('L1', 'DÍAS')
                ->setCellValue('M1', 'COB')
                ->setCellValue('N1', 'TRA')
                ->setCellValue('O1', 'PRO')
                ->setCellValue('P1', 'LEG')
                ->setCellValue('Q1', 'COD')
                ->setCellValue('R1', 'DIAGNOSTICO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $query->getResult();
        foreach ($arIncapacidades as $arIncapacidad) {
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arIncapacidad->getCodigoEmpleadoFk());
            $centroCosto = "";
            if ($arIncapacidad->getCodigoCentroCostoFk() != null) {
                $centroCosto = $arIncapacidad->getCentroCostoRel()->getNombre();
            }
            $salud = "";
            if ($arIncapacidad->getCodigoEntidadSaludFk() != null) {
                $salud = $arIncapacidad->getEntidadSaludRel()->getNombre();
            }
            $zona = "";
            if ($arEmpleado->getCodigoZonaFk() != NULL) {
                $zona = $arEmpleado->getZonaRel()->getNombre();
            }
            $subzona = "";
            if ($arEmpleado->getCodigoSubzonaFk() != NULL) {
                $subzona = $arEmpleado->getSubzonaRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidad->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidad->getNumeroEps())
                    ->setCellValue('C' . $i, $salud)
                    ->setCellValue('D' . $i, $arIncapacidad->getIncapacidadTipoRel()->getNombre())
                    ->setCellValue('E' . $i, $arIncapacidad->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('F' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $centroCosto)
                    ->setCellValue('H' . $i, $zona)
                    ->setCellValue('I' . $i, $subzona)
                    ->setCellValue('J' . $i, $arIncapacidad->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('K' . $i, $arIncapacidad->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('L' . $i, $arIncapacidad->getCantidad())
                    ->setCellValue('M' . $i, $objFuncinoes->devuelveBoolean($arIncapacidad->getEstadoCobrar()))
                    ->setCellValue('N' . $i, $objFuncinoes->devuelveBoolean($arIncapacidad->getEstadoTranscripcion()))
                    ->setCellValue('O' . $i, $objFuncinoes->devuelveBoolean($arIncapacidad->getEstadoProrroga()))
                    ->setCellValue('P' . $i, $objFuncinoes->devuelveBoolean($arIncapacidad->getEstadoLegalizado()))
                    ->setCellValue('Q' . $i, $arIncapacidad->getIncapacidadDiagnosticoRel()->getCodigo());
            if ($arIncapacidad->getCodigoIncapacidadDiagnosticoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $i, $arIncapacidad->getIncapacidadDiagnosticoRel()->getNombre());
            }

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Incapacidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Incapacidades.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function diasCobro($diasIncapacidad = 0, $prorroga = false, $tipo = 1) {
        $dias = 0;
        if ($tipo == 1) {
            if ($prorroga == 0) {
                if ($diasIncapacidad >= 3) {
                    $dias = $diasIncapacidad - 2;
                }
            } else {
                $dias = $diasIncapacidad;
            }
        }
        if ($tipo == 2) {
            $dias = $diasIncapacidad;
        }
        return $dias;
    }

}
