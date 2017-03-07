<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSucursalType;

class SucursalController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/base/sucursal/lista", name="brs_rhu_base_sucursal_lista")
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

        $arSucursal = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/Sucursal:lista.html.twig', array(
                    'arSucursal' => $arSucursal,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/sucursal/nuevo/{codigoSucursal}/{codigoCliente}", name="brs_rhu_base_sucursal_nuevo")
     */
    public function nuevoAction(Request $request, $codigoSucursal, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $arUsuario = $this->getUser();
        if ($codigoSucursal == 0) {
            $arSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSucursal();
        } else {
            $arSucursal = $em->getRepository('BrasaRecursoHumanoBundle:RhuSucursal')->find($codigoSucursal);
        }

        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
        $nombre = $arCliente->getNombreCorto();
        $form = $this->createForm(RhuSucursalType::class, $arSucursal);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSucursal = $form->getData();
            $arSucursal->setClienteRel($arCliente);
            $em->persist($arSucursal);
            $em->flush();
            //return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle'));
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Sucursal:nuevo.html.twig', array(
                    'arSucursal' => $arSucursal,
                    'arClientes' => $arCliente,
                    'nombre' => $nombre,
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSucursal')->listaDql(
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
        $arSucursal = new \Brasa\RecursoHumanoBundle\Entity\RhuSucursal();
        $arSucursal = $query->getResult();
        foreach ($arSucursal as $arSucursal) {
//            if ($arCentroTrabajo->getCodigoCargoSupervigilanciaFk() != null) {
//                $supervigilancia = $arCargos->getCargoSupervigilanciaRel()->getNombre();
//            } else {
//                $supervigilancia = "";
//            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSucursal->getCodigoSucursalPk())
                    ->setCellValue('B' . $i, $arSucursal->getNombre())
                    ->setCellValue('C' . $i, $arSucursal->getClienteRel()->getNombreCorto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Sucursal');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sucursal.xlsx"');
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
