<?php

namespace Brasa\RecursoHumanoBundle\Formatos;

class NotaCredito1 extends \FPDF_FPDF {

    public static $em;
    public static $codigoFactura;
    public static $strLetras;

    public function Generar($em, $codigoFactura) {       
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        ob_clean();
        $pdf = new NotaCredito1();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("NotaCredito$codigoFactura.pdf", 'D');
    }

    public function Header() {
        $this->GenerarEncabezadoFactura(self::$em);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $this->SetFont('Arial', '', 12);
        //$this->Text(90, 50, "NOTA CREDITO " . $arFactura->getNumero());        
        $this->SetXY(15,45);
        $this->SetFont('Arial', 'B', 8);
        
        $this->SetFont('Arial', '', 9);
        $this->Text(15, 65, "Fecha Factura");
        $this->Text(45, 65, ucwords(strtolower($this->devuelveMes($arFactura->getFecha()->format('m')))) . " " . $arFactura->getFecha()->format('d') . " de " . $arFactura->getFecha()->format('Y'));
        $this->Text(135, 65, "Fecha Vence");
        $this->Text(170, 65, ucwords(strtolower($this->devuelveMes($arFactura->getFechaVence()->format('m')))) . " " . $arFactura->getFechaVence()->format('d') . " de " . $arFactura->getFechaVence()->format('Y'));        
        $this->Text(15, 70, utf8_decode("Señores"));
        //$this->Text(45, 70, $arFactura->getClienteRel()->getNombreCompleto());
        $this->SetXY(44, 68);        
        $this->MultiCell(90, 4, $arFactura->getClienteRel()->getNombreCorto(), 0, 'L');         
        $this->Text(135, 70, "Nit");
        $this->Text(170, 70, $arFactura->getClienteRel()->getNit(). "-" . $arFactura->getClienteRel()->getDigitoVerificacion());        
        $this->Text(15, 80, "Direccion");
        $this->Text(45, 80, $arFactura->getClienteRel()->getDireccion());
        $this->Text(135, 80, "Telefono");
        $this->Text(170, 80, $arFactura->getClienteRel()->getTelefono());                
        
        $this->SetXY(110, 75);
        $this->SetMargins(10, 1, 10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $this->SetX(15);
        $header = array('CANT', 'DETALLE', 'Vr. UNITARIO', 'Vr. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 9);

        //creamos la cabecera de la tabla.
        $w = array(10, 124, 28, 28);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L');
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Rect(15, 96, 10, 100);
        $this->Rect(25, 96, 124, 100);
        $this->Rect(149, 96, 28, 100);
        $this->Rect(177, 96, 28, 100);
        $this->Ln(8);
    }

    public function Body($pdf) {

        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);         
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetX(15);
        $pdf->Cell(10, 4, '', 0, 0, 'R');                        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(120, 4, "", 0, 0, 'L');                        
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(30, 4, '', 0, 0, 'R');
        $pdf->Cell(30, 4, '', 0, 0, 'R'); 
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 9);                                 
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetX(15);
            $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');                        
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(124, 4, substr("", 0, 61), 0, 0, 'L');                        
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(15);
            $pdf->Cell(10, 4, '', 0, 0, 'R');                                   
            $strCampo = $arFacturaDetalle->getFacturaConceptoRel()->getNombre();            
            $pdf->MultiCell(124, 4, $strCampo, 0, 'L'); 
            $pdf->Cell(28, 4, '', 0, 0, 'R');
            $pdf->Cell(28, 4, '', 0, 0, 'R');            
            $pdf->Ln(1);
            $pdf->SetAutoPageBreak(true, 105);
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
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $this->SetXY(15,196);
        $this->Cell(50, 21, '', 1, 0, 'R');        
        $this->Cell(84, 21, '', 1, 0, 'R'); 
        $this->SetXY(15,217);
        $this->Cell(134, 14, '', 1, 0, 'R');        
        $this->SetXY(149,196);
        $this->Cell(28, 7, 'SUB TOTAL', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149,203);
        $this->Cell(28, 7, 'Base Gravable', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrBaseAIU(), 0, '.', ',') , 1, 0, 'R');
        $this->SetXY(149,210);
        $this->Cell(28, 7, 'IVA', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R'); 
        $this->SetXY(149,217);
        $this->Cell(28, 7, 'Rete Fuente', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149,224);
        $this->Cell(28, 7, 'TOTAL', 1, 0, 'L');        
        $this->Cell(28, 7, number_format($arFactura->getVrNeto(), 0, '.', ','), 1, 0, 'R');
        $this->SetFont('Arial', '', 8);
        $plazoPago = $arFactura->getClienteRel()->getPlazoPago();
        $this->Text(66, 201, "CONDICIONES DE PAGO: A $plazoPago DIAS A PARTIR");
        $this->Text(66, 205, "DE LA FECHA DE EXPEDICION");
        $this->SetFont('Arial', '', 9);
        $this->Ln(4);

        //Número de página
        $this->Text(180, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("NOTA CREDITO ". $arFactura->getNumero()), 0, 0, 'C', 1);
        $this->SetXY(53, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 30); 
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
    }

}

?>
