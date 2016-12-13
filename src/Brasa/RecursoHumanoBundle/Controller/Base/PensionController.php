<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPensionType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * RhuEntidadPension controller.
 *
 */
class PensionController extends Controller
{
    /**
     * @Route("/rhu/base/pension/listar", name="brs_rhu_base_pension_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 63, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = new Session;
        $form = $this->createFormBuilder() //
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))                
            ->getForm(); 
        $form->handleRequest($request);
        
        $arPensiones = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEntidadPensionPk) {
                        $arPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
                        $arPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($codigoEntidadPensionPk);
                        $em->remove($arPension);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la entidad de pension porque esta siendo utilizado', $this);
                  }    
            }
        
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoPension = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPension();
                $objFormatoPension->Generar($this);
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
                            ->setCellValue('D1', 'Direccion')
                            ->setCellValue('E1', 'Telefono')
                            ->setCellValue('F1', 'Codigo_interface');

                $i = 2;
                $arPensiones = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findAll();
                
                
                foreach ($arPensiones as $arPension) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPension->getcodigoEntidadPensionPk())
                            ->setCellValue('B' . $i, $arPension->getnombre())
                            ->setCellValue('C' . $i, $arPension->getnit())
                            ->setCellValue('D' . $i, $arPension->getdireccion())
                            ->setCellValue('E' . $i, $arPension->gettelefono())
                            ->setCellValue('F' . $i, $arPension->getCodigoInterface());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Entidades_Pension');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Pension.xlsx"');
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
        
        $arEntidadesPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadesPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findAll();
        $arEntidadesPension = $paginator->paginate($arEntidadesPension, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);                        
        

        return $this->render('BrasaRecursoHumanoBundle:Base/Pension:listar.html.twig', array(
                    'arEntidadesPension' => $arEntidadesPension,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/pension/nuevo/{codigoEntidadPensionPk}", name="brs_rhu_base_pension_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEntidadPensionPk) {
        $em = $this->getDoctrine()->getManager();
        $arPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        if ($codigoEntidadPensionPk != 0)
        {
            $arPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($codigoEntidadPensionPk);
        }
        $form = $this->createForm(RhuPensionType::class, $arPension);  
        //$formPension = $this->createForm(new RhuPensionType(), $arPension);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arPension);
            $arPension = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_pension_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Pension:nuevo.html.twig', array(
            'formPension' => $form->createView(),
        ));
    }
    
}
