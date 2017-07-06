<?php

namespace Brasa\ContabilidadBundle\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_sucursal")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbSucursalRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoSucursalPk"},message="Ya existe el código de la sucursal")
 */

class CtbSucursal
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="string", length=20)
     */    
    private $codigoSucursalPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="sucursalRel")
     */
    protected $rhuEmpleadosSucursalRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CtbTercero", mappedBy="sucursalRel")
     */
    protected $ctbTercerosSucursalRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuEmpleadosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ctbTercerosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoSucursalPk
     *
     * @param string $codigoSucursalPk
     *
     * @return CtbSucursal
     */
    public function setCodigoSucursalPk($codigoSucursalPk)
    {
        $this->codigoSucursalPk = $codigoSucursalPk;

        return $this;
    }

    /**
     * Get codigoSucursalPk
     *
     * @return string
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbSucursal
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
     * Add rhuEmpleadosSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosSucursalRel
     *
     * @return CtbSucursal
     */
    public function addRhuEmpleadosSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosSucursalRel)
    {
        $this->rhuEmpleadosSucursalRel[] = $rhuEmpleadosSucursalRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosSucursalRel
     */
    public function removeRhuEmpleadosSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosSucursalRel)
    {
        $this->rhuEmpleadosSucursalRel->removeElement($rhuEmpleadosSucursalRel);
    }

    /**
     * Get rhuEmpleadosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosSucursalRel()
    {
        return $this->rhuEmpleadosSucursalRel;
    }

    /**
     * Add ctbTercerosSucursalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $ctbTercerosSucursalRel
     *
     * @return CtbSucursal
     */
    public function addCtbTercerosSucursalRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $ctbTercerosSucursalRel)
    {
        $this->ctbTercerosSucursalRel[] = $ctbTercerosSucursalRel;

        return $this;
    }

    /**
     * Remove ctbTercerosSucursalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $ctbTercerosSucursalRel
     */
    public function removeCtbTercerosSucursalRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $ctbTercerosSucursalRel)
    {
        $this->ctbTercerosSucursalRel->removeElement($ctbTercerosSucursalRel);
    }

    /**
     * Get ctbTercerosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCtbTercerosSucursalRel()
    {
        return $this->ctbTercerosSucursalRel;
    }
}
