<?php

namespace Brasa\InventarioBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvOrdenCompraType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrdenCompraController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/inv/movimiento/orden/compra/lista", name="brs_inv_movimiento_orden_compra_lista")
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
                    foreach ($arrSeleccionados as $codigoOrdenCompra) {
                        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
                        $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
                        $em->remove($arOrdenCompra);
                        $em->flush();
                    }
                }
            }
        }
        $arOrdenesCompra = $paginator->paginate($this->strDqlLista, $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Movimiento/OrdenCompra:lista.html.twig', array(
                    'arOrdenesCompra' => $arOrdenesCompra,
                    'form'=> $form->createView()));
    }

    /**
     * @Route("/inv/movimiento/orden/compra/nuevo/{codigoOrdenCompra}", name="brs_inv_movimiento_orden_compra_nuevo")
     */
    public function nuevoAction(Request $request, $codigoOrdenCompra) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra->setFecha(new \DateTime('now'));
        if ($codigoOrdenCompra != 0) {
            $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
        }
        $form = $this->createForm(InvOrdenCompraType::class, $arOrdenCompra);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arOrdenCompra = $form->getData();
            $em->persist($arOrdenCompra);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_nuevo', array('codigoOrdenCompra' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_inv_movimiento_orden_compra_lista'));
            }
        }
        return $this->render('BrasaInventarioBundle:Movimiento/OrdenCompra:nuevo.html.twig', array(                  
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->findAll();
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
