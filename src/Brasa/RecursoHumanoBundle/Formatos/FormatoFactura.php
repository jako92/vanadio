<?php

namespace Brasa\RecursoHumanoBundle\Formatos;

class FormatoFactura extends \FPDF_FPDF {

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
        
        $pdf = new FormatoFactura('P','mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {
        
        $this->GenerarEncabezadoFactura(self::$em); 
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $this->SetXY(15, 45);
        $this->SetFont('Arial','B',15);
        $this->Cell(50, 4, "FACTURA DE VENTA", 0, 0, 'L', 0);
        $this->SetXY(178, 45);
        $this->SetFont('Arial','B',12);
        $this->Cell(20, 4, utf8_decode("N°.  "). $arFactura->getNumero(), 0, 0, 'L', 0);//$arFactura->getCodigoFacturaPk(), 0, 0, 'L', 0);
        //
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(15, 50);
        $this->Cell(25, 5, "CLIENTE:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 7.7);
        $this->Cell(115, 5, utf8_decode($arFactura->getClienteRel()->getNombreCorto()), 0, 0, 'L', 1);
        $this->SetXY(156, 50);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "FECHA EMISION:", 0, 0, 'C', 1);
        $this->Cell(25, 5, "FECHA VENCE:", 0, 0, 'C', 1);        
        $this->SetXY(15, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "NIT:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, $arFactura->getClienteRel()->getNit(), 0, 0, 'L', 1);
        $this->SetXY(156, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, $arFactura->getFecha()->format('Y-m-d'), 0, 0, 'C', 1);
        $this->Cell(25, 5, $arFactura->getFechaVence()->format('Y-m-d'), 0, 0, 'C', 1);
        $this->SetXY(15, 60);
        $this->Cell(25, 5, "CIUDAD:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(95, 5, utf8_decode($arFactura->getClienteRel()->getCiudadRel()->getNombre()), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);        
        $this->Cell(20, 5, "", 0, 0, 'L', 1);        
        $this->SetFont('Arial', '', 8);
        $this->SetXY(156, 60);
        $this->Cell(50.2, 5, '', 0, 0, 'L', 1);
        $this->SetXY(15, 65);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "DIRECCION:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(115, 5, $arFactura->getClienteRel()->getDireccion(), 0, 0, 'L', 1);
        $this->SetXY(156, 65);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, utf8_decode(""), 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(25, 5, "", 0, 0, 'L', 1);
        $this->SetXY(15, 70);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "TELEFONO:", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(50, 5, $arFactura->getClienteRel()->getTelefono(), 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, "", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(45, 5, "", 0, 0, 'L', 1);
        $this->SetXY(156, 70);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 5, "FORMA PAGO", 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(25, 5, utf8_decode($arFactura->getClienteRel()->getFormaPagoRel()->getNombre()), 0, 0, 'L', 1);
        //$this->Text(45, 70, utf8_decode($arFactura->getClienteRel()->getNombreCompleto()));
        /*$this->SetXY(44, 68);
        $this->MultiCell(90, 4, $arFactura->getClienteRel()->getNombreCompleto(), 0, 'L');
        $this->Text(135, 70, "Nit");
        $this->Text(170, 70, $arFactura->getClienteRel()->getNit(). "-" . $arFactura->getClienteRel()->getDigitoVerificacion());
        $this->Text(15, 80, "Direccion");
        //$this->Text(45, 80, utf8_decode($arFactura->getClienteRel()->getDireccion()));
        $this->SetXY(44, 77);
        $this->MultiCell(90, 4,  $arFactura->getClienteRel()->getDireccion(), 0, 'L');
        $this->Text(135, 80, "Telefono");
        $this->Text(170, 80, $arFactura->getClienteRel()->getTelefono());                */

        $this->SetXY(110, 65);
        $this->SetMargins(5, 1, 5);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $this->SetX(15);
        $header = array('DESCRIPCION', 'ADMON', 'INGRESO', 'CANT', 'PRECIO', 'TOTAL');
        //$this->SetFillColor(236, 236, 236);
        //$this->SetTextColor(0);
        //$this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7.5);

        //creamos la cabecera de la tabla.
        $w = array(95, 22, 22, 8, 22, 22);
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
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => self::$codigoFactura));
        foreach ($arFacturaDetalles as $arFacturaDetalle) {
            $concepto = $arFacturaDetalle->getFacturaConceptoRel()->getNombre();
            if($arFacturaDetalle->getCodigoCobroFk()) {
               $concepto .=  "[ Servicios del periodo: " . $arFacturaDetalle->getCobroRel()->getFechaDesde()->format('Y-m-d') . " hasta " . $arFacturaDetalle->getCobroRel()->getFechaHasta()->format('Y-m-d') . "]";
            }
            $pdf->SetX(15);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(95, 4, utf8_decode($concepto), 0, 0, 'L');
            $pdf->Cell(22, 4, number_format($arFacturaDetalle->getVrAdministracion(), 0, '.', ','), 0, 0, 'R');
            $pdf->Cell(22, 4, number_format($arFacturaDetalle->getVrOperacion(), 0, '.', ','), 0, 0, 'R');            
            $pdf->Cell(8, 4, number_format($arFacturaDetalle->getCantidad(), 0, '.', ','), 0, 0, 'C');                
            $pdf->Cell(22, 4, number_format($arFacturaDetalle->getVrPrecio(), 0, '.', ','), 0, 0, 'R');            
            $pdf->Cell(22, 4, number_format($arFacturaDetalle->getVrSubtotal(), 0, '.', ','), 0, 0, 'R');  
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
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find(self::$codigoFactura);
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $this->Rect(15, 77, 95, 97);
        $this->Rect(110, 77, 22, 97);
        $this->Rect(132, 77, 22, 97);
        $this->Rect(154, 77, 8, 97);
        $this->Rect(162, 77, 22, 97);
        $this->Rect(184, 77, 22, 97);
        $this->SetFillColor(200, 200, 200);
        $this->SetXY(15,174);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, 'OBSERVACIONES:', 1, 0, 'L');        
        $this->Cell(107, 6, '', 1, 0, 'L');        
        $this->Cell(22, 6, 'SUBTOTAL:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(15,181);
        //$this->Cell(147, 36, '', 1, 0, 'L');
        $this->SetFont('Arial', 'B', 7);
        $this->Rect(15, 180, 147, 30);
        $this->MultiCell(145, 4,  utf8_decode($arFactura->getComentarios()), 0, 'L');
        $this->SetXY(162,180);
        $this->SetFont('Arial', 'B', 8);        
        $this->Cell(22, 6, 'BASE AIU:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrBaseAIU(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(162,186);                
        $this->Cell(22, 6, 'RETE FTE:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(162,192);                
        $this->Cell(22, 6, 'CREE (0.8%):', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionCree(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(162,198);                
        $this->Cell(22, 6, 'IVA:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(162,204);        
        $this->Cell(22, 6, 'RETE IVA:', 1, 0, 'L');
        $this->Cell(22, 6, number_format($arFactura->getVrRetencionIva(), 0, '.', ','), 1, 0, 'R');
        $this->SetXY(15,210);        
        $this->Cell(147, 6, substr(strtoupper(self::$strLetras), 0, 96), 1, 0, 'L',1);
        $this->Cell(22, 6, 'TOTAL', 1, 0, 'L',1);
        $this->Cell(22, 6, number_format($arFactura->getVrNeto(), 0, '.', ','), 1, 0, 'R',1);
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
        $this->SetXY(15,228);
        $this->SetFont('Arial', 'B', 7.7);
        $this->MultiCell(191, 3.6, utf8_decode($arConfiguracion->getInformacionLegalFactura()), 1, 'L');        
        $this->SetXY(15,246);
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell(191, 3.9, utf8_decode($arConfiguracion->getInformacionPagoFactura()), 1, 'C');
        $this->SetXY(15,258);
        //$this->SetFont('Arial', '', 8);
        //$this->MultiCell(191, 3.5, 'Autorizo a la entidad SOGERCOL SERVICIOS TEMPORALES o a quien represente la calidad de acreedor, a reportar, procesar, solicitar o divulgar a cualquier entidad que maneje o administre base de datos la información referente a mi comportamiento comercial.', 1, 'L');        
        $this->SetFont('Arial', 'B', 7);
        $this->Text(15, 273, utf8_decode('Nuestra compañia, en favor del medio ambiente.'));        
        //Número de página
        $this->Text(188, 273, 'Pagina ' . $this->PageNo() . ' de {nb}');
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
