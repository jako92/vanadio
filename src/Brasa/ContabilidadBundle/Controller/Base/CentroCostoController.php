<?php

namespace Brasa\ContabilidadBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\ContabilidadBundle\Form\Type\CtbCentroCostoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CentroCostoController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/ctb/base/centro/costo/lista", name="brs_ctb_base_centro_costo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 93, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar'))
                ->getForm();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCentroCostos = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($codigoCentroCosto);
                        $em->remove($arCentroCosto);
                        $em->flush();
                    }
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->generarExcel();
                }
            }
        }
        $arCentroCostos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaContabilidadBundle:Base/CentroCosto:lista.html.twig', array(
                    'arCentroCostos' => $arCentroCostos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/ctb/base/centro/costo/nuevo/{codigoCentroCosto}", name="brs_ctb_base_centro_costo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = new \Brasa\ContabilidadBundle\Entity\CtbCentroCosto();
        if ($codigoCentroCosto != 0) {
            $arCentroCosto = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->find($codigoCentroCosto);
        }
        $form = $this->createForm(CtbCentroCostoType::class, $arCentroCosto);
        //$form = $this->createForm(new CtbCentroCostoType(), $arCentroCosto);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // guardar la tarea en la base de datos
                $arCentroCosto = $form->getData();
                $em->persist($arCentroCosto);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_ctb_base_centro_costo_lista'));
            }
        }
        return $this->render('BrasaContabilidadBundle:Base/CentroCosto:nuevo.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->listaDQL(
                $this->strNombre, $this->strCodigo
        );
    }

    private function filtrar($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', TextType::class, array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigo', TextType::class, array('label' => 'Codigo interface', 'data' => $this->strCodigo))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    public function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        $arCentroCostos = $em->getRepository('BrasaContabilidadBundle:CtbCentroCosto')->findAll();

        foreach ($arCentroCostos as $arCentroCostos) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCentroCostos->getCodigoCentroCostoPk())
                    ->setCellValue('B' . $i, $arCentroCostos->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CentroCostos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CentroCostos.xlsx"');
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
