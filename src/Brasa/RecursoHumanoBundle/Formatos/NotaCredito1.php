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
        $valor = round($arFactura->getVrNeto());
        $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($valor);
        self::$strLetras = $strLetras;
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
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(25, 50);
        $this->Cell(25, 5, "Fecha Factura:");
        $this->SetFont('Arial', '', 7.7);
        $this->Cell(26, 5, ucwords(strtolower($this->devuelveMes($arFactura->getFecha()->format('m')))) . " " . $arFactura->getFecha()->format('d') . " de " . $arFactura->getFecha()->format('Y'));
        $this->SetX(100);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Fecha Vence:");
        $this->SetFont('Arial', '', 7.7);
        $this->Cell(26, 5, ucwords(strtolower($this->devuelveMes($arFactura->getFechaVence()->format('m')))) . " " . $arFactura->getFechaVence()->format('d') . " de " . $arFactura->getFechaVence()->format('Y'));
        $this->SetXY(25, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Nit:");
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 5, $arFactura->getClienteRel()->getNit());
        $this->SetX(100);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Cliente:");
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, utf8_decode($arFactura->getClienteRel()->getNombreCorto()));
        $this->SetXY(25, 60);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Direccion:");
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 5, $arFactura->getClienteRel()->getDireccion());
        $this->SetX(100);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Telefono:");
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, $arFactura->getClienteRel()->getTelefono());
        $this->SetXY(25, 65);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "No Factura:");
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 5, $arFactura->getCodigoFacturaPk());
        $this->SetX(100);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "Fecha Proceso:");
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, ucwords(strtolower($this->devuelveMes($arFactura->getFecha()->format('m')))) . " " . $arFactura->getFecha()->format('d') . " de " . $arFactura->getFecha()->format('Y'));
        $this->SetXY(110, 60);
        $this->SetMargins(10, 1, 10);
        $this->EncabezadoDetalles();
        
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $this->SetX(15);
        $header = array('ITEM', 'CONCEPTO','Nro F', 'Vr. UNITARIO', 'Vr. TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 9);

        //creamos la cabecera de la tabla.
        $w = array(10, 110,15, 28, 28);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L');
            else
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Rect(15, 81, 10, 100);
        $this->Rect(25, 81, 110, 100);
        $this->Rect(135, 81, 15, 100);
        $this->Rect(150, 81, 28, 100);
        $this->Rect(178, 81, 28, 100);
        $this->Ln(8);
    }

    public function Body($pdf) {

        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);         
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        $pdf->SetFont('Arial', '', 9);
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $pdf->SetX(15);
            $pdf->Cell(10, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');                        
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(110, 4, utf8_decode($arFacturaDetalle->getFacturaConceptoRel()->getNombre()), 0, 0, 'L');                        
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(15, 4, number_format($arFacturaDetalle->getFacturaDetalleRel()->getFacturaRel()->getNumero(), 0, '.', ','), 0, 0, 'C');
            $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(28, 4, number_format($arFacturaDetalle->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');
            $pdf->Ln();
            $pdf->SetX(15);
            $pdf->Cell(10, 4, '', 0, 0, 'R');
            $pdf->Cell(110, 4, '', 0, 0, 'R');
            $pdf->Cell(15, 4, '', 0, 0, 'R');
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
        $this->Cell(134, 7, '', 1, 0, 'R');
        $this->SetXY(15,203);
        $this->Cell(134, 14, '', 1, 0, 'R');
        $this->SetXY(15,217);
        $this->Cell(134, 21, '', 1, 0, 'R');
        $this->SetXY(149,196);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(28, 7, 'SUB TOTAL', 1, 0, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Cell(28, 7, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149,203);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(28, 7, 'Base Gravable', 1, 0, 'L');  
        $this->SetFont('Arial', '', 9);
        $this->Cell(28, 7, number_format($arFactura->getVrBaseAIU(), 0, '.', ',') , 1, 0, 'R');
        $this->SetXY(149,210);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(28, 7, 'IVA', 1, 0, 'L'); 
        $this->SetFont('Arial', '', 9);
        $this->Cell(28, 7, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R'); 
        $this->SetXY(149,217);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(28, 7, 'Rete Iva', 1, 0, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Cell(28, 7, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R'); 
        $this->SetXY(149,224);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(28, 7, 'Rete Fuente', 1, 0, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Cell(28, 7, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(149,231);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(28, 7, 'TOTAL', 1, 0, 'L');
        $this->SetFont('Arial', '', 9);
        $this->Cell(28, 7, number_format($arFactura->getVrNeto(), 0, '.', ','), 1, 0, 'R');
        $this->SetFont('Arial', '', 8);
        $plazoPago = $arFactura->getClienteRel()->getPlazoPago();
        $this->Text(20, 200, substr(strtoupper(self::$strLetras), 0, 96), 1, 0, 'L');
        $this->SetXY(19, 207);        
        $this->MultiCell(120, 3, "NOTA: ". utf8_decode($arFactura->getComentarios()), 0, 'L');
        $this->Text(20, 236, "FIRMA Y SELLO");
        $this->SetFont('Arial', '', 9);
        $this->Ln(4);

        //Número de página
        $this->Text(180, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
    }

    public function GenerarEncabezadoFactura($em) {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionRecursoHumano = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionRecursoHumano = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
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
        $this->Cell(120, 4, "REGIMEN COMUN", 0, 0, 'C', 0);
        $this->SetXY(15, 34);
        $this->SetFont('Arial','b',8);
        //$this->Cell(120, 4, utf8_decode($arConfiguracionRecursoHumano->getInformacionResolucionDianFactura()), 0, 0, 'C', 0);
        $this->MultiCell(190, 3,  utf8_decode($arConfiguracionRecursoHumano->getInformacionResolucionDianFactura()), 0, 'C');
        $this->SetXY(50, 40);
        $this->Cell(120, 4, "__________________________________________________________________________________________________________________________", 0, 0, 'C', 0);
    }

}

?>
