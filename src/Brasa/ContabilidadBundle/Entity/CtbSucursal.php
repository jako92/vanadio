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
     * @ORM\Column(name="codigo_sucursal_fk", type="string", length=20, nullable=true)
     */
    private $codigoSucursalFk;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="sucursalRel")
     */
    protected $rhuEmpleadosSucursalRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CtbTercero", mappedBy="sucursalRel")
     */
    protected $tercerosSucursalRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CtbRegistro", mappedBy="sucursalRel")
     */
    protected $registrosSucursalRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuEmpleadosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tercerosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registrosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add tercerosSucursalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $tercerosSucursalRel
     *
     * @return CtbSucursal
     */
    public function addTercerosSucursalRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $tercerosSucursalRel)
    {
        $this->tercerosSucursalRel[] = $tercerosSucursalRel;

        return $this;
    }

    /**
     * Remove tercerosSucursalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $tercerosSucursalRel
     */
    public function removeTercerosSucursalRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $tercerosSucursalRel)
    {
        $this->tercerosSucursalRel->removeElement($tercerosSucursalRel);
    }

    /**
     * Get tercerosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTercerosSucursalRel()
    {
        return $this->tercerosSucursalRel;
    }

    /**
     * Add registrosSucursalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosSucursalRel
     *
     * @return CtbSucursal
     */
    public function addRegistrosSucursalRel(\Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosSucursalRel)
    {
        $this->registrosSucursalRel[] = $registrosSucursalRel;

        return $this;
    }

    /**
     * Remove registrosSucursalRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosSucursalRel
     */
    public function removeRegistrosSucursalRel(\Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosSucursalRel)
    {
        $this->registrosSucursalRel->removeElement($registrosSucursalRel);
    }

    /**
     * Get registrosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosSucursalRel()
    {
        return $this->registrosSucursalRel;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param string $codigoSucursalFk
     *
     * @return CtbSucursal
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return string
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }
}
