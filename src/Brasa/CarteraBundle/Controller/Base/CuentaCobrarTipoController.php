<?php

namespace Brasa\CarteraBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\CarteraBundle\Form\Type\CarCuentaCobrarTipoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuentaCobrarTipoController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/cartera/base/cuentacobrar/tipo/lista", name="brs_car_base_general_cuentacobrar_tipo_listar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 108, 1)) {
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
                    $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_car_base_general_cuentacobrar_tipo_listar'));
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
        $arCuentaCobrarTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Base/CuentaCobroTipo:lista.html.twig', array(
                    'arCuentaCobrarTipos' => $arCuentaCobrarTipos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/base/cuentacobrar/tipo/nuevo/{codigoCuentaCobrarTipo}", name="brs_car_base_cuentacobrar_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCuentaCobrarTipo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCuentaCobrarTipo = new \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo();
        if ($codigoCuentaCobrarTipo != '' && $codigoCuentaCobrarTipo != '0') {
            $arCuentaCobrarTipo = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->find($codigoCuentaCobrarTipo);
        }
        $form = $this->createForm(CarCuentaCobrarTipoType::class, $arCuentaCobrarTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arCuentaCobrarTipo = $form->getData();
                $em->persist($arCuentaCobrarTipo);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_car_base_cuentacobrar_tipo_nuevo', array('codigoCuentaCobroTipo' => 0)));
                } else {
                    return $this->redirect($this->generateUrl('brs_car_base_general_cuentacobrar_tipo_listar'));
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Base/CuentaCobroTipo:nuevo.html.twig', array(
                    'arCuentaCobrarTipo' => $arCuentaCobrarTipo,
                    'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrarTipo')->listaDQL(
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
        $session = new session();
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
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arCuentaCobrarTipos = new \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo();
        $arCuentaCobrarTipos = $query->getResult();

        foreach ($arCuentaCobrarTipos as $arCuentaCobrarTipo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk())
                    ->setCellValue('B' . $i, $arCuentaCobrarTipo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentaCobrarTipo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentaCobrarTipos.xlsx"');
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
