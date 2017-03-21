<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class Reclamo extends \FPDF_FPDF {
    public static $em;    
    public static $codigoReclamo;
    
    public function Generar($em, $codigoReclamo = "") {        
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
        self::$em = $em;
        self::$codigoReclamo = $codigoReclamo;

        $pdf = new Reclamo('P','mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $this->Body($pdf);
        $pdf->Output("Reclamo$codigoReclamo.pdf", 'D');                             
    } 
    
    public function Header() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(5);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 3);
        $this->Image('imagenes/logos/logo.jpg', 12, 5, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, "RECLAMO DE NOMINA", 0, 0, 'C', 1);
        $this->SetXY(53, 11);
        $this->SetFont('Arial','B',9);
        $this->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getNombreEmpresa() . " NIT:" . $arConfiguracion->getNitEmpresa()." - ". $arConfiguracion->getDigitoVerificacionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 15);
        $this->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getDireccionEmpresa(), 0, 0, 'L', 0);
        $this->SetXY(53, 19);
        $this->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $this->Cell(100, 4, $arConfiguracion->getTelefonoEmpresa(), 0, 0, 'L', 0);
        
    }

    public function Body($pdf) {
        $arReclamo = new \Brasa\RecursoHumanoBundle\Entity\RhuReclamo();        
        $arReclamo = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuReclamo')->find(self::$codigoReclamo);                           
        $pdf->SetFillColor(236, 236, 236);        
        $pdf->SetFont('Arial','B',10);
        //linea 1
        $pdf->SetXY(10, 40);
        $pdf->SetFillColor(200, 200, 200); 
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30, 6, utf8_decode("NUMERO:") , 1, 0, 'L', 1);
        $pdf->SetFillColor(272, 272, 272); 
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(106, 6, $arReclamo->getCodigoReclamoPk(), 1, 0, 'L', 1);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(30, 6, "FECHA:" , 1, 0, 'L', 1);
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor(272, 272, 272); 
        $pdf->Cell(30, 6, $arReclamo->getFecha()->format('Y-m-d'), 1, 0, 'L', 1); 
        //linea 1
        $pdf->SetXY(10, 46);
        $pdf->SetFillColor(200, 200, 200); 
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30, 6, "EMPLEADO:" , 1, 0, 'L', 1);
        $pdf->SetFillColor(272, 272, 272); 
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(106, 6, utf8_decode($arReclamo->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(30, 6, "SOLUCION:" , 1, 0, 'L', 1);
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor(272, 272, 272);
        $fechaSolucion = "";
        if($arReclamo->getFechaSolucion()) {
            $fechaSolucion = $arReclamo->getFechaSolucion()->format('Y-m-d');
        }
        $pdf->Cell(30, 6, $fechaSolucion, 1, 0, 'L', 1); 
        
        $pdf->SetXY(10, 55);        
        $pdf->MultiCell(0,4, "RECLAMACION: " . $arReclamo->getReclamo(), 1);
        $pdf->MultiCell(0,4, "RESPUESTA: " . $arReclamo->getComentarios(), 1);
        $pdf->ln(25);
        $pdf->Cell(30, 6, "FIRMA: _____________________________________________", 0, 0, 'L', 1);
                           
    }

    public function Footer() {
        
        //$this->SetFont('Arial','', 8);  
        //$this->Text(185, 140, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }   
    
    private function convertirArray($arSoportePagoProgramacion) {
        $arrProgramacionDetalle = array();
        if($arSoportePagoProgramacion) {
            $arrProgramacionDetalle[1] = $arSoportePagoProgramacion->getDia1();
            $arrProgramacionDetalle[2] = $arSoportePagoProgramacion->getDia2();
            $arrProgramacionDetalle[3] = $arSoportePagoProgramacion->getDia3();
            $arrProgramacionDetalle[4] = $arSoportePagoProgramacion->getDia4();
            $arrProgramacionDetalle[5] = $arSoportePagoProgramacion->getDia5();
            $arrProgramacionDetalle[6] = $arSoportePagoProgramacion->getDia6();
            $arrProgramacionDetalle[7] = $arSoportePagoProgramacion->getDia7();
            $arrProgramacionDetalle[8] = $arSoportePagoProgramacion->getDia8();
            $arrProgramacionDetalle[9] = $arSoportePagoProgramacion->getDia9();
            $arrProgramacionDetalle[10] = $arSoportePagoProgramacion->getDia10();
            $arrProgramacionDetalle[11] = $arSoportePagoProgramacion->getDia11();
            $arrProgramacionDetalle[12] = $arSoportePagoProgramacion->getDia12();
            $arrProgramacionDetalle[13] = $arSoportePagoProgramacion->getDia13();
            $arrProgramacionDetalle[14] = $arSoportePagoProgramacion->getDia14();
            $arrProgramacionDetalle[15] = $arSoportePagoProgramacion->getDia15();
            $arrProgramacionDetalle[16] = $arSoportePagoProgramacion->getDia16();
            $arrProgramacionDetalle[17] = $arSoportePagoProgramacion->getDia17();
            $arrProgramacionDetalle[18] = $arSoportePagoProgramacion->getDia18();
            $arrProgramacionDetalle[19] = $arSoportePagoProgramacion->getDia19();
            $arrProgramacionDetalle[20] = $arSoportePagoProgramacion->getDia20();
            $arrProgramacionDetalle[21] = $arSoportePagoProgramacion->getDia21();
            $arrProgramacionDetalle[22] = $arSoportePagoProgramacion->getDia22();
            $arrProgramacionDetalle[23] = $arSoportePagoProgramacion->getDia23();
            $arrProgramacionDetalle[24] = $arSoportePagoProgramacion->getDia24();
            $arrProgramacionDetalle[25] = $arSoportePagoProgramacion->getDia25();
            $arrProgramacionDetalle[26] = $arSoportePagoProgramacion->getDia26();
            $arrProgramacionDetalle[27] = $arSoportePagoProgramacion->getDia27();
            $arrProgramacionDetalle[28] = $arSoportePagoProgramacion->getDia28();
            $arrProgramacionDetalle[29] = $arSoportePagoProgramacion->getDia29();
            $arrProgramacionDetalle[30] = $arSoportePagoProgramacion->getDia30();
            $arrProgramacionDetalle[31] = $arSoportePagoProgramacion->getDia31();
        }
        return $arrProgramacionDetalle;
    }    
    
}

?>
