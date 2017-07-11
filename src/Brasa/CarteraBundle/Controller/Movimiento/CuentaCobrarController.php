<?php

namespace Brasa\CarteraBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Brasa\CarteraBundle\Form\Type\CarCuentaCobrarType;

//use Brasa\CarteraBundle\Form\Type\CarCuentaCobrarDetalleType;

class CuentaCobrarController extends Controller {

    var $strListaDql = "";
    var $numero = "";
    var $codigoCliente = "";
    var $estadoAutorizado = "";

    /**
     * @Route("/cartera/movimiento/cuentacobrar/lista", name="brs_cartera_movimiento_cuentacobrar_listar")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $session = new session;
        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 114, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /* if ($form->get('BtnEliminar')->isClicked()) {
                  $arrSeleccionados = $request->request->get('ChkSeleccionar');
                  $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->eliminar($arrSeleccionados);
                  return $this->redirect($this->generateUrl('brs_cartera_movimiento_cuentacobrar_listar'));
                  } */
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                    $this->lista();
                }
                if ($form->get('BtnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                    $this->lista();
                    $this->generarExcel();
                }
            }
        }
        $arCuentasCobrar = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/CuentaCobrar:lista.html.twig', array(
                    'arCuentasCobrar' => $arCuentasCobrar,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/cuentacobrar/nuevo/{codigoCuentaCobrar}", name="brs_cartera_movimiento_cuentacobrar_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCuentaCobrar) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($codigoCuentaCobrar);
        $form = $this->createForm(CarCuentaCobrarType::class, $arCuentasCobrar);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $arCuentasCobrar = $form->getData();
                if ($arCuentasCobrar->getCuentaCobrarTipoRel()->getsaldoInicial() == 1) {
                    if ($arCuentasCobrar->getValorOriginal() > $arCuentasCobrar->getAbono()) {
                        $saldo = $arCuentasCobrar->getValorOriginal() - $arCuentasCobrar->getAbono();
                        $saldoOperado = $saldo * $arCuentasCobrar->getOperacion();
                        $arCuentasCobrar->setSaldoOperado($saldoOperado);
                        $arCuentasCobrar->setSaldo($saldo);
                        $em->persist($arCuentasCobrar);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_cuentacobrar_listar'));
                    } else {
                        $objMensaje->Mensaje('error', 'El valor del saldo inicial no puede ser menor al valor del abono');
                    }
                } else {
                    $objMensaje->Mensaje('error', 'Solo se pueden editar los saldos iniciales');
                }
            }
        }
        return $this->render('BrasaCarteraBundle:Movimientos/CuentaCobrar:nuevo.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->listaDQL(
                $session->get('filtroCuentaCobrarNumero'), $session->get('filtroCodigoCliente'), $session->get('filtroCuentaCobrarTipo'));
    }

    private function filtrar($form) {
        $session = new session;
        $arCuentaCobrarTipo = $form->get('cuentaCobrarTipoRel')->getData();
        if ($arCuentaCobrarTipo == null) {
            $codigo = "";
        } else {
            $codigo = $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk();
        }
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroCuentaCobrarNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCuentaCobrarTipo', $codigo);
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $arrayPropiedades = array(
            'class' => 'BrasaCarteraBundle:CarCuentaCobrarTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                                ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroCuentaCobrarTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarCuentaCobrarTipo", $session->get('filtroCuentaCobrarTipo'));
        }
        $form = $this->createFormBuilder()
                ->add('TxtNumero', TextType::class, array('label' => 'Codigo', 'data' => $session->get('filtroCuentaCobrarNumero')))
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('cuentaCobrarTipoRel', EntityType::class, $arrayPropiedades)
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'I'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'NUMERO')
                ->setCellValue('C1', 'TIPO')
                ->setCellValue('D1', 'FECHA')
                ->setCellValue('E1', 'VENCE')
                ->setCellValue('F1', 'NIT')
                ->setCellValue('G1', 'CLIENTE')
                ->setCellValue('H1', 'ASESOR')
                ->setCellValue('I1', 'VALOR')
                ->setCellValue('J1', 'SALDO')
                ->setCellValue('K1', 'PLAZO')
                ->setCellValue('L1', 'ABONO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $query->getResult();

        foreach ($arCuentasCobrar as $arCuentasCobrar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentasCobrar->getCodigoCuentaCobrarPk())
                    ->setCellValue('B' . $i, $arCuentasCobrar->getNumeroDocumento())
                    ->setCellValue('D' . $i, $arCuentasCobrar->getFecha()->format('Y-m-d'))
                    ->setCellValue('E' . $i, $arCuentasCobrar->getFechaVence()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arCuentasCobrar->getValorOriginal())
                    ->setCellValue('J' . $i, $arCuentasCobrar->getSaldo())
                    ->setCellValue('K' . $i, $arCuentasCobrar->getPlazo())
                    ->setCellValue('L' . $i, $arCuentasCobrar->getAbono());
            if ($arCuentasCobrar->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $arCuentasCobrar->getClienteRel()->getNit());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $arCuentasCobrar->getClienteRel()->getNombreCorto());
            }
            if ($arCuentasCobrar->getAsesorRel()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arCuentasCobrar->getAsesorRel()->getNombre());
            }
            if ($arCuentasCobrar->getCuentaCobrarTipoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $arCuentasCobrar->getCuentaCobrarTipoRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentasCobrar');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentasCobrar.xlsx"');
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
