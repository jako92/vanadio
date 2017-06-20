<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvOrdenCompra
 *
 * @ORM\Table(name="inv_orden_compra")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvOrdenCompraRepository")
 */
class InvOrdenCompra
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_orden_compra_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoOrdenCompraPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvOrdenCompra
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return InvOrdenCompra
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Get codigoOrdenCompraPk
     *
     * @return integer
     */
    public function getCodigoOrdenCompraPk()
    {
        return $this->codigoOrdenCompraPk;
    }
}
