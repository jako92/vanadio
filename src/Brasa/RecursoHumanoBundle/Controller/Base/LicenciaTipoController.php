<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLicenciaTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * RhuLicenciaTipo controller.
 *
 */
class LicenciaTipoController extends Controller
{

    /**
     * @Route("/rhu/base/licenciatipo/listar", name="brs_rhu_base_licenciatipo_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 41, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arLicenciaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{    
                    foreach ($arrSeleccionados AS $codigoLicenciaTipoPk) {
                        $arLicenciaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicenciaTipo')->find($codigoLicenciaTipoPk);
                        $em->remove($arLicenciaTipo);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de licencia porque esta siendo utilizado', $this);
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
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'PAGO CONCEPTO')
                            ->setCellValue('D1', 'EFECTA SALUD')
                            ->setCellValue('E1', 'AUSENTIMSO');

                $i = 2;
                $arLicenciaTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicenciaTipo')->findAll();
                
                foreach ($arLicenciaTipos as $arLicenciaTipo) {
                    if($arLicenciaTipo->getAfectaSalud() == 1){
                        $afectaSalud = "SI";
                    }else{
                        $afectaSalud = "NO";
                    }
                    if($arLicenciaTipo->getAusentismo() == 1){
                        $ausentismo = "SI";
                    }else{
                        $ausentismo = "NO";
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arLicenciaTipo->getcodigoLicenciaTipoPk())
                            ->setCellValue('B' . $i, $arLicenciaTipo->getNombre())
                            ->setCellValue('C' . $i, $arLicenciaTipo->getPagoConceptoRel()->getNombre())
                            ->setCellValue('D' . $i, $afectaSalud)
                            ->setCellValue('E' . $i, $ausentismo);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Licencias_Tipos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="LicenciasTipos.xlsx"');
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
        $arLicenciaTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicenciaTipo')->findAll();
        $arLicenciaTipos = $paginator->paginate($query, $request->query->getInt('page', 1)/*page number*/,20/*limit per page*/);                
        

        return $this->render('BrasaRecursoHumanoBundle:Base/LicenciaTipo:listar.html.twig', array(
                    'arLicenciaTipos' => $arLicenciaTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/licenciatipo/nuevo/{codigoLicenciaTipoPk}", name="brs_rhu_base_licenciatipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoLicenciaTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $arLicenciaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo();
        if ($codigoLicenciaTipoPk != 0)
        {
            $arLicenciaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicenciaTipo')->find($codigoLicenciaTipoPk);
        }
        $form = $this->createForm(RhuLicenciaTipoType::class, $arLicenciaTipo);
        //$formLicenciaTipo = $this->createForm(new RhuLicenciaTipoType(), $arLicenciaTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos.
            $em->persist($arLicenciaTipo);
            $arLicenciaTipo = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_licenciatipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/LicenciaTipo:nuevo.html.twig', array(
            'formLicenciaTipo' => $form->createView(),
        ));
    }
    
    
}
