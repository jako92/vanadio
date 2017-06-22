<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * invOrdenCompraDocumento
 *
 * @ORM\Table(name="inv_orden_compra_documento")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\invOrdenCompraDocumentoRepository")
 */
class InvOrdenCompraDocumento
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_orden_compra_documento_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoOrdenCompraDocumentoPk;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="consecutivo", type="integer")
     */
    private $consecutivo;
    
    /**
     * @ORM\OneToMany(targetEntity="InvOrdenCompra", mappedBy="ordenCompraDocumentoRel")
     */
    protected $ordenesCompraDocumentoRel;


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
     * @return invOrdenCompraDocumento
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
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return invOrdenCompraDocumento
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return int
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ordenesCompraDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ordenesCompraDocumentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvOrdenCompra $ordenesCompraDocumentoRel
     *
     * @return invOrdenCompraDocumento
     */
    public function addOrdenesCompraDocumentoRel(\Brasa\InventarioBundle\Entity\InvOrdenCompra $ordenesCompraDocumentoRel)
    {
        $this->ordenesCompraDocumentoRel[] = $ordenesCompraDocumentoRel;

        return $this;
    }

    /**
     * Remove ordenesCompraDocumentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvOrdenCompra $ordenesCompraDocumentoRel
     */
    public function removeOrdenesCompraDocumentoRel(\Brasa\InventarioBundle\Entity\InvOrdenCompra $ordenesCompraDocumentoRel)
    {
        $this->ordenesCompraDocumentoRel->removeElement($ordenesCompraDocumentoRel);
    }

    /**
     * Get ordenesCompraDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdenesCompraDocumentoRel()
    {
        return $this->ordenesCompraDocumentoRel;
    }

    /**
     * Get codigoOrdenCompraDocumentoPk
     *
     * @return integer
     */
    public function getCodigoOrdenCompraDocumentoPk()
    {
        return $this->codigoOrdenCompraDocumentoPk;
    }
}
