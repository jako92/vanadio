<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDepartamentoEmpresaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuDepartamentoEmpresa controller.
 *
 */
class DepartamentoEmpresaController extends Controller
{

    /**
     * @Route("/rhu/base/departamento/empresa/listar", name="brs_rhu_base_contratacion_departamento_empresa_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 58, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDepartamentosEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoDepartamentoEmpresaPk) {
                        $arDepartamentosEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
                        $arDepartamentosEmpresa = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->find($codigoDepartamentoEmpresaPk);
                        $em->remove($arDepartamentosEmpresa);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_contratacion_departamento_empresa_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el departamento empresa porque esta siendo utilizado', $this);
                  }    
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
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NOMBRE');

                $i = 2;
                $arDepartamentosEmpresa = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->findAll();
                
                foreach ($arDepartamentosEmpresa as $arDepartamentosEmpresa) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDepartamentosEmpresa->getCodigoDepartamentoEmpresaPk())
                            ->setCellValue('B' . $i, $arDepartamentosEmpresa->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Departamentos empresa');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="DepartamentosEmpresa.xlsx"');
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
        $arDepartamentosEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->findAll();
        $arDepartamentosEmpresa = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);        
        

        return $this->render('BrasaRecursoHumanoBundle:Base/DepartamentosEmpresa:listar.html.twig', array(
                    'arDepartamentosEmpresa' => $arDepartamentosEmpresa,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/departamento/empresa/nuevo/{codigoDepartamentoEmpresa}", name="brs_rhu_base_departamento_empresa_nuevo")
     */
    public function nuevoAction(Request $request, $codigoDepartamentoEmpresa) {
        $em = $this->getDoctrine()->getManager();
        $arDepartamentoEmpresa = new \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa();
        if ($codigoDepartamentoEmpresa != 0)
        {
            $arDepartamentoEmpresa = $em->getRepository('BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa')->find($codigoDepartamentoEmpresa);
        }    
        $form = $this->createForm(RhuDepartamentoEmpresaType::class, $arDepartamentoEmpresa); 
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arDepartamentoEmpresa);
            $arDepartamentoEmpresa = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contratacion_departamento_empresa_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DepartamentosEmpresa:nuevo.html.twig', array(
            'formDepartamentoEmpresa' => $form->createView(),
        ));
    }
}
