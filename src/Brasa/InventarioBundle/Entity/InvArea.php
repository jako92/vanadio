<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_area")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvAreaRepository")
 */
class InvArea
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_area_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAreaPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="areaRel")
     */
    protected $movimientosAreaRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosAreaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAreaPk
     *
     * @return integer
     */
    public function getCodigoAreaPk()
    {
        return $this->codigoAreaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvArea
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
     * Add movimientosAreaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosAreaRel
     *
     * @return InvArea
     */
    public function addMovimientosAreaRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosAreaRel)
    {
        $this->movimientosAreaRel[] = $movimientosAreaRel;

        return $this;
    }

    /**
     * Remove movimientosAreaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosAreaRel
     */
    public function removeMovimientosAreaRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosAreaRel)
    {
        $this->movimientosAreaRel->removeElement($movimientosAreaRel);
    }

    /**
     * Get movimientosAreaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosAreaRel()
    {
        return $this->movimientosAreaRel;
    }
}
