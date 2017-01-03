<?php

namespace Brasa\TurnoBundle\Formatos;

class Factura5 extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($em, $codigoFactura) { //galaxia

        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $valor = round($arFactura->getVrTotalNeto());
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($valor);
        self::$strLetras = $strLetras;
        ob_clean();        
        $pdf = new Factura5('P','mm', 'letter'); //galaxia
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {
        
        $this->GenerarEncabezadoFactura(self::$em); 
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
                        
        
        $this->SetXY(10, 43);        
        $this->Cell(138, 5, $arFactura->getClienteRel()->getNombreCorto(), 0, 0, 'L', 0);
        
        $this->SetXY(10, 48);        
        $this->Cell(138, 5, $arFactura->getClienteRel()->getDireccion(), 0, 0, 'L', 0);
        $this->SetXY(10, 53);        
        $this->Cell(138, 5, "CIUDAD: " . $arFactura->getClienteRel()->getCiudadRel()->getNombre(), 0, 0, 'L', 0);
        $this->SetXY(10, 58);        
        $this->Cell(138, 5, "TELEFONOS: " . $arFactura->getClienteRel()->getTelefono(), 0, 0, 'L', 0);
 
        $this->SetXY(150, 43);        
        $this->Cell(61, 5, "NIT/CEDULA: " . $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(), 0, 0, 'L', 0);               
        $this->SetXY(150, 48);        
        $this->Cell(61, 5, "CEDULA: " . $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(), 0, 0, 'L', 0);               
        $this->SetXY(150, 53);        
        $this->Cell(61, 5, "FECHA VENCIMIENTO: " . $arFactura->getFechaVence()->format('d/m/Y'), 0, 0, 'L', 0);                       
        $this->SetXY(150, 58);        
        $this->Cell(61, 5, "", 0, 0, 'L', 0);                       
        
        $this->SetXY(110, 62);
        $this->SetMargins(5, 1, 5);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $this->SetX(10);
        $header = array('DESCRIPCION', 'CANT', 'VR. UNITARIO', 'IVA','VR. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', '', 8);

        //creamos la cabecera de la tabla.
        $w = array(130, 8, 21, 21,21);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L',0);
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C',0);

        //Restauración de colores y fuentes
        //$this->SetFillColor(224, 235, 255);
        //$this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(8);
    }

    public function Body($pdf) {
        
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
                
        $pdf->Ln(0);
        $pdf->SetFont('Arial', '', 8);
        if($arFactura->getImprimirRelacion() == false) {
            if($arFactura->getImprimirAgrupada() == 0) {
                foreach ($arFacturaDetalles as $arFacturaDetalle) {
                    $pdf->SetX(10);
                    if($arFacturaDetalle->getCodigoModalidadServicioFk()) {
                        $modalidad = "-" . utf8_decode($arFacturaDetalle->getModalidadServicioRel()->getNombre());
                    }
                    $pdf->Cell(130, 4, substr(utf8_decode($arFacturaDetalle->getPuestoRel()->getNombre()) . $modalidad, 0, 61), 0, 0, 'L');                    
                    $modalidad = "";                    
                    $pdf->Cell(8, 4, number_format($arFacturaDetalle->getCantidad(), 1, '.', ','), 0, 0, 'C');                    
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(21, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(21, 4, number_format($arFacturaDetalle->getPorIva(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(21, 4, number_format($arFacturaDetalle->getSubtotal(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(10);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');
                    if($arFacturaDetalle->getTipoPedido() == 'FIJO') {
                        $strCampo = $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacion() . " " . $arFacturaDetalle->getDetalle();
                    } else {
                        $strCampo = $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacionAdicional() . " " . $arFacturaDetalle->getDetalle();
                    }

                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(-1);
                    $pdf->SetAutoPageBreak(true, 86);
                }
            } /*else {
                $strSql = "SELECT tur_puesto.nombre AS puesto, tur_modalidad_servicio.nombre AS modalidadServicio, tur_concepto_servicio.nombre_facturacion AS conceptoServicio, cantidad  AS cantidad, vr_precio AS precio
                            FROM
                            tur_factura_detalle
                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk
                            LEFT JOIN tur_modalidad_servicio ON tur_factura_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk
                            LEFT JOIN tur_concepto_servicio ON tur_factura_detalle.codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk
                            WHERE codigo_factura_fk = " . self::$codigoFactura . " AND codigo_grupo_facturacion_fk IS NULL";
                $connection = self::$em->getConnection();
                $statement = $connection->prepare($strSql);
                $statement->execute();
                $results = $statement->fetchAll();
                foreach ($results as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']) . '-'  . $arFacturaDetalle['modalidadServicio'], 0, 61), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 9);

                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');
                    $strCampo = $arFacturaDetalle['conceptoServicio'];
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }

                $strSql = "SELECT tur_grupo_facturacion.nombre as puesto, tur_grupo_facturacion.concepto as conceptoServicio, SUM(cantidad)  AS cantidad, SUM(vr_precio) AS precio
                            FROM
                            tur_factura_detalle
                            LEFT JOIN tur_puesto ON tur_factura_detalle.codigo_puesto_fk = tur_puesto.codigo_puesto_pk
                            LEFT JOIN tur_modalidad_servicio ON tur_factura_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk
                            LEFT JOIN tur_concepto_servicio ON tur_factura_detalle.codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk
                            LEFT JOIN tur_grupo_facturacion ON tur_factura_detalle.codigo_grupo_facturacion_fk = tur_grupo_facturacion.codigo_grupo_facturacion_pk
                            WHERE codigo_factura_fk = " . self::$codigoFactura . "  AND codigo_grupo_facturacion_fk IS NOT NULL
                        GROUP BY tur_factura_detalle.codigo_grupo_facturacion_fk ";
                $connection = self::$em->getConnection();
                $statement = $connection->prepare($strSql);
                $statement->execute();
                $results = $statement->fetchAll();
                foreach ($results as $arFacturaDetalle) {
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle['cantidad'], 0, '.', ','), 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(124, 4, substr(utf8_decode($arFacturaDetalle['puesto']), 0, 61), 0, 0, 'L');
                    $pdf->SetFont('Arial', '', 9);
                    if($arFacturaDetalle['cantidad'] > 0) {
                        $precioUnitario = $arFacturaDetalle['precio'] / $arFacturaDetalle['cantidad'];
                    }
                    $pdf->Cell(28, 4, number_format($precioUnitario, 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(28, 4, number_format($arFacturaDetalle['precio'], 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetX(15);
                    $pdf->Cell(10, 4, '', 0, 0, 'R');
                    $strCampo = $arFacturaDetalle['conceptoServicio'];
                    $pdf->MultiCell(124, 4, $strCampo, 0, 'L');
                    //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(2);
                    $pdf->SetAutoPageBreak(true, 15);
                }
            }*/
        } /*else {
                $pdf->SetX(15);
                $pdf->Cell(10, 4, number_format(1, 0, '.', ','), 0, 0, 'C');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(124, 4, utf8_decode($arFactura->getTituloRelacion()), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(28, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
                $pdf->Cell(28, 4, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->Cell(10, 4, '', 0, 0, 'R');
                $pdf->MultiCell(124, 4, utf8_decode($arFactura->getDetalleRelacion()), 0, 'L');
                //$pdf->Cell(110, 4, $strCampo, 0, 0, 'L');
                $pdf->Cell(28, 4, '', 0, 0, 'R');
                $pdf->Cell(28, 4, '', 0, 0, 'R');
                $pdf->Ln(2);
        }*/

    }

    public function devuelveMes($intMes) {
        $strMes = "";
        switch ($intMes) {
            case 1:
                $strMes = "ENERO";
                break;
            case 2:
                $strMes = "FEBRERO";
                break;
            case 3:
                $strMes = "MARZO";
                break;
            case 4:
                $strMes = "ABRIL";
                break;
            case 5:
                $strMes = "MAYO";
                break;
            case 6:
                $strMes = "JUNIO";
                break;
            case 7:
                $strMes = "JULIO";
                break;
            case 8:
                $strMes = "AGOSTO";
                break;
            case 9:
                $strMes = "SEPTIEMBRE";
                break;
            case 10:
                $strMes = "OCTUBRE";
                break;
            case 11:
                $strMes = "NOVIEMBRE";
                break;
            case 12:
                $strMes = "DICIEMBRE";
                break;
        }
        return $strMes;
    }

    public function Footer() {
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $this->Rect(10, 77, 130, 115);
        $this->Rect(140, 77, 8, 115);
        $this->Rect(148, 77, 21, 115);
        $this->Rect(169, 77, 21, 115);
        $this->Rect(190, 77, 21, 115);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(148,192);
                                                
        $this->Cell(42, 5, 'SUBTOTAL:', 1, 0, 'L');
        $this->Cell(21, 5, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');        
        $this->SetXY(148,197);
        $this->Cell(42, 5, '+ I.V.A:', 1, 0, 'L');
        $this->Cell(21, 5, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(148,202);        
        $this->Cell(42, 5, 'TOTAL:', 1, 0, 'L');               
        $this->Cell(21, 5, number_format($arFactura->getVrTotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(148,207);        
        $this->Cell(42, 5, '- RETEN FUENTE', 1, 0, 'L');               
        $this->Cell(21, 5, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(148,212);        
        $this->Cell(42, 5, 'TOTAL A CANCELAR', 1, 0, 'L',0);               
        $this->Cell(21, 5, number_format($arFactura->getVrTotalNeto(), 0, '.', ','), 1, 0, 'R',0);
        $this->SetXY(148,217);        
        $this->Cell(42, 5, 'BASE AIU 10%', 1, 0, 'L');               
        $this->Cell(21, 5, number_format($arFactura->getVrBaseAIU(), 0, '.', ','), 1, 0, 'R');
        
        $this->SetXY(10,193);
        $this->Rect(10, 192, 138, 30);
        $this->SetFont('Arial', '', 7.3);
        $this->MultiCell(137, 3, utf8_decode($arConfiguracion->getInformacionPagoFactura()), 0, 'L');        
        $this->SetXY(10,208);        
        $this->MultiCell(137, 3, "OBSERVACION: ". utf8_decode($arFactura->getComentarios()), 0, 'L');
        $this->SetXY(10,223);        
        $this->MultiCell(137, 3, $arConfiguracion->getInformacionLegalFactura(), 0, 'L');
        $this->SetXY(10,235);        
        $this->MultiCell(137, 3, "IMPRESO EN COMPUTADOR PARA GALAXIA SEGURIDAD LTDA AÑO 2014 NIT 811017575-1", 0, 'L');
        $this->Rect(10, 222, 201, 40);
        $this->Text(149, 225, "ACEPTADA Y RECIBIDA POR:");
        $this->Text(149, 231, "NOMBRE LEGIBLE: __________________________");
        $this->Text(149, 236, "NIT O CEDULA:        __________________________");
        $this->Text(149, 242, "FECHA:                     __________________________");
        $this->Text(149, 248, "SELLO: ");
        $this->Text(10, 266, $arConfiguracion->getInformacionResolucionDianFactura());
        /*
        
        
        $this->Text(20, 211, "Sello:");
        $this->Text(20, 221, "Actividad Comercial");
        $this->Text(60, 221, "Sector comercial");
        //$this->Text(60, 221, utf8_decode($arFactura->getClienteRel()->getSectorComercialRel()->getNombre()));
        $this->Text(90, 221, "Estrato =");
        $this->Ln(4);
        $this->SetFont('Arial', '', 8);
        //$this->Text(20, $this->GetY($this->SetY(244)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetXY(30,228);
        $this->MultiCell(110, 5, $arConfiguracion->getInformacionPagoFactura(), 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', 'B', 8);
        $this->Text(30, 241, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable");
        //$this->MultiCell(100, 5, "Observacion: Si efectura retencion en la fuente, favor aplicar tarifa del 2% Sobre Base Gravable", 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Text(50, 251, "Favor remitir copia de la consignacion a los correos a.mona@seracis.com y d.mejia@seracis.com");

        //Número de página
        //$this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');*/
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionTurno = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','',8);
        //Logo
        $this->Image('imagenes/logos/logo.jpg', 100, 10, 30, 25);
        //$this->Text(108, 40, $arConfiguracion->getNitEmpresa()."-".$arConfiguracion->getDigitoVerificacionEmpresa());
        //INFORMACIÓN EMPRESA BLOQUE 1
        $this->SetXY(10, 10);
        $this->Cell(120, 4, utf8_decode($arConfiguracion->getDireccionEmpresa()), 0, 0, 'L', 0);
        $this->SetXY(10, 14);
        $this->Cell(120, 4, "PBX: ".$arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        //$this->Cell(120, 4, "NIT.: ".$arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(10, 18);
        $this->Cell(120, 4, $arConfiguracion->getCiudadRel()->getNombre(), 0, 0, 'L', 0);
        $this->SetXY(10, 22);
        $this->Cell(120, 4, "subgerencia@galaxiasegurdad.net", 0, 0, 'L', 0);
        $this->SetXY(10, 26);
        $this->Cell(120, 4, "VIGILADO ". $arConfiguracionTurno->getInformacionResolucionSupervigilanciaFactura(), 0, 0, 'L', 0);
        $this->SetXY(10, 30);        
        $this->Cell(120, 4, "IVA REGIMEN COMUN", 0, 0, 'L', 0);        
        //INFORMACIÓN EMPRESA BLOQUE 2
        $this->SetXY(150, 10);
        $this->Cell(120, 4, "PAGINA No.: ". $this->PageNo() . ' de {nb}', 0, 0, 'L', 0);        
        $this->SetXY(150, 14);
        $this->Cell(120, 4, "FECHA FACTURA (DD/MM/AAAA): ". $arFactura->getFecha()->format('d/m/Y'), 0, 0, 'L', 0);
        $this->SetXY(150, 18);
        $this->Cell(120, 4, "FACTURA CAMBIARIA DE COMPRAVENTA ", 0, 0, 'L', 0);
        $this->SetXY(150, 22);
        $this->Cell(120, 4, "No.: ".$arFactura->getNumero(), 0, 0, 'L', 0);
    }

}

?>
