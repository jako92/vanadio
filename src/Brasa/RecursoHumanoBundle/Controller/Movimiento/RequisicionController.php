<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionRequisitoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RequisicionController extends Controller {

    var $strDqlLista = "";
    var $strDqlListaAspirantes = "";

    /**
     * @Route("/rhu/movimiento/requisicion/lista", name="brs_rhu_requisicion_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 3, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->eliminarSeleccionRequisitos($arrSeleccionados);
            }
            if ($form->get('BtnEstadoAbierto')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->estadoAbiertoSeleccionRequisitos($arrSeleccionados);
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request);
                $this->listar();
                $this->generarExcel();
            }
            if ($form->get('BtnExcelDetalle')->isClicked()) {
                $this->filtrar($form, $request);
                $this->listar();
                $this->generarExcelDetalle();
            }
        }
        $arRequisitos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisicion:lista.html.twig', array('arRequisitos' => $arRequisitos, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/requisicion/nuevo/{codigoSeleccionRequisito}", name="brs_rhu_requisicion_nuevo")
     */
    public function nuevoAction(Request $request, $codigoSeleccionRequisito) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        if ($codigoSeleccionRequisito != 0) {
            $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);
        }
        $form = $this->createForm(RhuSeleccionRequisitoType::class, $arRequisito);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            $arRequisito = $form->getData();

            if ($arRequisito->getSalarioFijo() == $arRequisito->getSalarioVariable()) {
                 $objMensaje->Mensaje('error', 'Seleccione el tipo de salario (fijo o varible)');
            } else {
            


                if ($codigoSeleccionRequisito == 0) {
                    $arRequisito->setCodigoUsuario($arUsuario->getUserName());
                    $arRequisito->setFecha(new \DateTime('now'));
                }
                $em->persist($arRequisito);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_rhu_requisicion_nuevo', array('codigoSeleccionRequisito' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_rhu_requisicion_lista'));
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisicion:nuevo.html.twig', array(
                    'arRequisito' => $arRequisito,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/requisicion/detalle/{codigoSeleccionRequisito}", name="brs_rhu_requisicion_detalle")
     */
    public function detalleAction(Request $request, $codigoSeleccionRequisito) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arRequisicion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arRequisicion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);
        $arRequisicionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
        $arRequisicionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->findBy(array('codigoSeleccionRequisitoFk' => $codigoSeleccionRequisito));
        $form = $this->formularioDetalle($arRequisicion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnImprimir')->isClicked()) {
                $objSeleccionRequisito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSeleccionRequisito();
                $objSeleccionRequisito->Generar($em, $codigoSeleccionRequisito);
            }
            if ($form->get('BtnEliminarDetalle')->isClicked()) {
                if ($arRequisicion->getEstadoCerrado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->eliminarDetallesSeleccionados($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_rhu_requisicion_detalle', array('codigoSeleccionRequisito' => $codigoSeleccionRequisito)));
                } else {
                    $objMensaje->Mensaje('error', 'No se puede eliminar, la requisicion esta cerrada');
                }
            }
            if ($form->get('BtnAprobarDetalle')->isClicked()) {
                if ($arRequisicion->getEstadoCerrado() == 0) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->aprobarDetallesSeleccionados($arrSeleccionados);
                    if ($strRespuesta == '') {
                        return $this->redirect($this->generateUrl('brs_rhu_requisicion_detalle', array('codigoSeleccionRequisito' => $codigoSeleccionRequisito)));
                    } else {
                        $objMensaje->Mensaje('error', $strRespuesta);
                    }
                } else {
                    $objMensaje->Mensaje('error', 'No se puede aprobar, la requisicion esta cerrada');
                }
            }
            if ($form->get('BtnDesaprobarDetalle')->isClicked()) {
                if ($arRequisicion->getEstadoCerrado() == 0) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->desaprobarDetallesSeleccionados($arrSeleccionados);
                    if ($strRespuesta == '') {
                        return $this->redirect($this->generateUrl('brs_rhu_requisicion_detalle', array('codigoSeleccionRequisito' => $codigoSeleccionRequisito)));
                    } else {
                        $objMensaje->Mensaje('error', $strRespuesta);
                    }
                } else {
                    $objMensaje->Mensaje('error', 'No se puede aprobar, la requisicion esta cerrada');
                }
            }
            if ($request->request->get('OpAbrir')) {
                if ($arRequisicion->getEstadoCerrado() == 1) {
                    if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 111)) {
                        $objMensaje->Mensaje("error", "No tiene permisos para abrir la requisicion, comuniquese con el administrador");
                    } else {
                        $arRequisicion->setEstadoCerrado(0);
                        $em->persist($arRequisicion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_requisicion_detalle', array('codigoSeleccionRequisito' => $codigoSeleccionRequisito)));
                    }
                }
            }
            if ($form->get('BtnExcelAspirante')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                ob_clean();
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
                $objPHPExcel->getActiveSheet()->getStyle('2')->getFont()->setBold(true);
                for ($col = 'A'; $col !== 'K'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
                }
                if ($arRequisicion->getEstadoCerrado() == 1) {
                    $estado = "CERRADO";
                } else {
                    $estado = "ABIERTO";
                }
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'REQUISICION:')
                        ->setCellValue('B1', $arRequisicion->getNombre())
                        ->setCellValue('C1', 'ESTADO:')
                        ->setCellValue('D1', $estado);

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A2', 'CODIGO')
                        ->setCellValue('B2', 'IDENTIFICACION')
                        ->setCellValue('C2', 'NOMBRE')
                        ->setCellValue('D2', 'APROBADO')
                        ->setCellValue('E2', 'MOTIVO')
                        ->setCellValue('F2', 'FECHA DESCARTE')
                        ->setCellValue('G2', 'COMENTARIOS');
                $i = 3;

                foreach ($arRequisicionDetalle as $arRequisicionDetalle) {
                    if ($arRequisicionDetalle->getEstadoAprobado() == 1) {
                        $aprobado = "SI";
                    } else {
                        $aprobado = "NO";
                    }
                    if ($arRequisicionDetalle->getFechaDescarte()) {
                        $fechaDescarte = $arRequisicionDetalle->getFechaDescarte()->format('Y-m-d');
                    } else {
                        $fechaDescarte = "No aplica";
                    }
                    $motivo = "";
                    if ($arRequisicionDetalle->getCodigoMotivoDescarteRequisicionAspiranteFk() != null) {
                        $motivo = $arRequisicionDetalle->getMotivoDescarteRequisicionAspiranteRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arRequisicionDetalle->getCodigoAspiranteFk())
                            ->setCellValue('B' . $i, $arRequisicionDetalle->getAspiranteRel()->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arRequisicionDetalle->getAspiranteRel()->getNombreCorto())
                            ->setCellValue('D' . $i, $aprobado)
                            ->setCellValue('E' . $i, $motivo)
                            ->setCellValue('F' . $i, $fechaDescarte)
                            ->setCellValue('G' . $i, $arRequisicionDetalle->getComentarios());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('aspirantes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="aspirantes.xlsx"');
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
        }

        $dql = "SELECT c FROM BrasaRecursoHumanoBundle:RhuSeleccion c where c.codigoSeleccionRequisitoFk = $codigoSeleccionRequisito";
        $query = $em->createQuery($dql);
        $arSeleccion = $query->getResult();
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisicion:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arRequisicion' => $arRequisicion,
                    'arRequisicionDetalle' => $arRequisicionDetalle,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/movimiento/requisicion/descartar/aspirante/{codigoSelReqAsp}", name="brs_rhu_requisicion_descartar_aspirante")
     */
    public function descartarAction(Request $request, $codigoSelReqAsp) {

        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();

        $arSeleccionRequisicionAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
        $arSeleccionRequisicionAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->find($codigoSelReqAsp);
        $arSeleccionRequisicion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arSeleccionRequisicion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($arSeleccionRequisicionAspirante->getCodigoSeleccionRequisitoFk());
        $codigoAspirante = $arSeleccionRequisicionAspirante->getCodigoAspiranteFk();
        $form = $this->createFormBuilder()
                ->setAction($this->generateUrl('brs_rhu_requisicion_descartar_aspirante', array('codigoSelReqAsp' => $codigoSelReqAsp)))
                ->add('comentarios', TextareaType::class, array('data' => $arSeleccionRequisicionAspirante->getComentarios(), 'required' => true))
                ->add('fechaDescarte', DateType::class, array('label' => 'Fecha', 'data' => new \DateTime('now')))
                ->add('motivoDescarteRequisicionAspiranteRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuMotivoDescarteRequisicionAspirante',
                    'choice_label' => 'nombre',
                ))
                ->add('bloqueado', CheckboxType::class, array('required' => false))
                ->add('comentariosAspirante', TextareaType::class, array('required' => false))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {

            if ($arSeleccionRequisicion->getEstadoCerrado() == 0) {
                $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                $arSeleccionRequisicionAspirante->setComentarios($form->get('comentarios')->getData());
                $arSeleccionRequisicionAspirante->setFechaDescarte($form->get('fechaDescarte')->getData());
                $arSeleccionRequisicionAspirante->setMotivoDescarteRequisicionAspiranteRel($form->get('motivoDescarteRequisicionAspiranteRel')->getData());
                $arSeleccionRequisicionAspirante->setEstadoAprobado(0);
                if ($form->get('bloqueado')->getData() == true) {
                    $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
                    $arAspirante->setBloqueado(1);
                    $arAspirante->setComentarios($form->get('comentariosAspirante')->getData());
                    $em->persist($arAspirante);
                }

                $em->persist($arSeleccionRequisicionAspirante);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_requisicion_detalle', array('codigoSeleccionRequisito' => $arSeleccionRequisicionAspirante->getCodigoSeleccionRequisitoFk())));
            } else {
                $objMensaje->Mensaje("error", "La requisicion esta cerrada, no puede realizar el proceso");
                return $this->redirect($this->generateUrl('brs_rhu_requisicion_detalle', array('codigoSeleccionRequisito' => $arSeleccionRequisicionAspirante->getCodigoSeleccionRequisitoFk())));
            }


            //return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisicion:descartarAspirante.html.twig', array(
                    'arSeleccionRequisicion' => $arSeleccionRequisicion,
                    'arSeleccionRequisicionAspirante' => $arSeleccionRequisicionAspirante,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/movimiento/requisicion/buscar/aspirante/{codigoRequisicion}", name="brs_rhu_requisicion_buscar_aspirante")
     */
    public function buscarAspiranteAction(Request $request, $codigoRequisicion) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $arRequisicion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arRequisicion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoRequisicion);
        $form = $this->formularioBuscarAspirante();
        $form->handleRequest($request);
        $this->listarAspirantes();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarBuscarAspirante($form, $request);
                $this->listarAspirantes();
            }
            if ($form->get('BtnAplicar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigo);
                        $arRequisicionAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
                        $arRequisicionAspirante->setSeleccionRequisitoRel($arRequisicion);
                        $arRequisicionAspirante->setAspiranteRel($arAspirante);
                        $em->persist($arRequisicionAspirante);
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arAspirantes = $paginator->paginate($em->createQuery($this->strDqlListaAspirantes), $request->query->get('page', 1), 100);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisicion:buscarAspirante.html.twig', array(
                    'arRequisicion' => $arRequisicion,
                    'arAspirantes' => $arAspirantes,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;

        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->listaDQL(
                $session->get('filtroNombreSeleccionRequisito'), $session->get('filtroAbiertoSeleccionRequisito'), $session->get('filtroCodigoCargo'), $session->get('filtroDesde'), $session->get('filtroHasta')
        );
    }

    private function listarAspirantes() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $this->strDqlListaAspirantes = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->listaBuscarDql(
                $session->get('filtroIdentificacion'), $session->get('filtroAspiranteFechaNacimiento'), $session->get('filtroGeneralCodigoCiudad'), $session->get('filtroRecursoHumanoCodigoCargo'), $session->get('filtroDisponibilidad'), $session->get('filtroReintegro'), $session->get('filtroSexo'), $session->get('filtroRecursoHumanoCodigoEstadoCivil'), $session->get('filtroLibretaMilitar'), $session->get('filtroRecursoHumanoCodigoZona'), $session->get('filtroPesoMinimo'), $session->get('filtroPesoMaximo'), $session->get('filtroEstaturaMinima'), $session->get('filtroEstaturaMaxima')
        );
    }

    private function filtrar($form, Request $request) {
        $session = new Session;
        $session->set('filtroNombreSeleccionRequisito', $form->get('TxtNombre')->getData());
        $session->set('filtroAbiertoSeleccionRequisito', $form->get('estadoCerrado')->getData());
        $codigoCargo = '';
        if ($form->get('cargoRel')->getData()) {
            $codigoCargo = $form->get('cargoRel')->getData()->getCodigoCargoPk();
        }
        $session->set('filtroCodigoCargo', $codigoCargo);
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null) {
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }
    }

    private function filtrarBuscarAspirante($form, Request $request) {
        $session = new Session;
        $codigoCiudad = '';
        if ($form->get('ciudadRel')->getData()) {
            $codigoCiudad = $form->get('ciudadRel')->getData()->getCodigoCiudadPk();
        }
        $session->set('filtroGeneralCodigoCiudad', $codigoCiudad);
        $codigoCargo = '';
        if ($form->get('cargoRel')->getData()) {
            $codigoCargo = $form->get('cargoRel')->getData()->getCodigoCargoPk();
        }
        $session->set('filtroRecursoHumanoCodigoCargo', $codigoCargo);
        $session->set('filtroGeneralCodigoCiudad', $codigoCiudad);
        $codigoEstadoCivil = '';
        if ($form->get('estadoCivilRel')->getData()) {
            $codigoEstadoCivil = $form->get('estadoCivilRel')->getData()->getCodigoEstadoCivilPk();
        }
        $session->set('filtroRecursoHumanoCodigoEstadoCivil', $codigoEstadoCivil);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDisponibilidad', $form->get('codigoDisponibilidadFk')->getData());
        $session->set('filtroReintegro', $form->get('reintegro')->getData());
        $session->set('filtroSexo', $form->get('codigoSexoFk')->getData());
        $session->set('filtroLibretaMilitar', $form->get('codigoTipoLibreta')->getData());
        $session->set('filtroPesoMinimo', $form->get('pesoMinimo')->getData());
        $session->set('filtroPesoMaximo', $form->get('pesoMaximo')->getData());
        $session->set('filtroEstaturaMinima', $form->get('estaturaMinima')->getData());
        $session->set('filtroEstaturaMaxima', $form->get('estaturaMaxima')->getData());
        $codigoZona = '';
        if ($form->get('zonaRel')->getData()) {
            $codigoZona = $form->get('zonaRel')->getData()->getCodigoZonaPk();
        }
        $session->set('filtroRecursoHumanoCodigoZona', $codigoZona);
        if ($form->get('fechaNacimiento')->getData()) {
            $fechaNacimiento = $form->get('fechaNacimiento')->getData();
            $session->set('filtroAspiranteFechaNacimiento', $fechaNacimiento->format('Y-m-d'));
        } else {
            $session->set('filtroAspiranteFechaNacimiento', null);
        }
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $arrayPropiedadesCargo = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCodigoCargo')) {
            $arrayPropiedadesCargo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroCodigoCargo'));
        }
        $form = $this->createFormBuilder()
                ->add('cargoRel', EntityType::class, $arrayPropiedadesCargo)
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $session->get('filtroNombreSeleccionRequisito')))
                ->add('estadoCerrado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'SI' => '1', 'NO' => '0'), 'data' => $session->get('filtroAbiertoSeleccionRequisito')))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnEstadoAbierto', SubmitType::class, array('label' => 'Cerrar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnExcelDetalle', SubmitType::class, array('label' => 'Excel detalle',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonAprobarDetalle = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonDesaprobarDetalle = array('label' => 'Desaprobar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonExcelAspirante = array('label' => 'Excel', 'disabled' => false);
        if ($ar->getEstadoCerrado() == 1) {
            $arrBotonAprobarDetalle['disabled'] = true;
            $arrBotonDesaprobarDetalle['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnAprobarDetalle', SubmitType::class, $arrBotonAprobarDetalle)
                ->add('BtnDesaprobarDetalle', SubmitType::class, $arrBotonDesaprobarDetalle)
                ->add('BtnEliminarDetalle', SubmitType::class, $arrBotonEliminarDetalle)
                ->add('BtnExcelAspirante', SubmitType::class, $arrBotonExcelAspirante)
                ->getForm();
        return $form;
    }

    private function formularioBuscarAspirante() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedadesCiudad = array(
            'class' => 'BrasaGeneralBundle:GenCiudad',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroGeneralCodigoCiudad')) {
            $arrayPropiedadesCiudad['data'] = $em->getReference("BrasaGeneralBundle:GenCiudad", $session->get('filtroGeneralCodigoCiudad'));
        }
        $fechaNacimiento = null;
        if ($session->get('filtroAspiranteFechaNacimiento') != null) {
            $fechaNacimiento = date_create($session->get('filtroAspiranteFechaNacimiento'));
        }
        $arrayPropiedadesCargo = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroRecursoHumanoCodigoCargo')) {
            $arrayPropiedadesCargo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroRecursoHumanoCodigoCargo'));
        }
        $arrayPropiedadesEstadoCivil = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroRecursoHumanoCodigoEstadoCivil')) {
            $arrayPropiedadesEstadoCivil['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEstadoCivil", $session->get('filtroRecursoHumanoCodigoEstadoCivil'));
        }
        $arrayPropiedadesZona = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuZona',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroRecursoHumanoCodigoZona')) {
            $arrayPropiedadesZona['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroRecursoHumanoCodigoZona'));
        }
        $form = $this->createFormBuilder()
                ->add('ciudadRel', EntityType::class, $arrayPropiedadesCiudad)
                ->add('cargoRel', EntityType::class, $arrayPropiedadesCargo)
                ->add('zonaRel', EntityType::class, $arrayPropiedadesZona)
                ->add('estadoCivilRel', EntityType::class, $arrayPropiedadesEstadoCivil)
                ->add('codigoSexoFk', ChoiceType::class, array('choices' => array('TODOS' => '2', 'MASCULINO' => 'M', 'FEMENINO' => 'F')))
                ->add('TxtIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $fechaNacimiento, 'attr' => array('class' => 'date',)))
                ->add('codigoTipoLibreta', ChoiceType::class, array('choices' => array('TODOS' => '3', '1° CLASE' => '1', '2° CLASE' => '2')))
                ->add('reintegro', ChoiceType::class, array('choices' => array('TODOS' => '2', 'SI' => '1', 'NO' => '0')))
                ->add('codigoDisponibilidadFk', ChoiceType::class, array('choices' => array('TODOS' => '0', 'TIEMPO COMPLETO' => '1', 'MEDIO TIEMPO' => '2', 'POR HORAS' => '3', 'DESDE CASA' => '4', 'PRACTICAS' => '5')))
                ->add('pesoMinimo', TextType::class, array('required' => false))
                ->add('pesoMaximo', TextType::class, array('required' => false))
                ->add('estaturaMinima', TextType::class, array('required' => false))
                ->add('estaturaMaxima', TextType::class, array('required' => false))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnAplicar', SubmitType::class, array('label' => 'Aplicar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        //$strSqlLista = $this->getRequest()->getSession();
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
        for ($col = 'A'; $col !== 'V'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'FECHA')
                ->setCellValue('C1', 'NOMBRE')
                ->setCellValue('D1', 'CENTRO COSTO')
                ->setCellValue('E1', 'CARGO REQUISITO')
                ->setCellValue('F1', 'CANTIDAD')
                ->setCellValue('G1', 'SALARIO')
                ->setCellValue('H1', 'TIPO SALARIO')
                ->setCellValue('I1', 'CLIENTE REFERENCIA')
                ->setCellValue('J1', 'CIUDAD')
                ->setCellValue('K1', 'ESTADO CIVIL')
                ->setCellValue('L1', 'NIVEL DE ESTUDIO')
                ->setCellValue('M1', 'EDAD MINIMA')
                ->setCellValue('N1', 'EDAD MAXIMA')
                ->setCellValue('O1', 'NRO HIJOS')
                ->setCellValue('P1', 'SEXO')
                ->setCellValue('Q1', 'TIPO VEHICULO')
                ->setCellValue('R1', 'RELIGION')
                ->setCellValue('S1', 'EXPERIENCIA')
                ->setCellValue('T1', 'DISPONIBILIDAD')
                ->setCellValue('U1', 'LICENCIA CARRO')
                ->setCellValue('V1', 'LICENCIA MOTO')
                ->setCellValue('W1', 'CERRADO')
                ->setCellValue('X1', 'COMENTARIOS')
                ->setCellValue('Y1', 'CODIGO USUARIO');


        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arSeleccionRequisitos = $query->getResult();
        foreach ($arSeleccionRequisitos as $arSeleccionRequisito) {
            $strNombreCentroCosto = "";
            if ($arSeleccionRequisito->getCentroCostoRel()) {
                $strNombreCentroCosto = $arSeleccionRequisito->getCentroCostoRel()->getNombre();
            }
            $strCargo = "";
            if ($arSeleccionRequisito->getCargoRel()) {
                $strCargo = $arSeleccionRequisito->getCargoRel()->getNombre();
            }
            if ($arSeleccionRequisito->getEstadoCerrado() == 1) {
                $cerrado = "SI";
            } else {
                $cerrado = "NO";
            }
            $strEstadoCivil = "";
            if ($arSeleccionRequisito->getEstadoCivilRel()) {
                $strEstadoCivil = $arSeleccionRequisito->getEstadoCivilRel()->getNombre();
            }
            $strCiudad = "";
            if ($arSeleccionRequisito->getCiudadRel()) {
                $strCiudad = $arSeleccionRequisito->getCiudadRel()->getNombre();
            }
            $strEstudioTipo = "";
            if ($arSeleccionRequisito->getEstudioTipoRel()) {
                $strEstudioTipo = $arSeleccionRequisito->getEstudioTipoRel()->getNombre();
            }
            $sexo = "";
            if ($arSeleccionRequisito->getCodigoSexoFk() == "M") {
                $sexo = "MASCULINO";
            }
            if ($arSeleccionRequisito->getCodigoSexoFk() == "F") {
                $sexo = "FEMENINO";
            }
            if ($arSeleccionRequisito->getCodigoSexoFk() == "I") {
                $sexo = "INDIFERENTE";
            }
            $tipoVehiculo = "";
            if ($arSeleccionRequisito->getCodigoTipoVehiculoFk() == "1") {
                $tipoVehiculo = "CARRO";
            }
            if ($arSeleccionRequisito->getCodigoTipoVehiculoFk() == "2") {
                $tipoVehiculo = "MOTO";
            }
            if ($arSeleccionRequisito->getCodigoTipoVehiculoFk() == "0") {
                $tipoVehiculo = "INDIFERENTE";
            }
            $religion = "";
            if ($arSeleccionRequisito->getCodigoReligionFk() == "1") {
                $religion = "CATOLICO";
            }
            if ($arSeleccionRequisito->getCodigoReligionFk() == "2") {
                $religion = "CRISTIANO";
            }
            if ($arSeleccionRequisito->getCodigoReligionFk() == "3") {
                $religion = "PROTESTANTE";
            }
            if ($arSeleccionRequisito->getCodigoReligionFk() == "4") {
                $religion = "INDIFERENTE";
            }
            $experiencia = "";
            if ($arSeleccionRequisito->getCodigoExperienciaRequisicionFk() != null) {
                $experiencia = $arSeleccionRequisito->getExperienciaRequisicionRel()->getNombre();
            }

            $disponibilidad = "";
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "1") {
                $disponibilidad = "TIEMPO COMPLETO";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "2") {
                $disponibilidad = "MEDIO TIEMPO";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "3") {
                $disponibilidad = "POR HORAS";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "4") {
                $disponibilidad = "DESDE CASA";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "5") {
                $disponibilidad = "PRACTICAS";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "0") {
                $disponibilidad = "NO APLICA";
            }
            $licenciaCarro = "";
            if ($arSeleccionRequisito->getCodigoLicenciaCarroFk() == "0") {
                $licenciaCarro = "NO APLICA";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaCarroFk() == "1") {
                $licenciaCarro = "SI";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaCarroFk() == "2") {
                $licenciaCarro = "NO";
            }
            $licenciaMoto = "";
            if ($arSeleccionRequisito->getCodigoLicenciaMotoFk() == "0") {
                $licenciaMoto = "NO APLICA";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaMotoFk() == "1") {
                $licenciaMoto = "SI";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaMotoFk() == "2") {
                $licenciaMoto = "NO";
            }
            $salarioTipo = "";
            if($arSeleccionRequisito->getSalarioFijo() == '1'){
                $salarioTipo = "FIJO";
            }
            if($arSeleccionRequisito->getSalarioVariable() == '1'){
                $salarioTipo = "VARIABLE";
            }           
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSeleccionRequisito->getCodigoSeleccionRequisitoPk())
                    ->setCellValue('B' . $i, $arSeleccionRequisito->getFecha()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $arSeleccionRequisito->getNombre())
                    ->setCellValue('D' . $i, $strNombreCentroCosto)
                    ->setCellValue('E' . $i, $strCargo)
                    ->setCellValue('F' . $i, $arSeleccionRequisito->getCantidadSolicitada())
                    ->setCellValue('G' . $i, $arSeleccionRequisito->getVrsalario())
                    ->setCellValue('H' . $i, $salarioTipo)
                    ->setCellValue('I' . $i, $arSeleccionRequisito->getClienteReferencia())
                    ->setCellValue('J' . $i, $strCiudad)
                    ->setCellValue('K' . $i, $strEstadoCivil)
                    ->setCellValue('L' . $i, $strEstudioTipo)
                    ->setCellValue('M' . $i, $arSeleccionRequisito->getEdadMinima())
                    ->setCellValue('N' . $i, $arSeleccionRequisito->getEdadMaxima())
                    ->setCellValue('O' . $i, $arSeleccionRequisito->getNumeroHijos())
                    ->setCellValue('P' . $i, $sexo)
                    ->setCellValue('Q' . $i, $tipoVehiculo)
                    ->setCellValue('R' . $i, $religion)
                    ->setCellValue('S' . $i, $experiencia)
                    ->setCellValue('T' . $i, $disponibilidad)
                    ->setCellValue('U' . $i, $licenciaCarro)
                    ->setCellValue('V' . $i, $licenciaMoto)
                    ->setCellValue('W' . $i, $cerrado)
                    ->setCellValue('X' . $i, $arSeleccionRequisito->getComentarios())
                    ->setCellValue('Y' . $i, $arSeleccionRequisito->getCodigoUsuario());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RequisicionSeleccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RequisicionSeleccion.xlsx"');
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

    private function generarExcelDetalle() {
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
        for ($col = 'A'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'FECHA')
                ->setCellValue('C1', 'REQUISICION')
                ->setCellValue('D1', 'CENTRO COSTO')
                ->setCellValue('E1', 'CARGO')
                ->setCellValue('F1', 'CANTIDAD')
                ->setCellValue('G1', 'ESTADO')
                ->setCellValue('H1', 'IDENTIFICACION')
                ->setCellValue('I1', 'ASPIRANTE')
                ->setCellValue('J1', 'APROBADO')
                ->setCellValue('K1', 'MOTIVO')
                ->setCellValue('L1', 'FECHA DESCARTADO')
                ->setCellValue('M1', 'COMENTARIOS');
        $i = 2;

        $arRequisicionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arRequisicionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->listaDetalleDql(
                $session->get('filtroNombreSeleccionRequisito'), $session->get('filtroAbiertoSeleccionRequisito'), $session->get('filtroCodigoCargo'), $session->get('filtroDesde'), $session->get('filtroHasta')
        );
        $arRequisicionDetalle = $em->createQuery($arRequisicionDetalle);
        $arRequisicionDetalle = $arRequisicionDetalle->getResult();
        foreach ($arRequisicionDetalle as $arRequisicionDetalle) {
            if ($arRequisicionDetalle->getSeleccionRequisitoRel()->getEstadoCerrado() == 1) {
                $estado = "CERRADO";
            } else {
                $estado = "ABIERTO";
            }
            if ($arRequisicionDetalle->getEstadoAprobado() == 1) {
                $aprobado = "SI";
            } else {
                $aprobado = "NO";
            }
            if ($arRequisicionDetalle->getFechaDescarte()) {
                $fechaDescarte = $arRequisicionDetalle->getFechaDescarte()->format('Y-m-d');
            } else {
                $fechaDescarte = "No aplica";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRequisicionDetalle->getSeleccionRequisitoRel()->getCodigoSeleccionRequisitoPk())
                    ->setCellValue('B' . $i, $arRequisicionDetalle->getSeleccionRequisitoRel()->getFecha()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $arRequisicionDetalle->getSeleccionRequisitoRel()->getNombre())
                    ->setCellValue('D' . $i, $arRequisicionDetalle->getSeleccionRequisitoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('E' . $i, $arRequisicionDetalle->getSeleccionRequisitoRel()->getCargoRel()->getNombre())
                    ->setCellValue('F' . $i, $arRequisicionDetalle->getSeleccionRequisitoRel()->getCantidadSolicitada())
                    ->setCellValue('G' . $i, $estado)
                    ->setCellValue('H' . $i, $arRequisicionDetalle->getAspiranteRel()->getNumeroIdentificacion())
                    ->setCellValue('I' . $i, $arRequisicionDetalle->getAspiranteRel()->getNombreCorto())
                    ->setCellValue('J' . $i, $aprobado)
                    ->setCellValue('L' . $i, $fechaDescarte)
                    ->setCellValue('M' . $i, $arRequisicionDetalle->getComentarios());
            if ($arRequisicionDetalle->getCodigoMotivoDescarteRequisicionAspiranteFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $arRequisicionDetalle->getMotivoDescarteRequisicionAspiranteRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RequisicionDetalle');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RequisicionDetalle.xlsx"');
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

}
