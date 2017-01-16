<?php

namespace Brasa\InventarioBundle\Formatos;

class FormatoFactura2 extends \FPDF_FPDF { //jg

    public static $em;
    public static $codigoMovimiento;
    public static $strLetras;

    public function Generar($em, $codigoMovimiento) { //jg

        self::$em = $em;
        self::$codigoMovimiento = $codigoMovimiento;
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        $valor = round($arMovimiento->getVrNeto());
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($valor);
        self::$strLetras = $strLetras;
        ob_clean();
        //$pdf = new Movimiento3(); //jg
        $pdf = new FormatoFactura2('P','mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Movimiento$codigoMovimiento.pdf", 'D');
    }

    public function Header() {
        
        $this->GenerarEncabezadoMovimiento(self::$em); 
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $this->SetXY(120, 38);
        $this->SetFont('Arial','B',15);
        $this->Cell(50, 4, "FACTURA DE VENTA", 0, 0, 'L', 0);
        $this->SetXY(178, 38);
        $this->SetFont('Arial','B',12);
        $this->Cell(20, 4, utf8_decode("N°.  "). $arMovimiento->getNumero(), 0, 0, 'L', 0);//$arMovimiento->getCodigoMovimientoPk(), 0, 0, 'L', 0);
        //
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(15, 43);
        $this->Cell(25, 5, "CLIENTE:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7.7);
        $this->Cell(115, 5, $arMovimiento->getTerceroRel()->getNombreCorto(), 0, 0, 'L', 1);
        $this->SetXY(156, 43);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "FECHA EMISION:", 0, 0, 'C', 1);
        $this->Cell(25, 5, "FECHA VENCE:", 0, 0, 'C', 1);        
        $this->SetXY(15, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "NIT:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, $arMovimiento->getTerceroRel()->getNit(), 0, 0, 'L', 1);
        $this->SetXY(156, 48);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, $arMovimiento->getFecha()->format('Y-m-d'), 0, 0, 'C', 1);
        $this->Cell(25, 5, $arMovimiento->getFechaVence()->format('Y-m-d'), 0, 0, 'C', 1);        
        $this->SetXY(15, 53);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "DIRECCION:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, $arMovimiento->getTerceroRel()->getDireccion(), 0, 0, 'L', 1);
        $this->SetXY(156, 53);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode("FORMA PAGO"), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(25, 5, $arMovimiento->getTerceroRel()->getFormaPagoRel()->getNombre(), 0, 0, 'L', 1);
        $this->SetXY(15, 58);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "TELEFONO:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(50, 5, $arMovimiento->getTerceroRel()->getTelefono(), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, "CIUDAD:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(45, 5, $arMovimiento->getTerceroRel()->getCiudadRel()->getNombre(), 0, 0, 'L', 1);
        $this->SetXY(156, 58);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "PLAZO PAGO", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(25, 5, $arMovimiento->getPlazoPago(), 0, 0, 'L', 1);

        $this->SetXY(110, 62);
        $this->SetMargins(5, 1, 5);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(8);
        $this->SetX(15);
        $header = array('DESCRIPCION', 'CANT', 'VR.UND', 'DCTO', 'VR.TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(139, 8, 18, 8, 18);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L',1);
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C',1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(8);
    }

    public function Body($pdf) {
        
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $arMovimientoDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arMovimientoDetalles = self::$em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->findBy(array('codigoMovimientoFk' => self::$codigoMovimiento));                
        $pdf->Ln(0);
        $pdf->SetFont('Arial', '', 8);
        
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            $pdf->SetX(15);                    
            $pdf->Cell(139, 4, $arMovimientoDetalle->getItemRel()->getNombre(), 0, 0, 'L');
            $pdf->Cell(8, 4, number_format($arMovimientoDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');                                
            $pdf->Cell(18, 4, number_format($arMovimientoDetalle->getValor(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(8, 4, number_format($arMovimientoDetalle->getPorcentajeDescuento(), 0, '.', ','), 0, 0, 'C');
            $pdf->Cell(18, 4, number_format($arMovimientoDetalle->getVrSubTotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(15);
            $pdf->Cell(10, 4, '', 0, 0, 'R');                    
            $pdf->SetAutoPageBreak(true, 88);
        }

    }    

    public function Footer() {
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $arConfiguracion = new \Brasa\InventarioBundle\Entity\InvConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaInventarioBundle:InvConfiguracion')->find(1);
        $this->Rect(15, 77, 139, 115);
        $this->Rect(154, 77, 8, 115);
        $this->Rect(162, 77, 18, 115);
        $this->Rect(154, 77, 8, 115);
        $this->Rect(188, 77, 18, 115);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(15,192);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, 'OBSERVACIONES:', 1, 0, 'L');        
        $this->Cell(35, 6, 'RETE FTE SUGERIDA:', 1, 0, 'L');        
        $this->Cell(22, 6, number_format($arMovimiento->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->Cell(28, 6, '', 1, 0, 'L');
        $this->Cell(22, 6, "", 1, 0, 'R');
        $this->Cell(22, 6, 'SUBTOTAL', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arMovimiento->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(15,198);        
        $this->Cell(147, 12, '', 1, 0, 'L');
        $this->Cell(22, 6, 'IVA', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arMovimiento->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(162,204);        
        $this->Cell(22, 6, 'TOTAL', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arMovimiento->getVrTotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(15,210);        
        $this->Cell(147, 6, substr(strtoupper(self::$strLetras), 0, 96), 1, 0, 'L',1);
        $this->Cell(22, 6, 'NETO', 1, 0, 'L',1);
        $this->Cell(22, 6, number_format($arMovimiento->getVrTotal(), 0, '.', ','), 1, 0, 'R',1);
        $this->SetXY(15,216);        
        $this->Cell(64, 6, 'RECIBIDO POR', 1, 0, 'L');
        $this->Cell(64, 6, 'ACEPTADO POR', 1, 0, 'L');
        $this->Cell(63, 6, '', 1, 0, 'L');
        $this->SetXY(15,222);
        $this->Cell(32, 6, 'NIT/C.C', 1, 0, 'L');
        $this->Cell(32, 6, 'FECHA', 1, 0, 'L');
        $this->Cell(32, 6, 'NIT/C.C', 1, 0, 'L');
        $this->Cell(32, 6, 'FECHA', 1, 0, 'L');
        $this->Cell(63, 6, 'FIRMA DEL EMISOR', 1, 0, 'L');
        $this->SetXY(15,199);
        $this->SetFont('Arial', 'B', 7.7);
        $this->MultiCell(145, 3, utf8_decode($arMovimiento->getComentarios()), 0, 'L');
        $this->SetXY(15,228);
        $this->SetFont('Arial', 'B', 7.7);
        $this->MultiCell(191, 3.6, utf8_decode($arConfiguracion->getInformacionLegalMovimiento()), 1, 'L');        
        $this->SetXY(15,246);
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell(191, 3.9, utf8_decode($arConfiguracion->getInformacionPagoMovimiento()), 1, 'C');
        $this->SetXY(15,258);
    }

    public function GenerarEncabezadoMovimiento($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionInventario = new \Brasa\InventarioBundle\Entity\InvConfiguracion();
        $arConfiguracionInventario = self::$em->getRepository('BrasaInventarioBundle:InvConfiguracion')->find(1);
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',8);
        //Logo
        $this->Image('imagenes/logos/logo.jpg', 20, 8, 30, 25);
        //$this->Image('imagenes/logos/veritas.jpg', 165, 11, 17, 22);
        //$this->Image('imagenes/logos/iso.jpg', 185, 11, 24, 24);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 5);
        $this->Cell(120, 4, utf8_decode($arConfiguracion->getNombreEmpresa()), 0, 0, 'C', 0);
        $this->SetXY(50, 9);
        $this->Cell(120, 4, "NIT.: ".$arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'C', 0);
        $this->SetXY(50, 13);
        $this->Cell(120, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'C', 0);
        $this->SetXY(50, 18);
        $this->Cell(120, 4, "Telefono: ".$arConfiguracion->getTelefonoEmpresa()." ".$arConfiguracion->getPaginaWeb(), 0, 0, 'C', 0);
        $this->SetXY(50, 22);
        $this->Cell(120, 4, $arConfiguracion->getCiudadRel()->getNombre(), 0, 0, 'C', 0);
        $this->SetXY(50, 26);
        $this->SetFont('Arial','',8);
        $this->Cell(120, 4, "IVA REGIMEN COMUN", 0, 0, 'C', 0);
        $this->SetXY(50, 30);
        $this->Cell(120, 4, utf8_decode($arConfiguracionInventario->getInformacionResolucionDianMovimiento()), 0, 0, 'C', 0);
        $this->SetXY(50, 33);
        $this->Cell(120, 4, "__________________________________________________________________________________________________________________________", 0, 0, 'C', 0);
    }

}

?>
