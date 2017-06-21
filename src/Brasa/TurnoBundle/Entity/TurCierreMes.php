<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cierre_mes")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCierreMesRepository")
 */
class TurCierreMes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesPk;             
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;               
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false;     

    /**
     * @ORM\Column(name="fecha_generado", type="datetime", nullable=true)
     */    
    private $fechaGenerado;    

    /**
     * @ORM\Column(name="fecha_cerrado", type="datetime", nullable=true)
     */    
    private $fechaCerrado;    
    
    /**     
     * @ORM\Column(name="estado_generado_comercial", type="boolean")
     */    
    private $estadoGeneradoComercial = false;
    
    /**
     * @ORM\Column(name="fecha_generado_comercial", type="datetime", nullable=true)
     */    
    private $fechaGeneradoComercial;    
    
    /**     
     * @ORM\Column(name="estado_cerrado_comercial", type="boolean")
     */    
    private $estadoCerradoComercial = false;    
    
    /**
     * @ORM\Column(name="fecha_cerrado_comercial", type="datetime", nullable=true)
     */    
    private $fechaCerradoComercial;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $costosServiciosCierreMesRel;         

    /**
     * @ORM\OneToMany(targetEntity="TurCosto", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $costosCierreMesRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->costosServiciosCierreMesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->costosCierreMesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCierreMesPk
     *
     * @return integer
     */
    public function getCodigoCierreMesPk()
    {
        return $this->codigoCierreMesPk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCierreMes
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return TurCierreMes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurCierreMes
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
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return TurCierreMes
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
     * Set fechaGenerado
     *
     * @param \DateTime $fechaGenerado
     *
     * @return TurCierreMes
     */
    public function setFechaGenerado($fechaGenerado)
    {
        $this->fechaGenerado = $fechaGenerado;

        return $this;
    }

    /**
     * Get fechaGenerado
     *
     * @return \DateTime
     */
    public function getFechaGenerado()
    {
        return $this->fechaGenerado;
    }

    /**
     * Set estadoGeneradoComercial
     *
     * @param boolean $estadoGeneradoComercial
     *
     * @return TurCierreMes
     */
    public function setEstadoGeneradoComercial($estadoGeneradoComercial)
    {
        $this->estadoGeneradoComercial = $estadoGeneradoComercial;

        return $this;
    }

    /**
     * Get estadoGeneradoComercial
     *
     * @return boolean
     */
    public function getEstadoGeneradoComercial()
    {
        return $this->estadoGeneradoComercial;
    }

    /**
     * Set fechaGeneradoComercial
     *
     * @param \DateTime $fechaGeneradoComercial
     *
     * @return TurCierreMes
     */
    public function setFechaGeneradoComercial($fechaGeneradoComercial)
    {
        $this->fechaGeneradoComercial = $fechaGeneradoComercial;

        return $this;
    }

    /**
     * Get fechaGeneradoComercial
     *
     * @return \DateTime
     */
    public function getFechaGeneradoComercial()
    {
        return $this->fechaGeneradoComercial;
    }

    /**
     * Add costosServiciosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel
     *
     * @return TurCierreMes
     */
    public function addCostosServiciosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel)
    {
        $this->costosServiciosCierreMesRel[] = $costosServiciosCierreMesRel;

        return $this;
    }

    /**
     * Remove costosServiciosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel
     */
    public function removeCostosServiciosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCostoServicio $costosServiciosCierreMesRel)
    {
        $this->costosServiciosCierreMesRel->removeElement($costosServiciosCierreMesRel);
    }

    /**
     * Get costosServiciosCierreMesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosServiciosCierreMesRel()
    {
        return $this->costosServiciosCierreMesRel;
    }

    /**
     * Add costosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCosto $costosCierreMesRel
     *
     * @return TurCierreMes
     */
    public function addCostosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCosto $costosCierreMesRel)
    {
        $this->costosCierreMesRel[] = $costosCierreMesRel;

        return $this;
    }

    /**
     * Remove costosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCosto $costosCierreMesRel
     */
    public function removeCostosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCosto $costosCierreMesRel)
    {
        $this->costosCierreMesRel->removeElement($costosCierreMesRel);
    }

    /**
     * Get costosCierreMesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCostosCierreMesRel()
    {
        return $this->costosCierreMesRel;
    }

    /**
     * Set estadoCerradoComercial
     *
     * @param boolean $estadoCerradoComercial
     *
     * @return TurCierreMes
     */
    public function setEstadoCerradoComercial($estadoCerradoComercial)
    {
        $this->estadoCerradoComercial = $estadoCerradoComercial;

        return $this;
    }

    /**
     * Get estadoCerradoComercial
     *
     * @return boolean
     */
    public function getEstadoCerradoComercial()
    {
        return $this->estadoCerradoComercial;
    }

    /**
     * Set fechaCerrado
     *
     * @param \DateTime $fechaCerrado
     *
     * @return TurCierreMes
     */
    public function setFechaCerrado($fechaCerrado)
    {
        $this->fechaCerrado = $fechaCerrado;

        return $this;
    }

    /**
     * Get fechaCerrado
     *
     * @return \DateTime
     */
    public function getFechaCerrado()
    {
        return $this->fechaCerrado;
    }

    /**
     * Set fechaCerradoComercial
     *
     * @param \DateTime $fechaCerradoComercial
     *
     * @return TurCierreMes
     */
    public function setFechaCerradoComercial($fechaCerradoComercial)
    {
        $this->fechaCerradoComercial = $fechaCerradoComercial;

        return $this;
    }

    /**
     * Get fechaCerradoComercial
     *
     * @return \DateTime
     */
    public function getFechaCerradoComercial()
    {
        return $this->fechaCerradoComercial;
    }
}
