<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurControlPuestoDetalleTipoNovedad
 *
 * @ORM\Table(name="tur_control_puesto_detalle_tipo_novedad")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurControlPuestoDetalleTipoNovedadRepository")
 */
class TurControlPuestoDetalleTipoNovedad {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_tipo_novedad_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoNovedadPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TurControlPuestoDetalle", mappedBy="tipoNovedadRel")
     */
    protected $controlesPuestosDetallesTipoNovedadRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->controlesPuestosDetallesTipoNovedadRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoNovedadPk
     *
     * @return integer
     */
    public function getCodigoTipoNovedadPk()
    {
        return $this->codigoTipoNovedadPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurControlPuestoDetalleTipoNovedad
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
     * Add controlesPuestosDetallesTipoNovedadRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesTipoNovedadRel
     *
     * @return TurControlPuestoDetalleTipoNovedad
     */
    public function addControlesPuestosDetallesTipoNovedadRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesTipoNovedadRel)
    {
        $this->controlesPuestosDetallesTipoNovedadRel[] = $controlesPuestosDetallesTipoNovedadRel;

        return $this;
    }

    /**
     * Remove controlesPuestosDetallesTipoNovedadRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesTipoNovedadRel
     */
    public function removeControlesPuestosDetallesTipoNovedadRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalle $controlesPuestosDetallesTipoNovedadRel)
    {
        $this->controlesPuestosDetallesTipoNovedadRel->removeElement($controlesPuestosDetallesTipoNovedadRel);
    }

    /**
     * Get controlesPuestosDetallesTipoNovedadRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getControlesPuestosDetallesTipoNovedadRel()
    {
        return $this->controlesPuestosDetallesTipoNovedadRel;
    }
}
