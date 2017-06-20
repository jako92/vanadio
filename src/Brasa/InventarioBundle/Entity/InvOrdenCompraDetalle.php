<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvOdenCompraDetalle
 *
 * @ORM\Table(name="inv_oden_compra_detalle")
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
     * @ORM\Column(name="item_fk", type="integer")
     */
    private $codigoItemFk;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float")
     */
    private $valor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set itemFk
     *
     * @param integer $itemFk
     *
     * @return InvOdenCompraDetalle
     */
    public function setItemFk($itemFk)
    {
        $this->itemFk = $itemFk;

        return $this;
    }

    /**
     * Get itemFk
     *
     * @return int
     */
    public function getItemFk()
    {
        return $this->itemFk;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return InvOdenCompraDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return int
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
     * @return InvOdenCompraDetalle
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
}
