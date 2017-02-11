<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_prestacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPrestacionRepository")
 */
class RhuPrestacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prestacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPrestacionPk;            

    /**
     * @ORM\Column(name="codigo_cierre_anio_fk", type="integer", nullable=true)
     */    
    private $codigoCierreAnioFk;    

    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;               
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer")
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_motivo_terminacion_contrato_fk", type="integer", nullable=true)
     */    
    private $codigoMotivoTerminacionContratoFk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta; 
    
    /**
     * @ORM\Column(name="numero_dias", type="string", length=30, nullable=true)
     */    
    private $numeroDias;                 
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $VrCesantias = 0;    

    /**
     * @ORM\Column(name="vr_intereses_cesantias", type="float")
     */
    private $VrInteresesCesantias = 0;        

    /**
     * @ORM\Column(name="vr_cesantias_anterior", type="float")
     */
    private $VrCesantiasAnterior = 0;    

    /**
     * @ORM\Column(name="vr_intereses_cesantias_anterior", type="float")
     */
    private $VrInteresesCesantiasAnterior = 0;    
    
    /**
     * @ORM\Column(name="vr_prima", type="float")
     */
    private $VrPrima = 0;    

    /**
     * @ORM\Column(name="vr_deduccion_prima", type="float")
     */
    private $VrDeduccionPrima = 0;    
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $VrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_indemnizacion", type="float")
     */
    private $VrIndemnizacion = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;        
    
    /**
     * @ORM\Column(name="dias_cesantias", type="integer")
     */    
    private $diasCesantias = 0;     

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo", type="integer")
     */    
    private $diasCesantiasAusentismo = 0;

    /**
     * @ORM\Column(name="dias_cesantias_anterior", type="integer")
     */    
    private $diasCesantiasAnterior = 0;     

    /**
     * @ORM\Column(name="dias_cesantias_ausentismo_anterior", type="integer")
     */    
    private $diasCesantiasAusentismo_anterior = 0;
    
    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */    
    private $diasVacaciones = 0;                

    /**
     * @ORM\Column(name="dias_vacaciones_ausentismo", type="integer")
     */    
    private $diasVacacionesAusentismo = 0; 
    
    /**
     * @ORM\Column(name="dias_primas", type="integer")
     */    
    private $diasPrimas = 0;           

    /**
     * @ORM\Column(name="dias_primas_ausentismo", type="integer")
     */    
    private $diasPrimasAusentismo = 0; 
    
    /**
     * @ORM\Column(name="dias_laborados", type="integer")
     */    
    private $diasLaborados = 0;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date", nullable=true)
     */    
    private $fechaUltimoPago;        
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_adicional", type="float")
     */
    private $VrIngresoBasePrestacionAdicional = 0;        
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias", type="float")
     */
    private $VrIngresoBasePrestacionCesantias = 0;     

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas", type="float")
     */
    private $VrIngresoBasePrestacionPrimas = 0; 
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_cesantias_inicial", type="float")
     */
    private $VrIngresoBasePrestacionCesantiasInicial = 0;     

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion_primas_inicial", type="float")
     */
    private $VrIngresoBasePrestacionPrimasInicial = 0;    
    
    /**
     * @ORM\Column(name="dias_adicionales_ibp", type="integer")
     */    
    private $diasAdicionalesIBP = 0;            
    
    /**
     * @ORM\Column(name="vr_base_prestaciones", type="float")
     */
    private $VrBasePrestaciones = 0;    

    /**
     * @ORM\Column(name="vr_base_prestaciones_total", type="float")
     */
    private $VrBasePrestacionesTotal = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $VrAuxilioTransporte = 0;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;     

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias", type="float")
     */
    private $VrSalarioPromedioCesantias = 0;    

    /**
     * @ORM\Column(name="vr_salario_promedio_cesantias_anterior", type="float")
     */
    private $VrSalarioPromedioCesantiasAnterior = 0;     
    
    /**
     * @ORM\Column(name="vr_salario_promedio_primas", type="float")
     */
    private $VrSalarioPromedioPrimas = 0;
    
    /**
     * @ORM\Column(name="vr_salario_vacaciones", type="float")
     */
    private $VrSalarioVacaciones = 0;     
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $VrTotal = 0;    
    
    /**     
     * @ORM\Column(name="liquidar_cesantias", type="boolean")
     */    
    private $liquidarCesantias = 0;

    /**     
     * @ORM\Column(name="liquidar_vacaciones", type="boolean")
     */    
    private $liquidarVacaciones = 0;    

    /**     
     * @ORM\Column(name="liquidar_prima", type="boolean")
     */    
    private $liquidarPrima = 0;        
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_primas", type="date", nullable=true)
     */    
    private $fechaUltimoPagoPrimas;
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_vacaciones", type="date", nullable=true)
     */    
    private $fechaUltimoPagoVacaciones;
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias", type="date", nullable=true)
     */    
    private $fechaUltimoPagoCesantias;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago_cesantias_anterior", type="date", nullable=true)
     */    
    private $fechaUltimoPagoCesantiasAnterior;     
    
    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $VrDeducciones = 0; 
    
    /**
     * @ORM\Column(name="vr_bonificaciones", type="float")
     */
    private $VrBonificaciones = 0;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;
    
    /**
     * @ORM\Column(name="fecha_inicio_contrato", type="date", nullable=true)
     */    
    private $fechaInicioContrato;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**     
     * @ORM\Column(name="liquidar_manual", type="boolean")
     */    
    private $liquidarManual = 0;

    /**
     * @ORM\Column(name="estado_pago_generado", type="boolean")
     */
    private $estadoPagoGenerado = 0;
    
    /**
     * @ORM\Column(name="estado_pago_banco", type="boolean")
     */
    private $estadoPagoBanco = 0;    
    
    /**     
     * @ORM\Column(name="liquidar_salario", type="boolean")
     */    
    private $liquidarSalario = 0;    
    
    /**
     * @ORM\Column(name="porcentaje_ibp", type="float")
     */
    private $porcentajeIbp = 100;    
    
    /**     
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = false;    
    
    /**
     * @ORM\Column(name="dias_ausentismo_adicional", type="integer")
     */    
    private $diasAusentismoAdicional = 0;    
    
    /**
     * @ORM\Column(name="vr_salario_vacacion_propuesto", type="float")
     */
    private $VrSalarioVacacionPropuesto = 0;    

    /**
     * @ORM\Column(name="vr_salario_prima_propuesto", type="float")
     */
    private $VrSalarioPrimaPropuesto = 0; 

    /**
     * @ORM\Column(name="vr_salario_cesantias_propuesto", type="float")
     */
    private $VrSalarioCesantiasPropuesto = 0;
    
    /**     
     * @ORM\Column(name="eliminar_ausentismo", type="boolean")
     */    
    private $eliminarAusentismo = false;    
    
    /**
     * @ORM\Column(name="dias_ausentismo_propuesto", type="integer")
     */    
    private $diasAusentismoPropuesto = 0;     
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoDetalleFk;    

    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;
    
    /**     
     * @ORM\Column(name="omitir_cesantias_anterior", type="boolean")
     */    
    private $omitirCesantiasAnterior = false;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="prestacionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="prestacionesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="prestacionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;                  


    /**
     * Get codigoPrestacionPk
     *
     * @return integer
     */
    public function getCodigoPrestacionPk()
    {
        return $this->codigoPrestacionPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuPrestacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set codigoMotivoTerminacionContratoFk
     *
     * @param integer $codigoMotivoTerminacionContratoFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoMotivoTerminacionContratoFk($codigoMotivoTerminacionContratoFk)
    {
        $this->codigoMotivoTerminacionContratoFk = $codigoMotivoTerminacionContratoFk;

        return $this;
    }

    /**
     * Get codigoMotivoTerminacionContratoFk
     *
     * @return integer
     */
    public function getCodigoMotivoTerminacionContratoFk()
    {
        return $this->codigoMotivoTerminacionContratoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuPrestacion
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuPrestacion
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set numeroDias
     *
     * @param string $numeroDias
     *
     * @return RhuPrestacion
     */
    public function setNumeroDias($numeroDias)
    {
        $this->numeroDias = $numeroDias;

        return $this;
    }

    /**
     * Get numeroDias
     *
     * @return string
     */
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuPrestacion
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->VrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->VrCesantias;
    }

    /**
     * Set vrInteresesCesantias
     *
     * @param float $vrInteresesCesantias
     *
     * @return RhuPrestacion
     */
    public function setVrInteresesCesantias($vrInteresesCesantias)
    {
        $this->VrInteresesCesantias = $vrInteresesCesantias;

        return $this;
    }

    /**
     * Get vrInteresesCesantias
     *
     * @return float
     */
    public function getVrInteresesCesantias()
    {
        return $this->VrInteresesCesantias;
    }

    /**
     * Set vrCesantiasAnterior
     *
     * @param float $vrCesantiasAnterior
     *
     * @return RhuPrestacion
     */
    public function setVrCesantiasAnterior($vrCesantiasAnterior)
    {
        $this->VrCesantiasAnterior = $vrCesantiasAnterior;

        return $this;
    }

    /**
     * Get vrCesantiasAnterior
     *
     * @return float
     */
    public function getVrCesantiasAnterior()
    {
        return $this->VrCesantiasAnterior;
    }

    /**
     * Set vrInteresesCesantiasAnterior
     *
     * @param float $vrInteresesCesantiasAnterior
     *
     * @return RhuPrestacion
     */
    public function setVrInteresesCesantiasAnterior($vrInteresesCesantiasAnterior)
    {
        $this->VrInteresesCesantiasAnterior = $vrInteresesCesantiasAnterior;

        return $this;
    }

    /**
     * Get vrInteresesCesantiasAnterior
     *
     * @return float
     */
    public function getVrInteresesCesantiasAnterior()
    {
        return $this->VrInteresesCesantiasAnterior;
    }

    /**
     * Set vrPrima
     *
     * @param float $vrPrima
     *
     * @return RhuPrestacion
     */
    public function setVrPrima($vrPrima)
    {
        $this->VrPrima = $vrPrima;

        return $this;
    }

    /**
     * Get vrPrima
     *
     * @return float
     */
    public function getVrPrima()
    {
        return $this->VrPrima;
    }

    /**
     * Set vrDeduccionPrima
     *
     * @param float $vrDeduccionPrima
     *
     * @return RhuPrestacion
     */
    public function setVrDeduccionPrima($vrDeduccionPrima)
    {
        $this->VrDeduccionPrima = $vrDeduccionPrima;

        return $this;
    }

    /**
     * Get vrDeduccionPrima
     *
     * @return float
     */
    public function getVrDeduccionPrima()
    {
        return $this->VrDeduccionPrima;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuPrestacion
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->VrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->VrVacaciones;
    }

    /**
     * Set vrIndemnizacion
     *
     * @param float $vrIndemnizacion
     *
     * @return RhuPrestacion
     */
    public function setVrIndemnizacion($vrIndemnizacion)
    {
        $this->VrIndemnizacion = $vrIndemnizacion;

        return $this;
    }

    /**
     * Get vrIndemnizacion
     *
     * @return float
     */
    public function getVrIndemnizacion()
    {
        return $this->VrIndemnizacion;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuPrestacion
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set diasCesantias
     *
     * @param integer $diasCesantias
     *
     * @return RhuPrestacion
     */
    public function setDiasCesantias($diasCesantias)
    {
        $this->diasCesantias = $diasCesantias;

        return $this;
    }

    /**
     * Get diasCesantias
     *
     * @return integer
     */
    public function getDiasCesantias()
    {
        return $this->diasCesantias;
    }

    /**
     * Set diasCesantiasAusentismo
     *
     * @param integer $diasCesantiasAusentismo
     *
     * @return RhuPrestacion
     */
    public function setDiasCesantiasAusentismo($diasCesantiasAusentismo)
    {
        $this->diasCesantiasAusentismo = $diasCesantiasAusentismo;

        return $this;
    }

    /**
     * Get diasCesantiasAusentismo
     *
     * @return integer
     */
    public function getDiasCesantiasAusentismo()
    {
        return $this->diasCesantiasAusentismo;
    }

    /**
     * Set diasCesantiasAnterior
     *
     * @param integer $diasCesantiasAnterior
     *
     * @return RhuPrestacion
     */
    public function setDiasCesantiasAnterior($diasCesantiasAnterior)
    {
        $this->diasCesantiasAnterior = $diasCesantiasAnterior;

        return $this;
    }

    /**
     * Get diasCesantiasAnterior
     *
     * @return integer
     */
    public function getDiasCesantiasAnterior()
    {
        return $this->diasCesantiasAnterior;
    }

    /**
     * Set diasCesantiasAusentismoAnterior
     *
     * @param integer $diasCesantiasAusentismoAnterior
     *
     * @return RhuPrestacion
     */
    public function setDiasCesantiasAusentismoAnterior($diasCesantiasAusentismoAnterior)
    {
        $this->diasCesantiasAusentismo_anterior = $diasCesantiasAusentismoAnterior;

        return $this;
    }

    /**
     * Get diasCesantiasAusentismoAnterior
     *
     * @return integer
     */
    public function getDiasCesantiasAusentismoAnterior()
    {
        return $this->diasCesantiasAusentismo_anterior;
    }

    /**
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuPrestacion
     */
    public function setDiasVacaciones($diasVacaciones)
    {
        $this->diasVacaciones = $diasVacaciones;

        return $this;
    }

    /**
     * Get diasVacaciones
     *
     * @return integer
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * Set diasVacacionesAusentismo
     *
     * @param integer $diasVacacionesAusentismo
     *
     * @return RhuPrestacion
     */
    public function setDiasVacacionesAusentismo($diasVacacionesAusentismo)
    {
        $this->diasVacacionesAusentismo = $diasVacacionesAusentismo;

        return $this;
    }

    /**
     * Get diasVacacionesAusentismo
     *
     * @return integer
     */
    public function getDiasVacacionesAusentismo()
    {
        return $this->diasVacacionesAusentismo;
    }

    /**
     * Set diasPrimas
     *
     * @param integer $diasPrimas
     *
     * @return RhuPrestacion
     */
    public function setDiasPrimas($diasPrimas)
    {
        $this->diasPrimas = $diasPrimas;

        return $this;
    }

    /**
     * Get diasPrimas
     *
     * @return integer
     */
    public function getDiasPrimas()
    {
        return $this->diasPrimas;
    }

    /**
     * Set diasPrimasAusentismo
     *
     * @param integer $diasPrimasAusentismo
     *
     * @return RhuPrestacion
     */
    public function setDiasPrimasAusentismo($diasPrimasAusentismo)
    {
        $this->diasPrimasAusentismo = $diasPrimasAusentismo;

        return $this;
    }

    /**
     * Get diasPrimasAusentismo
     *
     * @return integer
     */
    public function getDiasPrimasAusentismo()
    {
        return $this->diasPrimasAusentismo;
    }

    /**
     * Set diasLaborados
     *
     * @param integer $diasLaborados
     *
     * @return RhuPrestacion
     */
    public function setDiasLaborados($diasLaborados)
    {
        $this->diasLaborados = $diasLaborados;

        return $this;
    }

    /**
     * Get diasLaborados
     *
     * @return integer
     */
    public function getDiasLaborados()
    {
        return $this->diasLaborados;
    }

    /**
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuPrestacion
     */
    public function setFechaUltimoPago($fechaUltimoPago)
    {
        $this->fechaUltimoPago = $fechaUltimoPago;

        return $this;
    }

    /**
     * Get fechaUltimoPago
     *
     * @return \DateTime
     */
    public function getFechaUltimoPago()
    {
        return $this->fechaUltimoPago;
    }

    /**
     * Set vrIngresoBasePrestacionAdicional
     *
     * @param float $vrIngresoBasePrestacionAdicional
     *
     * @return RhuPrestacion
     */
    public function setVrIngresoBasePrestacionAdicional($vrIngresoBasePrestacionAdicional)
    {
        $this->VrIngresoBasePrestacionAdicional = $vrIngresoBasePrestacionAdicional;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionAdicional
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionAdicional()
    {
        return $this->VrIngresoBasePrestacionAdicional;
    }

    /**
     * Set vrIngresoBasePrestacionCesantias
     *
     * @param float $vrIngresoBasePrestacionCesantias
     *
     * @return RhuPrestacion
     */
    public function setVrIngresoBasePrestacionCesantias($vrIngresoBasePrestacionCesantias)
    {
        $this->VrIngresoBasePrestacionCesantias = $vrIngresoBasePrestacionCesantias;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionCesantias
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionCesantias()
    {
        return $this->VrIngresoBasePrestacionCesantias;
    }

    /**
     * Set vrIngresoBasePrestacionPrimas
     *
     * @param float $vrIngresoBasePrestacionPrimas
     *
     * @return RhuPrestacion
     */
    public function setVrIngresoBasePrestacionPrimas($vrIngresoBasePrestacionPrimas)
    {
        $this->VrIngresoBasePrestacionPrimas = $vrIngresoBasePrestacionPrimas;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionPrimas
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionPrimas()
    {
        return $this->VrIngresoBasePrestacionPrimas;
    }

    /**
     * Set vrIngresoBasePrestacionCesantiasInicial
     *
     * @param float $vrIngresoBasePrestacionCesantiasInicial
     *
     * @return RhuPrestacion
     */
    public function setVrIngresoBasePrestacionCesantiasInicial($vrIngresoBasePrestacionCesantiasInicial)
    {
        $this->VrIngresoBasePrestacionCesantiasInicial = $vrIngresoBasePrestacionCesantiasInicial;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionCesantiasInicial
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionCesantiasInicial()
    {
        return $this->VrIngresoBasePrestacionCesantiasInicial;
    }

    /**
     * Set vrIngresoBasePrestacionPrimasInicial
     *
     * @param float $vrIngresoBasePrestacionPrimasInicial
     *
     * @return RhuPrestacion
     */
    public function setVrIngresoBasePrestacionPrimasInicial($vrIngresoBasePrestacionPrimasInicial)
    {
        $this->VrIngresoBasePrestacionPrimasInicial = $vrIngresoBasePrestacionPrimasInicial;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacionPrimasInicial
     *
     * @return float
     */
    public function getVrIngresoBasePrestacionPrimasInicial()
    {
        return $this->VrIngresoBasePrestacionPrimasInicial;
    }

    /**
     * Set diasAdicionalesIBP
     *
     * @param integer $diasAdicionalesIBP
     *
     * @return RhuPrestacion
     */
    public function setDiasAdicionalesIBP($diasAdicionalesIBP)
    {
        $this->diasAdicionalesIBP = $diasAdicionalesIBP;

        return $this;
    }

    /**
     * Get diasAdicionalesIBP
     *
     * @return integer
     */
    public function getDiasAdicionalesIBP()
    {
        return $this->diasAdicionalesIBP;
    }

    /**
     * Set vrBasePrestaciones
     *
     * @param float $vrBasePrestaciones
     *
     * @return RhuPrestacion
     */
    public function setVrBasePrestaciones($vrBasePrestaciones)
    {
        $this->VrBasePrestaciones = $vrBasePrestaciones;

        return $this;
    }

    /**
     * Get vrBasePrestaciones
     *
     * @return float
     */
    public function getVrBasePrestaciones()
    {
        return $this->VrBasePrestaciones;
    }

    /**
     * Set vrBasePrestacionesTotal
     *
     * @param float $vrBasePrestacionesTotal
     *
     * @return RhuPrestacion
     */
    public function setVrBasePrestacionesTotal($vrBasePrestacionesTotal)
    {
        $this->VrBasePrestacionesTotal = $vrBasePrestacionesTotal;

        return $this;
    }

    /**
     * Get vrBasePrestacionesTotal
     *
     * @return float
     */
    public function getVrBasePrestacionesTotal()
    {
        return $this->VrBasePrestacionesTotal;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuPrestacion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->VrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->VrAuxilioTransporte;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuPrestacion
     */
    public function setVrSalario($vrSalario)
    {
        $this->VrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * Set vrSalarioPromedioCesantias
     *
     * @param float $vrSalarioPromedioCesantias
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioPromedioCesantias($vrSalarioPromedioCesantias)
    {
        $this->VrSalarioPromedioCesantias = $vrSalarioPromedioCesantias;

        return $this;
    }

    /**
     * Get vrSalarioPromedioCesantias
     *
     * @return float
     */
    public function getVrSalarioPromedioCesantias()
    {
        return $this->VrSalarioPromedioCesantias;
    }

    /**
     * Set vrSalarioPromedioCesantiasAnterior
     *
     * @param float $vrSalarioPromedioCesantiasAnterior
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioPromedioCesantiasAnterior($vrSalarioPromedioCesantiasAnterior)
    {
        $this->VrSalarioPromedioCesantiasAnterior = $vrSalarioPromedioCesantiasAnterior;

        return $this;
    }

    /**
     * Get vrSalarioPromedioCesantiasAnterior
     *
     * @return float
     */
    public function getVrSalarioPromedioCesantiasAnterior()
    {
        return $this->VrSalarioPromedioCesantiasAnterior;
    }

    /**
     * Set vrSalarioPromedioPrimas
     *
     * @param float $vrSalarioPromedioPrimas
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioPromedioPrimas($vrSalarioPromedioPrimas)
    {
        $this->VrSalarioPromedioPrimas = $vrSalarioPromedioPrimas;

        return $this;
    }

    /**
     * Get vrSalarioPromedioPrimas
     *
     * @return float
     */
    public function getVrSalarioPromedioPrimas()
    {
        return $this->VrSalarioPromedioPrimas;
    }

    /**
     * Set vrSalarioVacaciones
     *
     * @param float $vrSalarioVacaciones
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioVacaciones($vrSalarioVacaciones)
    {
        $this->VrSalarioVacaciones = $vrSalarioVacaciones;

        return $this;
    }

    /**
     * Get vrSalarioVacaciones
     *
     * @return float
     */
    public function getVrSalarioVacaciones()
    {
        return $this->VrSalarioVacaciones;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuPrestacion
     */
    public function setVrTotal($vrTotal)
    {
        $this->VrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->VrTotal;
    }

    /**
     * Set liquidarCesantias
     *
     * @param boolean $liquidarCesantias
     *
     * @return RhuPrestacion
     */
    public function setLiquidarCesantias($liquidarCesantias)
    {
        $this->liquidarCesantias = $liquidarCesantias;

        return $this;
    }

    /**
     * Get liquidarCesantias
     *
     * @return boolean
     */
    public function getLiquidarCesantias()
    {
        return $this->liquidarCesantias;
    }

    /**
     * Set liquidarVacaciones
     *
     * @param boolean $liquidarVacaciones
     *
     * @return RhuPrestacion
     */
    public function setLiquidarVacaciones($liquidarVacaciones)
    {
        $this->liquidarVacaciones = $liquidarVacaciones;

        return $this;
    }

    /**
     * Get liquidarVacaciones
     *
     * @return boolean
     */
    public function getLiquidarVacaciones()
    {
        return $this->liquidarVacaciones;
    }

    /**
     * Set liquidarPrima
     *
     * @param boolean $liquidarPrima
     *
     * @return RhuPrestacion
     */
    public function setLiquidarPrima($liquidarPrima)
    {
        $this->liquidarPrima = $liquidarPrima;

        return $this;
    }

    /**
     * Get liquidarPrima
     *
     * @return boolean
     */
    public function getLiquidarPrima()
    {
        return $this->liquidarPrima;
    }

    /**
     * Set fechaUltimoPagoPrimas
     *
     * @param \DateTime $fechaUltimoPagoPrimas
     *
     * @return RhuPrestacion
     */
    public function setFechaUltimoPagoPrimas($fechaUltimoPagoPrimas)
    {
        $this->fechaUltimoPagoPrimas = $fechaUltimoPagoPrimas;

        return $this;
    }

    /**
     * Get fechaUltimoPagoPrimas
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoPrimas()
    {
        return $this->fechaUltimoPagoPrimas;
    }

    /**
     * Set fechaUltimoPagoVacaciones
     *
     * @param \DateTime $fechaUltimoPagoVacaciones
     *
     * @return RhuPrestacion
     */
    public function setFechaUltimoPagoVacaciones($fechaUltimoPagoVacaciones)
    {
        $this->fechaUltimoPagoVacaciones = $fechaUltimoPagoVacaciones;

        return $this;
    }

    /**
     * Get fechaUltimoPagoVacaciones
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoVacaciones()
    {
        return $this->fechaUltimoPagoVacaciones;
    }

    /**
     * Set fechaUltimoPagoCesantias
     *
     * @param \DateTime $fechaUltimoPagoCesantias
     *
     * @return RhuPrestacion
     */
    public function setFechaUltimoPagoCesantias($fechaUltimoPagoCesantias)
    {
        $this->fechaUltimoPagoCesantias = $fechaUltimoPagoCesantias;

        return $this;
    }

    /**
     * Get fechaUltimoPagoCesantias
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoCesantias()
    {
        return $this->fechaUltimoPagoCesantias;
    }

    /**
     * Set fechaUltimoPagoCesantiasAnterior
     *
     * @param \DateTime $fechaUltimoPagoCesantiasAnterior
     *
     * @return RhuPrestacion
     */
    public function setFechaUltimoPagoCesantiasAnterior($fechaUltimoPagoCesantiasAnterior)
    {
        $this->fechaUltimoPagoCesantiasAnterior = $fechaUltimoPagoCesantiasAnterior;

        return $this;
    }

    /**
     * Get fechaUltimoPagoCesantiasAnterior
     *
     * @return \DateTime
     */
    public function getFechaUltimoPagoCesantiasAnterior()
    {
        return $this->fechaUltimoPagoCesantiasAnterior;
    }

    /**
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuPrestacion
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->VrDeducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->VrDeducciones;
    }

    /**
     * Set vrBonificaciones
     *
     * @param float $vrBonificaciones
     *
     * @return RhuPrestacion
     */
    public function setVrBonificaciones($vrBonificaciones)
    {
        $this->VrBonificaciones = $vrBonificaciones;

        return $this;
    }

    /**
     * Get vrBonificaciones
     *
     * @return float
     */
    public function getVrBonificaciones()
    {
        return $this->VrBonificaciones;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuPrestacion
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuPrestacion
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set fechaInicioContrato
     *
     * @param \DateTime $fechaInicioContrato
     *
     * @return RhuPrestacion
     */
    public function setFechaInicioContrato($fechaInicioContrato)
    {
        $this->fechaInicioContrato = $fechaInicioContrato;

        return $this;
    }

    /**
     * Get fechaInicioContrato
     *
     * @return \DateTime
     */
    public function getFechaInicioContrato()
    {
        return $this->fechaInicioContrato;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuPrestacion
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set liquidarManual
     *
     * @param boolean $liquidarManual
     *
     * @return RhuPrestacion
     */
    public function setLiquidarManual($liquidarManual)
    {
        $this->liquidarManual = $liquidarManual;

        return $this;
    }

    /**
     * Get liquidarManual
     *
     * @return boolean
     */
    public function getLiquidarManual()
    {
        return $this->liquidarManual;
    }

    /**
     * Set estadoPagoGenerado
     *
     * @param boolean $estadoPagoGenerado
     *
     * @return RhuPrestacion
     */
    public function setEstadoPagoGenerado($estadoPagoGenerado)
    {
        $this->estadoPagoGenerado = $estadoPagoGenerado;

        return $this;
    }

    /**
     * Get estadoPagoGenerado
     *
     * @return boolean
     */
    public function getEstadoPagoGenerado()
    {
        return $this->estadoPagoGenerado;
    }

    /**
     * Set estadoPagoBanco
     *
     * @param boolean $estadoPagoBanco
     *
     * @return RhuPrestacion
     */
    public function setEstadoPagoBanco($estadoPagoBanco)
    {
        $this->estadoPagoBanco = $estadoPagoBanco;

        return $this;
    }

    /**
     * Get estadoPagoBanco
     *
     * @return boolean
     */
    public function getEstadoPagoBanco()
    {
        return $this->estadoPagoBanco;
    }

    /**
     * Set liquidarSalario
     *
     * @param boolean $liquidarSalario
     *
     * @return RhuPrestacion
     */
    public function setLiquidarSalario($liquidarSalario)
    {
        $this->liquidarSalario = $liquidarSalario;

        return $this;
    }

    /**
     * Get liquidarSalario
     *
     * @return boolean
     */
    public function getLiquidarSalario()
    {
        return $this->liquidarSalario;
    }

    /**
     * Set porcentajeIbp
     *
     * @param float $porcentajeIbp
     *
     * @return RhuPrestacion
     */
    public function setPorcentajeIbp($porcentajeIbp)
    {
        $this->porcentajeIbp = $porcentajeIbp;

        return $this;
    }

    /**
     * Get porcentajeIbp
     *
     * @return float
     */
    public function getPorcentajeIbp()
    {
        return $this->porcentajeIbp;
    }

    /**
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return RhuPrestacion
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * Set diasAusentismoAdicional
     *
     * @param integer $diasAusentismoAdicional
     *
     * @return RhuPrestacion
     */
    public function setDiasAusentismoAdicional($diasAusentismoAdicional)
    {
        $this->diasAusentismoAdicional = $diasAusentismoAdicional;

        return $this;
    }

    /**
     * Get diasAusentismoAdicional
     *
     * @return integer
     */
    public function getDiasAusentismoAdicional()
    {
        return $this->diasAusentismoAdicional;
    }

    /**
     * Set vrSalarioVacacionPropuesto
     *
     * @param float $vrSalarioVacacionPropuesto
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioVacacionPropuesto($vrSalarioVacacionPropuesto)
    {
        $this->VrSalarioVacacionPropuesto = $vrSalarioVacacionPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioVacacionPropuesto
     *
     * @return float
     */
    public function getVrSalarioVacacionPropuesto()
    {
        return $this->VrSalarioVacacionPropuesto;
    }

    /**
     * Set vrSalarioPrimaPropuesto
     *
     * @param float $vrSalarioPrimaPropuesto
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioPrimaPropuesto($vrSalarioPrimaPropuesto)
    {
        $this->VrSalarioPrimaPropuesto = $vrSalarioPrimaPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioPrimaPropuesto
     *
     * @return float
     */
    public function getVrSalarioPrimaPropuesto()
    {
        return $this->VrSalarioPrimaPropuesto;
    }

    /**
     * Set vrSalarioCesantiasPropuesto
     *
     * @param float $vrSalarioCesantiasPropuesto
     *
     * @return RhuPrestacion
     */
    public function setVrSalarioCesantiasPropuesto($vrSalarioCesantiasPropuesto)
    {
        $this->VrSalarioCesantiasPropuesto = $vrSalarioCesantiasPropuesto;

        return $this;
    }

    /**
     * Get vrSalarioCesantiasPropuesto
     *
     * @return float
     */
    public function getVrSalarioCesantiasPropuesto()
    {
        return $this->VrSalarioCesantiasPropuesto;
    }

    /**
     * Set eliminarAusentismo
     *
     * @param boolean $eliminarAusentismo
     *
     * @return RhuPrestacion
     */
    public function setEliminarAusentismo($eliminarAusentismo)
    {
        $this->eliminarAusentismo = $eliminarAusentismo;

        return $this;
    }

    /**
     * Get eliminarAusentismo
     *
     * @return boolean
     */
    public function getEliminarAusentismo()
    {
        return $this->eliminarAusentismo;
    }

    /**
     * Set diasAusentismoPropuesto
     *
     * @param integer $diasAusentismoPropuesto
     *
     * @return RhuPrestacion
     */
    public function setDiasAusentismoPropuesto($diasAusentismoPropuesto)
    {
        $this->diasAusentismoPropuesto = $diasAusentismoPropuesto;

        return $this;
    }

    /**
     * Get diasAusentismoPropuesto
     *
     * @return integer
     */
    public function getDiasAusentismoPropuesto()
    {
        return $this->diasAusentismoPropuesto;
    }

    /**
     * Set codigoProgramacionPagoDetalleFk
     *
     * @param integer $codigoProgramacionPagoDetalleFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoProgramacionPagoDetalleFk($codigoProgramacionPagoDetalleFk)
    {
        $this->codigoProgramacionPagoDetalleFk = $codigoProgramacionPagoDetalleFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoDetalleFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoDetalleFk()
    {
        return $this->codigoProgramacionPagoDetalleFk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoPagoFk($codigoPagoFk)
    {
        $this->codigoPagoFk = $codigoPagoFk;

        return $this;
    }

    /**
     * Get codigoPagoFk
     *
     * @return integer
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * Set omitirCesantiasAnterior
     *
     * @param boolean $omitirCesantiasAnterior
     *
     * @return RhuPrestacion
     */
    public function setOmitirCesantiasAnterior($omitirCesantiasAnterior)
    {
        $this->omitirCesantiasAnterior = $omitirCesantiasAnterior;

        return $this;
    }

    /**
     * Get omitirCesantiasAnterior
     *
     * @return boolean
     */
    public function getOmitirCesantiasAnterior()
    {
        return $this->omitirCesantiasAnterior;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuPrestacion
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuPrestacion
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuPrestacion
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set codigoCierreAnioFk
     *
     * @param integer $codigoCierreAnioFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoCierreAnioFk($codigoCierreAnioFk)
    {
        $this->codigoCierreAnioFk = $codigoCierreAnioFk;

        return $this;
    }

    /**
     * Get codigoCierreAnioFk
     *
     * @return integer
     */
    public function getCodigoCierreAnioFk()
    {
        return $this->codigoCierreAnioFk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return RhuPrestacion
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }
}
