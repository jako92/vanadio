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
        
        $this->SetXY(14, 44);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(138, 4.5, utf8_decode($arFactura->getClienteRel()->getNombreCorto()), 0, 0, 'L', 0);        
        $this->SetXY(14, 48); 
        $this->SetFont('Arial', '', 8);
        $this->Cell(138, 4.5, $arFactura->getClienteRel()->getDireccion(), 0, 0, 'L', 0);
        $this->SetXY(14, 52);        
        $this->Cell(138, 4.5, "CIUDAD: " . $arFactura->getClienteRel()->getCiudadRel()->getNombre(), 0, 0, 'L', 0);
        $this->SetXY(14, 56);        
        $this->Cell(138, 4.5, "TELEFONOS: " . $arFactura->getClienteRel()->getTelefono(), 0, 0, 'L', 0);
 
        $this->SetXY(135, 44);        
        $this->Cell(61, 4.5, "NIT/CEDULA: " . $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(), 0, 0, 'L', 0);               
        $this->SetXY(135, 48);        
        $this->Cell(61, 4.5, "CEDULA: " . $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(), 0, 0, 'L', 0);               
        $this->SetXY(135, 52);        
        $this->Cell(61, 4.5, "VENDEDOR: 001", 0, 0, 'L', 0);                       
        $this->SetXY(135, 56);        
        $this->Cell(61, 4.5, "FECHA VENCIMIENTO: " . $arFactura->getFechaVence()->format('d/m/Y'), 0, 0, 'L', 0);                       
        
        $this->SetXY(110, 62);
        $this->SetMargins(5, 1, 5);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(0);
        $this->SetX(14);
        $header = array('COD','DESCRIPCION', 'CANTIDAD', 'VLR. UNIT', 'IVA','VR. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', '', 8);

        //creamos la cabecera de la tabla.
        $w = array(10,123, 8, 23, 10,23);
        for ($i = 0; $i < count($header); $i++)
            if ($i >= 2) {
                $this->Cell($w[$i], 7, $header[$i], 0, 0, 'R',0);
            } else {
                $this->Cell($w[$i], 7, $header[$i], 0, 0, 'C',0);
            }
                

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
                    if($arFacturaDetalle->getCodigoConceptoServicioFk()== 120 || $arFacturaDetalle->getCodigoConceptoServicioFk()== 121 ){
                        $descripcion = $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacion();
                    }else{                   
                    $descripcion = $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacion() . " ". $this->devuelveMes($arFacturaDetalle->getFechaProgramacion()->format('m')) . ' DE ' . $arFacturaDetalle->getFechaProgramacion()->format('Y'). " - ". $arFacturaDetalle->getPuestoRel()->getNombre();                    
                    }
                    $pdf->SetX(14);
                    $pdf->Cell(10, 4, $arFacturaDetalle->getCodigoFacturaDetallePk(), 0, 0, 'L');                    
                    $modalidad = "";
                    if($arFacturaDetalle->getCodigoModalidadServicioFk()) {
                        $modalidad = "-" . utf8_decode($arFacturaDetalle->getModalidadServicioRel()->getNombre());
                    }
                    $pdf->Cell(123, 4, substr(utf8_decode($descripcion), 0, 80), 0, 0, 'L');                    
                    $modalidad = "";                    
                    $pdf->Cell(8, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');                    
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(23, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(10, 4, number_format($arFacturaDetalle->getPorIva(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Cell(23, 4, number_format($arFacturaDetalle->getSubtotal(), 0, '.', ','), 0, 0, 'R');
                    $pdf->Ln();
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Cell(28, 4, '', 0, 0, 'R');
                    $pdf->Ln(0);
                    $pdf->SetAutoPageBreak(true, 120);
                }
            } 
        } 
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
        $this->Rect(14, 62, 196, 7);
        //$this->Rect(140, 77, 8, 115);
        //$this->Rect(148, 77, 21, 115);
        //$this->Rect(169, 77, 21, 115);
        //$this->Rect(190, 77, 21, 115);
        //$this->SetFillColor(200, 200, 200);
        $this->SetXY(148,165);                                                
        $this->Cell(42, 5, 'SUBTOTAL:', 0, 0, 'L');
        $this->Cell(21, 5, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');        
        $this->SetXY(148,170);
        $this->Cell(42, 5, 'BASE AIU 10%', 0, 0, 'L');               
        $this->Cell(21, 5, number_format($arFactura->getVrBaseAIU(), 0, '.', ','), 0, 0, 'R');        
        $this->SetXY(148,175);
        $this->Cell(42, 5, '+ I.V.A:', 0, 0, 'L');
        $this->Cell(21, 5, number_format($arFactura->getVrIva(), 0, '.', ','), 0, 0, 'R');        
        $this->SetXY(148,180);        
        $this->Cell(42, 5, 'TOTAL:', 0, 0, 'L');               
        $this->Cell(21, 5, number_format($arFactura->getVrTotal(), 0, '.', ','), 0, 0, 'R');
        $this->SetXY(148,185);        
        $this->Cell(42, 5, 'RTE IVA:', 0, 0, 'L',0);               
        $this->Cell(21, 5, number_format($arFactura->getVrRetencionIva(), 0, '.', ','), 0, 0, 'R',0);
        $this->SetXY(148,190);        
        $this->Cell(42, 5, 'RTE FUENTE:', 0, 0, 'L',0);               
        $this->Cell(21, 5, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 0, 0, 'R',0);
        $this->SetXY(148,195);        
        $this->Cell(42, 5, 'TOTAL A CANCELAR', 0, 0, 'L',0);               
        $this->Cell(21, 5, number_format($arFactura->getVrTotalNeto(), 0, '.', ','), 0, 0, 'R',0);
        
        //$this->SetXY(14,193);
        //$this->Rect(10, 192, 138, 30);
        
        //$this->MultiCell(137, 3, utf8_decode($arConfiguracion->getInformacionPagoFactura()), 0, 'L');        
        $this->SetXY(14,200);        
        $this->MultiCell(137, 3, "OBSERVACION: ". utf8_decode($arFactura->getComentarios()), 0, 'L');
        $this->Text(14, 210, "_______________________________________________________________________________________________________________________________");
        $this->SetXY(14,219);        
        $this->MultiCell(118, 3, $arConfiguracion->getInformacionLegalFactura(), 0, 'L');
        $this->SetXY(14,238);        
        $this->MultiCell(118, 3, "IMPRESO EN COMPUTADOR POR APP SOGA S.A.S NIT 901042085-4 PARA GALAXIA SEGURIDAD LTDA AÑO 2017 NIT 811017575-1", 0, 'L');
        //$this->Rect(10, 222, 201, 40);
        $this->Text(149, 214, "ACEPTADA Y RECIBIDA POR:");
        $this->Text(149, 220, "NOMBRE LEGIBLE: __________________________");
        $this->Text(149, 226, "NIT O CEDULA:        __________________________");
        $this->Text(149, 232, "FECHA:                     __________________________");
        $this->Text(149, 238, "SELLO: ");
        $this->Text(14, 254, "_______________________________________________________________________________________________________________________________");
        $this->Text(14, 258, $arConfiguracion->getInformacionResolucionDianFactura());
        
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
        $this->SetFont('Arial','',8);        
        //$this->Image('imagenes/logos/logo.jpg', 100, 10, 30, 25);
        $this->Image('imagenes/logos/logo.jpg', 1, 1, 1, 1);
        
        $this->SetXY(14, 32);        
        $this->Cell(120, 4, "IVA REGIMEN COMUN", 0, 0, 'L', 0);        
        //INFORMACIÓN EMPRESA BLOQUE 2
        $this->SetXY(135, 10);
        $this->Cell(120, 4, "PAGINA No.: ". $this->PageNo() . ' de {nb}', 0, 0, 'L', 0);        
        $this->SetXY(135, 14);
        $this->Cell(120, 4, "FECHA FACTURA (DD/MM/AAAA): ". $arFactura->getFecha()->format('d/m/Y'), 0, 0, 'L', 0);
        $this->SetXY(135, 18);
        $this->Cell(120, 4, "FACTURA CAMBIARIA DE COMPRAVENTA ", 0, 0, 'L', 0);
        $this->SetXY(135, 22);
        $this->Cell(120, 4, "No.: ".$arFactura->getNumero(), 0, 0, 'L', 0);
    }

}

?>
