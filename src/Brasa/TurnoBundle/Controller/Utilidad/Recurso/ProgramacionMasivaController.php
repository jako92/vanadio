<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Recurso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProgramacionMasivaController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/tur/utilidad/recurso/programacion/masiva/{anio}/{mes}/{codigoRecurso}", name="brs_tur_utilidad_recurso_programacion_masiva")
     */
    public function detalleAction(Request $request, $anio, $mes, $codigoRecurso) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $form = $this->formularioDetalleEditar();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnGuardar')->isClicked()) {
                    $arrControles = $request->request->All();
                    $resultado = $this->actualizarDetalle($arrControles);
                    if ($resultado == false) {
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
                    }
                }
            }
        }
        $strAnioMes = $anio . "/" . $mes;
        $arrDiaSemana = array();
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $anio, 'mes' => $mes, 'codigoRecursoFk' => $codigoRecurso));
        return $this->render('BrasaTurnoBundle:Utilidades/Recurso:detalle.html.twig', array(
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arRecurso' => $arRecurso,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView(),
                    'anio' => $anio,
                    'mes' => $mes,
            
        ));
    }

    /**
     * @Route("/tur/utilidad/recurso/programacion/masiva/detalle/nuevo/{anio}/{mes}/{codigoRecurso}", name="brs_tur_utilidad_recurso_programacion_masiva_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $anio, $mes, $codigoRecurso) {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
        $strNombrePuesto = "";
        if ($session->get('filtroCodigoPuesto')) {
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($session->get('filtroCodigoPuesto'));
            if ($arPuesto) {
                $strNombrePuesto = $arPuesto->getNombre();
            } else {
                $session->set('filtroCodigoPuesto', null);
            }
        }        
        $form = $this->createFormBuilder()
                ->add('secuenciaDetalleRel', EntityType::class, array(
                    'class' => 'BrasaTurnoBundle:TurSecuenciaDetalle',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                                ->orderBy('s.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => false))
                ->add('TxtNit', TextType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', TextType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))                            
                ->add('TxtCodigoPuesto', TextType::class, array('data' => $session->get('filtroCodigoPuesto')), 'required')
                ->add('TxtNombrePuesto', TextType::class, array('data' => $strNombrePuesto))
                ->add('TxtPosicion', NumberType::class, array('data' => 1))
                ->add('TxtDesde', NumberType::class, array('data' => 1))
                ->add('TxtHasta', NumberType::class, array('data' => $intUltimoDia))                
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar',))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /*if ($form->get('BtnGuardar')->isClicked()) {
                    $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
                    $desde = $form->get('TxtDesde')->getData();
                    $hasta = $form->get('TxtHasta')->getData();
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $arrControles = $request->request->All();
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $intCantidad = $arrControles['TxtCantidad' . $codigo];
                            for ($i = 1; $i <= $intCantidad; $i++) {
                                $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                                $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo);
                                $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                                $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                                $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                                $arProgramacionDetalle->setAnio($arProgramacion->getFecha()->format('Y'));
                                $arProgramacionDetalle->setMes($arProgramacion->getFecha()->format('m'));
                                if ($arRecurso) {
                                    $arProgramacionDetalle->setRecursoRel($arRecurso);
                                }

                                $arSecuenciaDetalle = $form->get('secuenciaDetalleRel')->getData();
                                if ($arSecuenciaDetalle) {
                                    $posicionInicial = $form->get('TxtPosicion')->getData();
                                    $arrSecuenciaDetalle = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->convertirArray($arSecuenciaDetalle);
                                    $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $arProgramacion->getFecha()->format('m') + 1, 1, $arProgramacion->getFecha()->format('Y')) - 1));
                                    $j = 1;
                                    if ($posicionInicial <= $arrSecuenciaDetalle) {
                                        $j = $posicionInicial;
                                    }
                                    for ($i = $desde; $i <= $hasta; $i++) {
                                        if ($i == 1) {
                                            $arProgramacionDetalle->setDia1($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 2) {
                                            $arProgramacionDetalle->setDia2($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 3) {
                                            $arProgramacionDetalle->setDia3($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 4) {
                                            $arProgramacionDetalle->setDia4($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 5) {
                                            $arProgramacionDetalle->setDia5($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 6) {
                                            $arProgramacionDetalle->setDia6($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 7) {
                                            $arProgramacionDetalle->setDia7($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 8) {
                                            $arProgramacionDetalle->setDia8($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 9) {
                                            $arProgramacionDetalle->setDia9($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 10) {
                                            $arProgramacionDetalle->setDia10($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 11) {
                                            $arProgramacionDetalle->setDia11($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 12) {
                                            $arProgramacionDetalle->setDia12($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 13) {
                                            $arProgramacionDetalle->setDia13($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 14) {
                                            $arProgramacionDetalle->setDia14($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 15) {
                                            $arProgramacionDetalle->setDia15($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 16) {
                                            $arProgramacionDetalle->setDia16($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 17) {
                                            $arProgramacionDetalle->setDia17($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 18) {
                                            $arProgramacionDetalle->setDia18($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 19) {
                                            $arProgramacionDetalle->setDia19($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 20) {
                                            $arProgramacionDetalle->setDia20($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 21) {
                                            $arProgramacionDetalle->setDia21($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 22) {
                                            $arProgramacionDetalle->setDia22($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 23) {
                                            $arProgramacionDetalle->setDia23($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 24) {
                                            $arProgramacionDetalle->setDia24($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 25) {
                                            $arProgramacionDetalle->setDia25($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 26) {
                                            $arProgramacionDetalle->setDia26($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 27) {
                                            $arProgramacionDetalle->setDia27($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 28) {
                                            $arProgramacionDetalle->setDia28($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 29) {
                                            $arProgramacionDetalle->setDia29($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 30) {
                                            $arProgramacionDetalle->setDia30($arrSecuenciaDetalle[$j]);
                                        }
                                        if ($i == 31) {
                                            $arProgramacionDetalle->setDia31($arrSecuenciaDetalle[$j]);
                                        }
                                        $j++;
                                        if ($j > $arrSecuenciaDetalle['dias']) {
                                            $j = 1;
                                        }
                                    }
                                }
                                $em->persist($arProgramacionDetalle);
                            }
                        }
                        $em->flush();
                    }
                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                    
                }*/
                if ($request->request->get('OpSeleccionar')) {
                    $codigoPedidoDetalle = $request->request->get('OpSeleccionar');
                    $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                    $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigoPedidoDetalle);
                    $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->findOneBy(array('codigoClienteFk' => $arPedidoDetalle->getPedidoRel()->getCodigoClienteFk(), 'anio' => $anio, 'mes' => $mes));
                    if($arProgramacion) {
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                        $arProgramacionDetalle->setPuestoRel($arPedidoDetalle->getPuestoRel());
                        $arProgramacionDetalle->setAnio($anio);
                        $arProgramacionDetalle->setMes($mes);
                        $arProgramacionDetalle->setRecursoRel($arRecurso);
                        $em->persist($arProgramacionDetalle);
                        $em->flush();
                        //$em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                    
                }
                if ($form->get('BtnFiltrar')->isClicked()) {                
                    $session->set('filtroNit', $form->get('TxtNit')->getData());
                    $session->set('filtroCodigoPuesto', $form->get('TxtCodigoPuesto')->getData());
                    return $this->redirect($this->generateUrl('brs_tur_utilidad_recurso_programacion_masiva_detalle_nuevo', array('anio' => $anio, 'mes' => $mes, 'codigoRecurso' => $codigoRecurso)));
                }                
            }
        }
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaFecha($anio, $mes, $session->get('filtroCodigoCliente'), $session->get('filtroCodigoPuesto'));
        return $this->render('BrasaTurnoBundle:Utilidades/Recurso:detalleNuevo.html.twig', array(
                    'arPedidosDetalle' => $arPedidosDetalle,
                    'form' => $form->createView()));
    }    
    
    private function formularioDetalleEditar() {
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))
                ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
                ->getForm();
        return $form;
    }

    private function devuelveDiaSemanaEspaniol($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }

    private function actualizarDetalle($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $error = false;
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();
        $intIndice = 0;
        $boolTurnosSobrepasados = false;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());
            $validar = $this->validarHoras($intCodigo, $arrControles);
            if ($validar['validado']) {
                $horasDiurnasPendientes = $arPedidoDetalle->getHorasDiurnas() - ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas());
                $horasNocturnasPendientes = $arPedidoDetalle->getHorasNocturnas() - ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas());
                if ($horasDiurnasPendientes >= $validar['horasDiurnas'] || $validarHoras == false) {
                    if ($horasNocturnasPendientes >= $validar['horasNocturnas'] || $validarHoras == false) {
                        $horasDiurnasProgramadas = ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas()) + $validar['horasDiurnas'];
                        $horasNocturnasProgramadas = ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas()) + $validar['horasNocturnas'];
                        $horasProgramadas = $horasDiurnasProgramadas + $horasNocturnasProgramadas;
                        $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnasProgramadas);
                        $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnasProgramadas);
                        $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
                        $em->persist($arPedidoDetalle);

                        if ($arrControles['TxtDia01D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia1($arrControles['TxtDia01D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia1(null);
                        }
                        if ($arrControles['TxtDia02D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia2($arrControles['TxtDia02D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia2(null);
                        }
                        if ($arrControles['TxtDia03D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia3($arrControles['TxtDia03D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia3(null);
                        }
                        if ($arrControles['TxtDia04D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia4($arrControles['TxtDia04D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia4(null);
                        }
                        if ($arrControles['TxtDia05D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia5($arrControles['TxtDia05D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia5(null);
                        }
                        if ($arrControles['TxtDia06D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia6($arrControles['TxtDia06D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia6(null);
                        }
                        if ($arrControles['TxtDia07D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia7($arrControles['TxtDia07D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia7(null);
                        }
                        if ($arrControles['TxtDia08D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia8($arrControles['TxtDia08D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia8(null);
                        }
                        if ($arrControles['TxtDia09D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia9($arrControles['TxtDia09D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia9(null);
                        }
                        if ($arrControles['TxtDia10D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia10($arrControles['TxtDia10D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia10(null);
                        }
                        if ($arrControles['TxtDia11D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia11($arrControles['TxtDia11D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia11(null);
                        }
                        if ($arrControles['TxtDia12D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia12($arrControles['TxtDia12D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia12(null);
                        }
                        if ($arrControles['TxtDia13D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia13($arrControles['TxtDia13D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia13(null);
                        }
                        if ($arrControles['TxtDia14D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia14($arrControles['TxtDia14D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia14(null);
                        }
                        if ($arrControles['TxtDia15D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia15($arrControles['TxtDia15D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia15(null);
                        }
                        if ($arrControles['TxtDia16D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia16($arrControles['TxtDia16D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia16(null);
                        }
                        if ($arrControles['TxtDia17D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia17($arrControles['TxtDia17D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia17(null);
                        }
                        if ($arrControles['TxtDia18D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia18($arrControles['TxtDia18D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia18(null);
                        }
                        if ($arrControles['TxtDia19D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia19($arrControles['TxtDia19D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia19(null);
                        }
                        if ($arrControles['TxtDia20D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia20($arrControles['TxtDia20D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia20(null);
                        }
                        if ($arrControles['TxtDia21D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia21($arrControles['TxtDia21D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia21(null);
                        }
                        if ($arrControles['TxtDia22D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia22($arrControles['TxtDia22D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia22(null);
                        }
                        if ($arrControles['TxtDia23D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia23($arrControles['TxtDia23D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia23(null);
                        }
                        if ($arrControles['TxtDia24D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia24($arrControles['TxtDia24D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia24(null);
                        }
                        if ($arrControles['TxtDia25D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia25($arrControles['TxtDia25D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia25(null);
                        }
                        if ($arrControles['TxtDia26D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia26($arrControles['TxtDia26D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia26(null);
                        }
                        if ($arrControles['TxtDia27D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia27($arrControles['TxtDia27D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia27(null);
                        }
                        if ($arrControles['TxtDia28D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia28($arrControles['TxtDia28D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia28(null);
                        }
                        if ($arrControles['TxtDia29D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia29($arrControles['TxtDia29D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia29(null);
                        }
                        if ($arrControles['TxtDia30D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia30($arrControles['TxtDia30D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia30(null);
                        }
                        if ($arrControles['TxtDia31D' . $intCodigo] != '') {
                            $arProgramacionDetalle->setDia31($arrControles['TxtDia31D' . $intCodigo]);
                        } else {
                            $arProgramacionDetalle->setDia31(null);
                        }
                        $arProgramacionDetalle->setHorasDiurnas($validar['horasDiurnas']);
                        $arProgramacionDetalle->setHorasNocturnas($validar['horasNocturnas']);
                        $arProgramacionDetalle->setHoras($validar['horasDiurnas'] + $validar['horasNocturnas']);
                        $em->persist($arProgramacionDetalle);
                    } else {
                        $error = true;
                        $objMensaje->Mensaje("error", "Horas nocturnas superan las horas del pedido disponibles para programar detalle " . $intCodigo);
                    }
                } else {
                    $error = true;
                    $objMensaje->Mensaje("error", "Horas diurnas superan las horas del pedido disponibles para programar detalle " . $intCodigo);
                }
            } else {
                $error = true;
                $objMensaje->Mensaje("error", $validar['mensaje']);
            }
            if ($error) {
                break;
            }
        }
        return $error;
    }

    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'errado' => false);
        if ($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if ($arTurno) {
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            } else {
                $arrTurno['errado'] = true;
            }
        }

        return $arrTurno;
    }

    private function validarHoras($codigoProgramacionDetalle, $arrControles) {
        $arrDetalle = array('validado' => true, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'mensaje' => '');
        $horasDiurnas = 0;
        $horasNocturnas = 0;
        for ($i = 1; $i <= 31; $i++) {
            $dia = $i;
            if (strlen($dia) < 2) {
                $dia = "0" . $i;
            }
            if ($arrControles['TxtDia' . $dia . 'D' . $codigoProgramacionDetalle] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia' . $dia . 'D' . $codigoProgramacionDetalle]);
                if ($arrTurno['errado'] == true) {
                    $arrDetalle['validado'] = false;
                    $arrDetalle['mensaje'] = "Turno " . $arrControles['TxtDia' . $dia . 'D' . $codigoProgramacionDetalle] . " no esta creado";
                    break;
                }
                $horasDiurnas += $arrTurno['horasDiurnas'];
                $horasNocturnas += $arrTurno['horasNocturnas'];
            }
        }
        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        return $arrDetalle;
    }

}
