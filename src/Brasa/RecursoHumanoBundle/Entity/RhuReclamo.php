<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_reclamo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuReclamoRepository")
 */
class RhuReclamo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_reclamo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoReclamoPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;              
    
    /**
     * @ORM\Column(name="codigo_reclamo_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoReclamoConceptoFk;     
    
    /**
     * @ORM\Column(name="fecha_registro", type="date", nullable=true)
     */         
    private $fechaRegistro;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */         
    private $fecha;    

    /**
     * @ORM\Column(name="fecha_cierre", type="date", nullable=true)
     */         
    private $fechaCierre; 
    
    /**
     * @ORM\Column(name="fecha_solucion", type="date", nullable=true)
     */         
    private $fechaSolucion;    
    
    /**
     * @ORM\Column(name="reclamo", type="string", length=2000, nullable=true)
     */    
    private $reclamo;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=2000, nullable=true)
     */    
    private $comentarios;  
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**
     * @ORM\Column(name="responsable", type="string", length=100, nullable=true)
     */    
    private $responsable; 
    
    /**
     * @ORM\Column(name="puesto", type="string", length=100, nullable=true)
     */    
    private $puesto;     
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="reclamosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuReclamoConcepto", inversedBy="reclamosReclamoConceptoRel")
     * @ORM\JoinColumn(name="codigo_reclamo_concepto_fk", referencedColumnName="codigo_reclamo_concepto_pk")
     */
    protected $reclamoConceptoRel;     



    /**
     * Get codigoReclamoPk
     *
     * @return integer
     */
    public function getCodigoReclamoPk()
    {
        return $this->codigoReclamoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuReclamo
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
     * Set codigoReclamoConceptoFk
     *
     * @param integer $codigoReclamoConceptoFk
     *
     * @return RhuReclamo
     */
    public function setCodigoReclamoConceptoFk($codigoReclamoConceptoFk)
    {
        $this->codigoReclamoConceptoFk = $codigoReclamoConceptoFk;

        return $this;
    }

    /**
     * Get codigoReclamoConceptoFk
     *
     * @return integer
     */
    public function getCodigoReclamoConceptoFk()
    {
        return $this->codigoReclamoConceptoFk;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return RhuReclamo
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuReclamo
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
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     *
     * @return RhuReclamo
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set fechaSolucion
     *
     * @param \DateTime $fechaSolucion
     *
     * @return RhuReclamo
     */
    public function setFechaSolucion($fechaSolucion)
    {
        $this->fechaSolucion = $fechaSolucion;

        return $this;
    }

    /**
     * Get fechaSolucion
     *
     * @return \DateTime
     */
    public function getFechaSolucion()
    {
        return $this->fechaSolucion;
    }

    /**
     * Set reclamo
     *
     * @param string $reclamo
     *
     * @return RhuReclamo
     */
    public function setReclamo($reclamo)
    {
        $this->reclamo = $reclamo;

        return $this;
    }

    /**
     * Get reclamo
     *
     * @return string
     */
    public function getReclamo()
    {
        return $this->reclamo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuReclamo
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuReclamo
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuReclamo
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
     * @return RhuReclamo
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
     * Set reclamoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuReclamoConcepto $reclamoConceptoRel
     *
     * @return RhuReclamo
     */
    public function setReclamoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuReclamoConcepto $reclamoConceptoRel = null)
    {
        $this->reclamoConceptoRel = $reclamoConceptoRel;

        return $this;
    }

    /**
     * Get reclamoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuReclamoConcepto
     */
    public function getReclamoConceptoRel()
    {
        return $this->reclamoConceptoRel;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     *
     * @return RhuReclamo
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set puesto
     *
     * @param string $puesto
     *
     * @return RhuReclamo
     */
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Get puesto
     *
     * @return string
     */
    public function getPuesto()
    {
        return $this->puesto;
    }
}
