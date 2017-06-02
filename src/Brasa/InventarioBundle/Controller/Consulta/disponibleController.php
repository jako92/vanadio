<?php

namespace Brasa\InventarioBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class disponibleController extends Controller {

    var $strListaDql = "";

    /**
     * @Route("/inv/consulta/disponible", name="brs_inv_consulta_disponible")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        /* if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 48)) {
          return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
          } */
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                    $this->lista();
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $this->lista();
                    $this->generarExcel();
                }
            }
        }
        $query = $em->createQuery($this->strListaDql);
        $arResultados = $query->getResult();
        $arLotes = $paginator->paginate($arResultados, $request->query->get('page', 1), 50);
        return $this->render('BrasaInventarioBundle:Consultas/Lote:disponible.html.twig', array(
                    'arLotes' => $arLotes,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql = $em->getRepository('BrasaInventarioBundle:InvLote')->consultaDisponibleDql(
                $session->get('filtroCodigoItem'));
    }

    private function filtrar($form) {
        $session = new Session;
        $session->set('filtroCodigoItem', $form->get('TxtCodigoItem')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;
        $strNombreItem = "";
        if ($session->get('filtroCodigoItem')) {
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($session->get('filtroCodigoItem'));
            if ($arItem) {
                $strNombreItem = $arItem->getNombre();
            } else {
                $session->set('filtroCodigoItem', null);
            }
        }

        $form = $this->createFormBuilder()
                ->add('TxtCodigoItem', TextType::class, array('label' => 'Item', 'data' => $session->get('filtroCodigoItem')))
                ->add('TxtNombreItem', TextType::class, array('label' => 'NombreItem', 'data' => $strNombreItem))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ITEM')
                ->setCellValue('B1', 'LOTE')
                ->setCellValue('C1', 'BODEGA')
                ->setCellValue('D1', 'EXISTENCIA')
                ->setCellValue('E1', 'CANT REMISIONADA')
                ->setCellValue('F1', 'CANT DISPONIBLE')
                ->setCellValue('G1', 'FECHA VEN');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLote();
        $arLotes = $query->getResult();
        foreach ($arLotes as $arLotes) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLotes->getItemRel()->getNombre())
                    ->setCellValue('B' . $i, $arLotes->getLoteFk())
                    ->setCellValue('C' . $i, $arLotes->getBodegaRel()->getNombre())
                    ->setCellValue('D' . $i, $arLotes->getCantidadExistencia())
                    ->setCellValue('E' . $i, $arLotes->getCantidadRemisionada())
                    ->setCellValue('F' . $i, $arLotes->getCantidadDisponible())
                    ->setCellValue('G' . $i, $arLotes->getFechaVencimiento()->format('Y/m/d'));
            $i++;
        }
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->setTitle('Lotes');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Lotes.xlsx"');
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
