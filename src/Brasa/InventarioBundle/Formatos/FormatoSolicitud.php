<?php

namespace Brasa\InventarioBundle\Formatos;

class FormatoSolicitud extends \FPDF_FPDF { //jg

    public static $em;
    public static $codigoSolicitud;
    public static $strLetras;

    public function Generar($em, $codigoSolicitud) { //jg

        self::$em = $em;
        self::$codigoSolicitud = $codigoSolicitud;
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
        $valor = round($arSolicitud->getVrNeto());
        $strLetras = "";
        if($valor > 0) {
            $strLetras = \Brasa\GeneralBundle\MisClases\Funciones::devolverNumeroLetras($valor);
        }
        
        self::$strLetras = $strLetras;
        ob_clean();
        //$pdf = new Movimiento3(); //jg
        $pdf = new FormatoSolicitud('P','mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("Movimiento$codigoSolicitud.pdf", 'D');
    }

    public function Header() {
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = self::$em->getRepository('BrasaInventarioBundle:InvSolicitud')->find(self::$codigoSolicitud);       
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("SOLICITUD"), 0, 0, 'C', 1);
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
        $this->Cell(65, 4, $arSolicitud->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arSolicitud->getFecha()->format('Y/m/d'), 1, 0, 'L', 1); 
       
        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "TIPO ORDEN:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arSolicitud->getSolicitudDocumentoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'SOPORTE:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arSolicitud->getSoporte(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial','B',8);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA ENTREGA" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(160, 4, $arSolicitud->getFechaEntrega()->format('Y/m/d'), 1, 0, 'L', 1); 

                               
        
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
        
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = self::$em->getRepository('BrasaInventarioBundle:InvSolicitud')->find(self::$codigoSolicitud);
        $arSolicitudDetalles = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
        $arSolicitudDetalles = self::$em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->findBy(array('codigoSolicitudFk' => self::$codigoSolicitud));  
                $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSolicitudDetalles as $arSolicitudDetalle) {            
            $pdf->Cell(10, 4, $arSolicitudDetalle->getCodigoDetalleSolicitudPk(), 1, 0, 'L');
            $pdf->Cell(65, 4, $arSolicitudDetalle->getItemRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arSolicitudDetalle->getPorcentajeIva(), 1, 0, 'C');
            $pdf->Cell(15, 4, $arSolicitudDetalle->getCantidad(), 1, 0, 'C');                
            $pdf->Cell(20, 4, number_format($arSolicitudDetalle->getValor(), 0, '.', ','), 1, 0, 'R');                
            $pdf->Cell(20, 4, number_format($arSolicitudDetalle->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');                                     
            $pdf->Cell(20, 4, number_format($arSolicitudDetalle->getVrIva(), 0, '.', ','), 1, 0, 'R');                
    
            $pdf->Cell(25, 4,  number_format($arSolicitudDetalle->getVrTotal(), 0, '.', ','), 1, 0, 'R');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
                //TOTALES
                $pdf->Ln(2);
                $pdf->Cell(145, 4, "", 0, 0, 'R');
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetFillColor(236, 236, 236);
                $pdf->Cell(20, 4, "SUBTOTAL:", 1, 0, 'R',true);
                $pdf->Cell(25, 4, number_format($arSolicitud->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(145, 4, "", 0, 0, 'R');
                $pdf->Cell(20, 4, "IVA:", 1, 0, 'R',true);
                $pdf->Cell(25, 4, number_format($arSolicitud->getVrIva(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->Cell(145, 4, "", 0, 0, 'R');
                $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R',true);
                $pdf->Cell(25, 4, number_format($arSolicitud->getVrNeto(), 0, '.', ','), 1, 0, 'R');
                $pdf->Ln(-8);

    }    

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');

    }
}
?>
