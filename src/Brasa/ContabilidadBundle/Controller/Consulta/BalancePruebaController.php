<?php

namespace Brasa\ContabilidadBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BalancePruebaController extends Controller {

    var $strDqlLista = "";
    var $strCuentaDesde = "";
    var $strCuentaHasta = "";
    var $strDesde = "";
    var $strHasta = "";

    /**
     * @Route("/ctb/consultas/balance/prueba/", name="brs_ctb_consultas_balance_prueba")
     */
    public function balanceAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 36)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        //$this->listar();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($form->get('BtnExcel')->isClicked()) {
                    //$this->filtrar($form);
                    //$this->listar();
                    //$this->generarExcel();
                }
                if ($form->get('BtnPdf')->isClicked()) {
                    //$this->filtrarLista($form, $request);
                    //$this->listar();
                    //$objFormatoBalancePrueba = new \Brasa\ContabilidadBundle\Formatos\FormatoBalancePrueba();
                    //$objFormatoBalancePrueba->Generar($this, $this->strDqlLista);
                }
                if ($form->get('BtnFiltrar')->isClicked()) {
                    //$this->filtrar($form);
                    //$this->listar();
                }
            }
        }
        //$arBalancePrueba = $this->strDqlLista;
        return $this->render('BrasaContabilidadBundle:Consulta/BalancePrueba:balance.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->balancePruebaDql($this->strDesde, $this->strHasta, $this->strCuentaDesde, $this->strCuentaHasta);
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnPdf', SubmitType::class, array('label' => 'Pdf'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->getForm();
        return $form;
    }

    private function filtrar($form) {
        $this->strDesde = $form->get('fechaDesde')->getData();
        $this->strHasta = $form->get('fechaHasta')->getData();
        $request = $this->getRequest();
        $arrControles = $request->request->All();
        $this->strCuentaDesde = $arrControles['TxtCuentaDesde'];
        $this->strCuentaHasta = $arrControles['TxtCuentaHasta'];
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
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
                ->setCellValue('B1', 'NÚMERO')
                ->setCellValue('C1', 'REFERENCIA')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'COMPROBANTE')
                ->setCellValue('F1', 'CUENTA')
                ->setCellValue('G1', 'NIT')
                ->setCellValue('H1', 'TERCERO')
                ->setCellValue('I1', 'DEBITO')
                ->setCellValue('J1', 'CREDITO')
                ->setCellValue('K1', 'BASE')
                ->setCellValue('L1', 'DETALLE');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistros = $query->getResult();
        foreach ($arRegistros as $arRegistro) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getCodigoRegistroPk())
                    ->setCellValue('B' . $i, $arRegistro->getNumero())
                    ->setCellValue('C' . $i, $arRegistro->getNumeroReferencia())
                    ->setCellValue('D' . $i, $arRegistro->getFecha()->Format('Y-m-d'))
                    ->setCellValue('E' . $i, $arRegistro->getCodigoComprobanteContableFk())
                    ->setCellValue('F' . $i, $arRegistro->getCodigoCuentaFk())
                    ->setCellValue('G' . $i, $arRegistro->getCodigoTerceroFk())
                    ->setCellValue('H' . $i, '')
                    ->setCellValue('I' . $i, $arRegistro->getDebito())
                    ->setCellValue('J' . $i, $arRegistro->getCredito())
                    ->setCellValue('K' . $i, $arRegistro->getBase())
                    ->setCellValue('L' . $i, $arRegistro->getDescripcionContable());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RegistrosContables.xlsx"');
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
