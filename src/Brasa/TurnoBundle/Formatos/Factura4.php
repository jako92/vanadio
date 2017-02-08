<?php

namespace Brasa\TurnoBundle\Formatos;

class Factura4 extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($em, $codigoFactura) {        
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = $em->getRepository('BrasaTurnoBundle:TurFactura')->find($codigoFactura);
        $valor = round($arFactura->getVrTotal());
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($valor);
        self::$strLetras = $strLetras;
        ob_clean();
        $pdf = new Factura4('P','mm', 'letter'); //estelar
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionTurno = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arFactura = new \Brasa\TurnoBundle\Entity\TurFactura();
        $arFactura = self::$em->getRepository('BrasaTurnoBundle:TurFactura')->find(self::$codigoFactura);
        $this->SetXY(10, 5);
        $this->Cell(195, 263, '', 1, 0, 'L');

        $this->SetFont('Arial', '', 7);
        $this->SetXY(110, 75);
        //$this->MultiCell(140, 3, $arConfiguracionTurno->getInformacionResolucionDianFactura(), 0, 'L');

        $this->SetFont('Arial', 'B', 9);

        $this->SetMargins(10, 1, 10);
        //$this->Rect(4, 40, 130, 20);
        $this->ln(1);
        $this->SetY(44);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 3, 'CLIENTE', 0, 0, 'L');
        $this->SetY(46);
        $this->Ln();
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 9);
        $List1 = array('RAZON SOCIAL: ', 'NIT: ', 'TELEFONO:', 'DIRECCION:', 'SECTOR:', 'ESTRATO:');
        foreach ($List1 as $col) {
            $this->Cell(50, 4, $col, 0, 0, 'L');
            $this->Ln(4);
        }

        $Datos = array($arFactura->getClienteRel()->getNombreCorto(),
            $arFactura->getClienteRel()->getNit() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion(),
            $arFactura->getClienteRel()->getTelefono(),
            $arFactura->getClienteRel()->getDireccion(),
            $arFactura->getClienteRel()->getSectorRel()->getNombre(),
            $arFactura->getClienteRel()->getEstrato());
        $this->SetFont('Arial', '', 8);
        $this->SetY(50);

        foreach ($Datos as $col) {
            $this->SetX(36);
            $this->Cell(50, 4, $col, 0, 'L');
            $this->Ln(4);
        }

        //$this->SetMargins(4, 1, 10);
        //$this->Rect(135, 26, 73, 20);
        $this->ln(1);
        $this->SetY(20);

        $List1 = array('FACTURA DE VENTA', 'Fecha emision:', 'Fecha vencimiento:', 'Forma pago:', 'Plazo:', 'Fecha suspension:', 'Fecha cancelacion:');
        $this->SetFont('Arial', 'B', 8);
        foreach ($List1 as $col) {
            if ($col == 'FACTURA DE VENTA'){
                $this->SetFont('Arial', 'B', 10);    
            } else {
                $this->SetFont('Arial', 'B', 8);      
            }
            $this->SetX(150);            
            $this->Cell(10, 3, $col, 0, 0, 'L');
            $this->Ln();
        }
        $FechaSuspension = "" ;
        $FechaCancelacion = "" ;      
        if ($arFactura->getFacturaServicioRel()->getCodigoFacturaServicioPk() == 2) {
                $FechaSuspension = $arFactura->getFechaSuspension()->format('Y-m-d');
                $FechaCancelacion = $arFactura->getFechaCancelacion()->format('Y-m-d');
            }
        $List1 = array('',
            $arFactura->getFecha()->format('Y-m-d'),
            $arFactura->getFechaVence()->format('Y-m-d'),
            $arFactura->getClienteRel()->getFormaPagoRel()->getNombre(),
            $arFactura->getPlazoPago(),
            $FechaSuspension,
            $FechaCancelacion);
            
        
        $this->SetXY(175,20);
        $this->SetFont('Arial', '', 14);        
        $this->Cell(30, 3, $arFactura->getNumero(), 0, 0, 'R'); 
        $this->SetY(20);
        $this->SetFont('Arial', '', 8);        
        foreach ($List1 as $col) {
            $this->SetX(175);
            $this->Cell(30, 3, $col, 0, 0, 'R');
            $this->Ln();
        }
        $this->SetY(44);
        if($arFactura->getClienteDireccionRel()) {
            $arrayTexto = array($arFactura->getClienteDireccionRel()->getNombre(),
                $arFactura->getClienteDireccionRel()->getCiudadRel()->getNombre(),
                $arFactura->getClienteDireccionRel()->getDireccion(),
                $arFactura->getClienteDireccionRel()->getBarrio());            
        } else {
            $arrayTexto = array("PRINCIPAL",
                $arFactura->getClienteRel()->getCiudadRel()->getNombre(),
                $arFactura->getClienteRel()->getDireccion(),
                $arFactura->getClienteRel()->getBarrio());                        
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
        $header = array('CODIGO', 'DETALLE', 'PEDIDO', 'CANT', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(12, 141, 10, 10, 22);
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
        $arFacturaDetalles = new \Brasa\TurnoBundle\Entity\TurFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaTurnoBundle:TurFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetFont('Arial', '', 6.5);
            $strDetalle = "";
            if($arFacturaDetalle->getDetalle()) {
                $strDetalle = $arFacturaDetalle->getDetalle();
            } else { 
                if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                    $strDetalle = "SERVICIO " . $arFacturaDetalle->getConceptoServicioRel()->getNombreFacturacion() . " DESDE EL " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaDesde()
                            . " HASTA EL " . $arFacturaDetalle->getPedidoDetalleRel()->getDiaHasta() . " DE " .
                    $this->devuelveMes($arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('n')) . " " . $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getFechaProgramacion()->format('Y') . " " .substr($arFacturaDetalle->getPuestoRel()->getNombre(), 0,50);//. " - " . $arFacturaDetalle->getPedidoDetalleRel()->getPuestoRel()->getNombre();                                    
                } else {
                    $strDetalle = $arFacturaDetalle->getConceptoServicioRel()->getNombre() . " ". $arFacturaDetalle->getPuestoRel()->getNombre();
                }
            }
            $pdf->SetFont('Arial', '', 6.3);
            $pdf->Cell(12, 7, $arFacturaDetalle->getCodigoFacturaDetallePk(), 0, 0, 'L');
            $pdf->Cell(141, 7, $strDetalle, 0, 0, 'L');
            //$pdf->MultiCell(125, 3.5, $strDetalle, 1, 'L');
            $numeroPedido = "";
            if($arFacturaDetalle->getCodigoPedidoDetalleFk()) {
                $numeroPedido = $arFacturaDetalle->getPedidoDetalleRel()->getPedidoRel()->getNumero();
            }
            $pdf->Cell(10, 7, $numeroPedido, 0, 0, 'L');
            $pdf->Cell(10, 7, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(22, 7, number_format($arFacturaDetalle->getSubtotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 70);
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
        $this->SetY(200);
        $this->line(10, $this->GetY() + 5, 205, $this->GetY() + 5);

        $this->SetFont('Arial', 'B', 7.5);
        $this->ln(7);
        $totales = array('SUBTOTAL: ' . " " . " ",            
            '(+)IVA: ' . " " . " ",
            //'(+)RTE FUENTE: ' . " " . " ",
            //'(+)RTE IVA: ' . " " . " ",
            'TOTAL GENERAL: ' . " " . " ",
            'BASE AIU: ' . " " . " "
        );

        $this->line(10, $this->GetY() + 40, 205, $this->GetY() + 40);
        
        $this->SetMargins(170, 2, 15);
        for ($i = 0; $i < count($totales); $i++) {
            $this->SetX(165);
            $this->Cell(20, 4, $totales[$i], 0, 0, 'R');
            $this->ln();
        }

        $totales2 = array(number_format($arFactura->getVrSubtotal(), 0, '.', ','),            
            number_format($arFactura->getVrIva(), 0, '.', ','),
            //number_format($arFactura->getVrRetencionFuente(), 0, '.', ','),
            //number_format($arFactura->getVrRetencionIva(), 0, '.', ','),
            number_format($arFactura->getVrTotal(), 0, '.', ','),
            number_format($arFactura->getVrBaseAIU(), 0, '.', ',')
        );

        $this->SetFont('Arial', '', 7.5);
        $this->SetXY(190, $this->GetY() - 28);
        $this->ln(12);
        for ($i = 0; $i < count($totales2); $i++) {
            $this->SetX(185);
            $this->Cell(20, 4, $totales2[$i], 0, 0, 'R');
            $this->ln();
        }
        $this->Rect(10, 205, 145, 42);
        $this->SetY($this->GetY() - 17);
        $this->SetFont('Arial', 'B', 8);
        $this->SetX(10);
        //$this->Cell(145, 15, 'OBSERVACIONES:', 1, 'L');
        $this->MultiCell(145, 2.5, "OBSERVACIONES:", 0, 'L');
        $this->ln();
        $this->SetX(10);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(145, 3, $arFactura->getComentarios(), 0, 'L');
        $this->ln();
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell(145, 3, $arConfiguracion->getInformacionPagoFactura(), 1, 'L');
        $this->ln();
        $arrayNumero = explode(".", 0, 2);
        $intCentavos = 0;
        if (count($arrayNumero) > 1)
            $intCentavos = substr($arrayNumero[1], 0, 2);
        $strLetras = "";
        //$strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($arFactura->getVrTotal()) . " con " . \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($intCentavos);
        /*$this->SetFont('Arial', 'B', 10);
        $this->Text(12, 242, "SON : " . substr(strtoupper(self::$strLetras), 0, 96));
        $this->Ln();*/
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(145, 4, "SON : " . substr(strtoupper(self::$strLetras. " PESOS M/CTE."), 0, 96), 0, 'L');
        //$Text = array($arConfiguracion->getInformacionLegalFactura());

        $this->SetFont('Arial', '', 6);
        $this->GetY($this->SetY(228));
        $this->SetX(10);
        //$this->MultiCell(90, 3, $arConfiguracion->getInformacionLegalFactura());

        $this->SetFont('Arial', '', 7);
        $this->GetY($this->SetY(255));
        $this->SetX(10);
        //$this->MultiCell(90, 3, $arConfiguracion->getInformacionResolucionSupervigilanciaFactura(), 0, 'L');

        $this->GetY($this->SetY(235));
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(0, 0, $this->line(100, $this->GetY() + 27, 12, $this->GetY() + 27) . $this->Text(12, $this->GetY() + 30, "AUTORIZADO"));
        $this->Cell(0, 0, $this->line(203, $this->GetY() + 27, 105, $this->GetY() + 27) . $this->Text(105, $this->GetY() + 30, "RECIBI (Nombre y cedula)"));

        //$this->line(10, 260, 205, 260);
        $this->SetFont('Arial', '', 7);
        $this->SetY(-70);
        $this->Ln();
        //$this->line(10, 269, 205, 269);
        $this->Ln(3);
        $this->SetFont('Arial', 'B', 8);
        //$this->Text(20, $this->GetY($this->SetY(264)), $arConfiguracion->getInformacionPagoFactura());
        $this->SetFont('Arial', '', 7);
        //$this->Text(60, $this->GetY($this->SetY(267)), $arConfiguracion->getInformacionContactoFactura());

        //Número de página
        $this->Text(188, 266, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionTurno = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracionTurno = self::$em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);        
        
        $this->SetFont('Arial', '', 5);
        $this->Text(188, 7, ' [sogaApp - turnos]');
        $this->Image('imagenes/logos/logo.jpg', 13, 6, 45, 26);
        $this->ln(11);
        $this->SetFont('Arial', 'B', 12);
        $this->ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Text(21, 34, "NIT " . $arConfiguracion->getNitEmpresa() . "-" . $arConfiguracion->getDigitoVerificacionEmpresa());
        //informacion empresa
        $this->SetFont('Arial','B',8);
        $this->SetXY(57, 7);
        $this->Cell(88, 4, "Act. ICA: 323       %6.000", 0, 0, 'C');
        $this->SetXY(57, 11);
        $this->Cell(88, 4, utf8_decode("Iva Régimen Común"), 0, 0, 'C');
        $this->SetXY(57, 15);
        $this->Cell(88, 4, "Dir: ". $arConfiguracion->getDireccionEmpresa(), 0, 0, 'C');
        $this->SetXY(57, 19);
        $this->Cell(88, 4, "Tel: ". $arConfiguracion->getTelefonoEmpresa()."  - email: facturacion@seguridadestelar.com", 0, 0, 'C');
        $this->SetXY(57, 23);
        $this->SetFont('Arial','',8);
        $this->MultiCell(88, 2.7, utf8_decode($arConfiguracionTurno->getInformacionResolucionDianFactura()), 0, 'C');
        $this->SetXY(57, 29);
        $this->MultiCell(88, 2.6, utf8_decode($arConfiguracionTurno->getInformacionResolucionSupervigilanciaFactura()), 0, 'C');
        $this->SetXY(57, 32);
        $this->MultiCell(88, 2.6, utf8_decode($arConfiguracionTurno->getInformacionLegalFactura()), 0, 'C');
    }

}

?>
