<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_recibo_tipo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarReciboTipoRepository")
 */
class CarReciboTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboTipoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    
    /**
     * @ORM\OneToMany(targetEntity="CarRecibo", mappedBy="reciboTipoRel")
     */
    protected $recibosReciboTipoRel;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;
    
    /**
     * @ORM\Column(name="tipo_cuenta_cliente", type="bigint")
     */
    private $tipoCuentaCliente = 1;
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIvaFk;
    
    /**
     * @ORM\Column(name="tipo_cuenta_retencion_iva", type="bigint")
     */
    private $tipoCuentaRetencionIva = 1;
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_ica_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIcaFk;
    
    /**
     * @ORM\Column(name="tipo_cuenta_retencion_ica", type="bigint")
     */
    private $tipoCuentaRetencionIca = 1;
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionFuenteFk;
    
    /**
     * @ORM\Column(name="tipo_cuenta_retencion_fuente", type="bigint")
     */
    private $tipoCuentaRetencionFuente = 1;
       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recibosReciboTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoReciboTipoPk
     *
     * @return integer
     */
    public function getCodigoReciboTipoPk()
    {
        return $this->codigoReciboTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarReciboTipo
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
     * Add recibosReciboTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel
     *
     * @return CarReciboTipo
     */
    public function addRecibosReciboTipoRel(\Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel)
    {
        $this->recibosReciboTipoRel[] = $recibosReciboTipoRel;

        return $this;
    }

    /**
     * Remove recibosReciboTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel
     */
    public function removeRecibosReciboTipoRel(\Brasa\CarteraBundle\Entity\CarRecibo $recibosReciboTipoRel)
    {
        $this->recibosReciboTipoRel->removeElement($recibosReciboTipoRel);
    }

    /**
     * Get recibosReciboTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecibosReciboTipoRel()
    {
        return $this->recibosReciboTipoRel;
    }

    /**
     * Set codigoCuentaClienteFk
     *
     * @param string $codigoCuentaClienteFk
     *
     * @return CarReciboTipo
     */
    public function setCodigoCuentaClienteFk($codigoCuentaClienteFk)
    {
        $this->codigoCuentaClienteFk = $codigoCuentaClienteFk;

        return $this;
    }

    /**
     * Get codigoCuentaClienteFk
     *
     * @return string
     */
    public function getCodigoCuentaClienteFk()
    {
        return $this->codigoCuentaClienteFk;
    }

    /**
     * Set tipoCuentaCliente
     *
     * @param integer $tipoCuentaCliente
     *
     * @return CarReciboTipo
     */
    public function setTipoCuentaCliente($tipoCuentaCliente)
    {
        $this->tipoCuentaCliente = $tipoCuentaCliente;

        return $this;
    }

    /**
     * Get tipoCuentaCliente
     *
     * @return integer
     */
    public function getTipoCuentaCliente()
    {
        return $this->tipoCuentaCliente;
    }

    /**
     * Set codigoCuentaRetencionIvaFk
     *
     * @param string $codigoCuentaRetencionIvaFk
     *
     * @return CarReciboTipo
     */
    public function setCodigoCuentaRetencionIvaFk($codigoCuentaRetencionIvaFk)
    {
        $this->codigoCuentaRetencionIvaFk = $codigoCuentaRetencionIvaFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionIvaFk
     *
     * @return string
     */
    public function getCodigoCuentaRetencionIvaFk()
    {
        return $this->codigoCuentaRetencionIvaFk;
    }

    /**
     * Set tipoCuentaRetencionIva
     *
     * @param integer $tipoCuentaRetencionIva
     *
     * @return CarReciboTipo
     */
    public function setTipoCuentaRetencionIva($tipoCuentaRetencionIva)
    {
        $this->tipoCuentaRetencionIva = $tipoCuentaRetencionIva;

        return $this;
    }

    /**
     * Get tipoCuentaRetencionIva
     *
     * @return integer
     */
    public function getTipoCuentaRetencionIva()
    {
        return $this->tipoCuentaRetencionIva;
    }

    /**
     * Set codigoCuentaRetencionIcaFk
     *
     * @param string $codigoCuentaRetencionIcaFk
     *
     * @return CarReciboTipo
     */
    public function setCodigoCuentaRetencionIcaFk($codigoCuentaRetencionIcaFk)
    {
        $this->codigoCuentaRetencionIcaFk = $codigoCuentaRetencionIcaFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionIcaFk
     *
     * @return string
     */
    public function getCodigoCuentaRetencionIcaFk()
    {
        return $this->codigoCuentaRetencionIcaFk;
    }

    /**
     * Set tipoCuentaRetencionIca
     *
     * @param integer $tipoCuentaRetencionIca
     *
     * @return CarReciboTipo
     */
    public function setTipoCuentaRetencionIca($tipoCuentaRetencionIca)
    {
        $this->tipoCuentaRetencionIca = $tipoCuentaRetencionIca;

        return $this;
    }

    /**
     * Get tipoCuentaRetencionIca
     *
     * @return integer
     */
    public function getTipoCuentaRetencionIca()
    {
        return $this->tipoCuentaRetencionIca;
    }

    /**
     * Set codigoCuentaRetencionFuenteFk
     *
     * @param string $codigoCuentaRetencionFuenteFk
     *
     * @return CarReciboTipo
     */
    public function setCodigoCuentaRetencionFuenteFk($codigoCuentaRetencionFuenteFk)
    {
        $this->codigoCuentaRetencionFuenteFk = $codigoCuentaRetencionFuenteFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionFuenteFk
     *
     * @return string
     */
    public function getCodigoCuentaRetencionFuenteFk()
    {
        return $this->codigoCuentaRetencionFuenteFk;
    }

    /**
     * Set tipoCuentaRetencionFuente
     *
     * @param integer $tipoCuentaRetencionFuente
     *
     * @return CarReciboTipo
     */
    public function setTipoCuentaRetencionFuente($tipoCuentaRetencionFuente)
    {
        $this->tipoCuentaRetencionFuente = $tipoCuentaRetencionFuente;

        return $this;
    }

    /**
     * Get tipoCuentaRetencionFuente
     *
     * @return integer
     */
    public function getTipoCuentaRetencionFuente()
    {
        return $this->tipoCuentaRetencionFuente;
    }
}
