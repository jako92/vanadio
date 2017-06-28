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
        $this->Body($pdf);//Contenido de la carta al principio de la hoja
        $this->Body2($pdf);//Mitad de la hoja se repite el contenido 
        $pdf->Output("CartaAnuncioVacacion$codigoVacacion.pdf", 'D');        
    } 
    
    public function Header() {
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(1);    
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10);
        $this->SetXY(10, 10);
        $this->Line(10, 10, 60, 10);
        $this->Line(10, 10, 10, 40);
        $this->Line(10, 40, 60, 40);
        $this->Cell(0, 0, $this->Image('imagenes/logos/logo.jpg' , 15 ,15, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $this->SetXY(60, 10);
        $this->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $this->SetXY(60, 20);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial','B',11);
        $this->Cell(90, 10, utf8_decode("COMUNICACION INTERNA Y EXTERNA"), 1, 0, 'C', 1); //cuardo mitad medio
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','B',10); 
        $this->SetXY(60, 30);
        $this->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $this->SetXY(150, 10);
        $this->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $this->SetXY(150, 20);
        $this->Cell(50, 10, utf8_decode("Código: "). $arContenidoFormato->getCodigoFormatoIso(), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $this->SetXY(150, 30);
        $this->Cell(50, 5, utf8_decode("Versión: "). $arContenidoFormato->getVersion(), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $this->SetXY(150, 35);
        $fechaVerson = "";
        if ($arContenidoFormato->getFechaVersion() != null){ 
            $fechaVerson = $arContenidoFormato->getFechaVersion()->format('Y-m-d');    
        }
        $this->Cell(50, 5, "Fecha: ". $fechaVerson, 1, 0, 'C', 1); //cuadro derecho abajo 2
        
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);        
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(1);        
        $this->SetXY(10, 10);
        $this->Ln(10);
        //$this->Cell(0, 0, $this->Image('imagenes/logos/firmanomina.jpg' , 15 ,150, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
    }

    public function Body($pdf) {
        $pdf->SetXY(10, 45);
        $pdf->SetFont('Arial', '', 8);  
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(1);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        //$pdf->Text(10, 60, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). ", ". strftime("%d de %B de %Y", strtotime(date('Y-m-d'))));
        $usuarioCarta = self::$usuario;
        $usuarioCarta = $usuarioCarta->getNombreCorto();
        //se reemplaza el contenido de la tabla contenido formato
        $sustitucion1 = $arVacacion->getEmpleadoRel()->getNombreCorto();
        $sustitucion2 = date('Y/m/d');
        $sustitucion3 = $arVacacion->getFechaDesdeDisfrute()->format('Y/m/d');
        $disfruteDesde = $arVacacion->getFechaDesdeDisfrute();
        $disfruteDesde = strftime("%d de ". $this->MesesEspañol($disfruteDesde->format('m')) ." de %Y", strtotime($sustitucion3));
        $sustitucion3 = $disfruteDesde;
        $sustitucion4 = $arVacacion->getFechaHastaDisfrute()->format('Y/m/d');
        $disfruteHasta = $arVacacion->getFechaHastaDisfrute();
        $disfruteHasta = strftime("%d de ". $this->MesesEspañol($disfruteHasta->format('m')) ." de %Y", strtotime($sustitucion4));
        $sustitucion4 = $disfruteHasta;
        $sustitucion5 = $arVacacion->getFechaInicioLabor()->format('Y/m/d');
        $disfruteInicioLabor = $arVacacion->getFechaInicioLabor();
        $disfruteInicioLabor = strftime("%d de ". $this->MesesEspañol($disfruteInicioLabor->format('m')) ." de %Y", strtotime($sustitucion5));
        $sustitucion5 = $disfruteInicioLabor;
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#a/';
        $patron2 = '/#b/';
        $patron3 = '/#c/';
        $patron4 = '/#d/';
        $patron5 = '/#e/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
        $pdf->MultiCell(0,5, $cadenaCambiada);
    }
    
    public function Body2($pdf) {
        //Encabezado del contenido desde la mitad de la hoja------------------------------------------------------------------------
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(1);    
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetXY(10, 145);
        $pdf->Line(10, 145, 195, 145);
        $pdf->Line(10, 145, 10, 175);
        $pdf->Line(10, 175, 195, 175);
        $pdf->Cell(0, 0, $pdf->Image('imagenes/logos/logo.jpg' , 15 ,150, 40 , 20,'JPG'), 0, 0, 'C', 0); //cuadro para el logo
        $pdf->SetXY(60, 145);
        $pdf->Cell(90, 10, utf8_decode(""), 1, 0, 'C', 1); //cuardo mitad arriba
        $pdf->SetXY(60, 155);
        $pdf->SetFillColor(236, 236, 236);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(90, 10, utf8_decode("COMUNICACION INTERNA Y EXTERNA"), 1, 0, 'C', 1); //cuardo mitad medio
        $pdf->SetFillColor(272, 272, 272);
        $pdf->SetFont('Arial','B',10); 
        $pdf->SetXY(60, 165);
        $pdf->Cell(90, 10, utf8_decode(" "), 1, 0, 'C', 1); //cuardo mitad abajo
        $pdf->SetXY(150, 145);
        $pdf->Cell(50, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 1, 0, 'C', 1); //cuadro derecho arriba
        $pdf->SetXY(150, 155);
        $pdf->Cell(50, 10, utf8_decode("Código: "). $arContenidoFormato->getCodigoFormatoIso(), 1, 0, 'C', 1); //cuadro derecho mitad 1
        $pdf->SetXY(150, 165);
        $pdf->Cell(50, 5, utf8_decode("Versión: "). $arContenidoFormato->getVersion(), 1, 0, 'C', 1); //cuadro derecho abajo 1
        $pdf->SetXY(150, 170);
        $fechaVerson = "";
        if ($arContenidoFormato->getFechaVersion() != null){ 
            $fechaVerson = $arContenidoFormato->getFechaVersion()->format('Y-m-d');    
        }
        $pdf->Cell(50, 5, "Fecha: ". $fechaVerson, 1, 0, 'C', 1); //cuadro derecho abajo 2
        //Contenido de la carta desde la mitad de la hoja------------------------------------------------------------------------------------
        $pdf->SetXY(10, 181);
        $pdf->SetFont('Arial', '', 8);  
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find(self::$codigoVacacion);
        $arContenidoFormato = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
        $arContenidoFormato = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(1);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracionNomina = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        //$pdf->Text(10, 60, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). ", ". strftime("%d de %B de %Y", strtotime(date('Y-m-d'))));
        $usuarioCarta = self::$usuario;
        $usuarioCarta = $usuarioCarta->getNombreCorto();
        //se reemplaza el contenido de la tabla contenido formato
        $sustitucion1 = $arVacacion->getEmpleadoRel()->getNombreCorto();
        $sustitucion2 = date('Y/m/d');
        $sustitucion3 = $arVacacion->getFechaDesdeDisfrute()->format('Y/m/d');
        $disfruteDesde = $arVacacion->getFechaDesdeDisfrute();
        $disfruteDesde = strftime("%d de ". $this->MesesEspañol($disfruteDesde->format('m')) ." de %Y", strtotime($sustitucion3));
        $sustitucion3 = $disfruteDesde;
        $sustitucion4 = $arVacacion->getFechaHastaDisfrute()->format('Y/m/d');
        $disfruteHasta = $arVacacion->getFechaHastaDisfrute();
        $disfruteHasta = strftime("%d de ". $this->MesesEspañol($disfruteHasta->format('m')) ." de %Y", strtotime($sustitucion4));
        $sustitucion4 = $disfruteHasta;
        $sustitucion5 = $arVacacion->getFechaInicioLabor()->format('Y/m/d');
        $disfruteInicioLabor = $arVacacion->getFechaInicioLabor();
        $disfruteInicioLabor = strftime("%d de ". $this->MesesEspañol($disfruteInicioLabor->format('m')) ." de %Y", strtotime($sustitucion5));
        $sustitucion5 = $disfruteInicioLabor;
        $cadena = $arContenidoFormato->getContenido();
        $patron1 = '/#a/';
        $patron2 = '/#b/';
        $patron3 = '/#c/';
        $patron4 = '/#d/';
        $patron5 = '/#e/';
        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
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
        $this->Text(170, 290, utf8_decode('FECHA: 12/11/2013'));
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
