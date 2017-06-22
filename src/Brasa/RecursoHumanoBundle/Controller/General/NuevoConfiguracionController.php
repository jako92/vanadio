<?php

namespace Brasa\RecursoHumanoBundle\Controller\General;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuNuevoConfiguracionType;

/**
 * RhuConfiguracion controller.
 *
 */
class NuevoConfiguracionController extends Controller {

    /**
     * @Route("/rhu/nuevoconfiguracion/{codigoConfiguracion}", name="brs_rhu_configuracion_nomina_nuevo")
     */
    public function configuracionAction(Request $request, $codigoConfiguracion) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 92)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find($codigoConfiguracion);
        if ($arConfiguracion == NULL) {
             $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        } 
        $form = $this->createForm(RhuNuevoConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arConfiguracion = $form->getData();
            $em->persist($arConfiguracion);
            
            //Consecutivo de recurso humano
            $arrControles = $request->request->All();
            $intIndiceConsecutivo = 0;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
                $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->find($intCodigo);
                if (count($arConsecutivo) > 0) {
                    $intConsecutivo = $arrControles['TxtConsecutivo' . $intCodigo];
                    $arConsecutivo->setConsecutivo($intConsecutivo);
                    $em->persist($arConsecutivo);
                }
                $intIndiceConsecutivo++;
            }
            $em->flush();
        }
        $arConsecutivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->findAll();
        return $this->render('BrasaRecursoHumanoBundle:Configuracion:nuevoConfiguracion.html.twig', array(
                    'form' => $form->createView(),
                    'arConsecutivo' => $arConsecutivo));
    }

}
