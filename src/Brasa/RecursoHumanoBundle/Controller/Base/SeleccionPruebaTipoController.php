<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionPruebaTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * RhuSeleccionPruebaTipo controller.
 *
 */
class SeleccionPruebaTipoController extends Controller
{

    /**
     * @Route("/rhu/base/seleccion/prueba/tipo/listar", name="brs_rhu_base_seleccion_prueba_tipo_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 49, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arSeleccionPruebasTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoSeleccionPruebaTipoPk) {
                        $arSeleccionPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo();
                        $arSeleccionPruebaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPruebaTipo')->find($codigoSeleccionPruebaTipoPk);
                        $em->remove($arSeleccionPruebaTipo);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_seleccion_prueba_tipo_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de prueba porque esta siendo utilizado', $this);
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
                $arSeleccionPruebasTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPruebaTipo')->findAll();
                
                foreach ($arSeleccionPruebasTipo as $arSeleccionPruebasTipo) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSeleccionPruebasTipo->getCodigoSeleccionPruebaTipoPk())
                            ->setCellValue('B' . $i, $arSeleccionPruebasTipo->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Selección prueba tipo');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="SeleccionPruebaTipo.xlsx"');
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
        $arSeleccionPruebasTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPruebaTipo')->findAll();
        $arSeleccionPruebasTipo = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);                                       

        return $this->render('BrasaRecursoHumanoBundle:Base/SeleccionPruebaTipo:listar.html.twig', array(
                    'arSeleccionPruebasTipo' => $arSeleccionPruebasTipo,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/seleccion/prueba/tipo/nuevo{codigoSeleccionPruebaTipo}", name="brs_rhu_base_seleccion_prueba_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoSeleccionPruebaTipo) {
        $em = $this->getDoctrine()->getManager();
        $arSeleccionPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo();
        if ($codigoSeleccionPruebaTipo != 0)
        {
            $arSeleccionPruebaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPruebaTipo')->find($codigoSeleccionPruebaTipo);
        }
        $form = $this->createForm(RhuSeleccionPruebaTipoType::class, $arSeleccionPruebaTipo);
        //$formSeleccionPruebaTipo = $this->createForm(new RhuSeleccionPruebaTipoType(), $arSeleccionPruebaTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arSeleccionPruebaTipo);
            $arSeleccionPruebaTipo = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_seleccion_prueba_tipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/SeleccionPruebaTipo:nuevo.html.twig', array(
            'formSeleccionPruebaTipo' => $form->createView(),
        ));
    }
}
