<?php

namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurClienteType;
use Brasa\TurnoBundle\Form\Type\TurClientePuestoType;
use Brasa\TurnoBundle\Form\Type\TurProyectoType;
use Brasa\TurnoBundle\Form\Type\TurGrupoFacturacionType;
use Brasa\TurnoBundle\Form\Type\TurClienteDireccionType;
use Brasa\TurnoBundle\Form\Type\TurClienteContratoType;
use Brasa\TurnoBundle\Form\Type\TurClienteContactoType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;

class ClienteController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    var $strNit = "";

    /**
     * @Route("/tur/base/cliente/", name="brs_tur_base_cliente")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 74, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurCliente')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_cliente'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
            if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcelInterfaz();
            }
        }
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Cliente:lista.html.twig', array(
                    'arClientes' => $arClientes,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/nuevo/{codigoCliente}", name="brs_tur_base_cliente_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCliente = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($codigoCliente != '' && $codigoCliente != '0') {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        }
        $form = $this->createForm(TurClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arCliente = $form->getData();
            $arClienteValidar = new \Brasa\TurnoBundle\Entity\TurCliente();
            $arClienteValidar = $em->getRepository('BrasaTurnoBundle:TurCliente')->findBy(array('nit' => $arCliente->getNit()));
            if (($codigoCliente == 0 || $codigoCliente == '') && count($arClienteValidar) > 0) {
                $objMensaje->Mensaje("error", "El cliente con ese nit ya existe");
            } else {
                $arUsuario = $this->getUser();
                $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
                $digito = $objFunciones->devuelveDigitoVerificacion($arCliente->getNit());
                if ($digito != $arCliente->getDigitoVerificacion() || $arCliente->getDigitoVerificacion() == null) {
                $arCliente->setDigitoVerificacion($digito);
                }
                $arCliente->setUsuario($arUsuario->getUserName());
                $arCliente->setFechaIngreso(new \DateTime());
                $em->persist($arCliente);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_nuevo', array('codigoCliente' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente'));
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:nuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/detalle/{codigoCliente}", name="brs_tur_base_cliente_detalle")
     */
    public function detalleAction(Request $request, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $form = $this->formularioDetalle($arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnEliminarPuesto')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarPuesto');
                    $em->getRepository('BrasaTurnoBundle:TurPuesto')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
                }
                if ($form->get('BtnEliminarProyecto')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarProyecto');
                    $em->getRepository('BrasaTurnoBundle:TurProyecto')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
                }
                if ($form->get('BtnEliminarDireccion')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarDireccion');
                    $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
                }
                if ($form->get('BtnEliminarContrato')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                    $em->getRepository('BrasaTurnoBundle:TurClienteContrato')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
                }
                if ($form->get('BtnEliminarContacto')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarContacto');
                    $em->getRepository('BrasaTurnoBundle:TurClienteContacto')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
                }
            }
        }
        $arPuestos = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuestos = $em->getRepository('BrasaTurnoBundle:TurPuesto')->findBy(array('codigoClienteFk' => $codigoCliente));
        $arProyectos = new \Brasa\TurnoBundle\Entity\TurProyecto();
        $arProyectos = $em->getRepository('BrasaTurnoBundle:TurProyecto')->findBy(array('codigoClienteFk' => $codigoCliente));
        $arGruposFacturacion = new \Brasa\TurnoBundle\Entity\TurGrupoFacturacion();
        $arGruposFacturacion = $em->getRepository('BrasaTurnoBundle:TurGrupoFacturacion')->findBy(array('codigoClienteFk' => $codigoCliente));
        $arClienteDirecciones = new \Brasa\TurnoBundle\Entity\TurClienteDireccion();
        $arClienteDirecciones = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->findBy(array('codigoClienteFk' => $codigoCliente));
        $arContratos = new \Brasa\TurnoBundle\Entity\TurContrato();
        $arContratos = $em->getRepository('BrasaTurnoBundle:TurContrato')->findBy(array('codigoClienteFk' => $codigoCliente));
        $arClienteContactos = new \Brasa\TurnoBundle\Entity\TurClienteContacto();
        $arClienteContactos = $em->getRepository('BrasaTurnoBundle:TurClienteContacto')->findBy(array('codigoClienteFk' => $codigoCliente));
        return $this->render('BrasaTurnoBundle:Base/Cliente:detalle.html.twig', array(
                    'arCliente' => $arCliente,
                    'arPuestos' => $arPuestos,
                    'arProyectos' => $arProyectos,
                    'arGruposFacturacion' => $arGruposFacturacion,
                    'arClienteDirecciones' => $arClienteDirecciones,
                    'arContratos' => $arContratos,
                    'arClienteContactos' => $arClienteContactos,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/tur/base/cliente/puesto/nuevo/{codigoCliente}/{codigoPuesto}", name="brs_tur_base_cliente_puesto_nuevo")
     */
    public function puestoNuevoAction(Request $request, $codigoCliente, $codigoPuesto) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuesto->setCentroOperacionRel(null);
        if ($codigoPuesto != '' && $codigoPuesto != '0') {
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
        }
        $form = $this->createForm(TurClientePuestoType::class, $arPuesto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $controlPuesto = $form->get('controlPuesto')->getData();
            $centralOperacion = $form->get('centroOperacionRel')->getData();
            if ($controlPuesto == true && $centralOperacion == null) {
                $objMensaje->Mensaje("error", "Seleccionar la central de operacion para el control de puesto");
            } else {
                $arPuesto = $form->getData();
                $arPuesto->setClienteRel($arCliente);
                $em->persist($arPuesto);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_cliente_puesto_nuevo', array('codigoCliente' => $codigoCliente)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:puestoNuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/proyecto/nuevo/{codigoCliente}/{codigoProyecto}", name="brs_tur_base_cliente_proyecto_nuevo")
     */
    public function proyectoNuevoAction(Request $request, $codigoCliente, $codigoProyecto) {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arProyecto = new \Brasa\TurnoBundle\Entity\TurProyecto();
        if ($codigoProyecto != '' && $codigoProyecto != '0') {
            $arProyecto = $em->getRepository('BrasaTurnoBundle:TurProyecto')->find($codigoProyecto);
        }
        $form = $this->createForm(TurProyectoType::class, $arProyecto);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arProyecto = $form->getData();
                $arProyecto->setClienteRel($arCliente);
                $em->persist($arProyecto);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_cliente_proyecto_nuevo', array('codigoCliente' => $codigoCliente)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:proyectoNuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/grupo/facturacion/nuevo/{codigoCliente}/{codigoGrupoFacturacion}", name="brs_tur_base_cliente_grupo_facturacion_nuevo")
     */
    public function grupoFacturacionNuevoAction(Request $request, $codigoCliente, $codigoGrupoFacturacion) {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arGrupoFacturacion = new \Brasa\TurnoBundle\Entity\TurGrupoFacturacion();
        if ($codigoGrupoFacturacion != '' && $codigoGrupoFacturacion != '0') {
            $arGrupoFacturacion = $em->getRepository('BrasaTurnoBundle:TurGrupoFacturacion')->find($codigoGrupoFacturacion);
        }
        $form = $this->createForm(TurGrupoFacturacionType::class, $arGrupoFacturacion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arGrupoFacturacion = $form->getData();
                $arGrupoFacturacion->setClienteRel($arCliente);
                $em->persist($arGrupoFacturacion);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_cliente_grupo_facturacion_nuevo', array('codigoCliente' => $codigoCliente)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:grupoFacturacionNuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/direccion/nuevo/{codigoCliente}/{codigoDireccion}", name="brs_tur_base_cliente_direccion_nuevo")
     */
    public function direccionNuevoAction(Request $request, $codigoCliente, $codigoDireccion) {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arClienteDireccion = new \Brasa\TurnoBundle\Entity\TurClienteDireccion();
        if ($codigoDireccion != '' && $codigoDireccion != '0') {
            $arClienteDireccion = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->find($codigoDireccion);
        }
        $form = $this->createForm(TurClienteDireccionType::class, $arClienteDireccion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arClienteDireccion = $form->getData();
                $arClienteDireccion->setClienteRel($arCliente);
                $em->persist($arClienteDireccion);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_cliente_direccion_nuevo', array('codigoCliente' => $codigoCliente)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:direccionNuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/contrato/nuevo/{codigoCliente}/{codigoContrato}", name="brs_tur_base_cliente_contrato_nuevo")
     */
    public function contratoNuevoAction(Request $request, $codigoCliente, $codigoContrato) {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arContrato = new \Brasa\TurnoBundle\Entity\TurContrato();
        $arContrato->setFechaDesde(new \DateTime('now'));
        $arContrato->setFechaHasta(new \DateTime('now'));
        if ($codigoContrato != '' && $codigoContrato != '0') {
            $arContrato = $em->getRepository('BrasaTurnoBundle:TurContrato')->find($codigoContrato);
        }
        $form = $this->createForm(TurClienteContratoType::class, $arContrato);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arContrato = $form->getData();
                $arContrato->setClienteRel($arCliente);
                $em->persist($arContrato);
                $em->flush();

                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_contrato_nuevo', array('codigoCliente' => $codigoCliente, 'codigoContrato' => 0)));
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:contratoNuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/contacto/nuevo/{codigoCliente}/{codigoContacto}", name="brs_tur_base_cliente_contacto_nuevo")
     */
    public function contactoNuevoAction(Request $request, $codigoCliente, $codigoContacto) {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arClienteContacto = new \Brasa\TurnoBundle\Entity\TurClienteContacto();
        $arClienteContacto->setFechaNacimiento(new \DateTime('now'));
        if ($codigoContacto != '' && $codigoContacto != 0) {
            $arClienteContacto = $em->getRepository('BrasaTurnoBundle:TurClienteContacto')->find($codigoContacto);
        }
        $form = $this->createForm(TurClienteContactoType::class, $arClienteContacto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arClienteContacto = $form->getData();
            $arClienteContacto->setClienteRel($arCliente);
            $em->persist($arClienteContacto);
            $em->flush();

            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_cliente_contacto_nuevo', array('codigoCliente' => $codigoCliente, 'codigoContacto' => 0)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:contactoNuevo.html.twig', array(
                    'arCliente' => $arCliente,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/puesto/importar/{codigoCliente}", name="brs_tur_base_cliente_puesto_importar")
     */
    public function puestoImportarAction(Request $request, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
                ->add('attachment', FileType::class, array('required' => false))
                ->add('BtnCargar', SubmitType::class, array('label' => 'Cargar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnCargar')->isClicked()) {
                if ($form->get('attachment')->getData() != "") {
                    $this->importarPuestos($form, $codigoCliente);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje("error", "Por favor cargar un archivo");
                }
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->generarPlantillaExcelPuesto();
            }
        }

        return $this->render('BrasaTurnoBundle:Base/Cliente:puestoImportar.html.twig', array('form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurCliente')->listaDQL(
                $this->strNombre, $this->strCodigo, $this->strNit
        );
    }

    private function filtrar($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNit = $form->get('TxtNit')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigo', TextType::class, array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('TxtNit', TextType::class, array('label' => 'Codigo', 'data' => $this->strNit))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnInterfaz', SubmitType::class, array('label' => 'Interfaz',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonEliminarPuesto = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarProyecto = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarDireccion = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarContrato = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonEliminarContacto = array('label' => 'Eliminar', 'disabled' => false);

        $form = $this->createFormBuilder()
                ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                ->add('BtnEliminarPuesto', SubmitType::class, $arrBotonEliminarPuesto)
                ->add('BtnEliminarProyecto', SubmitType::class, $arrBotonEliminarProyecto)
                ->add('BtnEliminarDireccion', SubmitType::class, $arrBotonEliminarDireccion)
                ->add('BtnEliminarContrato', SubmitType::class, $arrBotonEliminarContrato)
                ->add('BtnEliminarContacto', SubmitType::class, $arrBotonEliminarContacto)
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AC'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NIT')
                ->setCellValue('C1', 'DÍGITO')
                ->setCellValue('D1', 'NOMBRE')
                ->setCellValue('E1', 'ESTRATO')
                ->setCellValue('F1', 'CONTACTO')
                ->setCellValue('G1', 'TELEFONO')
                ->setCellValue('H1', 'CELULAR')
                ->setCellValue('I1', 'DIRECCION')
                ->setCellValue('J1', 'CIUDAD')
                ->setCellValue('K1', 'BARRIO')
                ->setCellValue('L1', 'FORMA PAGO')
                ->setCellValue('M1', 'PLAZO PAGO')
                ->setCellValue('N1', 'FINANCIERO')
                ->setCellValue('O1', 'CELULAR')
                ->setCellValue('P1', 'GERENTE')
                ->setCellValue('Q1', 'CELULAR')
                ->setCellValue('R1', 'FA')
                ->setCellValue('S1', 'CODIGO INTERFAZ')
                ->setCellValue('T1', 'COBERTURA')
                ->setCellValue('U1', 'DIMENSIÓN')
                ->setCellValue('V1', 'ORIGEN CAPITAL')
                ->setCellValue('W1', 'ORIGEN JUDICIAL')
                ->setCellValue('X1', 'SECTOR ECONÓMICO')
                ->setCellValue('Y1', 'REG_SIM')
                ->setCellValue('Z1', 'REG_COM')
                ->setCellValue('AA1', 'RET_FTE')
                ->setCellValue('AB1', 'RET_IVA');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arClientes = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arClientes = $query->getResult();

        foreach ($arClientes as $arCliente) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNit())
                    ->setCellValue('C' . $i, $arCliente->getDigitoVerificacion())
                    ->setCellValue('D' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('E' . $i, $arCliente->getEstrato())
                    ->setCellValue('F' . $i, $arCliente->getContacto())
                    ->setCellValue('G' . $i, $arCliente->getTelefonoContacto())
                    ->setCellValue('H' . $i, $arCliente->getCelularContacto())
                    ->setCellValue('I' . $i, $arCliente->getDireccion())
                    ->setCellValue('J' . $i, $arCliente->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arCliente->getBarrio())
                    ->setCellValue('L' . $i, $arCliente->getFormaPagoRel()->getNombre())
                    ->setCellValue('M' . $i, $arCliente->getPlazoPago())
                    ->setCellValue('N' . $i, $arCliente->getFinanciero())
                    ->setCellValue('O' . $i, $arCliente->getCelularFinanciero())
                    ->setCellValue('P' . $i, $arCliente->getGerente())
                    ->setCellValue('Q' . $i, $arCliente->getCelularGerente())
                    ->setCellValue('R' . $i, $arCliente->getFacturaAgrupada())
                    ->setCellValue('S' . $i, $arCliente->getCodigoInterface())
                    ->setCellValue('Y' . $i, $arCliente->getRegimenSimplificado())
                    ->setCellValue('Z' . $i, $arCliente->getRegimenComun())
                    ->setCellValue('AA' . $i, $objFunciones->devuelveBoolean($arCliente->getRetencionFuente()))
                    ->setCellValue('AB' . $i, $objFunciones->devuelveBoolean($arCliente->getRetencionIva()));
            if ($arCliente->getCodigoCoberturaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $i, $arCliente->getCoberturaRel()->getNombre());
            }
            if ($arCliente->getCodigoDimensionFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $i, $arCliente->getDimensionRel()->getNombre());
            }
            if ($arCliente->getCodigoOrigenCapitalFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $i, $arCliente->getOrigenCapitalRel()->getNombre());
            }
            if ($arCliente->getCodigoOrigenJudicialFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $i, $arCliente->getOrigenJudicialRel()->getNombre());
            }
            if ($arCliente->getCodigoSectorEconomicoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X' . $i, $arCliente->getSectorEconomicoRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cliente');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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

    private function generarExcelInterfaz() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'T'; $col !== 'U'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'NIT')
                ->setCellValue('B1', 'clase')
                ->setCellValue('C1', 'TIPO')
                ->setCellValue('D1', 'NOMBRE')
                ->setCellValue('E1', 'nombre1')
                ->setCellValue('F1', 'nombre2')
                ->setCellValue('G1', 'apellido1')
                ->setCellValue('H1', 'apellido2')
                ->setCellValue('I1', 'direccion')
                ->setCellValue('J1', 'email')
                ->setCellValue('K1', 'tel1')
                ->setCellValue('L1', 'tel2')
                ->setCellValue('M1', 'fechaing')
                ->setCellValue('N1', 'CIIU')
                ->setCellValue('O1', 'CDCIIU')
                ->setCellValue('P1', 'SUCURSAL')
                ->setCellValue('Q1', 'CODALTERNO')
                ->setCellValue('R1', 'ESCLIENTE')
                ->setCellValue('S1', 'ESPROVEE')
                ->setCellValue('T1', 'HABILITADO')
                ->setCellValue('U1', 'INTCAR')
                ->setCellValue('V1', 'fecnac');
        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arClientes = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arClientes = $query->getResult();
        $fecha = new \DateTime('now');
        foreach ($arClientes as $arCliente) {
            if($arCliente->getFechaIngreso() !== null){
                $fechaIngreso = $arCliente->getFechaIngreso()->format("Y/m/d");
            } else {
                $fechaIngreso = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getNit() . "-" . $arCliente->getDigitoVerificacion())
                    ->setCellValue('B' . $i, $arCliente->getCodigoTipoIdentificacionFk())
                    ->setCellValue('D' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('E' . $i, $arCliente->getNombre1())
                    ->setCellValue('F' . $i, $arCliente->getNombre2())
                    ->setCellValue('G' . $i, $arCliente->getApellido1())
                    ->setCellValue('H' . $i, $arCliente->getApellido2())
                    ->setCellValue('I' . $i, $arCliente->getDireccion())
                    ->setCellValue('J' . $i, $arCliente->getEmail())
                    ->setCellValue('K' . $i, $arCliente->getTelefono())
                    ->setCellValue('L' . $i, $arCliente->getCelular())
                    ->setCellValue('M' . $i, $fechaIngreso)
                    ->setCellValue('N' . $i, '0')
                    ->setCellValue('O' . $i, $arCliente->getCiudadRel()->getCodigoInterface())
                    ->setCellValue('P' . $i, '0')
                    ->setCellValue('Q' . $i, '')
                    ->setCellValue('R' . $i, 'S')
                    ->setCellValue('S' . $i, 'N')
                    ->setCellValue('T' . $i, 'S')
                    ->setCellValue('U' . $i, 'S')
                    ->setCellValue('V' . $i, $fechaIngreso);
                    if ($arCliente->getCodigoOrigenJudicialFk()){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $arCliente->getOrigenJudicialRel()->getNombre());
                    }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cliente');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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

    private function generarPlantillaExcelPuesto() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'U'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'NOMBRE')
                ->setCellValue('B1', 'CODIGO CIUDAD FK')
                ->setCellValue('C1', 'CODIGO CENTRO OPERACION FK')
                ->setCellValue('D1', 'CODIGO PROGRAMADOR FK')
                ->setCellValue('E1', 'CODIGO ZONA FK')
                ->setCellValue('F1', 'CODIGO CENTRO COSTO FK')
                ->setCellValue('G1', 'CONTROL PUESTO')
                ->setCellValue('H1', 'DIRECCION')
                ->setCellValue('I1', 'TELEFONO')
                ->setCellValue('J1', 'CELULAR')
                ->setCellValue('K1', 'NUMERO COMUNICACION')
                ->setCellValue('L1', 'CODIGO INTERFAZ');

        $objPHPExcel->getActiveSheet(0)
                ->getComment('G1')
                ->getText()->createTextRun('0: NO, 1: SI.');

        $objPHPExcel->getActiveSheet()->setTitle('ImportarPuestos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ImportarPuestos.xlsx"');
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

    private function importarPuestos($form, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $error = "";
        $form['attachment']->getData()->move($arConfiguracion->getRutaTemporal(), "archivo.xls");
        $ruta = $arConfiguracion->getRutaTemporal() . "archivo.xls";
        $objPHPExcel = \PHPExcel_IOFactory::load($ruta);
        //Cargar informacion
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            for ($row = 2; $row <= $highestRow; ++$row) {
                $arCiudad = null;
                $arCentroOperacion = null;
                $arProgramador = null;
                $arZona = null;
                $arCentroCosto = null;

                //Empezar a recorrer cada campo ingresado en la fila y asignar un valor a las variables
                $nombre = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $codigoCiudadFk = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $codigoCentroOperacionFk = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $codigoProgramadorFk = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $codigoZonaFk = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $codigoCentroCostoFk = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $controlPuesto = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $direccion = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $telefono = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $celular = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $numeroComunicacion = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $codigoInterfaz = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                //Consultar las relaciones de los valores ingresados si no son vacios
                if ($codigoCiudadFk) {
                    $arCiudad = $em->getRepository('BrasaGeneralBundle:GenCiudad')->find($codigoCiudadFk);
                }
                if ($codigoCentroOperacionFk) {
                    $arCentroOperacion = $em->getRepository('BrasaTurnoBundle:TurCentroOperacion')->find($codigoCentroOperacionFk);
                }
                if ($codigoProgramadorFk) {
                    $arProgramador = $em->getRepository('BrasaTurnoBundle:TurProgramador')->find($codigoProgramadorFk);
                }
                if ($codigoZonaFk) {
                    $arZona = $em->getRepository('BrasaTurnoBundle:TurZona')->find($codigoZonaFk);
                }
                if ($codigoCentroCostoFk) {
                    $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($codigoCentroCostoFk);
                }

                //empezar a guardar cada registro que se esta importando.
                $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
                $arPuesto->setClienteRel($arCliente);
                $arPuesto->setNombre($nombre);
                $arPuesto->setCiudadRel($arCiudad);
                $arPuesto->setCentroOperacionRel($arCentroOperacion);
                $arPuesto->setProgramadorRel($arProgramador);
                $arPuesto->setZonaRel($arZona);
                $arPuesto->setCentroCostoContabilidadRel($arCentroCosto);
                $arPuesto->setControlPuesto($controlPuesto);
                $arPuesto->setDireccion($direccion);
                $arPuesto->setTelefono($telefono);
                $arPuesto->setCelular($celular);
                $arPuesto->setNumeroComunicacion($numeroComunicacion);
                $arPuesto->setCodigoInterface($codigoInterfaz);
                $em->persist($arPuesto);
            }
        }
        $em->flush();
    }

}
