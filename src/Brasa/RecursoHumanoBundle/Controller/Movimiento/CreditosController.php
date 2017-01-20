<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCreditoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CreditosController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/creditos/lista", name="brs_rhu_creditos_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 15, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $mensaje = 0;
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        if ($arCreditos->getEstadoPagado() == 1) {
                            $mensaje = "No se puede Eliminar el registro, por que el credito ya esta aprobado o cancelado!";
                        }
                        else {
                            $em->remove($arCreditos);
                            $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $form = $this->formularioLista();
                $this->generarExcel();
            }
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($em, $this->strSqlLista);
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar();
            }
        }
        $arCreditos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Creditos:lista.html.twig', array(
            'arCreditos' => $arCreditos,
            'mensaje' => $mensaje,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/creditos/nuevo/{codigoCredito}", name="brs_rhu_creditos_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCredito = 0) {
        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();    
        if($codigoCredito != 0) {
            $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
        } else {
            $arCredito->setFechaInicio(new \DateTime('now'));
            $arCredito->setFechaCredito(new \DateTime('now'));
            $arCredito->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RhuCreditoType::class, $arCredito);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
            if ($form->get('vrPagar')->getData() == 0 || $form->get('numeroCuotas')->getData() == 0){
                $objMensaje->Mensaje("error", "El total a pagar y/o las cuotas no pueden estar en cero");
            } else {
                $arrControles = $request->request->All();
                $arCredito = $form->getData();
                if($arrControles['form_txtNumeroIdentificacion'] != '') {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                    if(count($arEmpleado) > 0) {                        
                        $arCredito->setEmpleadoRel($arEmpleado);
                        $arCredito->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                        $mensaje = '';
                        if($codigoCredito == 0) {
                            $arCredito->setCodigoUsuario($arUsuario->getUserName());
                            $arCredito->setSaldo($arCredito->getVrPagar());
                        } else {
                            if ($arCredito->getVrPagar() >= $arCredito->getSaldo()){
                                if ($arCredito->getVrPagar() > $arCredito->getTotalPagos()){
                                    $arCredito->setSaldo($arCredito->getVrPagar() - $arCredito->getTotalPagos());
                                } else {
                                    $mensaje = "El valor del credito no puede ser menor al total de pagos";
                                }
                            } else {
                                $mensaje = "El valor del credito no puede ser menor al saldo";
                            }
                                                       
                        }
                        if ($mensaje == ""){
                            $em->persist($arCredito);
                            $em->flush();
                        } else {
                            $objMensaje->Mensaje("error", $mensaje);
                            return $this->redirect($this->generateUrl('brs_rhu_creditos_nuevo', array('codigoCredito' => $codigoCredito )));
                        }
                        
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_creditos_nuevo', array('codigoCredito' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_creditos_lista'));
                        }                                            
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no existe");
                    }                
                }
            }    
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Creditos:nuevo.html.twig', array(
            'arCredito' => $arCredito,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/creditos/detalle/{codigoCredito}", name="brs_rhu_credito_detalle")
     */
    public function detalleAction(Request $request, $codigoCredito) {
        $em = $this->getDoctrine()->getManager();
        
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);        
        $form = $this->formularioDetalle($arCredito);
        $form->handleRequest($request);
        $codigoCreditoFk = $codigoCredito;
        $arCreditoPago = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => $codigoCredito));
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDetalleCredito();
                $objFormatoDetalleCredito->Generar($em, $codigoCreditoFk);
            }
            if($form->get('BtnSuspender')->isClicked()) {
                if ($arCredito->getEstadoSuspendido() == 0){
                    $arCredito->setEstadoSuspendido(1);
                } else {
                    $arCredito->setEstadoSuspendido(0);
                }
                $em->persist($arCredito);
                $em->flush();  
                return $this->redirect($this->generateUrl('brs_rhu_credito_detalle', array('codigoCredito' => $codigoCredito)));
            }                        
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Creditos:detalle.html.twig', array(
                    'arCreditoPago' => $arCreditoPago,
                    'arCreditos' => $arCredito,
                    'form' => $form->createView()
                    ));
    }
    
    /**
     * @Route("/rhu/creditos/detalle/nuevo/{codigoCreditoPk}", name="brs_rhu_credito_nuevo_detalle")
     */
    public function nuevoDetalleAction(Request $request, $codigoCreditoPk) {
        
        $em = $this->getDoctrine()->getManager();
        $mensaje = 0;
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
        $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $form = $this->createFormBuilder()
            ->add('creditoRel', TextType::class, array('data' => $codigoCreditoPk, 'attr' => array('readonly' => 'readonly')))
            ->add('vrCuota', TextType::class, array('data' => round($arCredito->getVrCuota(),2), 'attr' => array('readonly' => 'readonly')))
            ->add('saldo', TextType::class, array('data' => round($arCredito->getSaldo(),2), 'attr' => array('readonly' => 'readonly')))
            ->add('saldoTotal', TextType::class, array('data' => round($arCredito->getSaldo(),2), 'attr' => array('readonly' => 'readonly')))
            ->add('vrAbono',TextType::class)
            ->add('tipoPago', HiddenType::class, array('data' => 'ABONO'))
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($arCredito->getEstadoPagado() == 0){
                $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
                $saldoA = $arCredito->getSaldo();
                $Abono = $form->get('vrAbono')->getData();
                if ($Abono > $arCredito->getSaldo()){
                    $mensaje = "El abono no puede ser superior al saldo!";
                } else {
                    $arCredito->setSaldo($saldoA - $Abono);                    
                    if ($arCredito->getSaldo() <= 0){
                       $arCredito->setEstadoPagado(1);
                    }
                    $nroACuotas = $arCredito->getNumeroCuotaActual();
                    $seguro = $arCredito->getSeguro();
                    $arCredito->setNumeroCuotaActual($nroACuotas + 1);
                    $arPagoCredito->setCreditoRel($arCredito);
                    $arPagoCredito->setvrCuota($form->get('vrAbono')->getData());
                    $arPagoCredito->setfechaPago(new \ DateTime("now"));
                    $arPagoCredito->setCodigoCreditoTipoPagoFk(2);
                    $arPagoCredito->setCreditoRel($arCredito);
                    $em->persist($arPagoCredito);
                    $em->persist($arCredito);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }   
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Creditos:nuevoDetalle.html.twig', array(
            'arPagoCredito' => $arPagoCredito,
            'arCredito' => $arCredito,
            'mensaje' => $mensaje,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/cargar/creditos", name="brs_rhu_cargar_creditos")
     */
    public function cargarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $rutaTemporal = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class)
            ->add('BtnCargar', SubmitType::class, array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "carga.txt");
                $fp = fopen($rutaTemporal->getRutaTemporal()."carga.txt", "r");
                $empleadoSinContrato = "";
                $empleadoNoExiste = "";
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $arrayDetalle = explode(";", $linea);
                        if($arrayDetalle[0] != "") {
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrayDetalle[0]));
                            if(count($arEmpleado) > 0) {
                                $arEmpleadoValidar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrayDetalle[0]));
                                if (count($arEmpleadoValidar) == NULL){
                                    $empleadoSinContrato = "El numero de identificación " .$arrayDetalle[0]. " No tiene contrato";
                                }else{
                                    //Créditos
                                    $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                    $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
                                    $arCreditoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($arrayDetalle[1]);
                                    $arCreditoTipoPago = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipoPago();
                                    $arCreditoTipoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipoPago')->find($arrayDetalle[4]);
                                    $arCredito->setEmpleadoRel($arEmpleado);
                                    $arCredito->setCreditoTipoRel($arCreditoTipo);
                                    $arCredito->setCreditoTipoPagoRel($arCreditoTipoPago);
                                    $arCredito->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                    $intVrCredito = $arrayDetalle[2];
                                    $arCredito->setVrPagar($intVrCredito);
                                    $intCuotas = $arrayDetalle[3];
                                    $arCredito->setNumeroCuotas($intCuotas);
                                    $arCredito->setVrCuota($intVrCredito / $intCuotas);
                                    $dateFecha = $arrayDetalle[5];
                                    $dateFecha = new \DateTime($dateFecha);
                                    $arCredito->setFecha(new \DateTime('now'));
                                    $arCredito->setFechaInicio($dateFecha);
                                    $intVrSeguro = $arrayDetalle[6];
                                    $arCredito->setSeguro($intVrSeguro);
                                    $arCredito->setSaldo($intVrCredito);
                                    $em->persist($arCredito);
                                }
                            }else{
                                $empleadoNoExiste = "El numero de identificación " .$arrayDetalle[0]. " No existe";
                            }
                        }
                    }
                }
                fclose($fp);
                if ($empleadoNoExiste <> ""){
                    $objMensaje->Mensaje("error", "" .$empleadoNoExiste. "");
                }else{
                    if($empleadoSinContrato <> ""){
                        $objMensaje->Mensaje("error", "" .$empleadoSinContrato. "");                        
                    }else{
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                        
                    }
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Creditos:cargarCredito.html.twig', array(
            'form' => $form->createView()
            ));
    }    
    
    /**
     * @Route("/rhu/creditos/refinanciar/{codigoCredito}", name="brs_rhu_creditos_refinanciar")
     */
    public function refinanciarAction(Request $request, $codigoCredito) {
        
        $em = $this->getDoctrine()->getManager();
        $formCredito = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_creditos_refinanciar', array('codigoCredito' => $codigoCredito)))
            ->add('numeroCuotas', TextType::class, array('label'  => 'Numero cuotas'))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $formCredito->handleRequest($request);
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
        if ($formCredito->isValid()) {
            $intCuotas = $formCredito->get('numeroCuotas')->getData();
            $douVrCuota = $arCredito->getSaldo() / $intCuotas;
            $arCredito->setVrCuota($douVrCuota);
            $arCredito->setNumeroCuotaActual(0);
            $arCredito->setNumeroCuotas($intCuotas);
            $em->persist($arCredito);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_credito_detalle', array('codigoCreditoPk' => $codigoCredito)));
        }
        $strErrores = "";
        if($arCredito->getVrCuotaTemporal() > 0) {
            $strErrores = "No se puede refinanciar el credito porque tiene periodos generados pendientes por pagar.";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Creditos:refinanciar.html.twig', array(
            'arCredito' => $arCredito,
            'formCredito' => $formCredito->createView(),
            'errores' => $strErrores
        ));
    }

    private function listar() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->listaCreditoDQL(
                    $session->get('filtroIdentificacion'),
                    "",
                    "",
                    $session->get('filtroRhuCreditoEstadoPagado'),
                    $session->get('filtroRhuCreditoEstadoSuspendido')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {                
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroIdentificacion', null);
            }          
        }
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', TextType::class, array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('estadoPagado', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'PAGADOS' => '1', 'SIN PAGAR' => '0'), 'data' => $session->get('filtroRhuCreditoEstadoPagado')))
            ->add('estadoSuspendido', ChoiceType::class, array('choices'   => array('TODOS' => '2', 'SUSPENDIDOS' => '1', 'SIN SUSPENDER' => '0'), 'data' => $session->get('filtroRhuCreditoEstadoSuspendido')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF',))            
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrBotonSuspender = array('label' => 'Suspender', 'disabled' => false);
        if($ar->getEstadoPagado() == 1) {   
             $arrBotonSuspender['disabled'] = true;
        }
        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir',))
            ->add('BtnSuspender', SubmitType::class, $arrBotonSuspender)
            ->getForm();
        return $form;
    }    
    
    private function filtrarLista($form) {
        $session = new session;
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData()); 
        $session->set('filtroRhuCreditoEstadoPagado', $form->get('estadoPagado')->getData());
        $session->set('filtroRhuCreditoEstadoSuspendido', $form->get('estadoSuspendido')->getData());
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
                for($col = 'A'; $col !== 'O'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
                }     
                for($col = 'G'; $col !== 'M'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }                
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'TIPO CRÉDITO')
                            ->setCellValue('C1', 'FECHA')
                            ->setCellValue('D1', 'IDENTIFICACIÓN')
                            ->setCellValue('E1', 'EMPLEADO')
                            ->setCellValue('F1', 'CENTRO COSTO')                        
                            ->setCellValue('G1', 'CRÉDITO')
                            ->setCellValue('H1', 'CUOTA')
                            ->setCellValue('I1', 'SEGURO')
                            ->setCellValue('J1', 'SALDO')
                            ->setCellValue('K1', 'CUOTAS')
                            ->setCellValue('L1', 'CUOTA ACTUAL')
                            ->setCellValue('M1', 'PAGADO')                            
                            ->setCellValue('N1', 'SUSPENDIDO');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                $arCreditos = $query->getResult();

                foreach ($arCreditos as $arCredito) {                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCredito->getCodigoCreditoPk())
                            ->setCellValue('C' . $i, $arCredito->getFecha())
                            ->setCellValue('D' . $i, $arCredito->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arCredito->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('G' . $i, $arCredito->getVrPagar())
                            ->setCellValue('H' . $i, $arCredito->getVrCuota())
                            ->setCellValue('I' . $i, $arCredito->getSeguro())
                            ->setCellValue('J' . $i, $arCredito->getSaldo())
                            ->setCellValue('K' . $i, $arCredito->getNumeroCuotas())
                            ->setCellValue('L' . $i, $arCredito->getNumeroCuotaActual())
                            ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arCredito->getEstadoPagado()))                            
                            ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arCredito->getEstadoSuspendido()));
                        if ($arCredito->getCodigoCreditoTipoFk()){
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $arCredito->getCreditoTipoRel()->getNombre());
                        }   
                        if ($arCredito->getEmpleadoRel()->getCodigoCentroCostoFk()){
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $arCredito->getEmpleadoRel()->getCentroCostoRel()->getNombre());
                        }                         
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Creditos.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0
                $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                $objWriter->save('php://output');
                exit;
            }

}
