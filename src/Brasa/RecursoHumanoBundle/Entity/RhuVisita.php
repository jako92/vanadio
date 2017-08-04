<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_visita")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVisitaRepository")
 */
class RhuVisita
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitaPk;            
    
    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer", nullable=true)
     */    
    private $codigoTipoIdentificacionFk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;                 
    
    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */    
    private $fechaCreacion;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_visita_tipo_fk", type="integer")
     */    
    private $codigoVisitaTipoFk;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk; 
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_cobro_fk", type="integer", nullable=true)
     */    
    private $codigoCobroFk;
    
    /**
     * @ORM\Column(name="fecha_vence", type="date")
     */    
    private $fechaVence;    
    
    /**     
     * @ORM\Column(name="validar_vencimiento", type="boolean")
     */    
    private $validarVencimiento = false;
    
    /**
     * @ORM\Column(name="nombre_quien_visita", type="string", length=100, nullable=true)
     */    
    private $nombreQuienVisita;
    
    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */    
    private $comentarios;    
    /**
     * @ORM\Column(name="nombre_corto", type="text", nullable=true)
     */    
    private $nombreCorto;    
    /**
     * @ORM\Column(name="numero_identificacion", type="text", nullable=true)
     */    
    private $numeroIdentificacion;    
       
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;
    
    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */
    private $estadoCobrado = 0;
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;
        
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="visitasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;                        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuVisitaTipo", inversedBy="visitasVisitaTipoRel")
     * @ORM\JoinColumn(name="codigo_visita_tipo_fk", referencedColumnName="codigo_visita_tipo_pk")
     */
    protected $visitaTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="visitasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="visitasCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCobro", inversedBy="visitasCobroRel")
     * @ORM\JoinColumn(name="codigo_cobro_fk", referencedColumnName="codigo_cobro_pk")
     */
    protected $cobroRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="rhuvisitasIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;

    /**
     * Get codigoVisitaPk
     *
     * @return integer
     */
    public function getCodigoVisitaPk()
    {
        return $this->codigoVisitaPk;
    }

    /**
     * Set codigoTipoIdentificacionFk
     *
     * @param integer $codigoTipoIdentificacionFk
     *
     * @return RhuVisita
     */
    public function setCodigoTipoIdentificacionFk($codigoTipoIdentificacionFk)
    {
        $this->codigoTipoIdentificacionFk = $codigoTipoIdentificacionFk;

        return $this;
    }

    /**
     * Get codigoTipoIdentificacionFk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionFk()
    {
        return $this->codigoTipoIdentificacionFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuVisita
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return RhuVisita
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuVisita
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
     * Set codigoVisitaTipoFk
     *
     * @param integer $codigoVisitaTipoFk
     *
     * @return RhuVisita
     */
    public function setCodigoVisitaTipoFk($codigoVisitaTipoFk)
    {
        $this->codigoVisitaTipoFk = $codigoVisitaTipoFk;

        return $this;
    }

    /**
     * Get codigoVisitaTipoFk
     *
     * @return integer
     */
    public function getCodigoVisitaTipoFk()
    {
        return $this->codigoVisitaTipoFk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuVisita
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
     * @return RhuVisita
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
     * Set codigoCobroFk
     *
     * @param integer $codigoCobroFk
     *
     * @return RhuVisita
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return RhuVisita
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set validarVencimiento
     *
     * @param boolean $validarVencimiento
     *
     * @return RhuVisita
     */
    public function setValidarVencimiento($validarVencimiento)
    {
        $this->validarVencimiento = $validarVencimiento;

        return $this;
    }

    /**
     * Get validarVencimiento
     *
     * @return boolean
     */
    public function getValidarVencimiento()
    {
        return $this->validarVencimiento;
    }

    /**
     * Set nombreQuienVisita
     *
     * @param string $nombreQuienVisita
     *
     * @return RhuVisita
     */
    public function setNombreQuienVisita($nombreQuienVisita)
    {
        $this->nombreQuienVisita = $nombreQuienVisita;

        return $this;
    }

    /**
     * Get nombreQuienVisita
     *
     * @return string
     */
    public function getNombreQuienVisita()
    {
        return $this->nombreQuienVisita;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuVisita
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuVisita
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuVisita
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuVisita
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuVisita
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuVisita
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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuVisita
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuVisita
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuVisita
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
     * Set visitaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisitaTipo $visitaTipoRel
     *
     * @return RhuVisita
     */
    public function setVisitaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisitaTipo $visitaTipoRel = null)
    {
        $this->visitaTipoRel = $visitaTipoRel;

        return $this;
    }

    /**
     * Get visitaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuVisitaTipo
     */
    public function getVisitaTipoRel()
    {
        return $this->visitaTipoRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuVisita
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
     * @return RhuVisita
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
     * Set cobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobroRel
     *
     * @return RhuVisita
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
     * Set tipoIdentificacionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel
     *
     * @return RhuVisita
     */
    public function setTipoIdentificacionRel(\Brasa\GeneralBundle\Entity\GenTipoIdentificacion $tipoIdentificacionRel = null)
    {
        $this->tipoIdentificacionRel = $tipoIdentificacionRel;

        return $this;
    }

    /**
     * Get tipoIdentificacionRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTipoIdentificacion
     */
    public function getTipoIdentificacionRel()
    {
        return $this->tipoIdentificacionRel;
    }
}
