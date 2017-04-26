<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_servicio_cobrar")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuServicioCobrarRepository")
 */
class RhuServicioCobrar
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_cobrar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioCobrarPk;   

    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;      
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk; 
    
    /**
     * @ORM\Column(name="codigo_cobro_fk", type="integer", nullable=true)
     */    
    private $codigoCobroFk;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;     
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;     

    /**
     * Es el salario corerspondiente a los dias * VrDia
     * @ORM\Column(name="vr_salario_periodo", type="float")
     */
    private $vrSalarioPeriodo = 0;        
    
    /**
     * Es el salario que tenia el empleado cuando se genero el pago
     * @ORM\Column(name="vr_salario_empleado", type="float")
     */
    private $vrSalarioEmpleado = 0;        
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */
    private $vrDevengado = 0;    

    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $vrDeducciones = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $vrAuxilioTransporte = 0;    
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte_cotizacion", type="float")
     */
    private $vrAuxilioTransporteCotizacion = 0;    
    
    /**
     * @ORM\Column(name="vr_riesgos", type="float")
     */
    private $vrRiesgos = 0;    
    
    /**
     * @ORM\Column(name="vr_salud", type="float")
     */
    private $vrSalud = 0;    
    
    /**
     * @ORM\Column(name="vr_pension", type="float")
     */
    private $vrPension = 0;    
    
    /**
     * @ORM\Column(name="vr_caja", type="float")
     */
    private $vrCaja = 0;    
    
    /**
     * @ORM\Column(name="vr_sena", type="float")
     */
    private $vrSena = 0;    
    
    /**
     * @ORM\Column(name="vr_icbf", type="float")
     */
    private $vrIcbf = 0;    
    
    /**
     * @ORM\Column(name="vr_cesantias", type="float")
     */
    private $vrCesantias = 0;
    
    /**
     * @ORM\Column(name="vr_cesantias_intereses", type="float")
     */
    private $vrCesantiasIntereses = 0;    
    
    /**
     * @ORM\Column(name="vr_primas", type="float")
     */
    private $vrPrimas = 0;

    /**
     * @ORM\Column(name="vr_prestaciones", type="float")
     */
    private $vrPrestaciones = 0;
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */
    private $vrVacaciones = 0;

    /**
     * @ORM\Column(name="vr_aporte_parafiscales", type="float")
     */
    private $vrAporteParafiscales = 0;
    
    /**
     * @ORM\Column(name="vr_administracion", type="float")
     */
    private $vrAdministracion = 0;    
    
    /**
     * @ORM\Column(name="vr_operacion", type="float")
     */
    private $vrOperacion = 0;             
    
    /**
     * @ORM\Column(name="vr_total_cobro", type="float")
     */
    private $vrTotalCobro = 0;    
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;    
    
    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */    
    private $estadoCobrado = 0;     
    
    /**
     * @ORM\Column(name="dias_periodo", type="integer")
     */
    private $diasPeriodo = 0;               
    
    /**
     * @ORM\Column(name="vr_prestacional", type="float")
     */
    private $vrPrestacional = 0;
    
    /**
     * @ORM\Column(name="vr_no_prestacional", type="float")
     */
    private $vrNoPrestacional = 0;    

    /**
     * @ORM\Column(name="porcentaje_riesgos", type="float")
     */
    private $porcentajeRiesgos = 0;    
    
    /**
     * @ORM\Column(name="horas_incapacidad", type="integer")
     */
    private $horasIncapacidad = 0;     
    
    /**
     * @ORM\Column(name="ingreso", type="string", length=20, nullable=true)
     */    
    private $ingreso;    
    
    /**
     * @ORM\Column(name="retiro", type="string", length=20, nullable=true)
     */    
    private $retiro;    
    
    /**
     * @ORM\Column(name="porcentaje_administracion", type="float")
     */
    private $porcentajeAdministracion = 0;     
    
    /**
     * @ORM\Column(name="valor_administracion_fijo", type="float")
     */
    private $valorAdministracionFijo = 0;    
    
    /**
     * @ORM\Column(name="administracion_fijo", type="boolean")
     */    
    private $administracionFijo = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_cesantias", type="float")
     */
    private $porcentajeCesantias = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_intereses_cesantias", type="float")
     */
    private $porcentajeInteresesCesantias = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_primas", type="float")
     */
    private $porcentajePrimas = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_prestaciones", type="float")
     */
    private $porcentajePrestaciones = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_vacaciones", type="float")
     */
    private $porcentajeVacaciones = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_caja", type="float")
     */
    private $porcentajeCaja = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCobro", inversedBy="serviciosCobrarCobroRel")
     * @ORM\JoinColumn(name="codigo_cobro_fk", referencedColumnName="codigo_cobro_pk")
     */
    protected $cobroRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="serviciosCobrarClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="serviciosCobrarPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="serviciosCobrarCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="serviciosCobrarEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="serviciosCobrarProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;
    


    /**
     * Get codigoServicioCobrarPk
     *
     * @return integer
     */
    public function getCodigoServicioCobrarPk()
    {
        return $this->codigoServicioCobrarPk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuServicioCobrar
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuServicioCobrar
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
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuServicioCobrar
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set codigoCobroFk
     *
     * @param integer $codigoCobroFk
     *
     * @return RhuServicioCobrar
     */
    public function setCodigoCobroFk($codigoCobroFk)
    {
        $this->codigoCobroFk = $codigoCobroFk;

        return $this;
    }

    /**
     * Get codigoCobroFk
     *
     * @return integer
     */
    public function getCodigoCobroFk()
    {
        return $this->codigoCobroFk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuServicioCobrar
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuServicioCobrar
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
     * @return RhuServicioCobrar
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuServicioCobrar
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set vrSalarioPeriodo
     *
     * @param float $vrSalarioPeriodo
     *
     * @return RhuServicioCobrar
     */
    public function setVrSalarioPeriodo($vrSalarioPeriodo)
    {
        $this->vrSalarioPeriodo = $vrSalarioPeriodo;

        return $this;
    }

    /**
     * Get vrSalarioPeriodo
     *
     * @return float
     */
    public function getVrSalarioPeriodo()
    {
        return $this->vrSalarioPeriodo;
    }

    /**
     * Set vrSalarioEmpleado
     *
     * @param float $vrSalarioEmpleado
     *
     * @return RhuServicioCobrar
     */
    public function setVrSalarioEmpleado($vrSalarioEmpleado)
    {
        $this->vrSalarioEmpleado = $vrSalarioEmpleado;

        return $this;
    }

    /**
     * Get vrSalarioEmpleado
     *
     * @return float
     */
    public function getVrSalarioEmpleado()
    {
        return $this->vrSalarioEmpleado;
    }

    /**
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return RhuServicioCobrar
     */
    public function setVrDevengado($vrDevengado)
    {
        $this->vrDevengado = $vrDevengado;

        return $this;
    }

    /**
     * Get vrDevengado
     *
     * @return float
     */
    public function getVrDevengado()
    {
        return $this->vrDevengado;
    }

    /**
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuServicioCobrar
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->vrDeducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->vrDeducciones;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuServicioCobrar
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set vrAuxilioTransporteCotizacion
     *
     * @param float $vrAuxilioTransporteCotizacion
     *
     * @return RhuServicioCobrar
     */
    public function setVrAuxilioTransporteCotizacion($vrAuxilioTransporteCotizacion)
    {
        $this->vrAuxilioTransporteCotizacion = $vrAuxilioTransporteCotizacion;

        return $this;
    }

    /**
     * Get vrAuxilioTransporteCotizacion
     *
     * @return float
     */
    public function getVrAuxilioTransporteCotizacion()
    {
        return $this->vrAuxilioTransporteCotizacion;
    }

    /**
     * Set vrRiesgos
     *
     * @param float $vrRiesgos
     *
     * @return RhuServicioCobrar
     */
    public function setVrRiesgos($vrRiesgos)
    {
        $this->vrRiesgos = $vrRiesgos;

        return $this;
    }

    /**
     * Get vrRiesgos
     *
     * @return float
     */
    public function getVrRiesgos()
    {
        return $this->vrRiesgos;
    }

    /**
     * Set vrSalud
     *
     * @param float $vrSalud
     *
     * @return RhuServicioCobrar
     */
    public function setVrSalud($vrSalud)
    {
        $this->vrSalud = $vrSalud;

        return $this;
    }

    /**
     * Get vrSalud
     *
     * @return float
     */
    public function getVrSalud()
    {
        return $this->vrSalud;
    }

    /**
     * Set vrPension
     *
     * @param float $vrPension
     *
     * @return RhuServicioCobrar
     */
    public function setVrPension($vrPension)
    {
        $this->vrPension = $vrPension;

        return $this;
    }

    /**
     * Get vrPension
     *
     * @return float
     */
    public function getVrPension()
    {
        return $this->vrPension;
    }

    /**
     * Set vrCaja
     *
     * @param float $vrCaja
     *
     * @return RhuServicioCobrar
     */
    public function setVrCaja($vrCaja)
    {
        $this->vrCaja = $vrCaja;

        return $this;
    }

    /**
     * Get vrCaja
     *
     * @return float
     */
    public function getVrCaja()
    {
        return $this->vrCaja;
    }

    /**
     * Set vrSena
     *
     * @param float $vrSena
     *
     * @return RhuServicioCobrar
     */
    public function setVrSena($vrSena)
    {
        $this->vrSena = $vrSena;

        return $this;
    }

    /**
     * Get vrSena
     *
     * @return float
     */
    public function getVrSena()
    {
        return $this->vrSena;
    }

    /**
     * Set vrIcbf
     *
     * @param float $vrIcbf
     *
     * @return RhuServicioCobrar
     */
    public function setVrIcbf($vrIcbf)
    {
        $this->vrIcbf = $vrIcbf;

        return $this;
    }

    /**
     * Get vrIcbf
     *
     * @return float
     */
    public function getVrIcbf()
    {
        return $this->vrIcbf;
    }

    /**
     * Set vrCesantias
     *
     * @param float $vrCesantias
     *
     * @return RhuServicioCobrar
     */
    public function setVrCesantias($vrCesantias)
    {
        $this->vrCesantias = $vrCesantias;

        return $this;
    }

    /**
     * Get vrCesantias
     *
     * @return float
     */
    public function getVrCesantias()
    {
        return $this->vrCesantias;
    }

    /**
     * Set vrCesantiasIntereses
     *
     * @param float $vrCesantiasIntereses
     *
     * @return RhuServicioCobrar
     */
    public function setVrCesantiasIntereses($vrCesantiasIntereses)
    {
        $this->vrCesantiasIntereses = $vrCesantiasIntereses;

        return $this;
    }

    /**
     * Get vrCesantiasIntereses
     *
     * @return float
     */
    public function getVrCesantiasIntereses()
    {
        return $this->vrCesantiasIntereses;
    }

    /**
     * Set vrPrimas
     *
     * @param float $vrPrimas
     *
     * @return RhuServicioCobrar
     */
    public function setVrPrimas($vrPrimas)
    {
        $this->vrPrimas = $vrPrimas;

        return $this;
    }

    /**
     * Get vrPrimas
     *
     * @return float
     */
    public function getVrPrimas()
    {
        return $this->vrPrimas;
    }

    /**
     * Set vrPrestaciones
     *
     * @param float $vrPrestaciones
     *
     * @return RhuServicioCobrar
     */
    public function setVrPrestaciones($vrPrestaciones)
    {
        $this->vrPrestaciones = $vrPrestaciones;

        return $this;
    }

    /**
     * Get vrPrestaciones
     *
     * @return float
     */
    public function getVrPrestaciones()
    {
        return $this->vrPrestaciones;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuServicioCobrar
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->vrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * Set vrAporteParafiscales
     *
     * @param float $vrAporteParafiscales
     *
     * @return RhuServicioCobrar
     */
    public function setVrAporteParafiscales($vrAporteParafiscales)
    {
        $this->vrAporteParafiscales = $vrAporteParafiscales;

        return $this;
    }

    /**
     * Get vrAporteParafiscales
     *
     * @return float
     */
    public function getVrAporteParafiscales()
    {
        return $this->vrAporteParafiscales;
    }

    /**
     * Set vrAdministracion
     *
     * @param float $vrAdministracion
     *
     * @return RhuServicioCobrar
     */
    public function setVrAdministracion($vrAdministracion)
    {
        $this->vrAdministracion = $vrAdministracion;

        return $this;
    }

    /**
     * Get vrAdministracion
     *
     * @return float
     */
    public function getVrAdministracion()
    {
        return $this->vrAdministracion;
    }

    /**
     * Set vrOperacion
     *
     * @param float $vrOperacion
     *
     * @return RhuServicioCobrar
     */
    public function setVrOperacion($vrOperacion)
    {
        $this->vrOperacion = $vrOperacion;

        return $this;
    }

    /**
     * Get vrOperacion
     *
     * @return float
     */
    public function getVrOperacion()
    {
        return $this->vrOperacion;
    }

    /**
     * Set vrTotalCobro
     *
     * @param float $vrTotalCobro
     *
     * @return RhuServicioCobrar
     */
    public function setVrTotalCobro($vrTotalCobro)
    {
        $this->vrTotalCobro = $vrTotalCobro;

        return $this;
    }

    /**
     * Get vrTotalCobro
     *
     * @return float
     */
    public function getVrTotalCobro()
    {
        return $this->vrTotalCobro;
    }

    /**
     * Set vrIngresoBaseCotizacion
     *
     * @param float $vrIngresoBaseCotizacion
     *
     * @return RhuServicioCobrar
     */
    public function setVrIngresoBaseCotizacion($vrIngresoBaseCotizacion)
    {
        $this->vrIngresoBaseCotizacion = $vrIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get vrIngresoBaseCotizacion
     *
     * @return float
     */
    public function getVrIngresoBaseCotizacion()
    {
        return $this->vrIngresoBaseCotizacion;
    }

    /**
     * Set vrIngresoBasePrestacion
     *
     * @param float $vrIngresoBasePrestacion
     *
     * @return RhuServicioCobrar
     */
    public function setVrIngresoBasePrestacion($vrIngresoBasePrestacion)
    {
        $this->vrIngresoBasePrestacion = $vrIngresoBasePrestacion;

        return $this;
    }

    /**
     * Get vrIngresoBasePrestacion
     *
     * @return float
     */
    public function getVrIngresoBasePrestacion()
    {
        return $this->vrIngresoBasePrestacion;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuServicioCobrar
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
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuServicioCobrar
     */
    public function setEstadoCobrado($estadoCobrado)
    {
        $this->estadoCobrado = $estadoCobrado;

        return $this;
    }

    /**
     * Get estadoCobrado
     *
     * @return boolean
     */
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * Set diasPeriodo
     *
     * @param integer $diasPeriodo
     *
     * @return RhuServicioCobrar
     */
    public function setDiasPeriodo($diasPeriodo)
    {
        $this->diasPeriodo = $diasPeriodo;

        return $this;
    }

    /**
     * Get diasPeriodo
     *
     * @return integer
     */
    public function getDiasPeriodo()
    {
        return $this->diasPeriodo;
    }

    /**
     * Set vrPrestacional
     *
     * @param float $vrPrestacional
     *
     * @return RhuServicioCobrar
     */
    public function setVrPrestacional($vrPrestacional)
    {
        $this->vrPrestacional = $vrPrestacional;

        return $this;
    }

    /**
     * Get vrPrestacional
     *
     * @return float
     */
    public function getVrPrestacional()
    {
        return $this->vrPrestacional;
    }

    /**
     * Set vrNoPrestacional
     *
     * @param float $vrNoPrestacional
     *
     * @return RhuServicioCobrar
     */
    public function setVrNoPrestacional($vrNoPrestacional)
    {
        $this->vrNoPrestacional = $vrNoPrestacional;

        return $this;
    }

    /**
     * Get vrNoPrestacional
     *
     * @return float
     */
    public function getVrNoPrestacional()
    {
        return $this->vrNoPrestacional;
    }

    /**
     * Set porcentajeRiesgos
     *
     * @param float $porcentajeRiesgos
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajeRiesgos($porcentajeRiesgos)
    {
        $this->porcentajeRiesgos = $porcentajeRiesgos;

        return $this;
    }

    /**
     * Get porcentajeRiesgos
     *
     * @return float
     */
    public function getPorcentajeRiesgos()
    {
        return $this->porcentajeRiesgos;
    }

    /**
     * Set horasIncapacidad
     *
     * @param integer $horasIncapacidad
     *
     * @return RhuServicioCobrar
     */
    public function setHorasIncapacidad($horasIncapacidad)
    {
        $this->horasIncapacidad = $horasIncapacidad;

        return $this;
    }

    /**
     * Get horasIncapacidad
     *
     * @return integer
     */
    public function getHorasIncapacidad()
    {
        return $this->horasIncapacidad;
    }

    /**
     * Set ingreso
     *
     * @param string $ingreso
     *
     * @return RhuServicioCobrar
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param string $retiro
     *
     * @return RhuServicioCobrar
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return string
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set porcentajeAdministracion
     *
     * @param float $porcentajeAdministracion
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajeAdministracion($porcentajeAdministracion)
    {
        $this->porcentajeAdministracion = $porcentajeAdministracion;

        return $this;
    }

    /**
     * Get porcentajeAdministracion
     *
     * @return float
     */
    public function getPorcentajeAdministracion()
    {
        return $this->porcentajeAdministracion;
    }

    /**
     * Set valorAdministracionFijo
     *
     * @param float $valorAdministracionFijo
     *
     * @return RhuServicioCobrar
     */
    public function setValorAdministracionFijo($valorAdministracionFijo)
    {
        $this->valorAdministracionFijo = $valorAdministracionFijo;

        return $this;
    }

    /**
     * Get valorAdministracionFijo
     *
     * @return float
     */
    public function getValorAdministracionFijo()
    {
        return $this->valorAdministracionFijo;
    }

    /**
     * Set administracionFijo
     *
     * @param boolean $administracionFijo
     *
     * @return RhuServicioCobrar
     */
    public function setAdministracionFijo($administracionFijo)
    {
        $this->administracionFijo = $administracionFijo;

        return $this;
    }

    /**
     * Get administracionFijo
     *
     * @return boolean
     */
    public function getAdministracionFijo()
    {
        return $this->administracionFijo;
    }

    /**
     * Set cobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobroRel
     *
     * @return RhuServicioCobrar
     */
    public function setCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobroRel = null)
    {
        $this->cobroRel = $cobroRel;

        return $this;
    }

    /**
     * Get cobroRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCobro
     */
    public function getCobroRel()
    {
        return $this->cobroRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuServicioCobrar
     */
    public function setClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set pagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel
     *
     * @return RhuServicioCobrar
     */
    public function setPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel = null)
    {
        $this->pagoRel = $pagoRel;

        return $this;
    }

    /**
     * Get pagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPago
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuServicioCobrar
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuServicioCobrar
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
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuServicioCobrar
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }

    /**
     * Set porcentajeCesantias
     *
     * @param float $porcentajeCesantias
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajeCesantias($porcentajeCesantias)
    {
        $this->porcentajeCesantias = $porcentajeCesantias;

        return $this;
    }

    /**
     * Get porcentajeCesantias
     *
     * @return float
     */
    public function getPorcentajeCesantias()
    {
        return $this->porcentajeCesantias;
    }

    /**
     * Set porcentajeInteresesCesantias
     *
     * @param float $porcentajeInteresesCesantias
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajeInteresesCesantias($porcentajeInteresesCesantias)
    {
        $this->porcentajeInteresesCesantias = $porcentajeInteresesCesantias;

        return $this;
    }

    /**
     * Get porcentajeInteresesCesantias
     *
     * @return float
     */
    public function getPorcentajeInteresesCesantias()
    {
        return $this->porcentajeInteresesCesantias;
    }

    /**
     * Set porcentajePrimas
     *
     * @param float $porcentajePrimas
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajePrimas($porcentajePrimas)
    {
        $this->porcentajePrimas = $porcentajePrimas;

        return $this;
    }

    /**
     * Get porcentajePrimas
     *
     * @return float
     */
    public function getPorcentajePrimas()
    {
        return $this->porcentajePrimas;
    }

    /**
     * Set porcentajePrestaciones
     *
     * @param float $porcentajePrestaciones
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajePrestaciones($porcentajePrestaciones)
    {
        $this->porcentajePrestaciones = $porcentajePrestaciones;

        return $this;
    }

    /**
     * Get porcentajePrestaciones
     *
     * @return float
     */
    public function getPorcentajePrestaciones()
    {
        return $this->porcentajePrestaciones;
    }

    /**
     * Set porcentajeVacaciones
     *
     * @param float $porcentajeVacaciones
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajeVacaciones($porcentajeVacaciones)
    {
        $this->porcentajeVacaciones = $porcentajeVacaciones;

        return $this;
    }

    /**
     * Get porcentajeVacaciones
     *
     * @return float
     */
    public function getPorcentajeVacaciones()
    {
        return $this->porcentajeVacaciones;
    }

    /**
     * Set porcentajeCaja
     *
     * @param float $porcentajeCaja
     *
     * @return RhuServicioCobrar
     */
    public function setPorcentajeCaja($porcentajeCaja)
    {
        $this->porcentajeCaja = $porcentajeCaja;

        return $this;
    }

    /**
     * Get porcentajeCaja
     *
     * @return float
     */
    public function getPorcentajeCaja()
    {
        return $this->porcentajeCaja;
    }
}
