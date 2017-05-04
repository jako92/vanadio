<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmpleadoCumpleanosController extends Controller {

    var $strDqlLista = "";
    var $strFecha = "";
    var $strNumeroIdentificacion = "";

    /**
     * @Route("/rhu/consulta/empleado/cumpleanos", name="brs_rhu_consulta_empleado_cumpleanos")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /* if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 17)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        //$this->filtrarLista($form);
        //$this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnExcel')->isClicked()) {
               
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
                
            }
        }
        //$arEmpleadosCumpleanos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);

        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:cumpleanos.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaCumpleanosDql();
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()
                ->add('Mes', TextType::class, array('label' => 'Mes','attr' => array('require' => 'true')))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $dateMes = $form->get('Mes')->getData();
        $session->set('filtroMes', $dateMes);
    }

    private function generarExcel() {
        $session = new Session;
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
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
        for ($col = 'A'; $col !== 'Z'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'CEDULA')
                ->setCellValue('C1', 'NOMBRE EMPLEADO')
                ->setCellValue('D1', 'FECHA NACIMIENTO')
                ->setCellValue('E1', 'GRUPO PAGO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();

        foreach ($arEmpleados as $arEmpleado) {
            if ($arEmpleado->getFechaNacimiento()->format('n') == $session->get('filtroMes')) {
                if($arEmpleado->getCentroCostoRel() == null){
                 $centroCosto = "";           
                }else{
                    $centroCosto = $arEmpleado->getCentroCostoRel()->getNombre();
                }
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                        ->setCellValue('B' . $i, $arEmpleado->getNumeroIdentificacion())
                        ->setCellValue('C' . $i, $arEmpleado->getNombreCorto())
                        ->setCellValue('D' . $i, $arEmpleado->getFechaNacimiento()->format("Y/m/d"))
                        ->setCellValue('E' . $i, $centroCosto);
                  
                $i++;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('EmpleadoCumpleaños');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="EmpleadoCumpleanos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

}
