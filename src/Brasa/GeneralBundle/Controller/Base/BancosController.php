<?php

namespace Brasa\GeneralBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenBancoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class BancosController extends Controller
{
    /**
     * @Route("/general/base/bancos", name="brs_gen_base_bancos")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 102, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoBanco) {
                    $arBanco = new \Brasa\GeneralBundle\Entity\GenBanco();
                    $arBanco = $em->getRepository('BrasaGeneralBundle:GenBanco')->find($codigoBanco);
                    $em->remove($arBanco);
                    $em->flush();
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Banco');

                $i = 2;
                $arBanco = $em->getRepository('BrasaGeneralBundle:GenBanco')->findAll();
                
                foreach ($arBanco as $arBanco) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arBanco->getCodigoBancoPk())
                            ->setCellValue('B' . $i, $arBanco->getNombre());
                            
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('BancoGeneral');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="BancoGeneral.xlsx"');
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
        $arBancos = new \Brasa\GeneralBundle\Entity\GenBanco();
        $query = $em->getRepository('BrasaGeneralBundle:GenBanco')->findAll();
        $arBancos = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,50/*limit per page*/);

        return $this->render('BrasaGeneralBundle:Base/Bancos:lista.html.twig', array(
                    'arBancos' => $arBancos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/general/base/bancos/nuevo/{codigoBanco}", name="brs_gen_base_bancos_nuevo")
     */
    public function nuevoAction(Request $request, $codigoBanco) {
        $em = $this->getDoctrine()->getManager();        
        $arBanco = new \Brasa\GeneralBundle\Entity\GenBanco();
        if ($codigoBanco != 0)
        {
            $arBanco = $em->getRepository('BrasaGeneralBundle:GenBanco')->find($codigoBanco);
        }    
        $form = $this->createForm(GenBancoType::class, $arBanco);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arBanco = $form->getData();
            $em->persist($arBanco);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_bancos'));
        }
        return $this->render('BrasaGeneralBundle:Base/Bancos:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
        
}
