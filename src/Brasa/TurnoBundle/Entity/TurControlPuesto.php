<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_control_puesto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurControlPuestoRepository")
 */
class TurControlPuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_control_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoControlPuestoPk;                   
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false;     
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;  
    
      /**
     * @ORM\Column(name="novedad", type="string", length=500, nullable=true)
     */    
    private $novedad;   
    
    /**
     * @ORM\OneToMany(targetEntity="TurControlPuestoDetalle", mappedBy="controlPuestoRel", cascade={"persist", "remove"})
     */
    protected $controlesPuestosDetallesControlPuestoRel;     
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->controlesPuestosDetallesControlPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoControlPuestoPk
     *
     * @return integer
     */
    public function getCodigoControlPuestoPk()
    {
        return $this->codigoControlPuestoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurControlPuesto
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurControlPuesto
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return TurControlPuesto
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurControlPuesto
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurControlPuesto
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
     * Set novedad
     *
     * @param string $novedad
     *
     * @return TurControlPuesto
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return string
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Add controlesPuestosDetallesControlPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesControlPuestoRel
     *
     * @return TurControlPuesto
     */
    public function addControlesPuestosDetallesControlPuestoRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesControlPuestoRel)
    {
        $this->controlesPuestosDetallesControlPuestoRel[] = $controlesPuestosDetallesControlPuestoRel;

        return $this;
    }

    /**
     * Remove controlesPuestosDetallesControlPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesControlPuestoRel
     */
    public function removeControlesPuestosDetallesControlPuestoRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesControlPuestoRel)
    {
        $this->controlesPuestosDetallesControlPuestoRel->removeElement($controlesPuestosDetallesControlPuestoRel);
    }

    /**
     * Get controlesPuestosDetallesControlPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getControlesPuestosDetallesControlPuestoRel()
    {
        return $this->controlesPuestosDetallesControlPuestoRel;
    }
}
