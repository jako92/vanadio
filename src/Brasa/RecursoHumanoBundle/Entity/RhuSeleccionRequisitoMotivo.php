<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RhuSeleccionRequisitoMotivo
 *
 * @ORM\Table(name="rhu_seleccion_requisito_motivo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisitoMotivoRepository")
 */
class RhuSeleccionRequisitoMotivo {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_seleccion_requisito_motivo_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionRequisitoMotivoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisito", mappedBy="seleccionRequisitoMotivoRel")
     */
    protected $seleccionesRequisitosMotivoRel;

    /**
     * Get codigoSeleccionRequisitoMotivoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoMotivoPk() {
        return $this->codigoSeleccionRequisitoMotivoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionRequisitoMotivo
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesRequisitosMotivoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add seleccionesRequisitosMotivoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosMotivoRel
     *
     * @return RhuSeleccionRequisitoMotivo
     */
    public function addSeleccionesRequisitosMotivoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosMotivoRel)
    {
        $this->seleccionesRequisitosMotivoRel[] = $seleccionesRequisitosMotivoRel;

        return $this;
    }

    /**
     * Remove seleccionesRequisitosMotivoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosMotivoRel
     */
    public function removeSeleccionesRequisitosMotivoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionesRequisitosMotivoRel)
    {
        $this->seleccionesRequisitosMotivoRel->removeElement($seleccionesRequisitosMotivoRel);
    }

    /**
     * Get seleccionesRequisitosMotivoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesRequisitosMotivoRel()
    {
        return $this->seleccionesRequisitosMotivoRel;
    }
}
