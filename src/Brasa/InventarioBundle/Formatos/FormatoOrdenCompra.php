<?php

namespace Brasa\InventarioBundle\Formatos;

class FormatoOrdenCompra extends \FPDF_FPDF { //jg

    public static $em;
    public static $codigoOrdenCompra;
    public static $strLetras;

    public function Generar($em, $codigoOrdenCompra) { //jg

        self::$em = $em;
        self::$codigoOrdenCompra = $codigoOrdenCompra;
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra = $em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find($codigoOrdenCompra);
        $valor = round($arOrdenCompra->getVrNeto());
        $strLetras = "";
        if($valor > 0) {
            $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($valor);
        }
        
        self::$strLetras = $strLetras;
        ob_clean();
        //$pdf = new Movimiento3(); //jg
        $pdf = new FormatoOrdenCompra('P','mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Movimiento$codigoOrdenCompra.pdf", 'D');
    }

    public function Header() {
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra = self::$em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find(self::$codigoOrdenCompra);       
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("ORDEN DE COMPRA"), 0, 0, 'C', 1);
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
        
        //ENCABEZADO ORDEN DE COMPRA
        $intY = 40;
        $this->SetFillColor(272, 272, 272); 
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NUMERO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arOrdenCompra->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arOrdenCompra->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);       

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "PROVEEDOR:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA ENTREGA" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arOrdenCompra->getFechaEntrega()->format('Y/m/d'), 1, 0, 'L', 1);               
        
        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NIT:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getNit(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'FORMA DE PAGO::' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getFormaPagoRel()->getNombre(), 1, 0, 'L', 1);
        
        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "DIRECCION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'PLAZO DE PAGO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getPlazoPagoProveedor(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "TIPO ORDEN:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arOrdenCompra->getOrdenCompraDocumentoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'SOPORTE:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arOrdenCompra->getSoporte(), 1, 0, 'L', 1);        

        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "TELEFONO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CELULAR:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arOrdenCompra->getTerceroRel()->getCelular(), 1, 0, 'L', 1);                                     
        
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        
        $this->Ln(14);
        $header = array('COD', 'ITEM', '% IVA', 'CANT', 'VALOR', 'SUBTOTAL', 'IVA', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 65, 15, 15, 20, 20, 20, 25);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
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
        
        $arOrdenCompra = new \Brasa\InventarioBundle\Entity\InvOrdenCompra();
        $arOrdenCompra = self::$em->getRepository('BrasaInventarioBundle:InvOrdenCompra')->find(self::$codigoOrdenCompra);
        $arOrdenCompraDetalles = new \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle();
        $arOrdenCompraDetalles = self::$em->getRepository('BrasaInventarioBundle:InvOrdenCompraDetalle')->findBy(array('codigoOrdenCompraFk' => self::$codigoOrdenCompra));  
                $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arOrdenCompraDetalles as $arOrdenCompraDetalle) {            
            $pdf->Cell(10, 4, $arOrdenCompraDetalle->getCodigoDetalleOrdenCompraPk(), 1, 0, 'L');
            $pdf->Cell(65, 4, $arOrdenCompraDetalle->getItemRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arOrdenCompraDetalle->getPorcentajeIva(), 1, 0, 'C');
            $pdf->Cell(15, 4, $arOrdenCompraDetalle->getCantidad(), 1, 0, 'C');                
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getValor(), 0, '.', ','), 1, 0, 'R');                
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');                                     
            $pdf->Cell(20, 4, number_format($arOrdenCompraDetalle->getVrIva(), 0, '.', ','), 1, 0, 'R');                
    
            $pdf->Cell(25, 4,  number_format($arOrdenCompraDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
                //TOTALES
                $pdf->Ln(2);
                $pdf->Cell(145, 4, "", 0, 0, 'R');
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetFillColor(236, 236, 236);
                $pdf->Cell(20, 4, "SUBTOTAL:", 1, 0, 'R',true);
                $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(145, 4, "", 0, 0, 'R');
                $pdf->Cell(20, 4, "IVA:", 1, 0, 'R',true);
                $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrIva(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(145, 4, "", 0, 0, 'R');
                $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R',true);
                $pdf->Cell(25, 4, number_format($arOrdenCompra->getVrNeto(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln(-8);

    }    

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');

    }
}
?>
