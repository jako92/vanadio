<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RhuInduccion
 *
 * @ORM\Table(name="rhu_induccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuInduccionRepository")
 */
class RhuInduccion {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_induccion_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoInduccionPk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_desde", type="date")
     */
    private $fechaDesde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hasta", type="date")
     */
    private $fechaHasta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="induccionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;


    /**
     * Get codigoInduccionPk
     *
     * @return integer
     */
    public function getCodigoInduccionPk()
    {
        return $this->codigoInduccionPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuInduccion
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuInduccion
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
     * @return RhuInduccion
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuInduccion
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuInduccion
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
     * @return RhuInduccion
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
}
