<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCentroTrabajoType;

class CentroTrabajoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/base/centro/trabajo/lista", name="brs_rhu_base_centro_trabajo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 31, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->filtrarLista($form);
        $this->listar();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }

        $arCentroTrabajo = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/CentroTrabajo:lista.html.twig', array(
                    'arCentroTrabajo' => $arCentroTrabajo,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/centro/trabajo/nuevo/{codigoCentroTrabajo}/{codigoSucursal}", name="brs_rhu_base_centro_trabajo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCentroTrabajo, $codigoSucursal) {
        $em = $this->getDoctrine()->getManager();
        $arUsuario = $this->getUser();
        if ($codigoCentroTrabajo == 0) {
            $arCentroTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo();
        } else {
            $arCentroTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroTrabajo')->find($codigoCentroTrabajo);
        }

        $arSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSucursal();
        $arSucursal = $em->getRepository('BrasaRecursoHumanoBundle:RhuSucursal')->find($codigoSucursal);
        $nombre = $arSucursal->getNombre();
        $form = $this->createForm(RhuCentroTrabajoType::class, $arCentroTrabajo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCentroTrabajo = $form->getData();
            $arCentroTrabajo->setSucursalRel($arSucursal);
            $em->persist($arCentroTrabajo);
            $em->flush();
            //return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle'));
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/CentroTrabajo:nuevo.html.twig', array(
                    'arCentroTrabajo' => $arCentroTrabajo,
                    'arSucursal' => $arSucursal,
                    'nombre' => $nombre,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroTrabajo')->listaDql(
                $session->get('filtroNombre')
        );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $session->get('filtroNombre')))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new Session;
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
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
        for ($col = 'A'; $col !== 'D'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'CLIENTE');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arCentroTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo();
        $arCentroTrabajo = $query->getResult();
        foreach ($arCentroTrabajo as $arCentroTrabajo) {
//            if ($arCentroTrabajo->getCodigoCargoSupervigilanciaFk() != null) {
//                $supervigilancia = $arCargos->getCargoSupervigilanciaRel()->getNombre();
//            } else {
//                $supervigilancia = "";
//            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCentroTrabajo->getCodigoCentroTrabajoPk())
                    ->setCellValue('B' . $i, $arCentroTrabajo->getNombre())
                    ->setCellValue('C' . $i, $arCentroTrabajo->getClienteRel()->getNombreCorto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CentroTrabajo');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CentroTrabajo.xlsx"');
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
