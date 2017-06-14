<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tur_centro_operacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCentroOperacionRepository")
 */
class TurCentroOperacion {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_operacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroOperacionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="centroOperacionRel")
     */
    protected $puestosCentroOperacionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="TurControlPuesto", mappedBy="centroOperacionRel")
     */
    protected $controlPuestosCentroOperacionRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->puestosCentroOperacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->controlPuestosCentroOperacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroOperacionPk
     *
     * @return integer
     */
    public function getCodigoCentroOperacionPk()
    {
        return $this->codigoCentroOperacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurCentroOperacion
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
     * Add puestosCentroOperacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosCentroOperacionRel
     *
     * @return TurCentroOperacion
     */
    public function addPuestosCentroOperacionRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosCentroOperacionRel)
    {
        $this->puestosCentroOperacionRel[] = $puestosCentroOperacionRel;

        return $this;
    }

    /**
     * Remove puestosCentroOperacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosCentroOperacionRel
     */
    public function removePuestosCentroOperacionRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosCentroOperacionRel)
    {
        $this->puestosCentroOperacionRel->removeElement($puestosCentroOperacionRel);
    }

    /**
     * Get puestosCentroOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosCentroOperacionRel()
    {
        return $this->puestosCentroOperacionRel;
    }

    /**
     * Add controlPuestosCentroOperacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuesto $controlPuestosCentroOperacionRel
     *
     * @return TurCentroOperacion
     */
    public function addControlPuestosCentroOperacionRel(\Brasa\TurnoBundle\Entity\TurControlPuesto $controlPuestosCentroOperacionRel)
    {
        $this->controlPuestosCentroOperacionRel[] = $controlPuestosCentroOperacionRel;

        return $this;
    }

    /**
     * Remove controlPuestosCentroOperacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuesto $controlPuestosCentroOperacionRel
     */
    public function removeControlPuestosCentroOperacionRel(\Brasa\TurnoBundle\Entity\TurControlPuesto $controlPuestosCentroOperacionRel)
    {
        $this->controlPuestosCentroOperacionRel->removeElement($controlPuestosCentroOperacionRel);
    }

    /**
     * Get controlPuestosCentroOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getControlPuestosCentroOperacionRel()
    {
        return $this->controlPuestosCentroOperacionRel;
    }
}
