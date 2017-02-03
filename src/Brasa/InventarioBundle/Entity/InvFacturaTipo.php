<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="inv_factura_tipo")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvFacturaTipoRepository")
 */
class InvFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje_retencion_fuente", type="float")
     */
    private $porcentajeRetencionFuente = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="facturaTipoRel")
     */
    protected $movimientosFacturaTipoRel;    

    /**
     * Get codigoFacturaTipoPk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvFacturaTipo
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
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosFacturaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movimientosFacturaTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosFacturaTipoRel
     *
     * @return InvFacturaTipo
     */
    public function addMovimientosFacturaTipoRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosFacturaTipoRel)
    {
        $this->movimientosFacturaTipoRel[] = $movimientosFacturaTipoRel;

        return $this;
    }

    /**
     * Remove movimientosFacturaTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosFacturaTipoRel
     */
    public function removeMovimientosFacturaTipoRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosFacturaTipoRel)
    {
        $this->movimientosFacturaTipoRel->removeElement($movimientosFacturaTipoRel);
    }

    /**
     * Get movimientosFacturaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosFacturaTipoRel()
    {
        return $this->movimientosFacturaTipoRel;
    }

    /**
     * Set porcentajeRetencionFuente
     *
     * @param float $porcentajeRetencionFuente
     *
     * @return InvFacturaTipo
     */
    public function setPorcentajeRetencionFuente($porcentajeRetencionFuente)
    {
        $this->porcentajeRetencionFuente = $porcentajeRetencionFuente;

        return $this;
    }

    /**
     * Get porcentajeRetencionFuente
     *
     * @return float
     */
    public function getPorcentajeRetencionFuente()
    {
        return $this->porcentajeRetencionFuente;
    }
}
