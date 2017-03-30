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
     * @ORM\Column(name="vr_adicional_tiempo", type="float")
     */
    private $vrAdicionalTiempo = 0;     

    /**
     * @ORM\Column(name="vr_adicional_valor", type="float")
     */
    private $vrAdicionalValor = 0;

    /**
     * @ORM\Column(name="vr_adicional_prestacional", type="float")
     */
    private $vrAdicionalPrestacional = 0;
    
    /**
     * @ORM\Column(name="vr_adicional_no_prestacional", type="float")
     */
    private $vrAdicionalNoPrestacional = 0;
    
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
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $vrBruto = 0;                
    
    /**
     * @ORM\Column(name="vr_total_cobrar", type="float")
     */
    private $vrTotalCobrar = 0;    

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;     
    
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
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="servicioCobrarRel")
     */
    protected $facturasDetallesServicioCobrarRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesServicioCobrarRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set vrAdicionalTiempo
     *
     * @param float $vrAdicionalTiempo
     *
     * @return RhuServicioCobrar
     */
    public function setVrAdicionalTiempo($vrAdicionalTiempo)
    {
        $this->vrAdicionalTiempo = $vrAdicionalTiempo;

        return $this;
    }

    /**
     * Get vrAdicionalTiempo
     *
     * @return float
     */
    public function getVrAdicionalTiempo()
    {
        return $this->vrAdicionalTiempo;
    }

    /**
     * Set vrAdicionalValor
     *
     * @param float $vrAdicionalValor
     *
     * @return RhuServicioCobrar
     */
    public function setVrAdicionalValor($vrAdicionalValor)
    {
        $this->vrAdicionalValor = $vrAdicionalValor;

        return $this;
    }

    /**
     * Get vrAdicionalValor
     *
     * @return float
     */
    public function getVrAdicionalValor()
    {
        return $this->vrAdicionalValor;
    }

    /**
     * Set vrAdicionalPrestacional
     *
     * @param float $vrAdicionalPrestacional
     *
     * @return RhuServicioCobrar
     */
    public function setVrAdicionalPrestacional($vrAdicionalPrestacional)
    {
        $this->vrAdicionalPrestacional = $vrAdicionalPrestacional;

        return $this;
    }

    /**
     * Get vrAdicionalPrestacional
     *
     * @return float
     */
    public function getVrAdicionalPrestacional()
    {
        return $this->vrAdicionalPrestacional;
    }

    /**
     * Set vrAdicionalNoPrestacional
     *
     * @param float $vrAdicionalNoPrestacional
     *
     * @return RhuServicioCobrar
     */
    public function setVrAdicionalNoPrestacional($vrAdicionalNoPrestacional)
    {
        $this->vrAdicionalNoPrestacional = $vrAdicionalNoPrestacional;

        return $this;
    }

    /**
     * Get vrAdicionalNoPrestacional
     *
     * @return float
     */
    public function getVrAdicionalNoPrestacional()
    {
        return $this->vrAdicionalNoPrestacional;
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
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return RhuServicioCobrar
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return RhuServicioCobrar
     */
    public function setVrBruto($vrBruto)
    {
        $this->vrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * Set vrTotalCobrar
     *
     * @param float $vrTotalCobrar
     *
     * @return RhuServicioCobrar
     */
    public function setVrTotalCobrar($vrTotalCobrar)
    {
        $this->vrTotalCobrar = $vrTotalCobrar;

        return $this;
    }

    /**
     * Get vrTotalCobrar
     *
     * @return float
     */
    public function getVrTotalCobrar()
    {
        return $this->vrTotalCobrar;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return RhuServicioCobrar
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
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
     * Add facturasDetallesServicioCobrarRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesServicioCobrarRel
     *
     * @return RhuServicioCobrar
     */
    public function addFacturasDetallesServicioCobrarRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesServicioCobrarRel)
    {
        $this->facturasDetallesServicioCobrarRel[] = $facturasDetallesServicioCobrarRel;

        return $this;
    }

    /**
     * Remove facturasDetallesServicioCobrarRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesServicioCobrarRel
     */
    public function removeFacturasDetallesServicioCobrarRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesServicioCobrarRel)
    {
        $this->facturasDetallesServicioCobrarRel->removeElement($facturasDetallesServicioCobrarRel);
    }

    /**
     * Get facturasDetallesServicioCobrarRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesServicioCobrarRel()
    {
        return $this->facturasDetallesServicioCobrarRel;
    }
}
