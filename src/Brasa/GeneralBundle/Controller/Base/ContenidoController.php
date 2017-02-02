<?php

namespace Brasa\GeneralBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenContenidoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * GenContenido controller.
 *
 */
class ContenidoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/general/base/contenido/lista/", name="brs_gen_base_contenido_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 138, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->lista();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoContenido) {
                        $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
                        $arContenido = $em->getRepository('BrasaGeneralBundle:GenContenido')->find($codigoContenido);
                        $em->remove($arContenido);                    
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_gen_base_contenido_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar contenidos de los formatos porque esta siendo utilizado');
                   }    
            }                        
        }
        $arContenidos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaGeneralBundle:Base/Contenido:listar.html.twig', array(
                    'arContenidos' => $arContenidos,
                    'form'=> $form->createView()
        ));
    }
    
    /**
     * @Route("/general/base/contenido/nuevo/{codigoContenido}", name="brs_gen_base_contenido_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContenido) {
        $em = $this->getDoctrine()->getManager();        
        $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
        if ($codigoContenido != 0) {
            $arContenido = $em->getRepository('BrasaGeneralBundle:GenContenido')->find($codigoContenido);
        }    
        $form = $this->createForm(GenContenidoType::class, $arContenido);
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContenido = $form->getData();
            $em->persist($arContenido);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_contenido_lista'));
        }
        return $this->render('BrasaGeneralBundle:Base/Contenido:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaGeneralBundle:GenContenido')->listaDql();         
    }
    
}
