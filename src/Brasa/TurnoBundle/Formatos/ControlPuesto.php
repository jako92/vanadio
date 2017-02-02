<?php

namespace Brasa\TurnoBundle\Formatos;

class ControlPuesto extends \FPDF_FPDF {

    public static $em;
    public static $codigoControlPuesto;
    public static $arUsuario;

    public function Generar($em, $codigoControlPuesto) {
        ob_clean();
        self::$em = $em;
        self::$codigoControlPuesto = $codigoControlPuesto;
        $pdf = new ControlPuesto('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $this->Body($pdf);
        $pdf->Output("ControlPuesto$codigoControlPuesto.pdf", 'D');
    }

    public function Header() {
        
      
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);

        $this->SetXY(35, 5);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, "CONTROL DE PUESTOS", 0, 0, 'C', 1); //$this->Cell(150, 7, utf8_decode("COMPROBANTE PAGO ". $arPago->getPagoTipoRel()->getNombre().""), 0, 0, 'C', 1);
        $this->SetFont('Arial', 'B', 9);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->SetXY(10, 35);
        //$this->Ln(45);
        $header = array('ID', 'CLIENTE', 'PUESTO', 'NUMERO-C', 'NOVEDAD', 'FECHA');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6.8);
        //creamos la cabecera de la tabla.
        $w = array(10, 42, 51, 18, 50, 25,);
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
        
        $arCodigoPuesto = new \Brasa\TurnoBundle\Entity\TurControlPuesto();
        $arCodigoPuesto= self::$em->getRepository('BrasaTurnoBundle:TurControlPuesto')->find(self::$codigoControlPuesto);

        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetFillColor(200, 200, 200);
        $y = 25;
        //FILA 1
        $pdf->SetXY(10, $y);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->Cell(22, 6, "FECHA:", 1, 0, 'L', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(70, 6, $arCodigoPuesto->getFecha()->format("Y/m/d H:i:s"), 1, 0, 'L', 1);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->Cell(24, 6, "USUARIO:", 1, 0, 'L', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(80, 6, $arCodigoPuesto->getUsuario(), 1, 0, 'L', 1);
        
    

        $arControlPuestoDetalles = new \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle();
        $arControlPuestoDetalles = self::$em->getRepository('BrasaTurnoBundle:TurControlPuestoDetalle')->findBy(array('codigoControlPuestoFk' => self::$codigoControlPuesto));


        $pdf->SetXY(10, 39);
        foreach ($arControlPuestoDetalles as $arControlPuestoDetalle) {
            $fecha = "";
            if($arControlPuestoDetalle->getFecha() != null){
                $fecha = $arControlPuestoDetalle->getFecha()->format("Y/m/d");    
            }
            $cliente= substr($arControlPuestoDetalle->getPuestoRel()->getClienteRel()->getNombreCorto(),0,30);
            $puesto = substr($arControlPuestoDetalle->getPuestoRel()->getNombre(),0,40);
            $pdf->SetFont('Arial', '', 6);
            $pdf->Cell(10, 4, $arControlPuestoDetalle->getCodigoControlPuestoDetallePk(), 1, 0, 'L');
            $pdf->Cell(42, 4, $cliente, 1, 0, 'L');
            $pdf->Cell(51, 4, $puesto, 1, 0, 'L');
            $pdf->Cell(18, 4, $arControlPuestoDetalle->getNumeroComunicacion(), 1, 0, 'L');
            $pdf->Cell(50, 4, $arControlPuestoDetalle->getNovedad(), 1, 0, 'L');
            $pdf->Cell(25, 4, $fecha, 1, 0, 'L');
            //$pdf->Cell(20, 4, number_format($arPagoDetalle->getPorcentaje(), 0, '.', ','), 1, 0, 'R');
            // $w = array(10, 25, 15, 28, 28, 65, 25,);
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);

            $w = array(13, 53, 20, 20, 15, 20, 27, 27);
        }
        $pdf->SetFont('Arial', 'B', 7);
    }

    public function Footer() {

        //$this->SetFont('Arial','', 8);  
        //$this->Text(185, 140, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}

?>