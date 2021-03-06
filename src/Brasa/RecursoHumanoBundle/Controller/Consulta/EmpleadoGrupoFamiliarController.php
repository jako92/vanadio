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

class EmpleadoGrupoFamiliarController extends Controller {

    var $strDqlLista = "";
    var $strFecha = "";
    var $strNumeroIdentificacion = "";

    /**
     * @Route("/rhu/consulta/empleado/grupo/familiar", name="brs_rhu_consulta_empleado_grupo_familiar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /* if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 17)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->filtrarLista($form);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arEmpleadoGrupoFamiliar = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:grupoFamiliar.html.twig', array(
                    'arEmpleadoGrupoFamiliar' => $arEmpleadoGrupoFamiliar,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->listaDqlGrupoFamiliar(
                $session->get('filtroNombre'), $session->get('filtroIdentificacion')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;

        $form = $this->createFormBuilder()
                ->add('TxtNumeroIdentificacion', TextType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroIdentificacion')))
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $session->get('filtroNombre')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        //$request = $this->getRequest();
        //$controles = $request->request->get('form');        
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
    }

    private function generarExcel() {
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
                ->setCellValue('B1', 'CODIGO EMPLEADO')
                ->setCellValue('C1', 'NOMBRE')
                ->setCellValue('D1', 'CEDULA')
                ->setCellValue('E1', 'PARENTESCO')
                ->setCellValue('F1', 'NOMBRES Y APELLIDOS')
                ->setCellValue('G1', 'EPS')
                ->setCellValue('H1', 'CAJA COMPENSACION')
                ->setCellValue('I1', 'FECHA NACIMIENTO')
                ->setCellValue('J1', 'OCUPACION')
                ->setCellValue('K1', 'TELEFONO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arEmpleadoGrupoFamiliar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoGrupoFamiliar = $query->getResult();

        foreach ($arEmpleadoGrupoFamiliar as $arEmpleadoGrupoFamiliar) {

            $salud = "";
            if ($arEmpleadoGrupoFamiliar->getCodigoEntidadSaludFk() != null) {
                $salud = $arEmpleadoGrupoFamiliar->getEntidadSaludRel()->getNombre();
            }
            
            $caja = "";
            if ($arEmpleadoGrupoFamiliar->getCodigoEntidadCajaFk() != null) {
                $caja = $arEmpleadoGrupoFamiliar->getEntidadCajaRel()->getNombre();
            }
            
            $fecha = "";
            if ($arEmpleadoGrupoFamiliar->getFechaNacimiento() != null) {
                $fecha = $arEmpleadoGrupoFamiliar->getFechaNacimiento();
            }
            
            $ocupacion = "";
            if ($arEmpleadoGrupoFamiliar->getOcupacion() != null) {
               $ocupacion =  $arEmpleadoGrupoFamiliar->getOcupacion();
            }
            
            $telefono = "";
            if ($arEmpleadoGrupoFamiliar->getTelefono() != null) {
                $telefono = $arEmpleadoGrupoFamiliar->getTelefono(); 
            }




            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleadoGrupoFamiliar->getCodigoEmpleadoFamiliaPk())
                    ->setCellValue('B' . $i, $arEmpleadoGrupoFamiliar->getEmpleadoRel()->getCodigoEmpleadoPk())
                    ->setCellValue('C' . $i, $arEmpleadoGrupoFamiliar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arEmpleadoGrupoFamiliar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arEmpleadoGrupoFamiliar->getEmpleadoFamiliaParentescoRel()->getNombre())
                    ->setCellValue('F' . $i, $arEmpleadoGrupoFamiliar->getNombres())
                    ->setCellValue('G' . $i, $salud)
                    ->setCellValue('H' . $i, $caja)
                    ->setCellValue('I' . $i, $fecha)
                    ->setCellValue('J' . $i, $ocupacion)
                    ->setCellValue('K' . $i, $telefono);

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Estudios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Estudios.xlsx"');
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
