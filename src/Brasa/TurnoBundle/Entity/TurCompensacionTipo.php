<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurCompensacionTipo
 *
 * @ORM\Table(name="tur_compensacion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCompensacionTipoRepository")
 */
class TurCompensacionTipo
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_compensacion_tipo_pk", type="integer")
     * @ORM\Id
     */
    private $codigoCompensacionTipoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto", mappedBy="compensacionTipoRel")
     */
    protected $rhuCentroCostosCompensacionTipoRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuCentroCostosCompensacionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoCompensacionTipoPk
     *
     * @param integer $codigoCompensacionTipoPk
     *
     * @return TurCompensacionTipo
     */
    public function setCodigoCompensacionTipoPk($codigoCompensacionTipoPk)
    {
        $this->codigoCompensacionTipoPk = $codigoCompensacionTipoPk;

        return $this;
    }

    /**
     * Get codigoCompensacionTipoPk
     *
     * @return integer
     */
    public function getCodigoCompensacionTipoPk()
    {
        return $this->codigoCompensacionTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurCompensacionTipo
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
     * Add rhuCentroCostosCompensacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCompensacionTipoRel
     *
     * @return TurCompensacionTipo
     */
    public function addRhuCentroCostosCompensacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCompensacionTipoRel)
    {
        $this->rhuCentroCostosCompensacionTipoRel[] = $rhuCentroCostosCompensacionTipoRel;

        return $this;
    }

    /**
     * Remove rhuCentroCostosCompensacionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCompensacionTipoRel
     */
    public function removeRhuCentroCostosCompensacionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCompensacionTipoRel)
    {
        $this->rhuCentroCostosCompensacionTipoRel->removeElement($rhuCentroCostosCompensacionTipoRel);
    }

    /**
     * Get rhuCentroCostosCompensacionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuCentroCostosCompensacionTipoRel()
    {
        return $this->rhuCentroCostosCompensacionTipoRel;
    }
}
