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
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;
    
    /**
     * @ORM\Column(name="codigo_orden_compra_documento_fk", type="integer", nullable=true)
     */    
    private $codigoOrdenCompraDocumentoFk;

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
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */    
    private $vrIva = 0;       
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;   
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;    

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = false;    
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="InvOrdenCompraDetalle", mappedBy="ordenCompraRel")
     */
    protected $ordenesCompraDetallesRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="ordenesCompraTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="InvOrdenCompraDocumento", inversedBy="ordenesCompraDocumentoRel")
     * @ORM\JoinColumn(name="codigo_orden_compra_documento_fk", referencedColumnName="codigo_orden_compra_documento_pk")
     */
    protected $ordenCompraDocumentoRel;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ordenesCompraDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return InvOrdenCompra
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
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
     * Set soporte
     *
     * @param string $soporte
     *
     * @return InvOrdenCompra
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
     * @return InvOrdenCompra
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
     * @return InvOrdenCompra
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
     * @return InvOrdenCompra
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return InvOrdenCompra
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
     * Add ordenesCompraDetallesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle $ordenesCompraDetallesRel
     *
     * @return InvOrdenCompra
     */
    public function addOrdenesCompraDetallesRel(\Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle $ordenesCompraDetallesRel)
    {
        $this->ordenesCompraDetallesRel[] = $ordenesCompraDetallesRel;

        return $this;
    }

    /**
     * Remove ordenesCompraDetallesRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle $ordenesCompraDetallesRel
     */
    public function removeOrdenesCompraDetallesRel(\Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle $ordenesCompraDetallesRel)
    {
        $this->ordenesCompraDetallesRel->removeElement($ordenesCompraDetallesRel);
    }

    /**
     * Get ordenesCompraDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdenesCompraDetallesRel()
    {
        return $this->ordenesCompraDetallesRel;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvTercero $terceroRel
     *
     * @return InvOrdenCompra
     */
    public function setTerceroRel(\Brasa\InventarioBundle\Entity\InvTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return InvOrdenCompra
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return InvOrdenCompra
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
     * @return InvOrdenCompra
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
     * @return InvOrdenCompra
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
     * Set codigoOrdenCompraDocumentoFk
     *
     * @param integer $codigoOrdenCompraDocumentoFk
     *
     * @return InvOrdenCompra
     */
    public function setCodigoOrdenCompraDocumentoFk($codigoOrdenCompraDocumentoFk)
    {
        $this->codigoOrdenCompraDocumentoFk = $codigoOrdenCompraDocumentoFk;

        return $this;
    }

    /**
     * Get codigoOrdenCompraDocumentoFk
     *
     * @return integer
     */
    public function getCodigoOrdenCompraDocumentoFk()
    {
        return $this->codigoOrdenCompraDocumentoFk;
    }

    /**
     * Set ordenCompraDocumentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvOrdenCompraDocumento $ordenCompraDocumentoRel
     *
     * @return InvOrdenCompra
     */
    public function setOrdenCompraDocumentoRel(\Brasa\InventarioBundle\Entity\InvOrdenCompraDocumento $ordenCompraDocumentoRel = null)
    {
        $this->ordenCompraDocumentoRel = $ordenCompraDocumentoRel;

        return $this;
    }

    /**
     * Get ordenCompraDocumentoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvOrdenCompraDocumento
     */
    public function getOrdenCompraDocumentoRel()
    {
        return $this->ordenCompraDocumentoRel;
    }
}
