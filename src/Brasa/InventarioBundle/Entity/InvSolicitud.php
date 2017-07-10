<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvSolicitud
 *
 * @ORM\Table(name="inv_solicitud")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvSolicitudRepository")
 */
class InvSolicitud {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_solicitud_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSolicitudPk;

    /**
     * @ORM\Column(name="codigo_solicitud_documento_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudDocumentoFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_entrega", type="date")
     */
    private $fechaEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="soporte", type="string", length=255)
     */
    private $soporte;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = FALSE;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado_impreso", type="boolean")
     */
    private $estadoImpreso = FALSE;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="InvSolicitudDocumento", inversedBy="solicitudesDocumentoRel")
     * @ORM\JoinColumn(name="codigo_solicitud_documento_fk", referencedColumnName="codigo_solicitud_documento_pk")
     */
    protected $solicitudDocumentoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="InvSolicitudDetalle", mappedBy="solicitudRel")
     */
    protected $solicitudesDetallesRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->solicitudesDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSolicitudPk
     *
     * @return integer
     */
    public function getCodigoSolicitudPk()
    {
        return $this->codigoSolicitudPk;
    }

    /**
     * Set codigoSolicitudDocumentoFk
     *
     * @param integer $codigoSolicitudDocumentoFk
     *
     * @return InvSolicitud
     */
    public function setCodigoSolicitudDocumentoFk($codigoSolicitudDocumentoFk)
    {
        $this->codigoSolicitudDocumentoFk = $codigoSolicitudDocumentoFk;

        return $this;
    }

    /**
     * Get codigoSolicitudDocumentoFk
     *
     * @return integer
     */
    public function getCodigoSolicitudDocumentoFk()
    {
        return $this->codigoSolicitudDocumentoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return InvSolicitud
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
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return InvSolicitud
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return InvSolicitud
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return InvSolicitud
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
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return InvSolicitud
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
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return InvSolicitud
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return InvSolicitud
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return InvSolicitud
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return InvSolicitud
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return InvSolicitud
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set solicitudDocumentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvSolicitudDocumento $solicitudDocumentoRel
     *
     * @return InvSolicitud
     */
    public function setSolicitudDocumentoRel(\Brasa\InventarioBundle\Entity\InvSolicitudDocumento $solicitudDocumentoRel = null)
    {
        $this->solicitudDocumentoRel = $solicitudDocumentoRel;

        return $this;
    }

    /**
     * Get solicitudDocumentoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvSolicitudDocumento
     */
    public function getSolicitudDocumentoRel()
    {
        return $this->solicitudDocumentoRel;
    }

    /**
     * Add solicitudesDetallesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvSolicitudDetalle $solicitudesDetallesRel
     *
     * @return InvSolicitud
     */
    public function addSolicitudesDetallesRel(\Brasa\InventarioBundle\Entity\InvSolicitudDetalle $solicitudesDetallesRel)
    {
        $this->solicitudesDetallesRel[] = $solicitudesDetallesRel;

        return $this;
    }

    /**
     * Remove solicitudesDetallesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvSolicitudDetalle $solicitudesDetallesRel
     */
    public function removeSolicitudesDetallesRel(\Brasa\InventarioBundle\Entity\InvSolicitudDetalle $solicitudesDetallesRel)
    {
        $this->solicitudesDetallesRel->removeElement($solicitudesDetallesRel);
    }

    /**
     * Get solicitudesDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSolicitudesDetallesRel()
    {
        return $this->solicitudesDetallesRel;
    }
}
