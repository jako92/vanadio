<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Brasa\SeguridadBundle\Form\Type\UserType;
use Brasa\SeguridadBundle\Form\Type\SegPermisoDocumentoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SegUsuariosController extends Controller {

    var $strDqlLista = "";
    var $strDqlListaSegDocumento = "";

    /**
     * @Route("/admin/usuario/lista/", name="brs_seg_admin_usuario_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arUsuarios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        $arGrupos = $paginator->paginate($em->getRepository("BrasaSeguridadBundle:SegGrupo")->findAll(), $request->query->get('page', 1), 50);
        return $this->render('BrasaSeguridadBundle:Usuarios:lista.html.twig', array(
                    'form' => $form->createView(),
                    'arUsuarios' => $arUsuarios,
                    'arGrupos' => $arGrupos
        ));
    }

    /**
     * @Route("/admin/usuario/nuevo/{codigoUsuario}", name="brs_seg_admin_usuario_nuevo")
     */
    public function nuevoAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        //El error es que se debe colocar el eslas entes de Brasa solo con eso toma la clase
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        if ($codigoUsuario != 0) {
            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
        }
        $form = $this->createForm(UserType::class, $arUsuario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $form->getData();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
            $arUsuario->setPassword($password);
            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:nuevo.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/usuario/detalle/{codigoUsuario}", name="brs_seg_admin_usuario_detalle")
     */
    public function detalleAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
                ->add('BtnEliminarEspecial', SubmitType::class, array('label' => 'Eliminar'))
                ->add('BtnEliminarDocumento', SubmitType::class, array('label' => 'Eliminar'))
                ->add('BtnEliminarRol', SubmitType::class, array('label' => 'Eliminar'))
                ->getForm();
        $form->handleRequest($request);

        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
        if ($form->isValid()) {
            if ($form->get('BtnEliminarEspecial')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoEspecial');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioPermisoEspecialPk) {
                        $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->find($codigoUsuarioPermisoEspecialPk);
                        $em->remove($arUsuarioPermisoEspecial);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if ($form->get('BtnEliminarDocumento')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoDocumento');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoDocumentoPk) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumentoPk);
                        $em->remove($arPermisoDocumento);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if ($form->get('BtnEliminarRol')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarUsuarioRol');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioRol) {
                        $arUsuarioRol = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                        $arUsuarioRol = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->find($codigoUsuarioRol);
                        $em->remove($arUsuarioRol);
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
        }
        $arPermisosDocumentos = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        $arPermisosDocumentos = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arPermisosEspeciales = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arUsuarioRoles = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
        $arUsuarioRoles = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        return $this->render('BrasaSeguridadBundle:Usuarios:detalle.html.twig', array(
                    'arPermisosDocumentos' => $arPermisosDocumentos,
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'arUsuarioRoles' => $arUsuarioRoles,
                    'arUsuario' => $arUsuario,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/seg/usuario/detalle/ver/{codigoUsuario}/", name="brs_seg_usuario_detalle_ver")
     */
    public function detalleVerAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
                ->getForm();
        $form->handleRequest($request);
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
        if ($form->isValid()) {
            if ($form->get('BtnEliminarEspecial')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoEspecial');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioPermisoEspecialPk) {
                        $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->find($codigoUsuarioPermisoEspecialPk);
                        $em->remove($arUsuarioPermisoEspecial);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if ($form->get('BtnEliminarDocumento')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoDocumento');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoDocumentoPk) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumentoPk);
                        $em->remove($arPermisoDocumento);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if ($form->get('BtnEliminarRol')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarUsuarioRol');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioRol) {
                        $arUsuarioRol = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                        $arUsuarioRol = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->find($codigoUsuarioRol);
                        $em->remove($arUsuarioRol);
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if ($form->get('BtnActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                if (isset($arrControles['LblCodigoGuiaDocumento'])) {
                    foreach ($arrControles['LblCodigoGuiaDocumento'] as $intCodigo) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($intCodigo);
                        if (isset($arrControles['ChkSeleccionarIngreso' . $intCodigo])) {
                            $arPermisoDocumento->setIngreso(1);
                        } else {
                            $arPermisoDocumento->setIngreso(0);
                        }
                        if (isset($arrControles['ChkSeleccionarNuevo' . $intCodigo])) {
                            $arPermisoDocumento->setNuevo(1);
                        } else {
                            $arPermisoDocumento->setNuevo(0);
                        }
                        if (isset($arrControles['ChkSeleccionarEditar' . $intCodigo])) {
                            $arPermisoDocumento->setEditar(1);
                        } else {
                            $arPermisoDocumento->setEditar(0);
                        }
                        if (isset($arrControles['ChkSeleccionarEliminar' . $intCodigo])) {
                            $arPermisoDocumento->setEliminar(1);
                        } else {
                            $arPermisoDocumento->setEliminar(0);
                        }
                        if (isset($arrControles['ChkSeleccionarAutorizar' . $intCodigo])) {
                            $arPermisoDocumento->setAutorizar(1);
                        } else {
                            $arPermisoDocumento->setAutorizar(0);
                        }
                        if (isset($arrControles['ChkSeleccionarDesautorizar' . $intCodigo])) {
                            $arPermisoDocumento->setDesautorizar(1);
                        } else {
                            $arPermisoDocumento->setDesautorizar(0);
                        }
                        if (isset($arrControles['ChkSeleccionarAprobar' . $intCodigo])) {
                            $arPermisoDocumento->setAprobar(1);
                        } else {
                            $arPermisoDocumento->setAprobar(0);
                        }
                        if (isset($arrControles['ChkSeleccionarDesaprobar' . $intCodigo])) {
                            $arPermisoDocumento->setDesaprobar(1);
                        } else {
                            $arPermisoDocumento->setDesaprobar(0);
                        }
                        if (isset($arrControles['ChkSeleccionarAnular' . $intCodigo])) {
                            $arPermisoDocumento->setAnular(1);
                        } else {
                            $arPermisoDocumento->setAnular(0);
                        }
                        if (isset($arrControles['ChkSeleccionarDesanular' . $intCodigo])) {
                            $arPermisoDocumento->setDesanular(1);
                        } else {
                            $arPermisoDocumento->setDesanular(0);
                        }
                        if (isset($arrControles['ChkSeleccionarImprimir' . $intCodigo])) {
                            $arPermisoDocumento->setImprimir(1);
                        } else {
                            $arPermisoDocumento->setImprimir(0);
                        }
                        $em->persist($arPermisoDocumento);
                    }
                }
                $em->flush();
            }
        }
        $arPermisosDocumentos = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        $arPermisosDocumentos = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arPermisosEspeciales = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arUsuarioRoles = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
        $arUsuarioRoles = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleVer.html.twig', array(
                    'arPermisosDocumentos' => $arPermisosDocumentos,
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'arUsuarioRoles' => $arUsuarioRoles,
                    'arUsuario' => $arUsuario,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admin/usuario/detalle/permiso/especial/nuevo/{codigoUsuario}/", name="brs_seg_admin_usuario_detalle_nuevo_especial")
     */
    public function detalleNuevoPermisoEspecialAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
                ->add('guardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoEspecial) {
                        $arUsuarioPermisoEspecialValidar = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecialValidar = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoPermisoEspecialFk' => $codigoPermisoEspecial));
                        if (!$arUsuarioPermisoEspecialValidar) {
                            $arPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
                            $arPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find($codigoPermisoEspecial);
                            $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                            $arUsuarioPermisoEspecial->setPermisoEspecialRel($arPermisoEspecial);
                            $arUsuarioPermisoEspecial->setUsuarioRel($arUsuario);
                            $arUsuarioPermisoEspecial->setPermitir(1);
                            $em->persist($arUsuarioPermisoEspecial);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->findBy(array(), array('modulo' => 'ASC'));
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoPermisoEspecial.html.twig', array(
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/admin/usuario/detalle/permiso/documento/nuevo/{codigoUsuario}/", name="brs_seg_admin_usuario_detalle_nuevo_documento")
     */
    public function detalleNuevoPermisoDocumentoAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioSegDocumento();
        $form->handleRequest($request);
        $this->listarSegDocumento();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')) {
                $this->filtrarListaSegDocumento($form);
                $this->listarSegDocumento();
            }
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arDatos = $form->getData();
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDocumento) {
                        $arUsuarioPermisoDocumentoValidar = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arUsuarioPermisoDocumentoValidar = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoDocumentoFk' => $codigoDocumento));
                        if (!$arUsuarioPermisoDocumentoValidar) {
                            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
                            $arDocumento = $em->getRepository('BrasaSeguridadBundle:SegDocumento')->find($codigoDocumento);
                            $arUsuarioPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                            $arUsuarioPermisoDocumento->setDocumentoRel($arDocumento);
                            $arUsuarioPermisoDocumento->setUsuarioRel($arUsuario);
                            $arUsuarioPermisoDocumento->setIngreso($arDatos['ingreso']);
                            $arUsuarioPermisoDocumento->setNuevo($arDatos['nuevo']);
                            $arUsuarioPermisoDocumento->setEditar($arDatos['editar']);
                            $arUsuarioPermisoDocumento->setEliminar($arDatos['eliminar']);
                            $arUsuarioPermisoDocumento->setAutorizar($arDatos['autorizar']);
                            $arUsuarioPermisoDocumento->setDesautorizar($arDatos['desautorizar']);
                            $arUsuarioPermisoDocumento->setAprobar($arDatos['aprobar']);
                            $arUsuarioPermisoDocumento->setDesaprobar($arDatos['desaprobar']);
                            $arUsuarioPermisoDocumento->setAnular($arDatos['anular']);
                            $arUsuarioPermisoDocumento->setDesanular($arDatos['desanular']);
                            $arUsuarioPermisoDocumento->setImprimir($arDatos['imprimir']);
                            $em->persist($arUsuarioPermisoDocumento);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje("error", "No selecciono ningun dato para grabar");
                }
            }
        }
        $arDocumentos = $paginator->paginate($em->createQuery($this->strDqlListaSegDocumento), $request->query->get('page', 1), 500);
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoPermisoDocumento.html.twig', array(
                    'arDocumentos' => $arDocumentos,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/seg/usuario/detalle/permiso/documento/editar/{codigoPermisoDocumento}/", name="brs_seg_usuario_detalle_permiso_documento_editar")
     */
    public function detallePermisoDocumentoEditarAction(Request $request, $codigoPermisoDocumento) {
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        if ($codigoPermisoDocumento != 0) {
            $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumento);
        }
        $form = $this->createForm(SegPermisoDocumentoType::class, $arPermisoDocumento);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arPermisoDocumento = $form->getData();
                $em->persist($arPermisoDocumento);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleEditarPermisoDocumento.html.twig', array(
                    'form' => $form->createView()));
    }

    /**
     * @Route("/seg/usuario/detalle/rol/nuevo/{codigoUsuario}/", name="brs_seg_usuario_detalle_rol_nuevo")
     */
    public function detalleNuevoRolAction(Request $request, $codigoUsuario) {
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arRoles = $em->getRepository('BrasaSeguridadBundle:SegRoles')->findAll();
        $form = $this->createFormBuilder()
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRol) {
                        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
                        $arRol = $em->getRepository('BrasaSeguridadBundle:SegRoles')->find($codigoRol);
                        $arUsuarioRolValidar = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                        $arUsuarioRolValidar = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->findBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoRolFk' => $codigoRol));
                        if (!$arUsuarioRolValidar) {
                            $arUsuarioRol = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                            $arUsuarioRol->setRolRel($arRol);
                            $arUsuarioRol->setUsuarioRel($arUsuario);
                            $em->persist($arUsuarioRol);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje("error", "No selecciono ningun dato para grabar");
                }
            }
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoRol.html.twig', array(
                    'arRoles' => $arRoles,
                    'form' => $form->createView()));
    }

    public function recuperarAction() {
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioRecuperar();
        $form->handleRequest($request);
        if ($form->isValid()) {
            return $this->redirect($this->generateUrl('login'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:recuperar.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/usuario/cambiar/clave/admin/{codigoUsuario}", name="brs_seg_admin_usuario_cambiar_clave")
     */
    public function cambiarClaveAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $formUsuario = $this->createFormBuilder()
                ->setAction($this->generateUrl('brs_seg_admin_usuario_cambiar_clave', array('codigoUsuario' => $codigoUsuario)))
                ->add('password', PasswordType::class)
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        $formUsuario->handleRequest($request);
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);

        if ($formUsuario->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($formUsuario->get('password')->getData(), $arUsuario->getSalt());
            $arUsuario->setPassword($password);
            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:cambiarClave.html.twig', array(
                    'arUsuario' => $arUsuario,
                    'formUsuario' => $formUsuario->createView()
        ));
    }

    /**
     * @Route("/user/usuario/cambiar/clave/usuario/{codigoUsuario}/", name="brs_seg_user_usuario_cambiar_clave")
     */
    public function cambiarClaveUsuarioAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $formUsuario = $this->createFormBuilder()
                ->setAction($this->generateUrl('brs_seg_user_usuario_cambiar_clave', array('codigoUsuario' => $codigoUsuario)))
                ->add('password', PasswordType::class)
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        $formUsuario->handleRequest($request);
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);

        if ($formUsuario->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($formUsuario->get('password')->getData(), $arUsuario->getSalt());
            $arUsuario->setPassword($password);
            $arUsuario->setCambiarClave(0);
            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_seg_usuario_detalle_ver', array('codigoUsuario' => $codigoUsuario)));
        }
        if ($arUsuario->getCambiarClave()) {
            $ruta = 'BrasaSeguridadBundle:Usuarios:cambiarClaveLogin.html.twig';
        } else {
            $ruta = 'BrasaSeguridadBundle:Usuarios:cambiarClave.html.twig';
        }
        return $this->render($ruta, array(
                    'arUsuario' => $arUsuario,
                    'formUsuario' => $formUsuario->createView()));
    }

    /**
     * @Route("/user/usuario/detalle/permiso/grupo/{codigoGrupo}/", name="brs_seg_admin_permiso_grupo_detalle")
     */
    public function detallePermisoGrupoAction(Request $request, $codigoGrupo) {
        $em = $this->getDoctrine()->getManager();
        $arGrupo = $em->getRepository('BrasaSeguridadBundle:SegGrupo')->find($codigoGrupo);
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioSegDocumento();
        $form->handleRequest($request);
        $this->listarSegDocumento();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnFiltrar')) {
                $this->filtrarListaSegDocumento($form);
                $this->listarSegDocumento();
            }
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionadosPermisoEspecial = $request->request->get('ChkSeleccionarPermisoEspecial');
                $arrSeleccionadosDocumentos = $request->request->get('ChkSeleccionarDocumento');
                $arDatos = $form->getData();
                if (count($arrSeleccionadosPermisoEspecial) > 0) {
                    foreach ($arrSeleccionadosPermisoEspecial AS $codigoPermisoEspecial) {
                        $arUsuarioPermisoGrupoValidarPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoGrupo();
                        $arUsuarioPermisoGrupoValidarPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegPermisoGrupo')->findBy(array('codigoGrupoFk' => $codigoGrupo, 'codigoPermisoEspecialFk' => $codigoPermisoEspecial));
                        if (!$arUsuarioPermisoGrupoValidarPermisoEspecial) {
                            $arPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find($codigoPermisoEspecial);
                            $arPermisoGrupo = new \Brasa\SeguridadBundle\Entity\SegPermisoGrupo();
                            $arPermisoGrupo->setCodigoPermisoEspecialFk($arPermisoEspecial->getCodigoPermisoEspecialPk());
                            $arPermisoGrupo->setGrupoRel($arGrupo);
                            $arPermisoGrupo->setPermisoEspecialRel($arPermisoEspecial);
                            $arPermisoGrupo->setPermitir(1);
                            $em->persist($arPermisoGrupo);
                            $em->flush();
                        }
                    }
                }
                if (count($arrSeleccionadosDocumentos) > 0) {
                    foreach ($arrSeleccionadosDocumentos AS $codigoDocumento) {
                        $arPermisoGrupoValidarPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoGrupo();
                        $arPermisoGrupoValidarPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoGrupo')->findBy(array('codigoGrupoFk' => $codigoGrupo, 'codigoDocumentoFk' => $codigoDocumento));
                        if (!$arPermisoGrupoValidarPermisoDocumento) {
                            $arDocumento = $em->getRepository('BrasaSeguridadBundle:SegDocumento')->find($codigoDocumento);
                            $arPermisoGrupo = new \Brasa\SeguridadBundle\Entity\SegPermisoGrupo();
                            $arPermisoGrupo->setGrupoRel($arGrupo);$arPermisoGrupo->setDocumentoRel($arDocumento);
                            $arPermisoGrupo->setIngreso($arDatos['ingreso']);
                            $arPermisoGrupo->setNuevo($arDatos['nuevo']);
                            $arPermisoGrupo->setEditar($arDatos['editar']);
                            $arPermisoGrupo->setEliminar($arDatos['eliminar']);
                            $arPermisoGrupo->setAutorizar($arDatos['autorizar']);
                            $arPermisoGrupo->setDesautorizar($arDatos['desautorizar']);
                            $arPermisoGrupo->setAprobar($arDatos['aprobar']);
                            $arPermisoGrupo->setDesaprobar($arDatos['desaprobar']);
                            $arPermisoGrupo->setAnular($arDatos['anular']);
                            $arPermisoGrupo->setDesanular($arDatos['desanular']);
                            $arPermisoGrupo->setImprimir($arDatos['imprimir']);
                            $em->persist($arPermisoGrupo);
                            $em->flush();
                        }
                    }
                }
            }
        }
        $arDocumentos = $paginator->paginate($em->createQuery($this->strDqlListaSegDocumento), $request->query->get('page', 1), 500);
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->findBy(array(), array('modulo' => 'ASC'));
        return $this->render("BrasaSeguridadBundle:Usuarios:detallePermisoGrupo.html.twig", array(
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'arDocumentos' => $arDocumentos,
                    'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaSeguridadBundle:User')->listaDql();
    }

    private function listarSegDocumento() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strDqlListaSegDocumento = $em->getRepository('BrasaSeguridadBundle:SegDocumento')->listaDql(
                $session->get('filtroTipo'), $session->get('filtroModulo'));
    }

    private function formularioRecuperar() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()
                ->add('username', TextType::class, array('label' => 'Numero', 'data' => ""))
                ->add('BtnRecuperar', SubmitType::class, array('label' => 'Recuperar'))
                ->getForm();
        return $form;
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Numero', 'data' => ""))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function formularioSegDocumento() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()
                ->add('ingreso', CheckboxType::class, array('required' => false))
                ->add('nuevo', CheckboxType::class, array('required' => false))
                ->add('editar', CheckboxType::class, array('required' => false))
                ->add('eliminar', CheckboxType::class, array('required' => false))
                ->add('autorizar', CheckboxType::class, array('required' => false))
                ->add('desautorizar', CheckboxType::class, array('required' => false))
                ->add('aprobar', CheckboxType::class, array('required' => false))
                ->add('desaprobar', CheckboxType::class, array('required' => false))
                ->add('anular', CheckboxType::class, array('required' => false))
                ->add('imprimir', CheckboxType::class, array('required' => false))
                ->add('desanular', CheckboxType::class, array('required' => false))
                ->add('TxtModulo', TextType::class, array('required' => false, 'data' => $session->get('filtroModulo')))
                ->add('TxtTipo', TextType::class, array('required' => false, 'data' => $session->get('filtroTipo')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'filtrar'))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        return $form;
    }

    private function filtrarListaSegDocumento($form) {
        $session = new Session;
        $session->set('filtroTipo', $form->get('TxtTipo')->getData());
        $session->set('filtroModulo', $form->get('TxtModulo')->getData());
    }

}
