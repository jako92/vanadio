<?php

namespace Brasa\InventarioBundle\Formatos;

class FormatoFactura extends \FPDF_FPDF {

    public static $em;
    
    public static $codigoMovimiento;
    
    public static $strLetras;
    
    public function Generar($em, $codigoMovimiento) {        
        ob_clean();        
        self::$em = $em;
        self::$codigoMovimiento = $codigoMovimiento;
        $pdf = new FormatoFactura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Movimiento_$codigoMovimiento.pdf", 'D');        
        
    }

    public function Header() {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionInventario = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionInventario = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $this->SetXY(10, 10);
        $this->Cell(195, 268, '', 1, 0, 'L');

        $this->SetFont('Arial', '', 7);
        $this->SetXY(110, 75);
        $this->MultiCell(140, 3, $arConfiguracionInventario->getInformacionResolucionDianFactura(), 0, 'L');
        //$this->MultiCell(140, 3, "Informacion Resolucion Dian Factura", 0, 'L');
        $this->SetFont('Arial', 'B', 9);

        $this->SetMargins(10, 1, 10);
        //$this->Rect(4, 40, 130, 20);
        $this->ln(1);
        $this->SetY(48);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'CLIENTE', 0, 0, 'L');
        $this->SetY(50);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 9);
        $List1 = array('NOMBRE: ', 'NIT: ', 'TELEFONO:', 'DIRECCION:', 'SECTOR:', 'ESTRATO:');
        foreach ($List1 as $col) {
            $this->Cell(50, 4, $col, 0, 0, 'L');
            $this->Ln(4);
        }

        $Datos = array($arMovimiento->getTerceroRel()->getNombreCorto(),
            $arMovimiento->getTerceroRel()->getNit() . "-" . $arMovimiento->getTerceroRel()->getDigitoVerificacion(),
            $arMovimiento->getTerceroRel()->getTelefono(),
            $arMovimiento->getTerceroRel()->getDireccion(),
            "Comercial",
            "5");
        $this->SetFont('Arial', '', 8);
        $this->SetY(54);

        foreach ($Datos as $col) {
            $this->SetX(33);
            $this->Cell(50, 4, $col, 0, 'L');
            $this->Ln(4);
        }

        //$this->SetMargins(4, 1, 10);
        //$this->Rect(135, 26, 73, 20);
        $this->ln(1);
        $this->SetY(27);

        $List1 = array('FACTURA DE VENTA', 'Fecha emision:', 'Fecha vencimiento:', 'Forma pago:', 'Plazo:', 'Soporte:');
        $this->SetFont('Arial', 'B', 8);
        foreach ($List1 as $col) {
            $this->SetX(150);
            $this->Cell(10, 3, $col, 0, 0, 'L');
            $this->Ln();
        }

        $List1 = array('',
            $arMovimiento->getFecha()->format('Y-m-d'),
            $arMovimiento->getFecha()->format('Y-m-d'),
            "Contado",
            $arMovimiento->getTerceroRel()->getPlazoPagoCliente(),
            $arMovimiento->getSoporte());
        $this->SetXY(175,25);
        $this->SetFont('Arial', '', 14);        
        $this->Cell(30, 3, $arMovimiento->getNumero(), 0, 0, 'R'); 
        $this->SetY(27);
        $this->SetFont('Arial', '', 8);        
        foreach ($List1 as $col) {
            $this->SetX(175);
            $this->Cell(30, 3, $col, 0, 0, 'R');
            $this->Ln();
        }
        $this->SetY(48);
        if($arMovimiento->getTerceroRel()->getDireccion()) {
            $arrayTexto = array($arMovimiento->getTerceroRel()->getNombreCorto(),
                "Medellin",
                $arMovimiento->getTerceroRel()->getDireccion(),
                "Belen");            
        } else {
            $arrayTexto = array("PRINCIPAL",
                "Medellin",
                $arMovimiento->getTerceroRel()->getDireccion(),
                "Belen");                        
        }
        
        $this->SetX(110);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'DIRECCION DE ENVIO', 0, 0, 'L');
        $this->SetY(50);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', '', 8);
        foreach ($arrayTexto as $col) {
            $this->SetX(110);
            $this->Cell(10, 4, $col, 0, 0, 'L');
            $this->Ln();
        }

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('CODIGO', 'ITEM', 'CANTIDAD', 'IVA', 'PRECIO', 'SUBTOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(15, 100, 20, 20, 20,20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $arMovimientoDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arMovimientoDetalles = self::$em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->findBy(array('codigoMovimientoFk' => self::$codigoMovimiento));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
            
            $pdf->Cell(15, 4, $arMovimientoDetalle->getCodigoDetalleMovimientoPk(), 1, 0, 'L');
            $pdf->Cell(100, 4,$arMovimientoDetalle->getItemRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arMovimientoDetalle->getCantidad(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arMovimientoDetalle->getVrIva(), 0, '.', ','), 1, 0, 'R');            
            $pdf->Cell(20, 4, number_format($arMovimientoDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arMovimientoDetalle->getVrSubTotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
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
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimiento = self::$em->getRepository('BrasaInventarioBundle:InvMovimiento')->find(self::$codigoMovimiento);
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $this->SetY(180);
        $this->line(10, $this->GetY() + 5, 205, $this->GetY() + 5);

        $this->SetFont('Arial', 'B', 7.5);
        $this->ln(7);
        $totales = array('SUBTOTAL: ' . " " . " ",
            'BASE AIU: ' . " " . " ",
            '(+)IVA: ' . " " . " ",
            '(+)RTE FUENTE: ' . " " . " ",
            '(+)RTE IVA: ' . " " . " ",
            'TOTAL GENERAL: ' . " " . " "
        );

        $this->line(10, $this->GetY() + 40, 205, $this->GetY() + 40);

        $this->SetMargins(170, 2, 15);
        for ($i = 0; $i < count($totales); $i++) {
            $this->SetX(165);
            $this->Cell(20, 4, $totales[$i], 0, 0, 'R');
            $this->ln();
        }

        $totales2 = array(number_format($arMovimiento->getVrSubtotal(), 0, '.', ','),
            number_format(0, 0, '.', ','),
            number_format($arMovimiento->getVrIva(), 0, '.', ','),
            number_format($arMovimiento->getVrRetencionFuente(), 0, '.', ','),
            number_format($arMovimiento->getVrRetencionIvaVentas(), 0, '.', ','),
            number_format($arMovimiento->getVrTotal(), 0, '.', ',')
        );

        $this->SetFont('Arial', '', 7.5);
        $this->SetXY(190, $this->GetY() - 36);
        $this->ln(12);
        for ($i = 0; $i < count($totales2); $i++) {
            $this->SetX(185);
            $this->Cell(20, 4, $totales2[$i], 0, 0, 'R');
            $this->ln();
        }

        $this->SetY($this->GetY() - 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetX(10);
        $this->Cell(20, 5, 'OBSERVACIONES:', 0, 'L');

        $this->ln();
        $this->SetX(10);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(140, 3, $arMovimiento->getComentarios(), 0, 'L');

        $arrayNumero = explode(".", 0, 2);
        $intCentavos = 0;
        if (count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], 0, 2);
        $strLetras = "";
        //$strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($arFactura->getVrTotal()) . " con " . \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($intCentavos);
        $this->SetFont('Arial', 'B', 6);
        $this->Text(12, 224, "SON : " . substr(strtoupper(self::$strLetras), 0, 96));
        $this->Ln();

        //$Text = array($arConfiguracion->getInformacionLegalFactura());

        $this->SetFont('Arial', '', 6);
        $this->GetY($this->SetY(228));
        $this->SetX(10);
        $this->MultiCell(90, 3, $arConfiguracion->getInformacionLegalFactura());


        $this->SetFont('Arial', '', 7);
        $this->GetY($this->SetY(255));
        $this->SetX(10);
        $this->MultiCell(90, 3, $arConfiguracion->getInformacionResolucionSupervigilanciaFactura(), 0, 'L');

        $this->GetY($this->SetY(235));
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 0, $this->line(100, $this->GetY() + 15, 150, $this->GetY() + 15) . $this->Text(100, $this->GetY() + 18, "AUTORIZADO"));
        $this->Cell(0, 0, $this->line(154, $this->GetY() + 15, 205, $this->GetY() + 15) . $this->Text(154, $this->GetY() + 18, "RECIBI (Nombre y firma)"));

        $this->line(10, 260, 205, 260);
        $this->SetFont('Arial', '', 7);
        $this->SetY(-70);
        $this->Ln();
        $this->line(10, 269, 205, 269);



        $this->Ln(3);
        $this->SetFont('Arial', 'B', 8);
        $this->GetY($this->SetY(261));
        $this->SetX(10);
        $this->MultiCell(200, 3, $arConfiguracion->getInformacionPagoFactura(), 0, 'C');
        //$this->Text(20, $this->GetY($this->SetY(264)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetFont('Arial', '', 7);
        $this->Text(60, $this->GetY($this->SetY(273)), $arConfiguracion->getInformacionContactoFactura(), 0, 'C');

        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->SetFont('Arial', '', 5);
        $this->Text(188, 13, ' [sogaApp - turnos]');
        $this->Image('imagenes/logos/logo.jpg', 15, 15, 35, 17);
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(21, 35, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        $this->SetXY(258, 18);
    }

}

?>
