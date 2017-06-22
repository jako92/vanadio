<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvOdenCompraDetalle
 *
 * @ORM\Table(name="inv_orden_compra_detalle")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvOrdenCompraDetalleRepository")
 */
class InvOrdenCompraDetalle
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_detalle_orden_compra_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDetalleOrdenCompraPk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_orden_compra_fk", type="integer", nullable=true)
     */     
    private $codigoOrdenCompraFk; 
    
    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_iva", type="integer")
     */    
    private $porcentajeIva = 0;
    
    /**
     * @ORM\Column(name="vr_iva", type="float")
     */    
    private $vrIva = 0;
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;   
    
    /**
     * @ORM\ManyToOne(targetEntity="InvOrdenCompra", inversedBy="ordenesCompraDetallesRel")
     * @ORM\JoinColumn(name="codigo_orden_compra_fk", referencedColumnName="codigo_orden_compra_pk")
     */
    protected $ordenCompraRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="ordenesCompraDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;


    /**
     * Get codigoDetalleOrdenCompraPk
     *
     * @return integer
     */
    public function getCodigoDetalleOrdenCompraPk()
    {
        return $this->codigoDetalleOrdenCompraPk;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     *
     * @return InvOrdenCompraDetalle
     */
    public function setCodigoItemFk($codigoItemFk)
    {
        $this->codigoItemFk = $codigoItemFk;

        return $this;
    }

    /**
     * Get codigoItemFk
     *
     * @return integer
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * Set codigoOrdenCompraFk
     *
     * @param integer $codigoOrdenCompraFk
     *
     * @return InvOrdenCompraDetalle
     */
    public function setCodigoOrdenCompraFk($codigoOrdenCompraFk)
    {
        $this->codigoOrdenCompraFk = $codigoOrdenCompraFk;

        return $this;
    }

    /**
     * Get codigoOrdenCompraFk
     *
     * @return integer
     */
    public function getCodigoOrdenCompraFk()
    {
        return $this->codigoOrdenCompraFk;
    }

    /**
     * Set ordenCompraRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvOrdenCompra $ordenCompraRel
     *
     * @return InvOrdenCompraDetalle
     */
    public function setOrdenCompraRel(\Brasa\InventarioBundle\Entity\InvOrdenCompra $ordenCompraRel = null)
    {
        $this->ordenCompraRel = $ordenCompraRel;

        return $this;
    }

    /**
     * Get ordenCompraRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvOrdenCompra
     */
    public function getOrdenCompraRel()
    {
        return $this->ordenCompraRel;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     *
     * @return InvOrdenCompraDetalle
     */
    public function setItemRel(\Brasa\InventarioBundle\Entity\InvItem $itemRel = null)
    {
        $this->itemRel = $itemRel;

        return $this;
    }

    /**
     * Get itemRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvItem
     */
    public function getItemRel()
    {
        return $this->itemRel;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return InvOrdenCompraDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return InvOrdenCompraDetalle
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set porcentajeIva
     *
     * @param integer $porcentajeIva
     *
     * @return InvOrdenCompraDetalle
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return integer
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return InvOrdenCompraDetalle
     */
    public function setVrIva($vrIva)
    {
        $this->vrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return InvOrdenCompraDetalle
     */
    public function setVrSubtotal($vrSubtotal)
    {
        $this->vrSubtotal = $vrSubtotal;

        return $this;
    }

    /**
     * Get vrSubtotal
     *
     * @return float
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return InvOrdenCompraDetalle
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }
}
