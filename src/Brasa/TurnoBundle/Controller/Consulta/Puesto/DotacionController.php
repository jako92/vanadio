<?php

namespace Brasa\TurnoBundle\Controller\Consulta\Puesto;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DotacionController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/tur/consulta/puesto/dotacion", name="brs_tur_consulta_puesto_dotacion")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arPuestoDotacion = new \Brasa\TurnoBundle\Entity\TurPuestoDotacion();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $this->lista();
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $this->lista();
                    $this->generarExcel();
                }
            }
        }
        $arPuestoDotacion = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaTurnoBundle:Consultas/Puesto:dotacion.html.twig', array(
                    'arPuestoDotacion' => $arPuestoDotacion,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new Session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPuestoDotacion')->ListaDqlConsulta($session->get('filtroCodigoCliente'));
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroCodigoCliente', $form->get('TxtNit')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroCodigoCliente')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($session->get('filtroCodigoCliente'));
            if ($arCliente) {
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
            }
        }
        $form = $this->createFormBuilder()
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroCodigoCliente')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("DOTACIONPUESTO")
                ->setLastModifiedBy("DOTACIONPUESTO")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'PUESTO')
                ->setCellValue('C1', 'CLIENTE')
                ->setCellValue('D1', 'ELEMENTO DOTACION')
                ->setCellValue('E1', 'CANTIDAD')
                ->setCellValue('F1', 'COSTO')
                ->setCellValue('G1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPuestoDotaciones = new \Brasa\TurnoBundle\Entity\TurPuestoDotacion();
        $arPuestoDotaciones = $query->getResult();

        foreach ($arPuestoDotaciones as $arPuestoDotacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPuestoDotacion->getCodigoPuestoDotacionPk())
                    ->setCellValue('B' . $i, $arPuestoDotacion->getPuestoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPuestoDotacion->getClienteRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arPuestoDotacion->getElementoDotacionRel()->getNombre())
                    ->setCellValue('E' . $i, $arPuestoDotacion->getCantidad())
                    ->setCellValue('F' . $i, $arPuestoDotacion->getCosto())
                    ->setCellValue('G' . $i, $arPuestoDotacion->getTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Dotacion Puesto');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Dotacion Puesto.xlsx"');
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
