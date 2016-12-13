<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSaludType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * RhuEntidadSalud controller.
 *
 */
class SaludController extends Controller
{
    /**
     * @Route("/rhu/base/salud/listar", name="brs_rhu_base_salud_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 62, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arSaluds = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEntidadSaludPk) {
                        $arSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                        $arSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($codigoEntidadSaludPk);
                        $em->remove($arSalud);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la entidad de salud porque esta siendo utilizado', $this);
                  }    
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoSalud = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSalud();
                $objFormatoSalud->Generar($this);
        }    
        
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Nit')
                            ->setCellValue('D1', 'DirecciÓn')
                            ->setCellValue('E1', 'Teléfono')
                            ->setCellValue('F1', 'Codigo_interface');
                $i = 2;
                $arSaluds = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findAll();
                
                foreach ($arSaluds as $arSalud) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSalud->getcodigoEntidadSaludPk())
                            ->setCellValue('B' . $i, $arSalud->getnombre())
                            ->setCellValue('C' . $i, $arSalud->getnit())
                            ->setCellValue('D' . $i, $arSalud->getdireccion())
                            ->setCellValue('E' . $i, $arSalud->gettelefono())
                            ->setCellValue('F' . $i, $arSalud->getCodigoInterface());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Entidades_Salud');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Salud.xlsx"');
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
        $arEntidadesSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findAll();
        $arEntidadesSalud = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);                        
        
        return $this->render('BrasaRecursoHumanoBundle:Base/Salud:listar.html.twig', array(
                    'arEntidadesSalud' => $arEntidadesSalud,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/salud/nuevo/{codigoEntidadSaludPk}", name="brs_rhu_base_salud_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEntidadSaludPk) {
        $em = $this->getDoctrine()->getManager();
        $arSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        if ($codigoEntidadSaludPk != 0)
        {
            $arSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($codigoEntidadSaludPk);
        }
        $form = $this->createForm(RhuSaludType::class, $arSalud);  
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arSalud);
            $arSalud = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_salud_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Salud:nuevo.html.twig', array(
            'formSalud' => $form->createView(),
        ));
    }
    
}
