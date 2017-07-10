<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * invSolicitudDocumento
 *
 * @ORM\Table(name="inv_solicitud_documento")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvSolicitudDocumentoRepository")
 */
class InvSolicitudDocumento
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_solicitud_documento_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSolicitudDocumentoPk;

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
     * @ORM\OneToMany(targetEntity="InvSolicitud", mappedBy="solicitudDocumentoRel")
     */
    protected $solicitudesDocumentoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->solicitudesDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSolicitudDocumentoPk
     *
     * @return integer
     */
    public function getCodigoSolicitudDocumentoPk()
    {
        return $this->codigoSolicitudDocumentoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvSolicitudDocumento
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
     * @return InvSolicitudDocumento
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Add solicitudesDocumentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvSolicitud $solicitudesDocumentoRel
     *
     * @return InvSolicitudDocumento
     */
    public function addSolicitudesDocumentoRel(\Brasa\InventarioBundle\Entity\InvSolicitud $solicitudesDocumentoRel)
    {
        $this->solicitudesDocumentoRel[] = $solicitudesDocumentoRel;

        return $this;
    }

    /**
     * Remove solicitudesDocumentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvSolicitud $solicitudesDocumentoRel
     */
    public function removeSolicitudesDocumentoRel(\Brasa\InventarioBundle\Entity\InvSolicitud $solicitudesDocumentoRel)
    {
        $this->solicitudesDocumentoRel->removeElement($solicitudesDocumentoRel);
    }

    /**
     * Get solicitudesDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSolicitudesDocumentoRel()
    {
        return $this->solicitudesDocumentoRel;
    }
}
