<?php

namespace Brasa\CarteraBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\CarteraBundle\Form\Type\CarReciboTipoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboTipoController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/cartera/base/recibo/tipo/lista", name="brs_car_base_general_recibo_tipo_listar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 107, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($form->get('BtnEliminar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository('BrasaCarteraBundle:CarReciboTipo')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_car_base_general_recibo_tipo_listar'));
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $this->generarExcel();
                }
            }
        }
        $arReciboTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Base/ReciboTipo:lista.html.twig', array(
                    'arReciboTipos' => $arReciboTipos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/base/recibo/tipo/nuevo/{codigoReciboTipo}", name="brs_car_base_recibo_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoReciboTipo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arReciboTipo = new \Brasa\CarteraBundle\Entity\CarReciboTipo();
        if ($codigoReciboTipo != '' && $codigoReciboTipo != '0') {
            $arReciboTipo = $em->getRepository('BrasaCarteraBundle:CarReciboTipo')->find($codigoReciboTipo);
        }
        $form = $this->createForm(CarReciboTipoType::class, $arReciboTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arReciboTipo = $form->getData();
                $em->persist($arReciboTipo);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_car_base_recibo_tipo_nuevo', array('codigoReciboTipo' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_car_base_general_recibo_tipo_listar'));
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Base/ReciboTipo:nuevo.html.twig', array(
                    'arReciboTipo' => $arReciboTipo,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaCarteraBundle:CarReciboTipo')->listaDQL(
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
                ->add('TxtCodigo', TextType::class, array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arReciboTipos = new \Brasa\CarteraBundle\Entity\CarReciboTipo();
        $arReciboTipos = $query->getResult();
        foreach ($arReciboTipos as $arReciboTipo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arReciboTipo->getCodigoReciboTipoPk())
                    ->setCellValue('B' . $i, $arReciboTipo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ReciboTipo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReciboTipos.xlsx"');
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
