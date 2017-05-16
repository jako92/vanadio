<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CertificadoIngresoRetencionController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/rhu/utilidades/certificado/ingreso/retencion", name="brs_rhu_utilidades_certificado_ingreso_retencion")
     */
    public function CertificadoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 84)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator = $this->get('knp_paginator');
        $formCertificado = $this->formularioLista();
        $formCertificado->handleRequest($request);
        if ($formCertificado->isValid()) {
            $controles = $request->request->get('form');
            if ($formCertificado->get('BtnGenerarExcel')->isClicked()) {
                $this->generarExcel($controles, $formCertificado);
            }
            if ($formCertificado->get('BtnGenerar')->isClicked()) {
                if ($formCertificado->get('txtIdentificacion')->getData() == "" && $formCertificado->get('centroCostoRel')->getData() == "") {
                    $objMensaje->Mensaje("error", "Por favor ingresar el número de identificación o el código del centro de costo para generar el certificado de ingresos y retenciones!");
                } else {
                    if ($formCertificado->get('txtIdentificacion')->getData() != "") {
                        $empleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $empleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $formCertificado->get('txtIdentificacion')->getData()));
                        if (count($empleado) == 0) {
                            $objMensaje->Mensaje("error", "No existe el empleado con el número de identificación " . $controles['txtIdentificacion'] . "");
                        } else {
                            $codigoEmpleado = $empleado->getCodigoEmpleadoPk();
                            $strFechaExpedicion = $formCertificado->get('fechaExpedicion')->getData();
                            $strLugarExpedicion = $controles['LugarExpedicion'];
                            $strFechaCertificado = $controles['fechaCertificado'];
                            $strAfc = $controles['afc'];
                            $stCertifico1 = $controles['certifico1'];
                            $stCertifico2 = $controles['certifico2'];
                            $stCertifico3 = $controles['certifico3'];
                            $stCertifico4 = $controles['certifico4'];
                            $stCertifico5 = $controles['certifico5'];
                            $stCertifico6 = $controles['certifico6'];
                            $datFechaCertificadoInicio = $strFechaCertificado . "-01-01";
                            $datFechaCertificadoFin = $strFechaCertificado . "-12-30";
                            $arrayCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $arrayInteresesCesantiasPagadas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveInteresesCesantiasFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floInteresesCesantiasPagadas = (float) $arrayInteresesCesantiasPagadas[0]['Neto'];
                            $arrayPrimasPagadas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelvePrimasFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floPrimasPagadas = (float) $arrayPrimasPagadas[0]['Neto'];
                            $floPrestacional = (float) $arrayCostos[0]['Prestacional'];
                            $douOtrosIngresos = (float) $arrayCostos[0]['NoPrestacional'];
                            $floAuxTransporte = 0;
                            $arConceptosAuxTransporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoAuxilioTransporte' => 1));
                            foreach ($arConceptosAuxTransporte as $arConceptosAuxTransporte) {
                                $codigoConcepto = $arConceptosAuxTransporte->getCodigoPagoConceptoPk();
                                $arrayAuxTransporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->auxTransporteCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $floAuxTransporte += $arrayAuxTransporte;
                            }
                            $floSalud = 0;
                            $arConceptosSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoSalud' => 1));
                            foreach ($arConceptosSalud as $arConceptosSalud) {
                                $codigoConcepto = $arConceptosSalud->getCodigoPagoConceptoPk();
                                $ValorSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->saludCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $floSalud += $ValorSalud;
                            }
                            $floPension = 0;
                            $arConceptosPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoPension' => 1));
                            foreach ($arConceptosPension as $arConceptosPension) {
                                $codigoConcepto = $arConceptosPension->getCodigoPagoConceptoPk();
                                $ValorPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->saludCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $floPension += $ValorPension;
                            }
                            $datFechaInicio = $arrayCostos[0]['fechaInicio'];
                            $datFechaFin = $arrayCostos[0]['fechaFin'];
                            $arrayPrestacionesSociales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelvePrestacionesSocialesFecha($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floCesantiaseInteresesLiquidadas = (float) $arrayPrestacionesSociales[0]['Cesantias'] + $arrayPrestacionesSociales[0]['CesantiasAnterior'] + $arrayPrestacionesSociales[0]['InteresesCesantias'] + $arrayPrestacionesSociales[0]['InteresesCesantiasAnterior'];
                            $floPrimaLiquidadas = (float) $arrayPrestacionesSociales[0]['Prima'];
                            $floVacacionesLiquidadas = (float) $arrayPrestacionesSociales[0]['Vacaciones'];
                            $arrayVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->devuelveVacacionesFecha($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floVacacionesPagadas = (float) $arrayVacaciones[0]['Vacaciones'];
                            $douRetencion = 0;
                            $arConceptosRetencion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoFondoSolidaridadPensional' => 1));
                            foreach ($arConceptosRetencion as $arConceptosRetencion) {
                                $codigoConcepto = $arConceptosRetencion->getCodigoPagoConceptoPk();
                                $ValorRetencion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveRetencionFuenteEmpleadoFecha($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $douRetencion += $ValorRetencion;
                            }
                            $duoGestosRepresentacion = 0;
                            $totalCesantiaseIntereses = $floInteresesCesantiasPagadas + $floCesantiaseInteresesLiquidadas;
                            $totalPrestacional = $floPrestacional + $floPrimasPagadas + $floAuxTransporte + $floPrimaLiquidadas + $floVacacionesLiquidadas + $floVacacionesPagadas;
                            $duoTotalIngresos = $duoGestosRepresentacion + $douOtrosIngresos + $totalPrestacional + $totalCesantiaseIntereses;
                            $strRuta = "";
                            if ($floPrestacional > 0) {
                                $objFormatoCertificadoIngreso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCertificadoIngreso();
                                $objFormatoCertificadoIngreso->Generar($em, $codigoEmpleado, $strFechaExpedicion, $strLugarExpedicion, $strFechaCertificado, $strAfc, $stCertifico1, $stCertifico2, $stCertifico3, $stCertifico4, $stCertifico5, $stCertifico6, $totalPrestacional, $floPension, $floSalud, $datFechaInicio, $datFechaFin, $totalCesantiaseIntereses, $douRetencion, $duoGestosRepresentacion, $douOtrosIngresos, $duoTotalIngresos, $strRuta);
                            } else {
                                $objMensaje->Mensaje("error", "Este empleado no registra información de ingresos  y retenciones para el año " . $strFechaCertificado . "");
                            }
                        }
                    } else {
                        $empleadosCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->createQueryBuilder('c')
                                ->where('c.codigoCentroCostoFk = :centroCosto')
                                ->andWhere('c.fechaDesde LIKE :fechaDesde')
                                ->andWhere('c.fechaHasta LIKE :fechaHasta')
                                ->setParameter('centroCosto', $controles['centroCostoRel'])
                                ->setParameter('fechaDesde', '%' . $controles['fechaCertificado'] . '%')
                                ->setParameter('fechaHasta', '%' . $controles['fechaCertificado'] . '%')
                                ->getQuery()
                                ->getResult();
                        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                        $strRutaGeneral = $arConfiguracion->getRutaTemporal();
                        if (!file_exists($strRutaGeneral)) {
                            mkdir($strRutaGeneral, 0777);
                        }
                        $strRuta = $strRutaGeneral . "CertificadoIngresoRetencion/";
                        if (!file_exists($strRuta)) {
                            mkdir($strRuta, 0777);
                        }
                        foreach ($empleadosCentroCosto as $empleadoCentroCosto) {
                            $codigoEmpleado = $empleadoCentroCosto->getCodigoEmpleadoFk();
                            $strFechaExpedicion = $formCertificado->get('fechaExpedicion')->getData();
                            $strLugarExpedicion = $controles['LugarExpedicion'];
                            $strFechaCertificado = $controles['fechaCertificado'];
                            $strAfc = $controles['afc'];
                            $stCertifico1 = $controles['certifico1'];
                            $stCertifico2 = $controles['certifico2'];
                            $stCertifico3 = $controles['certifico3'];
                            $stCertifico4 = $controles['certifico4'];
                            $stCertifico5 = $controles['certifico5'];
                            $stCertifico6 = $controles['certifico6'];
                            $datFechaCertificadoInicio = $strFechaCertificado . "-01-01";
                            $datFechaCertificadoFin = $strFechaCertificado . "-12-30";
                            $arrayCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $arrayInteresesCesantiasPagadas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveInteresesCesantiasFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floInteresesCesantiasPagadas = (float) $arrayInteresesCesantiasPagadas[0]['Neto'];
                            $arrayPrimasPagadas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelvePrimasFechaCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floPrimasPagadas = (float) $arrayPrimasPagadas[0]['Neto'];
                            $floPrestacional = (float) $arrayCostos[0]['Prestacional'];
                            $floAuxTransporte = 0;
                            $arConceptosAuxTransporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoAuxilioTransporte' => 1));
                            foreach ($arConceptosAuxTransporte as $arConceptosAuxTransporte) {
                                $codigoConcepto = $arConceptosAuxTransporte->getCodigoPagoConceptoPk();
                                $arrayAuxTransporte = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->auxTransporteCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $floAuxTransporte += $arrayAuxTransporte;
                            }
                            $floSalud = 0;
                            $arConceptosSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoSalud' => 1));
                            foreach ($arConceptosSalud as $arConceptosSalud) {
                                $codigoConcepto = $arConceptosSalud->getCodigoPagoConceptoPk();
                                $ValorSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->saludCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $floSalud += $ValorSalud;
                            }
                            $floPension = 0;
                            $arConceptosPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoPension' => 1));
                            foreach ($arConceptosPension as $arConceptosPension) {
                                $codigoConcepto = $arConceptosPension->getCodigoPagoConceptoPk();
                                $ValorPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->saludCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $floPension += $ValorPension;
                            }
                            $datFechaInicio = $arrayCostos[0]['fechaInicio'];
                            $datFechaFin = $arrayCostos[0]['fechaFin'];
                            $douOtrosIngresos = (float) $arrayCostos[0]['NoPrestacional'];
                            $arrayPrestacionesSociales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelvePrestacionesSocialesFecha($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floCesantiaseInteresesLiquidadas = (float) $arrayPrestacionesSociales[0]['Cesantias'] + $arrayPrestacionesSociales[0]['InteresesCesantias'] + $arrayPrestacionesSociales[0]['CesantiasAnterior'] + $arrayPrestacionesSociales[0]['InteresesCesantiasAnterior'];
                            $floPrimaLiquidadas = (float) $arrayPrestacionesSociales[0]['Prima'];
                            $floVacacionesLiquidadas = (float) $arrayPrestacionesSociales[0]['Vacaciones'];
                            $arrayVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->devuelveVacacionesFecha($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floVacacionesPagadas = (float) $arrayVacaciones[0]['Vacaciones'];
                            $douRetencion = 0;
                            $arConceptosRetencion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoFondoSolidaridadPensional' => 1));
                            foreach ($arConceptosRetencion as $arConceptosRetencion) {
                                $codigoConcepto = $arConceptosRetencion->getCodigoPagoConceptoPk();
                                $ValorRetencion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveRetencionFuenteEmpleadoFecha($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                                $douRetencion += $ValorRetencion;
                            }
                            $duoGestosRepresentacion = 0;
                            $totalCesantiaseIntereses = $floInteresesCesantiasPagadas + $floCesantiaseInteresesLiquidadas;
                            $totalPrestacional = $floPrestacional + $floPrimasPagadas + $floAuxTransporte + $floPrimaLiquidadas + $floVacacionesLiquidadas + $floVacacionesPagadas;
                            $duoTotalIngresos = $duoGestosRepresentacion + $douOtrosIngresos + $totalPrestacional + $totalCesantiaseIntereses;


                            //$strRutaGeneral = "C:\p";
                            //$strRuta = "C:\p";
                            if ($floPrestacional > 0) {
                                $objFormatoCertificadoIngreso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCertificadoIngreso();
                                $objFormatoCertificadoIngreso->Generar($em, $codigoEmpleado, $strFechaExpedicion, $strLugarExpedicion, $strFechaCertificado, $strAfc, $stCertifico1, $stCertifico2, $stCertifico3, $stCertifico4, $stCertifico5, $stCertifico6, $totalPrestacional, $floPension, $floSalud, $datFechaInicio, $datFechaFin, $totalCesantiaseIntereses, $douRetencion, $duoGestosRepresentacion, $douOtrosIngresos, $duoTotalIngresos, $strRuta);
                            }
                        }
                        $strRutaZip = $strRutaGeneral . 'Certificado.zip';
                        $this->comprimir($strRuta, $strRutaZip);
                        $dir = opendir($strRuta);
                        while ($current = readdir($dir)) {
                            if ($current != "." && $current != "..") {
                                unlink($strRuta . $current);
                            }
                        }
                        rmdir($strRuta);

                        $strArchivo = $strRutaZip;
                        header('Content-Description: File Transfer');
                        header('Content-Type: text/csv; charset=ISO-8859-15');
                        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($strArchivo));
                        readfile($strArchivo);
                        unlink($strRutaZip);
                    }
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/CertificadoIngresoRetencion:Certificado.html.twig', array(
                    'formCertificado' => $formCertificado->createView()));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $ConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $ConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $fechaActual = date('Y-m-j');
        $anioActual = date('Y');
        $fechaPrimeraAnterior = strtotime('-1 year', strtotime($fechaActual));
        $fechaPrimeraAnterior = date('Y', $fechaPrimeraAnterior);
        $fechaSegundaAnterior = strtotime('-2 year', strtotime($fechaActual));
        $fechaSegundaAnterior = date('Y', $fechaSegundaAnterior);
        $fechaTerceraAnterior = strtotime('-3 year', strtotime($fechaActual));
        $fechaTerceraAnterior = date('Y', $fechaTerceraAnterior);
        $arrayPropiedades = array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
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
        $formCertificado = $this->createFormBuilder()
                ->add('txtIdentificacion', TextType::class, array('data' => '', 'required' => false))
                ->add('centroCostoRel', EntityType::class, $arrayPropiedades)
                ->add('fechaCertificado', ChoiceType::class, array('choices' => array($anioActual = date('Y') => $anioActual = date('Y'), $fechaPrimeraAnterior => $fechaPrimeraAnterior, $fechaSegundaAnterior => $fechaSegundaAnterior, $fechaTerceraAnterior => $fechaTerceraAnterior),))
                ->add('fechaExpedicion', DateType::class, array('data' => new \ DateTime('now')))
                ->add('LugarExpedicion', EntityType::class, array(
                    'class' => 'BrasaGeneralBundle:GenCiudad',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => true))
                ->add('afc', NumberType::class, array('data' => '0', 'required' => false))
                ->add('certifico1', TextType::class, array('data' => '1. Mi patrimonio bruto era igual o inferior a 4.500 UVT ($123.683.000)', 'required' => true))
                ->add('certifico2', TextType::class, array('data' => '2. No fui responsable del impuesto sobre las ventas', 'required' => true))
                ->add('certifico3', TextType::class, array('data' => '3. Mis ingresos totales fueron iguales o inferiores a 1.400 UVT ($38.479.000)', 'required' => true))
                ->add('certifico4', TextType::class, array('data' => '4. Mis consumos mediante tarjeta de crédito no excedieron la suma de 2.800 UVT ($76.958.000)', 'required' => true))
                ->add('certifico5', TextType::class, array('data' => '5. Quen el total de mis compras y consumos no superaron la suma de 2.800 UVT ($76.958.000)', 'required' => true))
                ->add('certifico6', TextType::class, array('data' => '6. Que el valor total de mis consignaciones bancarias, depósitos o inversiones financieras no excedieron la suma de 4.500 UVT ($123.683.000)', 'required' => true))
                ->add('BtnGenerarExcel', SubmitType::class, array('label' => 'Generar excel'))
                ->add('BtnGenerar', SubmitType::class, array('label' => 'Generar'))
                ->getForm();
        return $formCertificado;
    }

    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false, $archivo = "") {

        /* Declara el handle del objeto */
        if (!$handle) {
            $handle = new \ZipArchive();
            if ($handle->open($zip_salida, ZipArchive::CREATE) === false) {
                return false; /* Imposible crear el archivo ZIP */
            }
        }

        /* Procesa directorio */
        if (is_dir($ruta)) {
            /* Aseguramos que sea un directorio sin carácteres corruptos */
            $ruta = dirname($ruta . '/arch.ext');
            $handle->addEmptyDir($ruta); /* Agrega el directorio comprimido */
            $dir = opendir($ruta);
            while ($current = readdir($dir)) {
                if ($current != "." && $current != "..") {
                    $this->comprimir($ruta . "/" . $current, $zip_salida, $handle, true, $current); /* Comprime el subdirectorio o archivo */
                }
            }
            //foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
            //$this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            //}

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta, $archivo);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

    private function generarExcel($controles, $formCertificado) {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for ($col = 'A'; $col !== 'BA'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Tipo de documento del empleado')
                ->setCellValue('B1', 'Número identificación del empleado')
                ->setCellValue('C1', 'Primer apellido del informado')
                ->setCellValue('D1', 'Segundo apellido del informado')
                ->setCellValue('E1', 'Primer nombre del informado')
                ->setCellValue('F1', 'Otros nombres del informado')
                ->setCellValue('G1', 'Fecha inicial periodo de certificación')
                ->setCellValue('H1', 'Fecha Final Periodo de certificación')
                ->setCellValue('I1', 'Fecha de Expedición del certificado')
                ->setCellValue('J1', 'Departamento donde se practicó la retención')
                ->setCellValue('K1', 'Municipio donde se practicó la retención')
                ->setCellValue('L1', 'Número entidades que consolidan la retención')
                ->setCellValue('M1', 'Pagos al Empleado')
                ->setCellValue('N1', 'Cesantías e Intereses pagadas periodo')
                ->setCellValue('O1', 'Gastos de Representación')
                ->setCellValue('P1', 'Pensiones de Jubilación, vejez o invalidez')
                ->setCellValue('Q1', 'Otros Ingresos como empleado')
                ->setCellValue('R1', 'Total Ingresos Brutos')
                ->setCellValue('S1', 'Aportes Obligatorios por salud')
                ->setCellValue('T1', 'Aportes Obligatorios a fondos de pensiones y solidaridad pensional')
                ->setCellValue('U1', 'Aportes Voluntarios a fondos de pensiones cuentas AFC')
                ->setCellValue('V1', 'Valores de las retenciones en la fuente por pago al empleado');
        $i = 2;
        $strFechaCertificado = $controles['fechaCertificado'];
        $datFechaCertificadoInicio = $strFechaCertificado . "-01-01";
        $datFechaCertificadoFin = $strFechaCertificado . "-12-31";
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->createQueryBuilder('p')
                ->select('p.codigoEmpleadoFk')
                ->where('p.fechaDesde >= :fechaDesde')
                ->andWhere('p.fechaDesde <= :fechaHasta')
                ->setParameter('fechaDesde', '' . $datFechaCertificadoInicio . '')
                ->setParameter('fechaHasta', '' . $datFechaCertificadoFin . '')
                ->groupBy('p.codigoEmpleadoFk')
                ->getQuery();
        $arPagos = $query->getResult();
        $arCiudad = $em->getRepository('BrasaGeneralBundle:GenCiudad')->find($controles['LugarExpedicion']);
        foreach ($arPagos as $arPago) {
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPago['codigoEmpleadoFk']);
            $codigoEmpleado = $arEmpleado->getCodigoEmpleadoPk();
            $strFechaExpedicion = $formCertificado->get('fechaExpedicion')->getData();
            $totalSalud = 0;
            $arConceptosSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoSalud' => 1));
            foreach ($arConceptosSalud as $arConceptosSalud) {
                $codigoConcepto = $arConceptosSalud->getCodigoPagoConceptoPk();
                $ValorSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->saludCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                $totalSalud += $ValorSalud;
            }
            $totalPension = 0;
            $arConceptosPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('conceptoPension' => 1));
            foreach ($arConceptosPension as $arConceptosPension) {
                $codigoConcepto = $arConceptosPension->getCodigoPagoConceptoPk();
                $ValorPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->saludCertificadoIngreso($datFechaCertificadoInicio, $datFechaCertificadoFin, $codigoEmpleado, $codigoConcepto);
                $totalPension += $ValorPension;
            }
            $pagoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->prestacionalCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
            $otroIngreso = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->noPrestacionalCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
            $valorPrimasPagadas = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->primaPagadasCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
            $totalRetencionFuente = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->retencionFuenteCertificadoIngreso($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
            $arrayPrestacionesSociales = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelvePrestacionesSocialesFecha($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
            $floCesantiaseInteresesLiquidadas = (float) $arrayPrestacionesSociales[0]['Cesantias'] + $arrayPrestacionesSociales[0]['InteresesCesantias'] + $arrayPrestacionesSociales[0]['CesantiasAnterior'] + $arrayPrestacionesSociales[0]['InteresesCesantiasAnterior'];
            $valorPrimaLiquidadas = (float) $arrayPrestacionesSociales[0]['Prima'];
            $floVacacionesLiquidadas = (float) $arrayPrestacionesSociales[0]['Vacaciones'];
            //Consultar si tiene acumulado de meses anteriores.
            $arAcumuladoIngresos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCertificadoIngresoAcumulado')->findOneBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'periodo' => $strFechaCertificado));
            if ($arAcumuladoIngresos) {
                $pagoEmpleado += $arAcumuladoIngresos->getAcumuladoIbp();
                $totalSalud += $arAcumuladoIngresos->getAcumuladoSalud();
                $totalPension += $arAcumuladoIngresos->getAcumuladoPension();
            }
            $arrayVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->devuelveVacacionesFecha($codigoEmpleado, $datFechaCertificadoInicio, $datFechaCertificadoFin);
            $valorVacacaciones = (float) $arrayVacaciones[0]['Vacaciones'];
            $totalPagoEmpleado = $pagoEmpleado + $valorVacacaciones + $valorPrimasPagadas + $valorPrimaLiquidadas + $floVacacionesLiquidadas;
            $ingresoBruto = $totalPagoEmpleado + $otroIngreso;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoTipoIdentificacionFk())
                    ->setCellValue('B' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arEmpleado->getApellido1())
                    ->setCellValue('D' . $i, $arEmpleado->getApellido2())
                    ->setCellValue('E' . $i, $arEmpleado->getNombre1())
                    ->setCellValue('F' . $i, $arEmpleado->getNombre2())
                    ->setCellValue('G' . $i, $controles['fechaCertificado'] . '-01-01')
                    ->setCellValue('H' . $i, $controles['fechaCertificado'] . '-12-30')
                    ->setCellValue('I' . $i, $strFechaExpedicion)
                    ->setCellValue('J' . $i, substr($arCiudad->getCodigoInterface(), 0, 2))
                    ->setCellValue('K' . $i, substr($arCiudad->getCodigoInterface(), 2, 8))
                    ->setCellValue('M' . $i, round($totalPagoEmpleado))
                    ->setCellValue('N' . $i, round($floCesantiaseInteresesLiquidadas))
                    ->setCellValue('O' . $i, round(0))
                    ->setCellValue('P' . $i, round(0))
                    ->setCellValue('Q' . $i, round($otroIngreso))
                    ->setCellValue('R' . $i, round($ingresoBruto))
                    ->setCellValue('S' . $i, round($totalSalud))
                    ->setCellValue('T' . $i, round($totalPension))
                    ->setCellValue('U' . $i, round(0))
                    ->setCellValue('V' . $i, round($totalRetencionFuente));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Certificado');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Certificado.xlsx"');
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
        ini_set('memory_limit', '512m');
        set_time_limit(60);
    }

}
