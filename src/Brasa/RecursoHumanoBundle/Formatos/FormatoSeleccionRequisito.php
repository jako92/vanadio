<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class FormatoSeleccionRequisito extends \FPDF_FPDF {
    public static $em;
    public static $codigoSeleccionRequisito;
    
    public function Generar($em, $codigoSeleccionRequisito) {        
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoSeleccionRequisito = $codigoSeleccionRequisito;
        $pdf = new FormatoSeleccionRequisito();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);

        $pdf->Output("SeleccionRequisicion$codigoSeleccionRequisito.pdf", 'D');        
        
    } 
    
    public function Header() {
        $arSeleccionRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arSeleccionRequisito = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find(self::$codigoSeleccionRequisito);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arContenidoFormatoA = new \Brasa\GeneralBundle\Entity\GenContenidoFormatoSecundario();
        $arContenidoFormatoA = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormatoSecundario')->find(1);
        $this->SetFillColor(200, 200, 200);        
        $this->SetFont('Arial','B',10);
        //Logo
        $this->SetXY(53, 10);
        $this->Image('imagenes/logos/logo.jpg', 12, 7, 35, 17);
        //INFORMACIÓN EMPRESA
        $this->Cell(150, 7, utf8_decode("REQUISITO SELECCIÓN ". $arSeleccionRequisito->getCodigoSeleccionRequisitoPk()." ". $arSeleccionRequisito->getNombre()), 0, 0, 'C', 1);
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
        //FORMATO ISO
        $this->SetXY(168, 18);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(35, 6, "CODIGO: ".$arContenidoFormatoA->getCodigoFormatoIso(), 1, 0, 'L', 1);
        $this->SetXY(168, 24);
        $this->Cell(35, 6, utf8_decode("VERSIÓN: ".$arContenidoFormatoA->getVersion()), 1, 0, 'L', 1);
        $this->SetXY(168, 30);
        $this->Cell(35, 6, utf8_decode("FECHA: ".$arContenidoFormatoA->getFechaVersion()->format('Y-m-d')), 1, 0, 'L', 1);
        //ENCABEZADO
        $this->SetXY(10, 40);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CÓDIGO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getCodigoSeleccionRequisitoPk() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, $arSeleccionRequisito->getFecha()->format('Y/m/d') , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "BRUTO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        if ($arSeleccionRequisito->getEstadoCerrado() == 1){
           $estadoCerrado = "SI"; 
        } else {
           $estadoCerrado = "NO";  
        }
        $this->Cell(50, 6, $estadoCerrado , 1, 0, 'L', 1);
        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("NOMBRE:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "CENTRO COSTO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, $arSeleccionRequisito->getCentroCostoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "CARGO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getCargoRel()->getNombre() , 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CANTIDAD:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getCantidadSolicitada() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "ESTADO CIVIL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $estadoCivil = "";
        if ($arSeleccionRequisito->getCodigoEstadoCivilFk() != null){
            $estadoCivil = $arSeleccionRequisito->getEstadoCivilRel()->getNombre();
        }
        $this->Cell(22, 6, $estadoCivil , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "CIUDAD:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $ciudad = "";
        if ($arSeleccionRequisito->getCodigoCiudadFk() <> null){
            $arSeleccionRequisito->getCiudadRel()->getNombre();
        }
        $this->Cell(50, 6, $ciudad , 1, 0, 'L', 1);
        //linea 4
        $this->SetXY(10, 55);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("NIVEL ESTUDIO:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getEstudioTipoRel()->getNombre() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "EDAD MINIMA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, $arSeleccionRequisito->getEdadMaxima() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "NRO HIJOS:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getNumeroHijos() , 1, 0, 'L', 1);
        //linea 5
        $salarioTipo = "";
        if($arSeleccionRequisito->getSalarioFijo() == "1"){
            $salarioTipo = "FIJO";
        }
        if($arSeleccionRequisito->getSalarioVariable() == "1"){
            $salarioTipo = "VARIABLE";
        }
        $this->SetXY(10, 60);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("CLIENTE REF:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $arSeleccionRequisito->getClienteReferencia() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(25, 6, "SALARIO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(22, 6, $arSeleccionRequisito->getVrSalario() , 1, 0, 'L', 1);
        $this->SetFont('Arial','B',7);
        $this->Cell(22, 6, "TIPO SALARIO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(50, 6, $salarioTipo , 1, 0, 'L', 1);
        //linea 6
        $this->SetXY(10, 65);
        $this->SetFont('Arial','B',7);
        $this->Cell(24, 6, utf8_decode("COMENTARIOS:") , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(169, 6, $arSeleccionRequisito->getComentarios() , 1, 0, 'L', 1);
        $this->EncabezadoDetalles();
        
    }

    public function EncabezadoDetalles() {
        
        $this->Ln(14);
        $header = array('ID', 'TIPO', 'FECHA PROCESO','IDENTIFICACION', 'NOMBRE', 'TELEFONO', 'CELULAR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 40, 22, 22, 65, 15, 20);
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
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSelecciones = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionRequisitoFk' => self::$codigoSeleccionRequisito));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arSelecciones as $arSeleccion) {            
            $pdf->Cell(10, 4, $arSeleccion->getCodigoSeleccionPk(), 1, 0, 'L');
            $pdf->Cell(40, 4, utf8_decode($arSeleccion->getSeleccionTipoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(22, 4, $arSeleccion->getFecha()->format('Y-m-d'), 1, 0, 'L');
            $pdf->Cell(22, 4, $arSeleccion->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(65, 4, utf8_decode($arSeleccion->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(15, 4, $arSeleccion->getTelefono(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arSeleccion->getCelular(), 1, 0, 'L');            
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }        
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);  
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }    
}

?>
