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
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 87)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {  
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $strSql = "DELETE FROM tur_servicio_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);                
                $dateFecha = $form->get('fecha')->getData();
                $strAnio = $dateFecha->format('Y');
                $strMes = $dateFecha->format('m'); 
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('estadoActivo' => 1));                                
                $this->recursosInactivosAsignacion();                
                $this->recursosSinAsignacion($arRecursos);                
                set_time_limit(60);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_servicio_inconsistencias')); 
            } 
            if($form->get('BtnEliminar')->isClicked()) {            
                $strSql = "DELETE FROM tur_servicio_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_servicio_inconsistencias')); 
            }
            if($form->get('BtnExportar')->isClicked()) {
                $this->generarExcel();
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
