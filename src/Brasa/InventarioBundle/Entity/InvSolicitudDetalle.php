<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvOdenCompraDetalle
 *
 * @ORM\Table(name="inv_solicitud_detalle")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvSolicitudDetalleRepository")
 */
class InvSolicitudDetalle
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_detalle_solicitud_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDetalleSolicitudPk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @ORM\Column(name="codigo_solicitud_fk", type="integer", nullable=true)
     */     
    private $codigoSolicitudFk; 
    
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
     * @ORM\ManyToOne(targetEntity="InvSolicitud", inversedBy="solicitudesDetallesRel")
     * @ORM\JoinColumn(name="codigo_solicitud_fk", referencedColumnName="codigo_solicitud_pk")
     */
    protected $solicitudRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvItem", inversedBy="solicitudesDetallesItemRel")
     * @ORM\JoinColumn(name="codigo_item_fk", referencedColumnName="codigo_item_pk")
     */
    protected $itemRel;
    

    /**
     * Get codigoDetalleSolicitudPk
     *
     * @return integer
     */
    public function getCodigoDetalleSolicitudPk()
    {
        return $this->codigoDetalleSolicitudPk;
    }

    /**
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     *
     * @return InvSolicitudDetalle
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
     * Set codigoSolicitudFk
     *
     * @param integer $codigoSolicitudFk
     *
     * @return InvSolicitudDetalle
     */
    public function setCodigoSolicitudFk($codigoSolicitudFk)
    {
        $this->codigoSolicitudFk = $codigoSolicitudFk;

        return $this;
    }

    /**
     * Get codigoSolicitudFk
     *
     * @return integer
     */
    public function getCodigoSolicitudFk()
    {
        return $this->codigoSolicitudFk;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return InvSolicitudDetalle
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
     * @return InvSolicitudDetalle
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
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return InvSolicitudDetalle
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
     * Set porcentajeIva
     *
     * @param integer $porcentajeIva
     *
     * @return InvSolicitudDetalle
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
     * @return InvSolicitudDetalle
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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return InvSolicitudDetalle
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

    /**
     * Set solicitudRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvSolicitud $solicitudRel
     *
     * @return InvSolicitudDetalle
     */
    public function setSolicitudRel(\Brasa\InventarioBundle\Entity\InvSolicitud $solicitudRel = null)
    {
        $this->solicitudRel = $solicitudRel;

        return $this;
    }

    /**
     * Get solicitudRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvSolicitud
     */
    public function getSolicitudRel()
    {
        return $this->solicitudRel;
    }

    /**
     * Set itemRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvItem $itemRel
     *
     * @return InvSolicitudDetalle
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
}
