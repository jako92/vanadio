<?php

namespace Brasa\TurnoBundle;

/**
 * Esta clase de utilidad es usada para calcular las horas de la jornada de trabajo
 * de un empleado. 
 * @author Jorge Alejandro Quiroz Serna <alejo.jko@gmail.com>
 */
class UtlProgramacion {
    /**
     * Instancia del gestor de conexión a la base de datos.
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    /********************************
     * 	Constantes para la jornada. *
     ********************************/

    const JORNADA_DIURNA = 0;
    const JORNADA_NOCTURNA = 1;
    const JORNADA_MIXTA_A = 2; # Diurno a nocturno.
    const JORNADA_MIXTA_B = 3; # Nocturno a diurno.
    const JORNADA_MIXTA_C = 4; # Diurno => Nocturno => Diurno.
    const JORNADA_MIXTA_D = 5; # Nocturno => Diurno => Nocturno.

    /******************************************
     * Constantes para diferencias de tiempo. *	
     ******************************************/
    const ANIO = 0;
    const MES = 1;
    const DIA = 2;
    const HORA = 3;
    const MINUTO = 4;

    /************************************************
     *      Configuraciones para el calculador.     *
     ************************************************/

    /**
     * Festivos disponibles para el año.
     * @var array
     */
    private $festivos = [];

    /**
     * Hora en la que empieza la jornada diurna.
     * @var integer
     */
    private $minJornadaDiurna = 6;

    /**
     * Hora en la que termina la jornada diurna.
     * @var integer
     */
    private $maxJornadaDiurna = 21;

    /**
     * Mínimo de horas que debe laborar un empleado.
     * @var integer
     */
    private $minJornadaTrabajo = 8;

    /************************************************
     *      Variables para calcular las horas       * 
     ************************************************/

    /**
     * Tipo de jornada
     * @var int
     */
    private $tipoDeJornada;

    /**
     * Día en que se inicia la jornada.
     * @var string
     */
    private $diaInicio;

    /**
     * Día en que finaliza la jornada.
     * @var string
     */
    private $diaFin;

    /**
     * Hora exacta en que se inicia la jornada.
     * @var string
     */
    private $horaInicial;

    /**
     * Hora exacta en que finaliza la jornada.
     * @var string
     */
    private $horaFinal;

    /**
     * Valor númerico de 1 a 24 de la hora inicial.
     * @var integer
     */
    private $horaIni;

    /**
     * Valor númerico de 1 a 24 de la hora final.
     * @var integer.
     */
    private $horaFin;

    /****************************************
     *              Resultados              * 
     ****************************************/

    /**
     * Total de horas laboradas.
     * @var integer
     */
    private $horasLaboradas = 0;

    /**
     * Horas diurnas laboradas.
     * @var integer
     */
    private $diurnas = 0;

    /**
     * Horas diurnas extra laboradas.
     * @var integer
     */
    private $diurnasExt = 0;

    /**
     * Horas diurnas festivas laboradas.
     * @var integer
     */
    private $diurnasFes = 0;

    /**
     * Horas diurnas festivas extra laboradas.
     * @var integer
     */
    private $diurnasFesExt = 0;

    /**
     * Horas nocturnas laboradas.
     * @var integer
     */
    private $nocturnas = 0;

    /**
     * Horas nocturnas extra laboradas.
     * @var integer
     */
    private $nocturnasExt = 0;

    /**
     * Horas nocturnas festivas laboradas.
     * @var integer
     */
    private $nocturnasFes = 0;

    /**
     * Horas nocturnas festivas extra laboradas.
     * @var integer
     */
    private $nocturnasFesExt = 0;

    /**
     * Esta clase no debe ser instanciada desde afuera.
     */
    private function __construct() {
        
    }
    /**
     * 
     * @param \Doctrine\ORM\Decorator\EntityManagerDecorator $em
     */
    public function setEntityManager($em){
        $this->em = $em;
    }

    /**
     * Esta función permite cargar los festivos.
     */
    public function setFestivos(){
        $arFestivos = $this->em->getRepository("BrasaGeneralBundle:GenFestivo")
                        ->festivos(date("Y-01-01"), date("Y-12-31"));
        foreach($arFestivos AS $festivo) { 
            $this->festivos[] = $festivo['fecha']->format("Y-m-d");             
        }        
    }

    /**
     * Esta función retorna la única instancia de las utilidades.
     * @return  \Soga\MatrizProgramacoinBundle\UtlProgramacion
     */
    public static function Util() {
        static $instance = null;
        if ($instance == null) {
            $instance = new UtlProgramacion();
        }
        return $instance;
    }

    public function calcularHoras($dia, $horaInicial, $horaFinal) {
        $this->horasLaboradas = $this->getDiferencia("{$dia} {$horaInicial}", "{$dia} {$horaFinal}", self::HORA);
        $this->diaInicio = $dia;
        $this->diaFin = $this->sumarAFecha("{$dia} {$horaInicial}", self::HORA, $this->horasLaboradas);
        if (intval($this->getFormato($horaFinal, "H")) == 0) {
            $this->diaFin = $this->diaInicio;
        }
        $this->horaInicial = $horaInicial;
        $this->horaFinal = $horaFinal;
        $this->obtenerTipoDeJornada();
        # echo "<pre>";
        $this->calcular();
        return $data = [
            'ordDiurnas' => $this->diurnas,
            'ordNocturnas' => $this->nocturnas,
            'ordDiuExt' => $this->diurnasExt,
            'ordNocExt' => $this->nocturnasExt,
            'fesDiurnas' => $this->diurnasFes,
            'fesNocturnas' => $this->nocturnasFes,
            'fesDiuExt' => $this->diurnasFesExt,
            'fesNocExt' => $this->nocturnasFesExt
        ];
    }

    /**
     * Esta función llama la función encargada de calcular cada jornada.
     */
    private function calcular() {
        switch ($this->tipoDeJornada) {
            case self::JORNADA_DIURNA: $this->resolverDiurna();
                break;
            case self::JORNADA_NOCTURNA: $this->resolverNocturna();
                break;
            case self::JORNADA_MIXTA_A: $this->resolverMixtaA();
                break;
            case self::JORNADA_MIXTA_B: $this->resolverMixtaB();
                break;
            case self::JORNADA_MIXTA_C: $this->resolverMixtaC();
                break;
            case self::JORNADA_MIXTA_D: $this->resolverMixtaD();
                break;
        }
    }

    /**
     * Esta función limpia las horas calcular.
     * @return 
     */
    public function limpiar() {
        $this->diurnas = 0;
        $this->nocturnas = 0;
        $this->diurnasExt = 0;
        $this->nocturnasExt = 0;
        $this->diurnasFes = 0;
        $this->nocturnasFes = 0;
        $this->diurnasFesExt = 0;
        $this->nocturnasFesEx = 0;
    }

    /**
     * Esta función retorna las horas extra de cualquier jornada.
     * @return integer|float
     */
    private function getExtras() {
        if ($this->horasLaboradas > $this->minJornadaTrabajo) {
            return $this->horasLaboradas - $this->minJornadaTrabajo;
        }
        return 0;
    }

    /**
     * Esta función retorna las horas nocturnas laboradas antes de las doce de la noche (para jornadas nocturnas). 
     * @return integer|float
     */
    private function nocturnasAntesDeMediaNoche() {
        return ($this->horaIni >= $this->maxJornadaDiurna && $this->horaIni < 24) ? 24 - $this->horaIni : 0;
    }

    /**
     * Esta función retorna el total de horas nocturnas restantes para la media noche.
     * @return int
     */
    private function getNocturnasDiaActual() {
        return (24 - $this->maxJornadaDiurna);
    }

    /**
     * Esta función se encarga de resolver la jornada diurna.
     * @return
     */
    private function resolverDiurna() {
        $extras = $this->getExtras();
        if ($this->esFestivo($this->diaInicio)) {
            $this->diurnasFes = $this->horasLaboradas - $extras;
            $this->diurnasFesExt = $extras;
        } else {
            $this->diurnas = $this->horasLaboradas - $extras;
            $this->diurnasExt = $extras;
        }
    }

    /**
     * Esta función se encarga de resolver las horas nocturnas.
     */
    private function resolverNocturna() {
        $extras = $this->getExtras();
        $nocturnasA = $this->nocturnasAntesDeMediaNoche();
        $nocturnasB = $this->horasLaboradas - $nocturnasA - $extras;
        if ($this->esFestivo($this->diaInicio) && !$this->esFestivo($this->diaFin)) {
            $this->nocturnasFes = $nocturnasA;
            $this->nocturnas = $nocturnasB;
            $this->nocturnasExt = $extras;
        } else if ($this->esFestivo($this->diaFin) && !$this->esFestivo($this->diaInicio)) {
            $this->nocturnas = $nocturnasA;
            $this->nocturnasFes = $nocturnasB;
            $this->nocturnasFesExt = $extras;
        } else {
            $this->nocturnas = $nocturnasA + $nocturnasB;
            $this->nocturnasExt = $extras;
        }
    }

    /**
     * Esta función valida si las horas trabajadas (valor ingresado) es = o 
     * superior a la jornada mínima de trabajo que debe realizar un empleado.
     * @param float $valor
     * @return boolean
     */
    private function cumple($valor) {
        return $valor >= $this->minJornadaTrabajo;
    }

    /**
     * Esta función permite obtener el número de horas restantes para completar
     * la jornada de trabajo mínima de un empleado.
     * @param float $trabajadas
     * @return float
     */
    private function obtenerHorasRestantes($trabajadas) {
        return $trabajadas < $this->minJornadaTrabajo ?
                $this->minJornadaTrabajo - $trabajadas : 0;
    }

    /**
     * Esta función suma el valor ingresado a una variable, después de sumado el 
     * nuevo valor de esta variable es sumado a el segundo argumento.
     * @param float $sumarA
     * @param float $asignarA
     * @param float $valor
     */
    private function sumarAyAsignarA(&$sumarA, &$asignarA, $valor) {
        $sumarA += $valor;
        $asignarA += $sumarA;
    }

    /**
     * Esta función intercambia el valor de dos variables.
     * @param mixed $a integer|float 
     * @param mixed $b integer|float 
     */
    private function cambiarValor(&$a, &$b) {
        $tmp = $a;
        $a = $b;
        $b = $tmp;
    }

    /**
     * Esta función valida si determinado valor sobre pasa un límite, de ser así
     * se calcula la diferencia, se descuenta el excedente y se suma la diferencia
     * a la variable deseada.
     * @param float $valor Valor a comprobar.
     * @param float $limite Valor contra el cual comparar.
     * @param type $asignarA Variable a la cual asignar la diferencia.
     * @return boolean
     */
    private function validarDesfase(&$valor, $limite, &$asignarA) {
        if ($valor < $limite)
            return false;
        $diferencia = $valor - $limite;
        $valor = $limite;
        $asignarA += $diferencia;
        return true;
    }

    /**
     * Esta función calcula las horas si la jornada es diurna y pasa a ser 
     * nocturna.
     */
    private function resolverMixtaA() {
        $jornadaA = $this->maxJornadaDiurna - $this->horaIni; # Jornada diurna inicial.
        $restante = $this->horasLaboradas - $jornadaA;
        $jornadaB = $restante > 0 ? $restante : 0;
        $nocturnas = $this->getNocturnasDiaActual();
        $nocturnasDiaSiguiente = $jornadaB > $nocturnas ? $jornadaB - $nocturnas : 0;
        $jornadaB -= $nocturnasDiaSiguiente;
        $mismoDia = $this->diaInicio == $this->diaFin;

        $this->sumarAyAsignarA($this->diurnas, $trabajadas, $jornadaA);
        $this->validarDesfase($this->diurnas, $this->minJornadaTrabajo, $this->diurnasExt);
        if (!$this->cumple($trabajadas)) {
            $this->sumarAyAsignarA($this->nocturnas, $trabajadas, $jornadaB);
        }
        if ($trabajadas > $this->minJornadaTrabajo && $this->nocturnas > 0) {
            $this->validarDesfase($trabajadas, $this->minJornadaTrabajo, $this->nocturnasExt);
        }
        $this->nocturnas -= $this->nocturnasExt;
        if ($this->cumple($trabajadas) && $trabajadas > $this->minJornadaTrabajo) {
            $this->sumarAyAsignarA($this->nocturnasExt, $trabajadas, $jornadaB);
        }
        # Validamos festivos.
        if (!$mismoDia && $this->esFestivo($this->diaInicio) && !$this->esFestivo($this->diaFin)) {
            # Si el día actual es festivo y el siguiente no.
            $this->cambiarValor($this->diurnas, $this->diurnasFes);
            $this->cambiarValor($this->diurnasExt, $this->diurnasFesExt);
            $this->cambiarValor($this->nocturnas, $this->nocturnasFes);
            $restante = $this->obtenerHorasRestantes($trabajadas);
            if (!$this->cumple($trabajadas)) {
                $this->sumarAyAsignarA($this->nocturnas, $trabajadas, $restante);
            }
            if ($trabajadas < $this->horasLaboradas) {
                $diferencia = $this->horasLaboradas - $trabajadas;
                $this->nocturnasExt += $diferencia;
            }
        } else if (!$mismoDia && $this->esFestivo($this->diaFin) && !$this->esFestivo($this->diaInicio)) {
            # Si el dia actual no es festivo y el siguiente si.
            if (!$this->cumple($trabajadas)) {
                $this->sumarAyAsignarA($this->nocturnasFes, $trabajadas, $nocturnasDiaSiguiente);
            }
            if ($trabajadas > $this->minJornadaTrabajo) {
                $diferencia = $trabajadas - $this->minJornadaTrabajo;
                $this->nocturnasFesExt = $diferencia;
                $this->nocturnasFes -= $diferencia;
            }
        } else if (!$mismoDia && $this->esFestivo($this->diaInicio) && $this->esFestivo($this->diaFin)) {
            # Si el día actual y el siguiente son festivos.
            $restante = $this->obtenerHorasRestantes($trabajadas);
            $this->sumarAyAsignarA($this->nocturnas, $trabajadas, $restante);
            if ($nocturnasDiaSiguiente > 0 && $this->cumple($trabajadas)) {
                $this->nocturnasExt += $nocturnasDiaSiguiente - $restante;
            }
            $this->cambiarValor($this->diurnas, $this->diurnasFes);
            $this->cambiarValor($this->diurnasExt, $this->diurnasFesExt);
            $this->cambiarValor($this->nocturnas, $this->nocturnasFes);
            $this->cambiarValor($this->nocturnasExt, $this->nocturnasFesExt);
        } else if ($this->esFestivo($this->diaInicio)) {
            # Si el día actual es festivo y no llega al siguiente día.
            $this->cambiarValor($this->diurnas, $this->diurnasFes);
            $this->cambiarValor($this->diurnasExt, $this->diurnasFesExt);
            $this->cambiarValor($this->nocturnas, $this->nocturnasFes);
            $this->cambiarValor($this->nocturnasExt, $this->nocturnasFesExt);
        } else {
            # Si no es festivo.
            $restante = $this->obtenerHorasRestantes($trabajadas);
            $this->nocturnasExt = $restante;
        }
    }

    /**
     * Esta función resuelve las horas si la jornada es nocturna y pasa a
     * ser diurna.
     */
    private function resolverMixtaB() {
        $jornadaA = $this->nocturnasAntesDeMediaNoche();
        $jornadaB = ($this->horaFin > $this->minJornadaDiurna) ? $this->minJornadaDiurna - ($this->horaIni < $this->minJornadaTrabajo ? $this->horaIni : 0) : $this->jornadaB = $horaFin;
        $jornadaC = $this->horasLaboradas - $jornadaA - $jornadaB;
        $mismoDia = $this->diaInicio == $this->diaFin;

        $this->sumarAyAsignarA($this->nocturnas, $trabajadas, $jornadaA);
        $this->sumarAyAsignarA($this->nocturnas, $trabajadas, $jornadaB);

        if (!$this->cumple($this->nocturnas)) {
            $restantes = $this->obtenerHorasRestantes($trabajadas);
            $this->sumarAyAsignarA($this->diurnas, $trabajadas, $restantes);
            $jornadaC -= $restantes;
        } else {
            $this->validarDesfase($this->nocturnas, $this->minJornadaTrabajo, $this->nocturnasExt);
        }
        if ($this->cumple($trabajadas)) {
            $this->sumarAyAsignarA($this->diurnasExt, $trabajadas, $jornadaC);
        }

        # Validamos festivos.
        if (!$mismoDia && $this->esFestivo($this->diaInicio) && !$this->esFestivo($this->diaFin)) {
            # Si el día actual es festivo y el siguiente no.
            $this->nocturnasFes = $jornadaA;
            $this->nocturnas -= $this->nocturnasFes;
            $this->cambiarValor($this->nocturnasExt, $this->nocturnasFesExt);
        } else if (!$mismoDia && $this->esFestivo($this->diaFin) && !$this->esFestivo($this->diaInicio)) {
            # Si el dia actual no es festivo y el siguiente si.
            $this->nocturnasFes = $this->nocturnas - $jornadaA;
            $this->nocturnas -= $this->nocturnasFes;
            $this->cambiarValor($this->nocturnasExt, $this->nocturnasFesExt);
            $this->cambiarValor($this->diurnas, $this->diurnasFes);
            $this->cambiarValor($this->diurnasExt, $this->diurnasFesExt);
        } else if (!$mismoDia && $this->esFestivo($this->diaInicio) && $this->esFestivo($this->diaFin)) {
            # Si el día actual y el siguiente son festivos.
            $this->cambiarValor($this->diurnas, $this->diurnasFes);
            $this->cambiarValor($this->diurnasExt, $this->diurnasFesExt);
            $this->cambiarValor($this->nocturnas, $this->nocturnasFes);
            $this->cambiarValor($this->nocturnasExt, $this->nocturnasFesExt);
        } else if ($this->esFestivo($this->diaInicio)) {
            # Si el día actual es festivo y no llega al siguiente día.
            $this->cambiarValor($this->nocturnas, $this->nocturnasFes);
            $this->cambiarValor($this->nocturnasExt, $this->nocturnasFesExt);
        }
    }

    private function resolverMixtaC() {
        
    }

    private function resolverMixtaD() {
        
    }

    /**
     * Esta función determina que tipo de jornada laboró el empleado.
     */
    private function obtenerTipoDeJornada() {
        # Las variables fueron nombradas así para facilitar las condicionales que vienen.
        $hi = $this->horaIni = intval($this->getFormato($this->horaInicial, "H"));
        $hf = $this->horaFin = intval($this->getFormato($this->horaFinal, "H"));
        $max = $this->maxJornadaDiurna;
        $min = $this->minJornadaDiurna;
        $mismoDia = $this->diaInicio == $this->diaFin;

        if ($hi == $hf || $this->horasLaboradas > 12) { $mismoDia = false; }
        if ($hi == 0) { $hi = 24; }
        if ($mismoDia && ($hi != $hf) && ($hi >= $min && $hi < $max) && ($hf <= $max && $hf > $min)){
            $this->tipoDeJornada = self::JORNADA_DIURNA;
        } else if (($hi != $hf) && ($hi >= $max || $hi < $min) && ($hf >= $max || $hf <= $min)) {
            $this->tipoDeJornada = self::JORNADA_NOCTURNA;
        } else if (($hi != $hf) && ($hi >= $min && $hi < $max) && ($hf > $max || $hf <= $min)) {
            $this->tipoDeJornada = self::JORNADA_MIXTA_A;
        } else if (($hi != $hf) && ($hi >= $max || $hi < $min) && ($hf >= $min)) {
            $this->tipoDeJornada = self::JORNADA_MIXTA_B;
        } else if (!$mismoDia && ($hi >= $min && $hi < $max) && ($hf >= $min && $hf <= $max)) {
            $this->tipoDeJornada = self::JORNADA_MIXTA_C;
        } else if (!$mismoDia && (($hi >= $max && $hi < 24) || ($hi < $min && $hi > 0)) && (($hf >= $max && $hf < 24) || ($hf < $max && $hf > 0))) {
            $this->tipoDeJornada = self::JORNADA_MIXTA_D;
        } else {
            throw new \Exception("Jornada no valida ({$this->horaInicial} - {$this->horaFinal}).");
        }
    }

    /**
     * Esta función retorna la jornada en string.
     * @return string
     */
    private function getTipoJornadaString() {
        switch ($this->tipoDeJornada) {
            case self::JORNADA_DIURNA: return "Diurna";
            case self::JORNADA_NOCTURNA: return "Nocturna";
            case self::JORNADA_MIXTA_A: return "Diurna/Nocturna";
            case self::JORNADA_MIXTA_B: return "Nocturna/Diurna";
            case self::JORNADA_MIXTA_C: return "Nocturna/Diurna/Nocturna (24)";
            case self::JORNADA_MIXTA_D: return "Diurna/Nocturna/Diurna (24)";
            default: return null;
        }
    }

    /**
     * Esta función permite hacer la sumatoria de horas. 
     * @param array $horas
     * @param array $data
     */
    public function sumarHoras($horas, &$data) {
        $data['ordDiurnas'] += $horas['ordDiurnas'];
        $data['ordNocturnas'] += $horas['ordNocturnas'];
        $data['ordDiuExt'] += $horas['ordDiuExt'];
        $data['ordNocExt'] += $horas['ordNocExt'];
        $data['fesDiurnas'] += $horas['fesDiurnas'];
        $data['fesNocturnas'] += $horas['fesNocturnas'];
        $data['fesDiuExt'] += $horas['fesDiuExt'];
        $data['fesNocExt'] += $horas['fesNocExt'];
    }

    /**
     * Esta función permite obtener la diferencia entre dos fechas.
     * @param  string $fechaInicial 
     * @param  string $fechaFinal 
     * @param  int $formatoSalida 
     * @return float Minutos|Horas|Segundos
     */
    public function getDiferencia($fechaInicial, $fechaFinal, $formatoSalida = null) {
        $desde = strtotime($fechaInicial);
        $hasta = strtotime($fechaFinal);
        if ($hasta < $desde) {
            $hasta = strtotime(date("Y-m-d H:i:s", strtotime("{$fechaFinal} + 1 days")));
        }
        $diferencia = $hasta - $desde;
        switch ($formatoSalida) {
            case self::DIA: return round($diferencia / 60 / 60 / 24, 2) + 1;
            case self::MINUTO: return round($diferencia / 60, 2);
            case self::HORA: return round($diferencia / 60 / 60, 2);
            default: return $diferencia;
        }
    }    

    /**
     * Esta función agrega cualquier faltante a una fecha especificada para devolverla en formato
     * YYYY/MM/DD HH:MM:SS.
     * @param  string $fecha
     * @return string
     */
    public function completarFecha($fecha) {
        $fecha = new \DateTime($fecha);
        return $fecha->format("Y-m-d H:i:s");
    }

    /**
     * Esta función permite obtener un formato especifico de una fecha pasada como argumento.
     * @param  string $fecha
     * @param  string $formato Y-m-d H:i:s (cualquiera oficial http://php.net/manual/es/function.date.php)
     * @return string
     */
    public function getFormato($fecha, $formato) {
        $fecha = new \DateTime($fecha);
        return $fecha->format($formato);
    }

    /**
     * Esta función permite sumar unidades a una fecha, se permite sumar desde años, días, meses y horas.
     * @param  string $fecha
     * @param  int  $unidad
     * @param  int  $valor
     * @param  boolean $completa
     * @return string
     */
    public function sumarAFecha($fecha, $unidad, $valor, $completa = false) {
        $valor = floor($valor); #No podemos mandar decimales en el formato del intervalo.
        switch ($unidad) {
            case self::ANIO: $intervalo = "PT{$valor}Y";
                break;
            case self::MES: $intervalo = "P{$valor}M";
                break;
            case self::DIA: $intervalo = "P{$valor}D";
                break;
            case self::HORA: $intervalo = "PT{$valor}H";
                break;
            case self::MINUTO: $intervalo = "PT{$valor}M";
                break;
            default: $intervalo = "PT1D";
                break;
        }

        $objIntervalo = new \DateInterval($intervalo);
        $objFecha = new \DateTime($fecha);
        $objFecha->add($objIntervalo);

        return $completa ? $objFecha->format("Y-m-d H:i:s") : $objFecha->format("Y-m-d");
    }

    /**
     * Esta función valida si se trata de un día festivo.
     * @param  string $fecha
     * @return boolean
     */
    public function esFestivo($fecha) {
        $objFecha = new \DateTime($fecha);
        $esDomingo = intval($objFecha->format("N")) == 7; # Si se trata de un domingo es considerado festivo.
        return in_array($fecha, $this->festivos) || $esDomingo;
    }

}