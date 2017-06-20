<?php

namespace Brasa\InventarioBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvDocumentoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/inv/base/documento/", name="brs_inv_base_documento")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->lista();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnEliminarDocumento')) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados > 0) {
                    foreach ($arrSeleccionados as $codigoDocumento) {
                        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
                        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($codigoDocumento);
                        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
                        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->findBy(array('codigoDocumentoFk' => $codigoDocumento));
                        if ($arMovimiento == null) {
                            $em->remove($arDocumento);
                            $em->flush();
                        } else {
                            $objMensaje->Mensaje("error", "No se puede eliminar el documento, tiene movimientos generados");
                        }
                    }
                }
            }
        }
        $arDocumentos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Base/Documento:lista.html.twig', array(
                    'arDocumentos' => $arDocumentos,
                    'form'=> $form->createView()));
    }

    /**
     * @Route("/inv/base/documento/nuevo/{codigoDocumento}", name="brs_inv_base_documento_nuevo")
     */
    public function nuevoAction(Request $request, $codigoDocumento) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
        if ($codigoDocumento != 0) {
            $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($codigoDocumento);
        }
        $form = $this->createForm(InvDocumentoType::class, $arDocumento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arDocumento = $form->getData();
            $arDocumento->setCodigoDocumentoClaseFk($arDocumento->getDocumentoTipoRel()->getCodigoDocumentoTipoPk());
            $em->persist($arDocumento);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_base_documento_nuevo', array('codigoDocumento' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_inv_base_documento'));
            }
        }
        return $this->render('BrasaInventarioBundle:Base/Documento:nuevo.html.twig', array(
                    'arDocumento' => $arDocumento,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvDocumento')->listaDQL();
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $form = $this->createFormBuilder()
                ->add('BtnEliminarDocumento', SubmitType::class, array('label' => 'Eliminar',))
                ->getForm();
        return $form;
    }

}
