<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Servicio;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ActualizarPrecioController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/servicio/actualizar/precio", name="brs_tur_utilidad_servicio_actualizar_precio")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 87)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnActualizar')->isClicked()) {  
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $arServicioDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
                $arServicioDetalles =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->findBy(array('estadoCerrado' => 0, 'compuesto' => 1));                                
                foreach ($arServicioDetalles as $arServicioDetalle) {
                    if($arServicioDetalle->getServicioRel()->getEstadoCerrado() == 0) {
                        $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->liquidar($arServicioDetalle->getCodigoServicioDetallePk());                        
                    }                    
                }               
                $arServicios = new \Brasa\TurnoBundle\Entity\TurServicio();
                $arServicios =  $em->getRepository('BrasaTurnoBundle:TurServicio')->findBy(array('estadoCerrado' => 0));                                
                foreach ($arServicios as $arServicio) {
                    $em->getRepository('BrasaTurnoBundle:TurServicio')->liquidar($arServicio->getCodigoServicioPk());
                }
                
                $objMensaje->Mensaje("informacion", "Los servicios se han actualizado con exito");
                return $this->redirect($this->generateUrl('brs_tur_utilidad_servicio_actualizar_precio')); 
            } 
        }                         
        return $this->render('BrasaTurnoBundle:Utilidades/Servicios:actualizarPrecio.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                                    
            ->add('BtnActualizar', SubmitType::class, array('label'  => 'Actualizar precios'))    
            ->getForm();        
        return $form;
    }    

}
