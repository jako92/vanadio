<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_devolucion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDetalleRepository")
 */
class TurPedidoDevolucionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_devolucion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDevolucionDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_pedido_devolucion_fk", type="integer")
     */    
    private $codigoPedidoDevolucionFk;     
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;         
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDevolucion", inversedBy="pedidosDevolucionesDetallesPedidoDevolucionRel")
     * @ORM\JoinColumn(name="codigo_pedido_devolucion_fk", referencedColumnName="codigo_pedido_devolucion_pk")
     */
    protected $pedidoDevolucionRel;       



    /**
     * Get codigoPedidoDevolucionDetallePk
     *
     * @return integer
     */
    public function getCodigoPedidoDevolucionDetallePk()
    {
        return $this->codigoPedidoDevolucionDetallePk;
    }

    /**
     * Set codigoPedidoDevolucionFk
     *
     * @param integer $codigoPedidoDevolucionFk
     *
     * @return TurPedidoDevolucionDetalle
     */
    public function setCodigoPedidoDevolucionFk($codigoPedidoDevolucionFk)
    {
        $this->codigoPedidoDevolucionFk = $codigoPedidoDevolucionFk;

        return $this;
    }

    /**
     * Get codigoPedidoDevolucionFk
     *
     * @return integer
     */
    public function getCodigoPedidoDevolucionFk()
    {
        return $this->codigoPedidoDevolucionFk;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return TurPedidoDevolucionDetalle
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set pedidoDevolucionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidoDevolucionRel
     *
     * @return TurPedidoDevolucionDetalle
     */
    public function setPedidoDevolucionRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidoDevolucionRel = null)
    {
        $this->pedidoDevolucionRel = $pedidoDevolucionRel;

        return $this;
    }

    /**
     * Get pedidoDevolucionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDevolucion
     */
    public function getPedidoDevolucionRel()
    {
        return $this->pedidoDevolucionRel;
    }
}
