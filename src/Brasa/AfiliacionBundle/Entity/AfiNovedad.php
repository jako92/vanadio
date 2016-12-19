<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_novedad")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiNovedadRepository")
 */
class AfiNovedad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;    
       
    /**
     * @ORM\Column(name="codigo_novedad_tipo_fk", type="integer")
     */    
    private $codigoNovedadTipoFk;     
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;        

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;            
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;    

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContatoFk;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0; 
    
    

    /**
     * Get codigoNovedadPk
     *
     * @return integer
     */
    public function getCodigoNovedadPk()
    {
        return $this->codigoNovedadPk;
    }

    /**
     * Set codigoNovedadTipoFk
     *
     * @param integer $codigoNovedadTipoFk
     *
     * @return AfiNovedad
     */
    public function setCodigoNovedadTipoFk($codigoNovedadTipoFk)
    {
        $this->codigoNovedadTipoFk = $codigoNovedadTipoFk;

        return $this;
    }

    /**
     * Get codigoNovedadTipoFk
     *
     * @return integer
     */
    public function getCodigoNovedadTipoFk()
    {
        return $this->codigoNovedadTipoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiNovedad
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
     * @return AfiNovedad
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return AfiNovedad
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
     * Set codigoContatoFk
     *
     * @param integer $codigoContatoFk
     *
     * @return AfiNovedad
     */
    public function setCodigoContatoFk($codigoContatoFk)
    {
        $this->codigoContatoFk = $codigoContatoFk;

        return $this;
    }

    /**
     * Get codigoContatoFk
     *
     * @return integer
     */
    public function getCodigoContatoFk()
    {
        return $this->codigoContatoFk;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return AfiNovedad
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }
}
