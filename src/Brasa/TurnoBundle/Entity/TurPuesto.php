<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_puesto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPuestoRepository")
 */
class TurPuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=65)
     */
    private $nombre;      
            
    /**
     * @ORM\Column(name="direccion", type="string", length=300, nullable=true)
     */
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;     

    /**
     * @ORM\Column(name="numero_comunicacion", type="string", length=30, nullable=true)
     */
    private $numeroComunicacion;
    
    /**
     * @ORM\Column(name="contacto", type="string", length=90, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=30, nullable=true)
     */
    private $telefonoContacto;    

    /**
     * @ORM\Column(name="celular_contacto", type="string", length=30, nullable=true)
     */
    private $celularContacto;    
    
    /**
     * @ORM\Column(name="costo_dotacion", type="float", nullable=true)
     */    
    private $costoDotacion = 0;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_programador_fk", type="integer", nullable=true)
     */    
    private $codigoProgramadorFk;    
    
    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk;     
    
    /**
     * @ORM\Column(name="codigo_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoOperacionFk;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;   
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_contabilidad_fk", type="string", length=20, nullable=true)
     */    
    private $codigoCentroCostoContabilidadFk;     
    
    /**     
     * @ORM\Column(name="control_puesto", type="boolean")
     */    
    private $controlPuesto = false;

    /**
     * @ORM\Column(name="edad_minima", type="integer", nullable=true)
     */
    private $edad_minima;
    
    /**
     * @ORM\Column(name="edad_maxima", type="integer", nullable=true)
     */
    private $edad_maxima;
    
    /**
     * @ORM\Column(name="estatura_minima", type="integer", length=20, nullable=true)
     */
    private $estaturaMinima;
    
    /**
     * @ORM\Column(name="estatura_maxima", type="integer", length=20, nullable=true)
     */
    private $estaturaMaxima;
    
    /**
     * @ORM\Column(name="peso_minimo", type="integer", nullable=true)
     */
    private $peso_minimo;
    
    /**
     * @ORM\Column(name="peso_maximo", type="integer", nullable=true)
     */
    private $peso_maximo;
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */
    private $codigoSexoFk;
    
    /**
     * @ORM\Column(name="codigo_tipo_libreta_militar", type="integer", nullable=true)
     */    
    private $codigoTipoLibretaMilitar;
    
    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    private $codigoEstadoCivilFk;
    
    /**
     * @ORM\Column(name="moto", type="boolean", nullable=true)
     */    
    private $moto;
    
    /**
     * @ORM\Column(name="carro", type="boolean", nullable=true)
     */    
    private $carro;
    
    /**
     /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil", inversedBy="turPuestosEstadoCivilRel")
     * @ORM\JoinColumn(name="codigo_estado_civil_fk", referencedColumnName="codigo_estado_civil_pk")
     */
    protected $estadoCivilRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="puestosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turPuestosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurProgramador", inversedBy="puestosProgramadorRel")
     * @ORM\JoinColumn(name="codigo_programador_fk", referencedColumnName="codigo_programador_pk")
     */
    protected $programadorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurZona", inversedBy="puestosZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurOperacion", inversedBy="puestosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    protected $operacionRel;                   
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\ContabilidadBundle\Entity\CtbCentroCosto", inversedBy="turPuestosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_contabilidad_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoContabilidadRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleConcepto", mappedBy="puestoRel")
     */
    protected $pedidosDetallesConceptosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="puestoRel")
     */
    protected $serviciosDetallesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleConcepto", mappedBy="puestoRel")
     */
    protected $serviciosDetallesConceptosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="puestoRel")
     */
    protected $programacionesDetallesPuestoRel;        

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionAlterna", mappedBy="puestoRel")
     */
    protected $programacionesAlternasPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="puestoRel")
     */
    protected $costosServiciosPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="puestoRel")
     */
    protected $puestosDotacionesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuestoAdicional", mappedBy="puestoRel")
     */
    protected $puestosAdicionalesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurSimulacionDetalle", mappedBy="puestoRel")
     */
    protected $simulacionesDetallesPuestoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="puestoRel")
     */
    protected $facturasDetallesPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="puestoRel")
     */
    protected $rhuEmpleadosPuestoRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurControlPuestoDetalle", mappedBy="puestoRel")
     */
    protected $controlesPuestosDetallesPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoDetalle", mappedBy="puestoRel")
     */
    protected $costosDetallesPuestoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesConceptosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesConceptosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesAlternasPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosServiciosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puestosDotacionesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puestosAdicionalesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->simulacionesDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuEmpleadosPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->controlesPuestosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPuestoPk
     *
     * @return integer
     */
    public function getCodigoPuestoPk()
    {
        return $this->codigoPuestoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPuesto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return TurPuesto
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TurPuesto
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return TurPuesto
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set numeroComunicacion
     *
     * @param string $numeroComunicacion
     *
     * @return TurPuesto
     */
    public function setNumeroComunicacion($numeroComunicacion)
    {
        $this->numeroComunicacion = $numeroComunicacion;

        return $this;
    }

    /**
     * Get numeroComunicacion
     *
     * @return string
     */
    public function getNumeroComunicacion()
    {
        return $this->numeroComunicacion;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return TurPuesto
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return TurPuesto
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return TurPuesto
     */
    public function setCelularContacto($celularContacto)
    {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * Set costoDotacion
     *
     * @param float $costoDotacion
     *
     * @return TurPuesto
     */
    public function setCostoDotacion($costoDotacion)
    {
        $this->costoDotacion = $costoDotacion;

        return $this;
    }

    /**
     * Get costoDotacion
     *
     * @return float
     */
    public function getCostoDotacion()
    {
        return $this->costoDotacion;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurPuesto
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
     * Set codigoProgramadorFk
     *
     * @param integer $codigoProgramadorFk
     *
     * @return TurPuesto
     */
    public function setCodigoProgramadorFk($codigoProgramadorFk)
    {
        $this->codigoProgramadorFk = $codigoProgramadorFk;

        return $this;
    }

    /**
     * Get codigoProgramadorFk
     *
     * @return integer
     */
    public function getCodigoProgramadorFk()
    {
        return $this->codigoProgramadorFk;
    }

    /**
     * Set codigoZonaFk
     *
     * @param integer $codigoZonaFk
     *
     * @return TurPuesto
     */
    public function setCodigoZonaFk($codigoZonaFk)
    {
        $this->codigoZonaFk = $codigoZonaFk;

        return $this;
    }

    /**
     * Get codigoZonaFk
     *
     * @return integer
     */
    public function getCodigoZonaFk()
    {
        return $this->codigoZonaFk;
    }

    /**
     * Set codigoOperacionFk
     *
     * @param integer $codigoOperacionFk
     *
     * @return TurPuesto
     */
    public function setCodigoOperacionFk($codigoOperacionFk)
    {
        $this->codigoOperacionFk = $codigoOperacionFk;

        return $this;
    }

    /**
     * Get codigoOperacionFk
     *
     * @return integer
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return TurPuesto
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPuesto
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return TurPuesto
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Set codigoCentroCostoContabilidadFk
     *
     * @param string $codigoCentroCostoContabilidadFk
     *
     * @return TurPuesto
     */
    public function setCodigoCentroCostoContabilidadFk($codigoCentroCostoContabilidadFk)
    {
        $this->codigoCentroCostoContabilidadFk = $codigoCentroCostoContabilidadFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoContabilidadFk
     *
     * @return string
     */
    public function getCodigoCentroCostoContabilidadFk()
    {
        return $this->codigoCentroCostoContabilidadFk;
    }

    /**
     * Set controlPuesto
     *
     * @param boolean $controlPuesto
     *
     * @return TurPuesto
     */
    public function setControlPuesto($controlPuesto)
    {
        $this->controlPuesto = $controlPuesto;

        return $this;
    }

    /**
     * Get controlPuesto
     *
     * @return boolean
     */
    public function getControlPuesto()
    {
        return $this->controlPuesto;
    }

    /**
     * Set edadMinima
     *
     * @param integer $edadMinima
     *
     * @return TurPuesto
     */
    public function setEdadMinima($edadMinima)
    {
        $this->edad_minima = $edadMinima;

        return $this;
    }

    /**
     * Get edadMinima
     *
     * @return integer
     */
    public function getEdadMinima()
    {
        return $this->edad_minima;
    }

    /**
     * Set edadMaxima
     *
     * @param integer $edadMaxima
     *
     * @return TurPuesto
     */
    public function setEdadMaxima($edadMaxima)
    {
        $this->edad_maxima = $edadMaxima;

        return $this;
    }

    /**
     * Get edadMaxima
     *
     * @return integer
     */
    public function getEdadMaxima()
    {
        return $this->edad_maxima;
    }

    /**
     * Set estaturaMinima
     *
     * @param integer $estaturaMinima
     *
     * @return TurPuesto
     */
    public function setEstaturaMinima($estaturaMinima)
    {
        $this->estaturaMinima = $estaturaMinima;

        return $this;
    }

    /**
     * Get estaturaMinima
     *
     * @return integer
     */
    public function getEstaturaMinima()
    {
        return $this->estaturaMinima;
    }

    /**
     * Set estaturaMaxima
     *
     * @param integer $estaturaMaxima
     *
     * @return TurPuesto
     */
    public function setEstaturaMaxima($estaturaMaxima)
    {
        $this->estaturaMaxima = $estaturaMaxima;

        return $this;
    }

    /**
     * Get estaturaMaxima
     *
     * @return integer
     */
    public function getEstaturaMaxima()
    {
        return $this->estaturaMaxima;
    }

    /**
     * Set pesoMinimo
     *
     * @param integer $pesoMinimo
     *
     * @return TurPuesto
     */
    public function setPesoMinimo($pesoMinimo)
    {
        $this->peso_minimo = $pesoMinimo;

        return $this;
    }

    /**
     * Get pesoMinimo
     *
     * @return integer
     */
    public function getPesoMinimo()
    {
        return $this->peso_minimo;
    }

    /**
     * Set pesoMaximo
     *
     * @param integer $pesoMaximo
     *
     * @return TurPuesto
     */
    public function setPesoMaximo($pesoMaximo)
    {
        $this->peso_maximo = $pesoMaximo;

        return $this;
    }

    /**
     * Get pesoMaximo
     *
     * @return integer
     */
    public function getPesoMaximo()
    {
        return $this->peso_maximo;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return TurPuesto
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set codigoTipoLibretaMilitar
     *
     * @param integer $codigoTipoLibretaMilitar
     *
     * @return TurPuesto
     */
    public function setCodigoTipoLibretaMilitar($codigoTipoLibretaMilitar)
    {
        $this->codigoTipoLibretaMilitar = $codigoTipoLibretaMilitar;

        return $this;
    }

    /**
     * Get codigoTipoLibretaMilitar
     *
     * @return integer
     */
    public function getCodigoTipoLibretaMilitar()
    {
        return $this->codigoTipoLibretaMilitar;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return TurPuesto
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return string
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set moto
     *
     * @param boolean $moto
     *
     * @return TurPuesto
     */
    public function setMoto($moto)
    {
        $this->moto = $moto;

        return $this;
    }

    /**
     * Get moto
     *
     * @return boolean
     */
    public function getMoto()
    {
        return $this->moto;
    }

    /**
     * Set carro
     *
     * @param boolean $carro
     *
     * @return TurPuesto
     */
    public function setCarro($carro)
    {
        $this->carro = $carro;

        return $this;
    }

    /**
     * Get carro
     *
     * @return boolean
     */
    public function getCarro()
    {
        return $this->carro;
    }

    /**
     * Set estadoCivilRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel
     *
     * @return TurPuesto
     */
    public function setEstadoCivilRel(\Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil $estadoCivilRel = null)
    {
        $this->estadoCivilRel = $estadoCivilRel;

        return $this;
    }

    /**
     * Get estadoCivilRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEstadoCivil
     */
    public function getEstadoCivilRel()
    {
        return $this->estadoCivilRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurPuesto
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return TurPuesto
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set programadorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramador $programadorRel
     *
     * @return TurPuesto
     */
    public function setProgramadorRel(\Brasa\TurnoBundle\Entity\TurProgramador $programadorRel = null)
    {
        $this->programadorRel = $programadorRel;

        return $this;
    }

    /**
     * Get programadorRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProgramador
     */
    public function getProgramadorRel()
    {
        return $this->programadorRel;
    }

    /**
     * Set zonaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurZona $zonaRel
     *
     * @return TurPuesto
     */
    public function setZonaRel(\Brasa\TurnoBundle\Entity\TurZona $zonaRel = null)
    {
        $this->zonaRel = $zonaRel;

        return $this;
    }

    /**
     * Get zonaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurZona
     */
    public function getZonaRel()
    {
        return $this->zonaRel;
    }

    /**
     * Set operacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurOperacion $operacionRel
     *
     * @return TurPuesto
     */
    public function setOperacionRel(\Brasa\TurnoBundle\Entity\TurOperacion $operacionRel = null)
    {
        $this->operacionRel = $operacionRel;

        return $this;
    }

    /**
     * Get operacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurOperacion
     */
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * Set centroCostoContabilidadRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoContabilidadRel
     *
     * @return TurPuesto
     */
    public function setCentroCostoContabilidadRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoContabilidadRel = null)
    {
        $this->centroCostoContabilidadRel = $centroCostoContabilidadRel;

        return $this;
    }

    /**
     * Get centroCostoContabilidadRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoContabilidadRel()
    {
        return $this->centroCostoContabilidadRel;
    }

    /**
     * Add pedidosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPedidosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel)
    {
        $this->pedidosDetallesPuestoRel[] = $pedidosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel
     */
    public function removePedidosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel)
    {
        $this->pedidosDetallesPuestoRel->removeElement($pedidosDetallesPuestoRel);
    }

    /**
     * Get pedidosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPuestoRel()
    {
        return $this->pedidosDetallesPuestoRel;
    }

    /**
     * Add pedidosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel
     *
     * @return TurPuesto
     */
    public function addPedidosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel)
    {
        $this->pedidosDetallesConceptosPuestoRel[] = $pedidosDetallesConceptosPuestoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel
     */
    public function removePedidosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosPuestoRel)
    {
        $this->pedidosDetallesConceptosPuestoRel->removeElement($pedidosDetallesConceptosPuestoRel);
    }

    /**
     * Get pedidosDetallesConceptosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesConceptosPuestoRel()
    {
        return $this->pedidosDetallesConceptosPuestoRel;
    }

    /**
     * Add serviciosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addServiciosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel)
    {
        $this->serviciosDetallesPuestoRel[] = $serviciosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel
     */
    public function removeServiciosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel)
    {
        $this->serviciosDetallesPuestoRel->removeElement($serviciosDetallesPuestoRel);
    }

    /**
     * Get serviciosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPuestoRel()
    {
        return $this->serviciosDetallesPuestoRel;
    }

    /**
     * Add serviciosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel
     *
     * @return TurPuesto
     */
    public function addServiciosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel)
    {
        $this->serviciosDetallesConceptosPuestoRel[] = $serviciosDetallesConceptosPuestoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesConceptosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel
     */
    public function removeServiciosDetallesConceptosPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosPuestoRel)
    {
        $this->serviciosDetallesConceptosPuestoRel->removeElement($serviciosDetallesConceptosPuestoRel);
    }

    /**
     * Get serviciosDetallesConceptosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesConceptosPuestoRel()
    {
        return $this->serviciosDetallesConceptosPuestoRel;
    }

    /**
     * Add programacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addProgramacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel)
    {
        $this->programacionesDetallesPuestoRel[] = $programacionesDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel
     */
    public function removeProgramacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel)
    {
        $this->programacionesDetallesPuestoRel->removeElement($programacionesDetallesPuestoRel);
    }

    /**
     * Get programacionesDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesPuestoRel()
    {
        return $this->programacionesDetallesPuestoRel;
    }

    /**
     * Add programacionesAlternasPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasPuestoRel
     *
     * @return TurPuesto
     */
    public function addProgramacionesAlternasPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasPuestoRel)
    {
        $this->programacionesAlternasPuestoRel[] = $programacionesAlternasPuestoRel;

        return $this;
    }

    /**
     * Remove programacionesAlternasPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasPuestoRel
     */
    public function removeProgramacionesAlternasPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionAlterna $programacionesAlternasPuestoRel)
    {
        $this->programacionesAlternasPuestoRel->removeElement($programacionesAlternasPuestoRel);
    }

    /**
     * Get programacionesAlternasPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesAlternasPuestoRel()
    {
        return $this->programacionesAlternasPuestoRel;
    }

    /**
     * Add costosServiciosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel
     *
     * @return TurPuesto
     */
    public function addCostosServiciosPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel)
    {
        $this->costosServiciosPuestoRel[] = $costosServiciosPuestoRel;

        return $this;
    }

    /**
     * Remove costosServiciosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel
     */
    public function removeCostosServiciosPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosPuestoRel)
    {
        $this->costosServiciosPuestoRel->removeElement($costosServiciosPuestoRel);
    }

    /**
     * Get costosServiciosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosPuestoRel()
    {
        return $this->costosServiciosPuestoRel;
    }

    /**
     * Add puestosDotacionesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPuestosDotacionesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel)
    {
        $this->puestosDotacionesPuestoRel[] = $puestosDotacionesPuestoRel;

        return $this;
    }

    /**
     * Remove puestosDotacionesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel
     */
    public function removePuestosDotacionesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel)
    {
        $this->puestosDotacionesPuestoRel->removeElement($puestosDotacionesPuestoRel);
    }

    /**
     * Get puestosDotacionesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosDotacionesPuestoRel()
    {
        return $this->puestosDotacionesPuestoRel;
    }

    /**
     * Add puestosAdicionalesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoAdicional $puestosAdicionalesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPuestosAdicionalesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoAdicional $puestosAdicionalesPuestoRel)
    {
        $this->puestosAdicionalesPuestoRel[] = $puestosAdicionalesPuestoRel;

        return $this;
    }

    /**
     * Remove puestosAdicionalesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoAdicional $puestosAdicionalesPuestoRel
     */
    public function removePuestosAdicionalesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoAdicional $puestosAdicionalesPuestoRel)
    {
        $this->puestosAdicionalesPuestoRel->removeElement($puestosAdicionalesPuestoRel);
    }

    /**
     * Get puestosAdicionalesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosAdicionalesPuestoRel()
    {
        return $this->puestosAdicionalesPuestoRel;
    }

    /**
     * Add simulacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addSimulacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel)
    {
        $this->simulacionesDetallesPuestoRel[] = $simulacionesDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove simulacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel
     */
    public function removeSimulacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurSimulacionDetalle $simulacionesDetallesPuestoRel)
    {
        $this->simulacionesDetallesPuestoRel->removeElement($simulacionesDetallesPuestoRel);
    }

    /**
     * Get simulacionesDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimulacionesDetallesPuestoRel()
    {
        return $this->simulacionesDetallesPuestoRel;
    }

    /**
     * Add facturasDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addFacturasDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel)
    {
        $this->facturasDetallesPuestoRel[] = $facturasDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel
     */
    public function removeFacturasDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesPuestoRel)
    {
        $this->facturasDetallesPuestoRel->removeElement($facturasDetallesPuestoRel);
    }

    /**
     * Get facturasDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesPuestoRel()
    {
        return $this->facturasDetallesPuestoRel;
    }

    /**
     * Add rhuEmpleadosPuestoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel
     *
     * @return TurPuesto
     */
    public function addRhuEmpleadosPuestoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel)
    {
        $this->rhuEmpleadosPuestoRel[] = $rhuEmpleadosPuestoRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosPuestoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel
     */
    public function removeRhuEmpleadosPuestoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPuestoRel)
    {
        $this->rhuEmpleadosPuestoRel->removeElement($rhuEmpleadosPuestoRel);
    }

    /**
     * Get rhuEmpleadosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosPuestoRel()
    {
        return $this->rhuEmpleadosPuestoRel;
    }

    /**
     * Add controlesPuestosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addControlesPuestosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesPuestoRel)
    {
        $this->controlesPuestosDetallesPuestoRel[] = $controlesPuestosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove controlesPuestosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesPuestoRel
     */
    public function removeControlesPuestosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesPuestoRel)
    {
        $this->controlesPuestosDetallesPuestoRel->removeElement($controlesPuestosDetallesPuestoRel);
    }

    /**
     * Get controlesPuestosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getControlesPuestosDetallesPuestoRel()
    {
        return $this->controlesPuestosDetallesPuestoRel;
    }

    /**
     * Add costosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoDetalle $costosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addCostosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoDetalle $costosDetallesPuestoRel)
    {
        $this->costosDetallesPuestoRel[] = $costosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove costosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoDetalle $costosDetallesPuestoRel
     */
    public function removeCostosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurCostoDetalle $costosDetallesPuestoRel)
    {
        $this->costosDetallesPuestoRel->removeElement($costosDetallesPuestoRel);
    }

    /**
     * Get costosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosDetallesPuestoRel()
    {
        return $this->costosDetallesPuestoRel;
    }
}
