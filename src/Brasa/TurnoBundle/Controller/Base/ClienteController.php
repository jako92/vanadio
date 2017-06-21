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
                $arCliente->setUsuario($arUsuario->getUserName());
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
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        if ($codigoPuesto != '' && $codigoPuesto != '0') {
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
        }
        $form = $this->createForm(TurClientePuestoType::class, $arPuesto);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
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
                ->setCellValue('C1', 'NOMBRE')
                ->setCellValue('D1', 'nombre1')
                ->setCellValue('E1', 'nombre2')
                ->setCellValue('F1', 'apellido1')
                ->setCellValue('G1', 'apellido2')
                ->setCellValue('H1', 'direccion')
                ->setCellValue('I1', 'email')
                ->setCellValue('J1', 'tel1')
                ->setCellValue('K1', 'tel2')
                ->setCellValue('L1', 'fechaing')
                ->setCellValue('M1', 'CIIU')
                ->setCellValue('N1', 'CDCIIU')
                ->setCellValue('O1', 'SUCURSAL')
                ->setCellValue('P1', 'CODALTERNO')
                ->setCellValue('Q1', 'ESCLIENTE')
                ->setCellValue('R1', 'HABILITADO')
                ->setCellValue('S1', 'INTCAR')
                ->setCellValue('T1', 'fecnac');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arClientes = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arClientes = $query->getResult();
        $fecha = new \DateTime('now');
        foreach ($arClientes as $arCliente) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getNit() . "-" . $arCliente->getDigitoVerificacion())
                    ->setCellValue('B' . $i, $arCliente->getCodigoTipoIdentificacionFk())
                    ->setCellValue('C' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('D' . $i, $arCliente->getNombre1())
                    ->setCellValue('E' . $i, $arCliente->getNombre2())
                    ->setCellValue('F' . $i, $arCliente->getApellido1())
                    ->setCellValue('G' . $i, $arCliente->getApellido2())
                    ->setCellValue('H' . $i, $arCliente->getDireccion())
                    ->setCellValue('I' . $i, $arCliente->getEmail())
                    ->setCellValue('J' . $i, $arCliente->getTelefono())
                    ->setCellValue('K' . $i, $arCliente->getCelular())
                    ->setCellValue('L' . $i, $fecha->format('d/m/Y'))
                    ->setCellValue('M' . $i, $arCliente->getCiudadRel()->getCodigoInterface())
                    ->setCellValue('N' . $i, $arCliente->getCiudadRel()->getCodigoInterface())
                    ->setCellValue('O' . $i, '0')
                    ->setCellValue('P' . $i, '')
                    ->setCellValue('Q' . $i, 'S')
                    ->setCellValue('R' . $i, 'S')
                    ->setCellValue('S' . $i, 'S')
                    ->setCellValue('T' . $i, PHPExcel_Shared_Date::PHPToExcel(gmmktime(0, 0, 0, $fecha->format('m'), $fecha->format('d'), $fecha->format('Y'))));
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

}
