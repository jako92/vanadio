<?php
namespace Brasa\RecursoHumanoBundle\Formatos;
class CambioSalarioNotificacion extends \FPDF_FPDF {
    public static $em;    
    public static $codigoCambioSalario;    
    public static $usuario;
    
    public function Generar($em, $codigoCambioSalario, $usuario) {        
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCambioSalario = $codigoCambioSalario;
        self::$usuario = $usuario;
        $pdf = new CambioSalarioNotificacion();
        #Establecemos los márgenes izquierda, arriba y derecha: 
        $pdf->SetMargins(30, 25 , 30); 
        #Establecemos el margen inferior: 
        $pdf->SetAutoPageBreak(true,25);          
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        
        $this->Body($pdf);
        $pdf->Output("CambioSalario.pdf", 'D');                    

    } 
    
    public function Header() {         
        $this->EncabezadoDetalles();        
    }

    public function EncabezadoDetalles() {
        
    }

    public function Body($pdf) {        
        $arrCodigoCambioSalario = self::$codigoCambioSalario;
        $registros = count($arrCodigoCambioSalario);
        $registro = 0;
        foreach ($arrCodigoCambioSalario as $codigoCambioSalario) {                    
            $arCambioSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();        
            $arCambioSalario = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->find($codigoCambioSalario);                           
            $pdf->SetXY(30, 60);
            $pdf->SetFont('Arial', '', 10);  
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenidoFormato();
            $arContenido = self::$em->getRepository('BrasaGeneralBundle:GenContenido')->find(2);
            $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
            $arConfiguracion = self::$em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
            $arConfiguracionNomina = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arConfiguracionNomina = self::$em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
            setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
            //$pdf->Text(10, 60, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). ", ". strftime("%d de %B de %Y", strtotime(date('Y-m-d'))));
            $usuarioCarta = self::$usuario;
            $usuarioCarta = $usuarioCarta->getNombreCorto();
            //se reemplaza el contenido de la tabla contenido formato
            $fechaActual = new \DateTime('now');        
            $sustitucion1 = strftime("%d de ". $this->MesesEspañol($fechaActual->format('m')) ." de %Y");        
            $sustitucion2 = $arCambioSalario->getFechaInicio()->format('Y');
            $sustitucion3 = strftime("%d de ". $this->MesesEspañol($arCambioSalario->getFechaInicio()->format('m')) ." de %Y");         
            $sustitucion4 = number_format($arCambioSalario->getVrSalarioNuevo(), 0,'.',',');            
            $sustitucion5 = $arConfiguracion->getNombreEmpresa();                        
            $sustitucion6 = $arCambioSalario->getEmpleadoRel()->getNombreCorto();                        
            $sustitucion7 = "";
            if($arCambioSalario->getEmpleadoRel()->getCodigoCargoFk()) {
                $sustitucion7 = $arCambioSalario->getEmpleadoRel()->getCargoRel()->getNombre();                        
            }
            

            $cadena = $arContenido->getContenido();
            $patron1 = '/#1/';
            $patron2 = '/#2/';
            $patron3 = '/#3/';
            $patron4 = '/#4/';
            $patron5 = '/#5/';
            $patron6 = '/#6/';
            $patron7 = '/#7/';
            $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
            $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
            $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
            $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
            $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
            $cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
            $cadenaCambiada = preg_replace($patron7, $sustitucion7, $cadenaCambiada);
            $pdf->MultiCell(0,5, $cadenaCambiada); 
            $registro++;
            if($registro < $registros) {
                $pdf->AddPage();
            }
        }        
    }

    public function Footer() {
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');        
        
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
