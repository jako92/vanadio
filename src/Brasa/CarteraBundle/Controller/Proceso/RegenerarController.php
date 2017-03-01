<?php

namespace Brasa\CarteraBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\CarteraBundle\Form\Type\CarReciboType;
use Brasa\CarteraBundle\Form\Type\CarReciboDetalleType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RegenerarController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/cartera/proceso/regenerar", name="brs_cartera_proceso_regenerar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnRegenerar')->isClicked()) {
                    $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->findAll();
                    foreach ($arCuentasCobrar as $arCuentaCobrar) {
                        $arCuentaCobrarAct = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                        $arCuentaCobrarAct = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arCuentaCobrar->getCodigoCuentaCobrarPk());
                        $vrPago = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->vrPago($arCuentaCobrar->getCodigoCuentaCobrarPk());
                        $vrPagoAplicacion = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->vrPagoAplicacion($arCuentaCobrar->getCodigoCuentaCobrarPk());
                        $vrTotalPago = $vrPago + $vrPagoAplicacion;
                        $saldo = $arCuentaCobrar->getValorOriginal() - $vrTotalPago;
                        $saldoOperado = $saldo * $arCuentaCobrarAct->getOperacion();
                        $arCuentaCobrarAct->setSaldo($saldo);
                        $arCuentaCobrarAct->setSaldoOperado($saldoOperado);
                        $arCuentaCobrarAct->setAbono($vrTotalPago);
                        $em->persist($arCuentaCobrarAct);
                    }
                    $em->flush();
                    $objMensaje->Mensaje('informacion', "El proceso se ejecuto con exito");
                    return $this->redirect($this->generateUrl('brs_cartera_proceso_regenerar'));
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Proceso/Regenerar:lista.html.twig', array(
                    'form' => $form->createView()));
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
                ->add('BtnRegenerar', SubmitType::class, array('label' => 'Regenerar'))
                ->getForm();
        return $form;
    }

}
