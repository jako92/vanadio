<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_prueba")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPruebaRepository")
 */
class RhuPrueba
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prueba_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPruebaPk;            
    
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
     * @ORM\Column(name="codigo_prueba_tipo_fk", type="integer")
     */    
    private $codigoPruebaTipoFk;
    
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
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer", nullable=true)
     */    
    private $codigoTipoIdentificacionFk;
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50, nullable=true)
     */    
    private $nombreCorto;
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=50, nullable=true)
     */    
    private $numeroIdentificacion;
    /**
     * @ORM\Column(name="resultado", type="string", length=50, nullable=true)
     */    
    private $resultado;
    /**
     * @ORM\Column(name="tipo_persona", type="string", length=50, nullable=true)
     */    
    private $tipoPersona;
    
    /**
     * @ORM\Column(name="resultado_cuantitativo", type="integer", nullable=true)
     */    
    private $resultadoCuantitativo;
    
    /**
     * @ORM\Column(name="nombre_quien_hace_prueba", type="string", length=100, nullable=true)
     */    
    private $nombreQuienHacePrueba;     
    
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
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pruebasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;                        
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPruebaTipo", inversedBy="pruebasPruebaTipoRel")
     * @ORM\JoinColumn(name="codigo_prueba_tipo_fk", referencedColumnName="codigo_prueba_tipo_pk")
     */
    protected $pruebaTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="pruebasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="pruebasCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCobro", inversedBy="pruebasCobroRel")
     * @ORM\JoinColumn(name="codigo_cobro_fk", referencedColumnName="codigo_cobro_pk")
     */
    protected $cobroRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="rhupruebasIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;

    /**
     * Get codigoPruebaPk
     *
     * @return integer
     */
    public function getCodigoPruebaPk()
    {
        return $this->codigoPruebaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * Set codigoPruebaTipoFk
     *
     * @param integer $codigoPruebaTipoFk
     *
     * @return RhuPrueba
     */
    public function setCodigoPruebaTipoFk($codigoPruebaTipoFk)
    {
        $this->codigoPruebaTipoFk = $codigoPruebaTipoFk;

        return $this;
    }

    /**
     * Get codigoPruebaTipoFk
     *
     * @return integer
     */
    public function getCodigoPruebaTipoFk()
    {
        return $this->codigoPruebaTipoFk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * Set codigoTipoIdentificacionFk
     *
     * @param integer $codigoTipoIdentificacionFk
     *
     * @return RhuPrueba
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuPrueba
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * Set resultado
     *
     * @param string $resultado
     *
     * @return RhuPrueba
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set tipoPersona
     *
     * @param string $tipoPersona
     *
     * @return RhuPrueba
     */
    public function setTipoPersona($tipoPersona)
    {
        $this->tipoPersona = $tipoPersona;

        return $this;
    }

    /**
     * Get tipoPersona
     *
     * @return string
     */
    public function getTipoPersona()
    {
        return $this->tipoPersona;
    }

    /**
     * Set resultadoCuantitativo
     *
     * @param integer $resultadoCuantitativo
     *
     * @return RhuPrueba
     */
    public function setResultadoCuantitativo($resultadoCuantitativo)
    {
        $this->resultadoCuantitativo = $resultadoCuantitativo;

        return $this;
    }

    /**
     * Get resultadoCuantitativo
     *
     * @return integer
     */
    public function getResultadoCuantitativo()
    {
        return $this->resultadoCuantitativo;
    }

    /**
     * Set nombreQuienHacePrueba
     *
     * @param string $nombreQuienHacePrueba
     *
     * @return RhuPrueba
     */
    public function setNombreQuienHacePrueba($nombreQuienHacePrueba)
    {
        $this->nombreQuienHacePrueba = $nombreQuienHacePrueba;

        return $this;
    }

    /**
     * Get nombreQuienHacePrueba
     *
     * @return string
     */
    public function getNombreQuienHacePrueba()
    {
        return $this->nombreQuienHacePrueba;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuPrueba
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuPrueba
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
     * Set pruebaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo $pruebaTipoRel
     *
     * @return RhuPrueba
     */
    public function setPruebaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo $pruebaTipoRel = null)
    {
        $this->pruebaTipoRel = $pruebaTipoRel;

        return $this;
    }

    /**
     * Get pruebaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo
     */
    public function getPruebaTipoRel()
    {
        return $this->pruebaTipoRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * @return RhuPrueba
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
     * @return RhuPrueba
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
