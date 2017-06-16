<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="rhu_poligrafia_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPoligrafiaRepository")
 */
class RhuPoligrafiaTipo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_poligrafia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPoligrafiaTipoPK;

    /**
     * @ORM\Column(name="nombre", type="string")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuPoligrafiaTipo", mappedBy="poligrafiaTipoRel")
     */
    protected $poligrafiasPoligrafiaTipoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->poligrafiasPoligrafiaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPoligrafiaTipoPK
     *
     * @return integer
     */
    public function getCodigoPoligrafiaTipoPK()
    {
        return $this->codigoPoligrafiaTipoPK;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPoligrafiaTipo
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
     * Add poligrafiasPoligrafiaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafiaTipo $poligrafiasPoligrafiaTipoRel
     *
     * @return RhuPoligrafiaTipo
     */
    public function addPoligrafiasPoligrafiaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafiaTipo $poligrafiasPoligrafiaTipoRel)
    {
        $this->poligrafiasPoligrafiaTipoRel[] = $poligrafiasPoligrafiaTipoRel;

        return $this;
    }

    /**
     * Remove poligrafiasPoligrafiaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafiaTipo $poligrafiasPoligrafiaTipoRel
     */
    public function removePoligrafiasPoligrafiaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafiaTipo $poligrafiasPoligrafiaTipoRel)
    {
        $this->poligrafiasPoligrafiaTipoRel->removeElement($poligrafiasPoligrafiaTipoRel);
    }

    /**
     * Get poligrafiasPoligrafiaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPoligrafiasPoligrafiaTipoRel()
    {
        return $this->poligrafiasPoligrafiaTipoRel;
    }
}
