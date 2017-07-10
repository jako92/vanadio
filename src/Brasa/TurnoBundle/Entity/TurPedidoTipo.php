<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoTipoRepository")
 */
class TurPedidoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoTipoPk;               
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;             
    
    /**
     * @ORM\Column(name="tipo", type="string", length=50, nullable=true)
     */    
    private $tipo;    
    
    /**     
     * @ORM\Column(name="control", type="boolean")
     */    
    private $control = false;
    
    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="integer", nullable=true)
     */    
    private $codigoComprobanteFk;
    
    /**
     * @ORM\Column(name="tipo_cuenta_cartera", type="bigint")
     */     
    private $tipoCuentaCartera = 1;
    
    /**
     * @ORM\Column(name="tipo_cuenta_iva", type="bigint")
     */     
    private $tipoCuentaIva = 1;
    
    /**
     * @ORM\Column(name="tipo_cuenta_ingreso", type="bigint")
     */     
    private $tipoCuentaIngreso = 1;

    /**
     * @ORM\Column(name="codigo_cuenta_cartera_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaCarteraFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIvaFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIngresoFk;
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="pedidoTipoRel")
     */
    protected $pedidosPedidoTipoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosPedidoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoTipoPk
     *
     * @return integer
     */
    public function getCodigoPedidoTipoPk()
    {
        return $this->codigoPedidoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPedidoTipo
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return TurPedidoTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set control
     *
     * @param boolean $control
     *
     * @return TurPedidoTipo
     */
    public function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Get control
     *
     * @return boolean
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Set codigoComprobanteFk
     *
     * @param integer $codigoComprobanteFk
     *
     * @return TurPedidoTipo
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk)
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;

        return $this;
    }

    /**
     * Get codigoComprobanteFk
     *
     * @return integer
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * Set tipoCuentaCartera
     *
     * @param integer $tipoCuentaCartera
     *
     * @return TurPedidoTipo
     */
    public function setTipoCuentaCartera($tipoCuentaCartera)
    {
        $this->tipoCuentaCartera = $tipoCuentaCartera;

        return $this;
    }

    /**
     * Get tipoCuentaCartera
     *
     * @return integer
     */
    public function getTipoCuentaCartera()
    {
        return $this->tipoCuentaCartera;
    }

    /**
     * Set tipoCuentaIva
     *
     * @param integer $tipoCuentaIva
     *
     * @return TurPedidoTipo
     */
    public function setTipoCuentaIva($tipoCuentaIva)
    {
        $this->tipoCuentaIva = $tipoCuentaIva;

        return $this;
    }

    /**
     * Get tipoCuentaIva
     *
     * @return integer
     */
    public function getTipoCuentaIva()
    {
        return $this->tipoCuentaIva;
    }

    /**
     * Set tipoCuentaIngreso
     *
     * @param integer $tipoCuentaIngreso
     *
     * @return TurPedidoTipo
     */
    public function setTipoCuentaIngreso($tipoCuentaIngreso)
    {
        $this->tipoCuentaIngreso = $tipoCuentaIngreso;

        return $this;
    }

    /**
     * Get tipoCuentaIngreso
     *
     * @return integer
     */
    public function getTipoCuentaIngreso()
    {
        return $this->tipoCuentaIngreso;
    }

    /**
     * Set codigoCuentaCarteraFk
     *
     * @param string $codigoCuentaCarteraFk
     *
     * @return TurPedidoTipo
     */
    public function setCodigoCuentaCarteraFk($codigoCuentaCarteraFk)
    {
        $this->codigoCuentaCarteraFk = $codigoCuentaCarteraFk;

        return $this;
    }

    /**
     * Get codigoCuentaCarteraFk
     *
     * @return string
     */
    public function getCodigoCuentaCarteraFk()
    {
        return $this->codigoCuentaCarteraFk;
    }

    /**
     * Set codigoCuentaIvaFk
     *
     * @param string $codigoCuentaIvaFk
     *
     * @return TurPedidoTipo
     */
    public function setCodigoCuentaIvaFk($codigoCuentaIvaFk)
    {
        $this->codigoCuentaIvaFk = $codigoCuentaIvaFk;

        return $this;
    }

    /**
     * Get codigoCuentaIvaFk
     *
     * @return string
     */
    public function getCodigoCuentaIvaFk()
    {
        return $this->codigoCuentaIvaFk;
    }

    /**
     * Set codigoCuentaIngresoFk
     *
     * @param string $codigoCuentaIngresoFk
     *
     * @return TurPedidoTipo
     */
    public function setCodigoCuentaIngresoFk($codigoCuentaIngresoFk)
    {
        $this->codigoCuentaIngresoFk = $codigoCuentaIngresoFk;

        return $this;
    }

    /**
     * Get codigoCuentaIngresoFk
     *
     * @return string
     */
    public function getCodigoCuentaIngresoFk()
    {
        return $this->codigoCuentaIngresoFk;
    }

    /**
     * Add pedidosPedidoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel
     *
     * @return TurPedidoTipo
     */
    public function addPedidosPedidoTipoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel)
    {
        $this->pedidosPedidoTipoRel[] = $pedidosPedidoTipoRel;

        return $this;
    }

    /**
     * Remove pedidosPedidoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel
     */
    public function removePedidosPedidoTipoRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosPedidoTipoRel)
    {
        $this->pedidosPedidoTipoRel->removeElement($pedidosPedidoTipoRel);
    }

    /**
     * Get pedidosPedidoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosPedidoTipoRel()
    {
        return $this->pedidosPedidoTipoRel;
    }
}
