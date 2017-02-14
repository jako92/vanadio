<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_centro_costo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCentroCostoRepository")
 */
class CtbCentroCosto
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk", type="string", length=20)
     */    
    private $codigoCentroCostoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;      
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;                 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="centroCostoContabilidadRel")
     */
    protected $rhuEmpleadosCentroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurPuesto", mappedBy="centroCostoContabilidadRel")
     */
    protected $turPuestosCentroCostoRel;         

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCosto", mappedBy="centroCostoRel")
     */
    protected $turCostosCentroCostoRel;     

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCostoDetalle", mappedBy="centroCostoRel")
     */
    protected $turCostosDetallesCentroCostoRel; 
    
    /**
     * Set codigoCentroCostoPk
     *
     * @param string $codigoCentroCostoPk
     *
     * @return CtbCentroCosto
     */
    public function setCodigoCentroCostoPk($codigoCentroCostoPk)
    {
        $this->codigoCentroCostoPk = $codigoCentroCostoPk;

        return $this;
    }

    /**
     * Get codigoCentroCostoPk
     *
     * @return string
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbCentroCosto
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return CtbCentroCosto
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
     * Constructor
     */
    public function __construct()
    {
        $this->rhuEmpleadosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->turPuestosCentroCostoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add rhuEmpleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addRhuEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel)
    {
        $this->rhuEmpleadosCentroCostoRel[] = $rhuEmpleadosCentroCostoRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosCentroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel
     */
    public function removeRhuEmpleadosCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCentroCostoRel)
    {
        $this->rhuEmpleadosCentroCostoRel->removeElement($rhuEmpleadosCentroCostoRel);
    }

    /**
     * Get rhuEmpleadosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosCentroCostoRel()
    {
        return $this->rhuEmpleadosCentroCostoRel;
    }

    /**
     * Add turPuestosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addTurPuestosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel)
    {
        $this->turPuestosCentroCostoRel[] = $turPuestosCentroCostoRel;

        return $this;
    }

    /**
     * Remove turPuestosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel
     */
    public function removeTurPuestosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurPuesto $turPuestosCentroCostoRel)
    {
        $this->turPuestosCentroCostoRel->removeElement($turPuestosCentroCostoRel);
    }

    /**
     * Get turPuestosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurPuestosCentroCostoRel()
    {
        return $this->turPuestosCentroCostoRel;
    }

    /**
     * Add turCostosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCosto $turCostosCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addTurCostosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCosto $turCostosCentroCostoRel)
    {
        $this->turCostosCentroCostoRel[] = $turCostosCentroCostoRel;

        return $this;
    }

    /**
     * Remove turCostosCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCosto $turCostosCentroCostoRel
     */
    public function removeTurCostosCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCosto $turCostosCentroCostoRel)
    {
        $this->turCostosCentroCostoRel->removeElement($turCostosCentroCostoRel);
    }

    /**
     * Get turCostosCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurCostosCentroCostoRel()
    {
        return $this->turCostosCentroCostoRel;
    }

    /**
     * Add turCostosDetallesCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoDetalle $turCostosDetallesCentroCostoRel
     *
     * @return CtbCentroCosto
     */
    public function addTurCostosDetallesCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCostoDetalle $turCostosDetallesCentroCostoRel)
    {
        $this->turCostosDetallesCentroCostoRel[] = $turCostosDetallesCentroCostoRel;

        return $this;
    }

    /**
     * Remove turCostosDetallesCentroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCostoDetalle $turCostosDetallesCentroCostoRel
     */
    public function removeTurCostosDetallesCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCostoDetalle $turCostosDetallesCentroCostoRel)
    {
        $this->turCostosDetallesCentroCostoRel->removeElement($turCostosDetallesCentroCostoRel);
    }

    /**
     * Get turCostosDetallesCentroCostoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurCostosDetallesCentroCostoRel()
    {
        return $this->turCostosDetallesCentroCostoRel;
    }
}
