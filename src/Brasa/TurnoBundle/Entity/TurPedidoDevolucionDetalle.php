<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_devolucion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDevolucionDetalleRepository")
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
     * @ORM\Column(name="codigo_pedido_devolucion_tipo_fk", type="string", length=3, nullable=true)
     */    
    private $codigoPedidoDevolucionTipoFk;    
    
    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleFk;    
    
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
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="pedidosDevolucionesDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;

    

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
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurPedidoDevolucionDetalle
     */
    public function setCodigoPedidoDetalleFk($codigoPedidoDetalleFk)
    {
        $this->codigoPedidoDetalleFk = $codigoPedidoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPedidoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleFk()
    {
        return $this->codigoPedidoDetalleFk;
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

    /**
     * Set pedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel
     *
     * @return TurPedidoDevolucionDetalle
     */
    public function setPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel = null)
    {
        $this->pedidoDetalleRel = $pedidoDetalleRel;

        return $this;
    }

    /**
     * Get pedidoDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDetalle
     */
    public function getPedidoDetalleRel()
    {
        return $this->pedidoDetalleRel;
    }

    /**
     * Set codigoPedidoDevolucionTipoFk
     *
     * @param string $codigoPedidoDevolucionTipoFk
     *
     * @return TurPedidoDevolucionDetalle
     */
    public function setCodigoPedidoDevolucionTipoFk($codigoPedidoDevolucionTipoFk)
    {
        $this->codigoPedidoDevolucionTipoFk = $codigoPedidoDevolucionTipoFk;

        return $this;
    }

    /**
     * Get codigoPedidoDevolucionTipoFk
     *
     * @return string
     */
    public function getCodigoPedidoDevolucionTipoFk()
    {
        return $this->codigoPedidoDevolucionTipoFk;
    }
}
