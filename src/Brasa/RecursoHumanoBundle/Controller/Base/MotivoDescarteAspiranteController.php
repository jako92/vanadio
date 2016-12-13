<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuMotivoDescarteAspiranteType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

//use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
//use Doctrine\DBAL\Driver\PDOException;

/**
 * RhuMotivoDescarteAspirante controller.
 *
 */
class MotivoDescarteAspiranteController extends Controller
{
    /**
     * @Route("/rhu/base/motivodescarte/aspirante/listar", name="brs_rhu_base_motivodescarte_aspirante_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 132, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            //->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arMotivos = new \Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoMotivoDescarteAspirantePk) {
                        $arMotivo = new \Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante();
                        $arMotivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuMotivoDescarteRequisicionAspirante')->find($codigoMotivoDescarteAspirantePk);
                        $em->remove($arMotivo);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_motivodescarte_aspirante_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el motivo porque esta siendo utilizado', $this);
                  }     
            }
            
        /*if($form->get('BtnPdf')->isClicked()) {
                $objFormatoBanco = new \Brasa\RecursoHumanoBundle\Formatos\FormatoBanco();
                $objFormatoBanco->Generar($this);
        }*/    
        
        if($form->get('BtnExcel')->isClicked()) {
            ob_clean();
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
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'NOMBRE');

                $i = 2;
                $arMotivos = $em->getRepository('BrasaRecursoHumanoBundle:RhuMotivoDescarteRequisicionAspirante')->findAll();
                
                foreach ($arMotivos as $arMotivos) {
                                          
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arMotivos->getCodigoMotivoDescarteRequisicionAspirantePk())
                            ->setCellValue('B' . $i, $arMotivos->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('MotivosDescarteAspirante');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="MotivosDescarteAspirante.xlsx"');
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
        $arMotivos = new \Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuMotivoDescarteRequisicionAspirante')->findAll();            
        $arMotivos = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);                

        return $this->render('BrasaRecursoHumanoBundle:Base/MotivoDescarteAspirante:listar.html.twig', array(
                    'arMotivos' => $arMotivos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/motivodescarte/aspirante/nuevo/{codigoMotivoDescarteAspirantePk}", name="brs_rhu_base_motivodescarte_aspirante_nuevo")
     */
    public function nuevoAction(Request $request, $codigoMotivoDescarteAspirantePk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arMotivo = new \Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante();
        if ($codigoMotivoDescarteAspirantePk != 0)
        {
            $arMotivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuMotivoDescarteRequisicionAspirante')->find($codigoMotivoDescarteAspirantePk);
        }    
        $formMotivo = $this->createForm(new RhuMotivoDescarteAspiranteType(), $arMotivo);
        $formMotivo->handleRequest($request);
        if ($formMotivo->isValid())
        {
            // guardar la tarea en la base de datos
            $arMotivo = $formMotivo->getData();
            $em->persist($arMotivo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_motivodescarte_aspirante_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/MotivoDescarteAspirante:nuevo.html.twig', array(
            'formMotivo' => $formMotivo->createView(),
        ));
    }
    
}
