<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenRegion
 *
 * @ORM\Table(name="gen_region")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenRegionRepository")
 */
class GenRegion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_region_pk", type="integer")
     */
    private $codigoRegionPk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_pais_fk", type="integer")
     */
    private $codigoPaisFk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_interface", type="integer")
     */
    private $codigoInterface;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenPais", inversedBy="regionesRel")
     * @ORM\JoinColumn(name="codigo_pais_fk", referencedColumnName="codigo_pais_pk")
     */
    protected $paisRel;
    
    /**
     * @ORM\OneToMany(targetEntity="GenDepartamento", mappedBy="regionRel")
     */
    protected $departamentosRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departamentosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoRegionPk
     *
     * @param integer $codigoRegionPk
     *
     * @return GenRegion
     */
    public function setCodigoRegionPk($codigoRegionPk)
    {
        $this->codigoRegionPk = $codigoRegionPk;

        return $this;
    }

    /**
     * Get codigoRegionPk
     *
     * @return integer
     */
    public function getCodigoRegionPk()
    {
        return $this->codigoRegionPk;
    }

    /**
     * Set codigoPaisFk
     *
     * @param integer $codigoPaisFk
     *
     * @return GenRegion
     */
    public function setCodigoPaisFk($codigoPaisFk)
    {
        $this->codigoPaisFk = $codigoPaisFk;

        return $this;
    }

    /**
     * Get codigoPaisFk
     *
     * @return integer
     */
    public function getCodigoPaisFk()
    {
        return $this->codigoPaisFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenRegion
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
     * @param integer $codigoInterface
     *
     * @return GenRegion
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return integer
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Set paisRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenPais $paisRel
     *
     * @return GenRegion
     */
    public function setPaisRel(\Brasa\GeneralBundle\Entity\GenPais $paisRel = null)
    {
        $this->paisRel = $paisRel;

        return $this;
    }

    /**
     * Get paisRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenPais
     */
    public function getPaisRel()
    {
        return $this->paisRel;
    }

    /**
     * Add departamentosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel
     *
     * @return GenRegion
     */
    public function addDepartamentosRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel)
    {
        $this->departamentosRel[] = $departamentosRel;

        return $this;
    }

    /**
     * Remove departamentosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel
     */
    public function removeDepartamentosRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentosRel)
    {
        $this->departamentosRel->removeElement($departamentosRel);
    }

    /**
     * Get departamentosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartamentosRel()
    {
        return $this->departamentosRel;
    }
}
