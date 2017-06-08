<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cobro")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCobroRepository")
 */
class RhuCobro {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cobro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCobroPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_cobro_tipo_fk", type="string", length=1, nullable=true)
     */
    private $codigoCobroTipoFk;

    /**
     * @ORM\Column(name="codigo_centro_trabajo_fk", type="integer", nullable=true)
     */
    private $codigoCentroTrabajoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="vr_basico", type="float")
     */
    private $vrBasico = 0;

    /**
     * @ORM\Column(name="vr_prestacional", type="float")
     */
    private $vrPrestacional = 0;

    /**
     * @ORM\Column(name="vr_no_prestacional", type="float")
     */
    private $vrNoPrestacional = 0;

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
     * @ORM\Column(name="vr_operacion", type="float")
     */
    private $vrOperacion = 0;

    /**
     * @ORM\Column(name="vr_administracion", type="float")
     */
    private $vrAdministracion = 0;

    /**
     * @ORM\Column(name="vr_examen", type="float")
     */
    private $vrExamen = 0;

    /**
     * @ORM\Column(name="vr_seleccion", type="float")
     */
    private $vrSeleccion = 0;

    /**
     * @ORM\Column(name="vr_incapacidad", type="float")
     */
    private $vrIncapacidad = 0;
    
    /**
     * @ORM\Column(name="vr_visita", type="float")
     */
    private $vrVisita = 0;
    
    /**
     * @ORM\Column(name="vr_prueba", type="float")
     */
    private $vrPrueba = 0;
    
    /**
     * @ORM\Column(name="vr_poligrafia", type="float")
     */
    private $vrPoligrafia= 0;

    /**
     * @ORM\Column(name="vr_total_cobro", type="float")
     */
    private $vrTotalCobro = 0;

    /**
     * @ORM\Column(name="vr_ajuste", type="float")
     */
    private $vrAjuste = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="numero_registros", type="integer")
     */
    private $numeroRegistros = 0;

    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */
    private $estadoCobrado = 0;

    /**
     * @ORM\Column(name="estado_facturado", type="boolean")
     */
    private $estadoFacturado = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="cobrosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="cobrosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCobroTipo", inversedBy="cobrosCobroTipoRel")
     * @ORM\JoinColumn(name="codigo_cobro_tipo_fk", referencedColumnName="codigo_cobro_tipo_pk")
     */
    protected $cobroTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroTrabajo", inversedBy="cobrosCentroTrabajoRel")
     * @ORM\JoinColumn(name="codigo_centro_trabajo_fk", referencedColumnName="codigo_centro_trabajo_pk")
     */
    protected $centroTrabajoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="cobroRel")
     */
    protected $serviciosCobrarCobroRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="cobroRel")
     */
    protected $examenesCobroRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="cobroRel")
     */
    protected $seleccionesCobroRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="cobroRel")
     */
    protected $incapacidadesCobroRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuVisita", mappedBy="cobroRel")
     */
    protected $visitasCobroRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPrueba", mappedBy="cobroRel")
     */
    protected $pruebasCobroRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPoligrafia", mappedBy="cobroRel")
     */
    protected $poligrafiasCobroRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="cobroRel")
     */
    protected $facturasDetallesCobroRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosCobrarCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visitasCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pruebasCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->poligrafiasCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCobroPk
     *
     * @return integer
     */
    public function getCodigoCobroPk()
    {
        return $this->codigoCobroPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuCobro
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuCobro
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
     * Set codigoCobroTipoFk
     *
     * @param string $codigoCobroTipoFk
     *
     * @return RhuCobro
     */
    public function setCodigoCobroTipoFk($codigoCobroTipoFk)
    {
        $this->codigoCobroTipoFk = $codigoCobroTipoFk;

        return $this;
    }

    /**
     * Get codigoCobroTipoFk
     *
     * @return string
     */
    public function getCodigoCobroTipoFk()
    {
        return $this->codigoCobroTipoFk;
    }

    /**
     * Set codigoCentroTrabajoFk
     *
     * @param integer $codigoCentroTrabajoFk
     *
     * @return RhuCobro
     */
    public function setCodigoCentroTrabajoFk($codigoCentroTrabajoFk)
    {
        $this->codigoCentroTrabajoFk = $codigoCentroTrabajoFk;

        return $this;
    }

    /**
     * Get codigoCentroTrabajoFk
     *
     * @return integer
     */
    public function getCodigoCentroTrabajoFk()
    {
        return $this->codigoCentroTrabajoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCobro
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuCobro
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
     * @return RhuCobro
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
     * Set vrBasico
     *
     * @param float $vrBasico
     *
     * @return RhuCobro
     */
    public function setVrBasico($vrBasico)
    {
        $this->vrBasico = $vrBasico;

        return $this;
    }

    /**
     * Get vrBasico
     *
     * @return float
     */
    public function getVrBasico()
    {
        return $this->vrBasico;
    }

    /**
     * Set vrPrestacional
     *
     * @param float $vrPrestacional
     *
     * @return RhuCobro
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
     * @return RhuCobro
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
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * @return RhuCobro
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
     * Set vrOperacion
     *
     * @param float $vrOperacion
     *
     * @return RhuCobro
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
     * Set vrAdministracion
     *
     * @param float $vrAdministracion
     *
     * @return RhuCobro
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
     * Set vrExamen
     *
     * @param float $vrExamen
     *
     * @return RhuCobro
     */
    public function setVrExamen($vrExamen)
    {
        $this->vrExamen = $vrExamen;

        return $this;
    }

    /**
     * Get vrExamen
     *
     * @return float
     */
    public function getVrExamen()
    {
        return $this->vrExamen;
    }

    /**
     * Set vrSeleccion
     *
     * @param float $vrSeleccion
     *
     * @return RhuCobro
     */
    public function setVrSeleccion($vrSeleccion)
    {
        $this->vrSeleccion = $vrSeleccion;

        return $this;
    }

    /**
     * Get vrSeleccion
     *
     * @return float
     */
    public function getVrSeleccion()
    {
        return $this->vrSeleccion;
    }

    /**
     * Set vrIncapacidad
     *
     * @param float $vrIncapacidad
     *
     * @return RhuCobro
     */
    public function setVrIncapacidad($vrIncapacidad)
    {
        $this->vrIncapacidad = $vrIncapacidad;

        return $this;
    }

    /**
     * Get vrIncapacidad
     *
     * @return float
     */
    public function getVrIncapacidad()
    {
        return $this->vrIncapacidad;
    }

    /**
     * Set vrVisita
     *
     * @param float $vrVisita
     *
     * @return RhuCobro
     */
    public function setVrVisita($vrVisita)
    {
        $this->vrVisita = $vrVisita;

        return $this;
    }

    /**
     * Get vrVisita
     *
     * @return float
     */
    public function getVrVisita()
    {
        return $this->vrVisita;
    }

    /**
     * Set vrPrueba
     *
     * @param float $vrPrueba
     *
     * @return RhuCobro
     */
    public function setVrPrueba($vrPrueba)
    {
        $this->vrPrueba = $vrPrueba;

        return $this;
    }

    /**
     * Get vrPrueba
     *
     * @return float
     */
    public function getVrPrueba()
    {
        return $this->vrPrueba;
    }

    /**
     * Set vrTotalCobro
     *
     * @param float $vrTotalCobro
     *
     * @return RhuCobro
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
     * Set vrAjuste
     *
     * @param float $vrAjuste
     *
     * @return RhuCobro
     */
    public function setVrAjuste($vrAjuste)
    {
        $this->vrAjuste = $vrAjuste;

        return $this;
    }

    /**
     * Get vrAjuste
     *
     * @return float
     */
    public function getVrAjuste()
    {
        return $this->vrAjuste;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuCobro
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCobro
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
     * Set numeroRegistros
     *
     * @param integer $numeroRegistros
     *
     * @return RhuCobro
     */
    public function setNumeroRegistros($numeroRegistros)
    {
        $this->numeroRegistros = $numeroRegistros;

        return $this;
    }

    /**
     * Get numeroRegistros
     *
     * @return integer
     */
    public function getNumeroRegistros()
    {
        return $this->numeroRegistros;
    }

    /**
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuCobro
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
     * Set estadoFacturado
     *
     * @param boolean $estadoFacturado
     *
     * @return RhuCobro
     */
    public function setEstadoFacturado($estadoFacturado)
    {
        $this->estadoFacturado = $estadoFacturado;

        return $this;
    }

    /**
     * Get estadoFacturado
     *
     * @return boolean
     */
    public function getEstadoFacturado()
    {
        return $this->estadoFacturado;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuCobro
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
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuCobro
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
     * Set cobroTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo $cobroTipoRel
     *
     * @return RhuCobro
     */
    public function setCobroTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo $cobroTipoRel = null)
    {
        $this->cobroTipoRel = $cobroTipoRel;

        return $this;
    }

    /**
     * Get cobroTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo
     */
    public function getCobroTipoRel()
    {
        return $this->cobroTipoRel;
    }

    /**
     * Set centroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo $centroTrabajoRel
     *
     * @return RhuCobro
     */
    public function setCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo $centroTrabajoRel = null)
    {
        $this->centroTrabajoRel = $centroTrabajoRel;

        return $this;
    }

    /**
     * Get centroTrabajoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo
     */
    public function getCentroTrabajoRel()
    {
        return $this->centroTrabajoRel;
    }

    /**
     * Add serviciosCobrarCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel
     *
     * @return RhuCobro
     */
    public function addServiciosCobrarCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel)
    {
        $this->serviciosCobrarCobroRel[] = $serviciosCobrarCobroRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel
     */
    public function removeServiciosCobrarCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel)
    {
        $this->serviciosCobrarCobroRel->removeElement($serviciosCobrarCobroRel);
    }

    /**
     * Get serviciosCobrarCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarCobroRel()
    {
        return $this->serviciosCobrarCobroRel;
    }

    /**
     * Add examenesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCobroRel
     *
     * @return RhuCobro
     */
    public function addExamenesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCobroRel)
    {
        $this->examenesCobroRel[] = $examenesCobroRel;

        return $this;
    }

    /**
     * Remove examenesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCobroRel
     */
    public function removeExamenesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesCobroRel)
    {
        $this->examenesCobroRel->removeElement($examenesCobroRel);
    }

    /**
     * Get examenesCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesCobroRel()
    {
        return $this->examenesCobroRel;
    }

    /**
     * Add seleccionesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCobroRel
     *
     * @return RhuCobro
     */
    public function addSeleccionesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCobroRel)
    {
        $this->seleccionesCobroRel[] = $seleccionesCobroRel;

        return $this;
    }

    /**
     * Remove seleccionesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCobroRel
     */
    public function removeSeleccionesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesCobroRel)
    {
        $this->seleccionesCobroRel->removeElement($seleccionesCobroRel);
    }

    /**
     * Get seleccionesCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesCobroRel()
    {
        return $this->seleccionesCobroRel;
    }

    /**
     * Add incapacidadesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCobroRel
     *
     * @return RhuCobro
     */
    public function addIncapacidadesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCobroRel)
    {
        $this->incapacidadesCobroRel[] = $incapacidadesCobroRel;

        return $this;
    }

    /**
     * Remove incapacidadesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCobroRel
     */
    public function removeIncapacidadesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesCobroRel)
    {
        $this->incapacidadesCobroRel->removeElement($incapacidadesCobroRel);
    }

    /**
     * Get incapacidadesCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesCobroRel()
    {
        return $this->incapacidadesCobroRel;
    }

    /**
     * Add visitasCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasCobroRel
     *
     * @return RhuCobro
     */
    public function addVisitasCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasCobroRel)
    {
        $this->visitasCobroRel[] = $visitasCobroRel;

        return $this;
    }

    /**
     * Remove visitasCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasCobroRel
     */
    public function removeVisitasCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasCobroRel)
    {
        $this->visitasCobroRel->removeElement($visitasCobroRel);
    }

    /**
     * Get visitasCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitasCobroRel()
    {
        return $this->visitasCobroRel;
    }

    /**
     * Add pruebasCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasCobroRel
     *
     * @return RhuCobro
     */
    public function addPruebasCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasCobroRel)
    {
        $this->pruebasCobroRel[] = $pruebasCobroRel;

        return $this;
    }

    /**
     * Remove pruebasCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasCobroRel
     */
    public function removePruebasCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasCobroRel)
    {
        $this->pruebasCobroRel->removeElement($pruebasCobroRel);
    }

    /**
     * Get pruebasCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasCobroRel()
    {
        return $this->pruebasCobroRel;
    }

    /**
     * Add poligrafiasCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasCobroRel
     *
     * @return RhuCobro
     */
    public function addPoligrafiasCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasCobroRel)
    {
        $this->poligrafiasCobroRel[] = $poligrafiasCobroRel;

        return $this;
    }

    /**
     * Remove poligrafiasCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasCobroRel
     */
    public function removePoligrafiasCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasCobroRel)
    {
        $this->poligrafiasCobroRel->removeElement($poligrafiasCobroRel);
    }

    /**
     * Get poligrafiasCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPoligrafiasCobroRel()
    {
        return $this->poligrafiasCobroRel;
    }

    /**
     * Add facturasDetallesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCobroRel
     *
     * @return RhuCobro
     */
    public function addFacturasDetallesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCobroRel)
    {
        $this->facturasDetallesCobroRel[] = $facturasDetallesCobroRel;

        return $this;
    }

    /**
     * Remove facturasDetallesCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCobroRel
     */
    public function removeFacturasDetallesCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesCobroRel)
    {
        $this->facturasDetallesCobroRel->removeElement($facturasDetallesCobroRel);
    }

    /**
     * Get facturasDetallesCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesCobroRel()
    {
        return $this->facturasDetallesCobroRel;
    }

    /**
     * Set vrPoligrafia
     *
     * @param float $vrPoligrafia
     *
     * @return RhuCobro
     */
    public function setVrPoligrafia($vrPoligrafia)
    {
        $this->vrPoligrafia = $vrPoligrafia;

        return $this;
    }

    /**
     * Get vrPoligrafia
     *
     * @return float
     */
    public function getVrPoligrafia()
    {
        return $this->vrPoligrafia;
    }
}
