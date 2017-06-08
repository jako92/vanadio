<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_prueba_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPruebaTipoRepository")
 */
class RhuPruebaTipo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_prueba_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPruebaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuPrueba", mappedBy="pruebaTipoRel")
     */
    protected $pruebasPruebaTipoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pruebasPruebaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPruebaTipoPk
     *
     * @return integer
     */
    public function getCodigoPruebaTipoPk()
    {
        return $this->codigoPruebaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPruebaTipo
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
     * Add pruebasPruebaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasPruebaTipoRel
     *
     * @return RhuPruebaTipo
     */
    public function addPruebasPruebaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasPruebaTipoRel)
    {
        $this->pruebasPruebaTipoRel[] = $pruebasPruebaTipoRel;

        return $this;
    }

    /**
     * Remove pruebasPruebaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasPruebaTipoRel
     */
    public function removePruebasPruebaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasPruebaTipoRel)
    {
        $this->pruebasPruebaTipoRel->removeElement($pruebasPruebaTipoRel);
    }

    /**
     * Get pruebasPruebaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasPruebaTipoRel()
    {
        return $this->pruebasPruebaTipoRel;
    }
}
