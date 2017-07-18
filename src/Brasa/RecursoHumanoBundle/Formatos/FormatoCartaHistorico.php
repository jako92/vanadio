<?php

namespace Brasa\RecursoHumanoBundle\Formatos;

class FormatoCartaHistorico extends \FPDF_FPDF {

    public static $em;
    public static $codigoEmpleado;

    public function Generar($em, $codigoEmpleado) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoEmpleado = $codigoEmpleado;
        $pdf = new FormatoCartaHistorico();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf); //Contenido de la carta al principio de la hoja
        $pdf->Output("FormatoCartaHistorico$codigoEmpleado.pdf", 'D');
    }

    public function Header() {
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(5); //Contenido del formato de carta laboral creado por datafixture
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 40);
        $this->Line(10, 40, 60, 40);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg', 15, 15, 40, 20, 'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuadro mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(90, 10, utf8_decode($arContenidoFormato->getTitulo()), 1, 0, 'C', 1); //cuadro mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(60, 30);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuadro mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 10, utf8_decode("Código: ") . $arContenidoFormato->getCodigoFormatoIso(), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 30);
        $this->Cell(50, 5, utf8_decode("Versión: ") . $arContenidoFormato->getVersion(), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 35);
        $fechaVerson = "";
        if ($arContenidoFormato->getFechaVersion() != null) {
            $fechaVerson = $arContenidoFormato->getFechaVersion()->format('Y-m-d');
        }
        $this->Cell(50, 5, "Fecha: " . $fechaVerson, 1, 0, 'C', 1); //cuadro derecho abajo 2

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {

        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(10);
        $this->SetXY(10, 10);
        $this->Ln(10);
        //$this->Cell(0, 0, $this->Image('imagenes/logos/firmanomina.jpg' , 15 ,150, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 45);
        $pdf->SetFont('Arial', '', 8);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find(self::$codigoEmpleado);
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(5); //carta laboral historico
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);


        //se reemplaza el contenido de la tabla contenido formato
        $sustitucion1 = $arConfiguracion->getNombreEmpresa();
        $sustitucion2 = $arEmpleado->getNombreCorto();
        $sustitucion3 = $arEmpleado->getNumeroIdentificacion();
        $sustitucion4 = $arEmpleado->getCiudadExpedicionRel()->getNombre();

        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        

        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");

        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron2, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $pdf->MultiCell(0, 5, $cadenaCambiada);
        
        $i=1;
        $i2=2;
        $pdf->SetXY(10, 100);
        foreach ($arContratos as $arContrato) {
            //Se empieza a recorrer cada contrato.
            $sustitucion5 = $arContrato->getFechaDesde()->format('Y/m/d');
            $contratoDesde = $arContrato->getFechaDesde();
            $contratoDesde = strftime("%d de " . $this->MesesEspañol($contratoDesde->format('m')) . " de %Y", strtotime($sustitucion5));
            $sustitucion1 = $contratoDesde;
            $sustitucion6 = $arContrato->getFechaHasta()->format('Y/m/d');
            $contratoHasta = $arContrato->getFechaHasta();
            $contratoHasta = strftime("%d de " . $this->MesesEspañol($contratoHasta->format('m')) . " de %Y", strtotime($sustitucion6));
            $sustitucion2 = $contratoHasta;
            if($arContrato->getEstadoActivo() == 0){
            $estado = "inactivo";}else{$estado = "activo";}
            $sustitucion10 = "Contrato ".$i.": Desde ". $contratoDesde ." Hasta ". $contratoHasta ." Cargo ". $arContrato->getCargoRel()->getNombre()." Estado ".  $estado;
            if ($i >= $i2){
                $pdf->SetXY(10, 100+$i+$i2);
            }
            $pdf->MultiCell(0, 5, $sustitucion10);
            $i++;
        }
    }

    public function Footer() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);

        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

    public static function MesesEspañol($mes) {
        $mesEspañol = "";
        if ($mes == '01') {
            $mesEspañol = "Enero";
        }
        if ($mes == '02') {
            $mesEspañol = "Febrero";
        }
        if ($mes == '03') {
            $mesEspañol = "Marzo";
        }
        if ($mes == '04') {
            $mesEspañol = "Abril";
        }
        if ($mes == '05') {
            $mesEspañol = "Mayo";
        }
        if ($mes == '06') {
            $mesEspañol = "Junio";
        }
        if ($mes == '07') {
            $mesEspañol = "Julio";
        }
        if ($mes == '08') {
            $mesEspañol = "Agosto";
        }
        if ($mes == '09') {
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10') {
            $mesEspañol = "Octubre";
        }
        if ($mes == '11') {
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12') {
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }

}

?>
