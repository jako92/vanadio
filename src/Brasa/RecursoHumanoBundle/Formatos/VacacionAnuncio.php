<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class VacacionAnuncio extends \FPDF_FPDF {
    public static $em;    
    public static $codigoVacacion;    
    public static $usuario;
    
    public function Generar($em, $codigoVacacion, $usuario) {        
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoVacacion = $codigoVacacion;
        self::$usuario = $usuario;
        $pdf = new VacacionAnuncio();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CartaAnuncioVacacion$codigoVacacion.pdf", 'D');        
    } 
    
    public function Header() {
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 50);
        $this->Line(10, 50, 60, 50);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,20, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',16);
        $this->Cell(90, 20, utf8_decode("CARTA PRESENTACIÓN"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(60, 40);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 20, utf8_decode(""), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 40);
        $this->Cell(50, 5, utf8_decode("Versión 01"), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 45);
        $this->Cell(50, 5, "Fecha: 01/09/2015", 1, 0, 'C', 1); //cuadro derecho abajo 2
        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);        
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(19);        
        $this->SetXY(10, 10);
        $this->Ln(10);
        //$this->Cell(0, 0, $this->Image('imagenes/logos/firmanomina.jpg' , 15 ,150, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 80);
        $pdf->SetFont('Arial', '', 10);  
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenidoFormato')->find(19);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $pdf->Text(10, 60, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). ", ". strftime("%d de %B de %Y", strtotime(date('Y-m-d'))));
        $usuarioCarta = self::$usuario;
        $usuarioCarta = $usuarioCarta->getNombreCorto();
        //se reemplaza el contenido de la tabla contenido formato
        $sustitucion1 = $arVacacion->getEmpleadoRel()->getNumeroIdentificacion();
        $sustitucion2 = $arVacacion->getEmpleadoRel()->getNombreCorto();
        
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
    }

    public function Footer() {
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);
        $strRutaImagen = '';
        if($arVacacion->getEmpleadoRel()->getRutaFoto() != "") {
            $strRutaImagen = $arConfiguracion->getRutaAlmacenamiento()."imagenes/"."empleados/" . $arVacacion->getEmpleadoRel()->getRutaFoto();
            //$strRutaImagen = "/var/www/html/almacenamientodorchester/imagenes/"."empleados/" . $arVacacion->getEmpleadoRel()->getRutaFoto();
            $this->Cell(0, 0, $this->Image($strRutaImagen , 150 ,200, 25 , 32,'JPG'), 0, 0, 'C', 0); //foto
        }
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
        //$this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
        
    }

    public static function MesesEspañol($mes) {
        $mesEspañol = "";
        if ($mes == '01'){
            $mesEspañol = "Enero";
        }
        if ($mes == '02'){
            $mesEspañol = "Febrero";
        }
        if ($mes == '03'){
            $mesEspañol = "Marzo";
        }
        if ($mes == '04'){
            $mesEspañol = "Abril";
        }
        if ($mes == '05'){
            $mesEspañol = "Mayo";
        }
        if ($mes == '06'){
            $mesEspañol = "Junio";
        }
        if ($mes == '07'){
            $mesEspañol = "Julio";
        }
        if ($mes == '08'){
            $mesEspañol = "Agosto";
        }
        if ($mes == '09'){
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10'){
            $mesEspañol = "Octubre";
        }
        if ($mes == '11'){
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12'){
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }
}

?>
