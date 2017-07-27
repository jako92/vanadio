<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuIncapacidadDiagnosticoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * RhuIncapacidadDiagnostico controller.
 *
 */
class IncapacidadDiagnosticoController extends Controller
{
    var $strDqlLista = "";     
    var $strNombre = "";
    var $strCodigo = "";
    
    /**
     * @Route("/rhu/base/incapacidadDiagnostico/", name="brs_rhu_base_salud_ocupacional_incapacidad_diagnostico")
     */     
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 136, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arIncapacidadDiagnosticos = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico();
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoIncapacidadDiagnosticoPk) {
                            $arIncapacidadDiagnostico = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico();
                            $arIncapacidadDiagnostico = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico')->find($codigoIncapacidadDiagnosticoPk);
                            $em->remove($arIncapacidadDiagnostico);                        
                        }                    
                        $em->flush();                                            
                        return $this->redirect($this->generateUrl('brs_rhu_base_salud_ocupacional_incapacidad_diagnostico'));                        
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el registro porque esta siendo utilizado', $this);
                    }

                }                
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoIncapacidadDiagnostico = new \Brasa\RecursoHumanoBundle\Formatos\FormatoIncapacidadDiagnostico();
                $objFormatoIncapacidadDiagnostico->Generar($this, $this->strDqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }    
        }
      
        $arIncapacidadDiagnosticos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/IncapacidadDiagnostico:listar.html.twig', array(
                    'arIncapacidadDiagnosticos' => $arIncapacidadDiagnosticos,
                    'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/incapacidaddiagnostico/nuevo/{codigoIncapacidadDiagnosticoPk}", name="brs_rhu_base_salud_ocupacional_incapacidad_diagnostico_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoIncapacidadDiagnosticoPk) {
        $em = $this->getDoctrine()->getManager();
        $arIncapacidadDiagnostico = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico();
        if ($codigoIncapacidadDiagnosticoPk != 0)
        {
            $arIncapacidadDiagnostico = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico')->find($codigoIncapacidadDiagnosticoPk);
        }
        $form = $this->createForm(RhuIncapacidadDiagnosticoType::class, $arIncapacidadDiagnostico);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arIncapacidadDiagnostico);
            $arIncapacidadDiagnostico = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_salud_ocupacional_incapacidad_diagnostico'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/IncapacidadDiagnostico:nuevo.html.twig', array(
            'formIncapacidadDiagnostico' => $form->createView(),
        ));
    }
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico')->listaDQL(
                $this->strNombre,
                $this->strCodigo
                ); 
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;   
        $form = $this->createFormBuilder()                                    
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => "", 'required' => false))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => "", 'required' => false))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            //->add('BtnPdf', 'submit', array('label'  => 'Pdf',))    
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                                            
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strCodigo = $form->get('TxtCodigo')->getData();
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CÓDIGO')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'CODIGO INTERFACE');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arIncapacidadDiagnosticos = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico();
        $arIncapacidadDiagnosticos = $query->getResult();
        foreach ($arIncapacidadDiagnosticos as $arIncapacidadDiagnosticos) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arIncapacidadDiagnosticos->getCodigoIncapacidadDiagnosticoPk())
                            ->setCellValue('B' . $i, $arIncapacidadDiagnosticos->getNombre())
                            ->setCellValue('C' . $i, $arIncapacidadDiagnosticos->getCodigo());
                    $i++;
                }

        $objPHPExcel->getActiveSheet()->setTitle('IncapacidadDiagnosticos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="IncapacidadDiagnosticos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
    
}
