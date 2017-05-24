<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class CobroIncapacidad extends \FPDF_FPDF {
    public static $em;
    public static $codigoCobro;
    
    public function Generar($em, $codigoCobro) {        
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCobro = $codigoCobro;
        $pdf = new CobroIncapacidad('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CobroIncapacidad$codigoCobro.pdf", 'D');                
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->Image('imagenes/logos/logo.jpg', 12, 13, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->SetXY(50, 10);
        $this->Cell(215, 7, utf8_decode("RELACION COBRO"), 0, 0, 'C', 1);
        $this->SetXY(50, 18);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 22);
        $this->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 26);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(50, 30);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);        
        //
        $arCobro = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
        $arCobro = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find(self::$codigoCobro);
        $this->SetFillColor(236, 236, 236);        
        $this->SetFont('Arial','B',10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200); 
        $this->SetFont('Arial','B',8);
        $this->Cell(40, 6, utf8_decode("CÓDIGO CLIENTE:") , 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272); 
        $this->SetFont('Arial','',8);
        $this->Cell(40, 6, $arCobro->getCodigoClienteFk(), 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(50, 6, "CLIENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(125, 6, utf8_decode($arCobro->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 5, utf8_decode("DESDE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, utf8_decode($arCobro->getFechaDesde()->format('Y/m/d')) , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(50, 5, "HASTA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(125, 5, $arCobro->getFechaHasta()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 5, utf8_decode("% ADMIN:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 5, 0 , 1, 0, 'R', 1);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(50, 5, "REGISTROS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272); 
        $this->Cell(125, 5, number_format($arCobro->getNumeroRegistros(), 0, '.', ',') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        //linea 4
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 5, utf8_decode("CENTRO TRABAJO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->SetFillColor(272, 272, 272);
        if ($arCobro->getCentroTrabajoRel()){$centroTrabajo = $arCobro->getCentroTrabajoRel()->getNombre();}else{$centroTrabajo = "";}
        $this->Cell(215, 5, utf8_decode($centroTrabajo) , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',6);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ITEM', 'NRO INCAPACIDAD', 'DOCUMENTO', 'EMPLEADO', 'DESDE','HASTA','TIPO', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 6);

        //creamos la cabecera de la tabla.
        $w = array(8, 22, 30, 100, 20, 20, 35, 20);
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
        $arCobro = new \Brasa\RecursoHumanoBundle\Entity\RhuCobro();
        $arCobro = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCobro')->find(self::$codigoCobro);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoCobroFk' => self::$codigoCobro));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $var = 1;
        foreach ($arIncapacidades as $arIncapacidad) {                        
            $pdf->Cell(8, 6, $var, 1, 0, 'C');
            $pdf->Cell(22, 6, $arIncapacidad->getCodigoIncapacidadPk(), 1, 0, 'C');
            $pdf->Cell(30, 6, $arIncapacidad->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'C');
            $pdf->Cell(100, 6, utf8_decode($arIncapacidad->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(20, 6, $arIncapacidad->getFechaDesde()->format("Y/m/d"), 1, 0, 'C');
            $pdf->Cell(20, 6, $arIncapacidad->getFechaHasta()->format("Y/m/d"), 1, 0, 'C');
            $pdf->Cell(35, 6, $arIncapacidad->getIncapacidadTipoRel()->getNombre(), 1, 0, 'C');
            $pdf->Cell(20, 6, number_format($arIncapacidad->getVrIncapacidad(),0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $var++;
        }   
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(235, 5, "TOTAL: ", 1, 0, 'R');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(20, 5, number_format($arCobro->getVrTotalCobro(),0, '.', ','), 1, 0, 'R');        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
