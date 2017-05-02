<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cobro_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCobroTipoRepository")
 */
class RhuCobroTipo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cobro_tipo_pk", type="string", length=1)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCobroTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuCobro", mappedBy="cobroTipoRel")
     */
    protected $cobrosCobroTipoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cobrosCobroTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCobroTipoPk
     *
     * @return string
     */
    public function getCodigoCobroTipoPk()
    {
        return $this->codigoCobroTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCobroTipo
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
     * Add cobrosCobroTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCobroTipoRel
     *
     * @return RhuCobroTipo
     */
    public function addCobrosCobroTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCobroTipoRel)
    {
        $this->cobrosCobroTipoRel[] = $cobrosCobroTipoRel;

        return $this;
    }

    /**
     * Remove cobrosCobroTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCobroTipoRel
     */
    public function removeCobrosCobroTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCobroTipoRel)
    {
        $this->cobrosCobroTipoRel->removeElement($cobrosCobroTipoRel);
    }

    /**
     * Get cobrosCobroTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCobrosCobroTipoRel()
    {
        return $this->cobrosCobroTipoRel;
    }
}
