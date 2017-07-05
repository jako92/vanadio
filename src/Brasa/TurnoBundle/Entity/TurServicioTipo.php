<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurServicioTipo
 *
 * @ORM\Table(name="tur_servicio_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioTipoRepository")
 */
class TurServicioTipo
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_servicio_tipo_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioTipoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicio", mappedBy="servicioTipoRel")
     */
    protected $serviciosServicioTipoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosServicioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoServicioTipoPk
     *
     * @return integer
     */
    public function getCodigoServicioTipoPk()
    {
        return $this->codigoServicioTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurServicioTipo
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
     * Add serviciosServicioTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $serviciosServicioTipoRel
     *
     * @return TurServicioTipo
     */
    public function addServiciosServicioTipoRel(\Brasa\TurnoBundle\Entity\TurServicio $serviciosServicioTipoRel)
    {
        $this->serviciosServicioTipoRel[] = $serviciosServicioTipoRel;

        return $this;
    }

    /**
     * Remove serviciosServicioTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $serviciosServicioTipoRel
     */
    public function removeServiciosServicioTipoRel(\Brasa\TurnoBundle\Entity\TurServicio $serviciosServicioTipoRel)
    {
        $this->serviciosServicioTipoRel->removeElement($serviciosServicioTipoRel);
    }

    /**
     * Get serviciosServicioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosServicioTipoRel()
    {
        return $this->serviciosServicioTipoRel;
    }
}
