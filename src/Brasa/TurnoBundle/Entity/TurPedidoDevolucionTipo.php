<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_devolucion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDevolucionTipoRepository")
 */
class TurPedidoDevolucionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_devolucion_tipo_pk", type="string", length=3)
     */
    private $codigoPedidoDevolucionTipoPk;           

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDevolucion", mappedBy="pedidoDevolucionTipoRel")
     */
    protected $pedidosDevolucionesPedidoDevolucionTipoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDevolucionesPedidoDevolucionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoPedidoDevolucionTipoPk
     *
     * @param string $codigoPedidoDevolucionTipoPk
     *
     * @return TurPedidoDevolucionTipo
     */
    public function setCodigoPedidoDevolucionTipoPk($codigoPedidoDevolucionTipoPk)
    {
        $this->codigoPedidoDevolucionTipoPk = $codigoPedidoDevolucionTipoPk;

        return $this;
    }

    /**
     * Get codigoPedidoDevolucionTipoPk
     *
     * @return string
     */
    public function getCodigoPedidoDevolucionTipoPk()
    {
        return $this->codigoPedidoDevolucionTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPedidoDevolucionTipo
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
     * Add pedidosDevolucionesPedidoDevolucionTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionTipoRel
     *
     * @return TurPedidoDevolucionTipo
     */
    public function addPedidosDevolucionesPedidoDevolucionTipoRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionTipoRel)
    {
        $this->pedidosDevolucionesPedidoDevolucionTipoRel[] = $pedidosDevolucionesPedidoDevolucionTipoRel;

        return $this;
    }

    /**
     * Remove pedidosDevolucionesPedidoDevolucionTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionTipoRel
     */
    public function removePedidosDevolucionesPedidoDevolucionTipoRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionTipoRel)
    {
        $this->pedidosDevolucionesPedidoDevolucionTipoRel->removeElement($pedidosDevolucionesPedidoDevolucionTipoRel);
    }

    /**
     * Get pedidosDevolucionesPedidoDevolucionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDevolucionesPedidoDevolucionTipoRel()
    {
        return $this->pedidosDevolucionesPedidoDevolucionTipoRel;
    }
}
