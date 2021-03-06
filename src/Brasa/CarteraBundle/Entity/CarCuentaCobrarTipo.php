<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cuenta_cobrar_tipo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarCuentaCobrarTipoRepository")
 */
class CarCuentaCobrarTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaCobrarTipoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="operacion", type="integer")
     */    
    private $operacion = 0;
    
    /**
     * @ORM\Column(name="saldo_inicial", type="boolean")
     */    
    private $saldoInicial = false;  
    
    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaClienteFk; 

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIvaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_ica_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionIcaFk;

    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaRetencionFuenteFk;   
    
    /**
     * @ORM\Column(name="codigo_cuenta_contribucion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaContribucionFk; 
    
    /**
     * @ORM\Column(name="codigo_cuenta_estampilla_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaEstampillaFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_ajuste_peso_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAjustePesoFk; 
    
    /**
     * @ORM\Column(name="codigo_cuenta_descuento_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDescuentoFk; 
    
    /**
     * @ORM\OneToMany(targetEntity="CarCuentaCobrar", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposCuentaCobrarRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposReciboDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarAnticipoDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposAnticipoDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebitoDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposNotaDebitoDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaCreditoDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposNotaCreditoDetalleRel;
    
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuentasCobrarTiposCuentaCobrarRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposReciboDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposAnticipoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposNotaDebitoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposNotaCreditoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCuentaCobrarTipoPk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarTipoPk()
    {
        return $this->codigoCuentaCobrarTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarCuentaCobrarTipo
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
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return CarCuentaCobrarTipo
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set saldoInicial
     *
     * @param boolean $saldoInicial
     *
     * @return CarCuentaCobrarTipo
     */
    public function setSaldoInicial($saldoInicial)
    {
        $this->saldoInicial = $saldoInicial;

        return $this;
    }

    /**
     * Get saldoInicial
     *
     * @return boolean
     */
    public function getSaldoInicial()
    {
        return $this->saldoInicial;
    }

    /**
     * Add cuentasCobrarTiposCuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel)
    {
        $this->cuentasCobrarTiposCuentaCobrarRel[] = $cuentasCobrarTiposCuentaCobrarRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposCuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel
     */
    public function removeCuentasCobrarTiposCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel)
    {
        $this->cuentasCobrarTiposCuentaCobrarRel->removeElement($cuentasCobrarTiposCuentaCobrarRel);
    }

    /**
     * Get cuentasCobrarTiposCuentaCobrarRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposCuentaCobrarRel()
    {
        return $this->cuentasCobrarTiposCuentaCobrarRel;
    }

    /**
     * Add cuentasCobrarTiposReciboDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposReciboDetalleRel(\Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel)
    {
        $this->cuentasCobrarTiposReciboDetalleRel[] = $cuentasCobrarTiposReciboDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposReciboDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel
     */
    public function removeCuentasCobrarTiposReciboDetalleRel(\Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel)
    {
        $this->cuentasCobrarTiposReciboDetalleRel->removeElement($cuentasCobrarTiposReciboDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposReciboDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposReciboDetalleRel()
    {
        return $this->cuentasCobrarTiposReciboDetalleRel;
    }

    /**
     * Add cuentasCobrarTiposAnticipoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposAnticipoDetalleRel(\Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel)
    {
        $this->cuentasCobrarTiposAnticipoDetalleRel[] = $cuentasCobrarTiposAnticipoDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposAnticipoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel
     */
    public function removeCuentasCobrarTiposAnticipoDetalleRel(\Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel)
    {
        $this->cuentasCobrarTiposAnticipoDetalleRel->removeElement($cuentasCobrarTiposAnticipoDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposAnticipoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposAnticipoDetalleRel()
    {
        return $this->cuentasCobrarTiposAnticipoDetalleRel;
    }

    /**
     * Add cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposNotaDebitoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaDebitoDetalleRel[] = $cuentasCobrarTiposNotaDebitoDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel
     */
    public function removeCuentasCobrarTiposNotaDebitoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaDebitoDetalleRel->removeElement($cuentasCobrarTiposNotaDebitoDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposNotaDebitoDetalleRel()
    {
        return $this->cuentasCobrarTiposNotaDebitoDetalleRel;
    }

    /**
     * Add cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposNotaCreditoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaCreditoDetalleRel[] = $cuentasCobrarTiposNotaCreditoDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel
     */
    public function removeCuentasCobrarTiposNotaCreditoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaCreditoDetalleRel->removeElement($cuentasCobrarTiposNotaCreditoDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposNotaCreditoDetalleRel()
    {
        return $this->cuentasCobrarTiposNotaCreditoDetalleRel;
    }

    /**
     * Set codigoCuentaClienteFk
     *
     * @param string $codigoCuentaClienteFk
     *
     * @return CarCuentaCobrarTipo
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
     * Set codigoCuentaRetencionIvaFk
     *
     * @param string $codigoCuentaRetencionIvaFk
     *
     * @return CarCuentaCobrarTipo
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
     * Set codigoCuentaRetencionIcaFk
     *
     * @param string $codigoCuentaRetencionIcaFk
     *
     * @return CarCuentaCobrarTipo
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
     * Set codigoCuentaRetencionFuenteFk
     *
     * @param string $codigoCuentaRetencionFuenteFk
     *
     * @return CarCuentaCobrarTipo
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
     * Set codigoCuentaContribucionFk
     *
     * @param string $codigoCuentaContribucionFk
     *
     * @return CarCuentaCobrarTipo
     */
    public function setCodigoCuentaContribucionFk($codigoCuentaContribucionFk)
    {
        $this->codigoCuentaContribucionFk = $codigoCuentaContribucionFk;

        return $this;
    }

    /**
     * Get codigoCuentaContribucionFk
     *
     * @return string
     */
    public function getCodigoCuentaContribucionFk()
    {
        return $this->codigoCuentaContribucionFk;
    }

    /**
     * Set codigoCuentaEstampillaFk
     *
     * @param string $codigoCuentaEstampillaFk
     *
     * @return CarCuentaCobrarTipo
     */
    public function setCodigoCuentaEstampillaFk($codigoCuentaEstampillaFk)
    {
        $this->codigoCuentaEstampillaFk = $codigoCuentaEstampillaFk;

        return $this;
    }

    /**
     * Get codigoCuentaEstampillaFk
     *
     * @return string
     */
    public function getCodigoCuentaEstampillaFk()
    {
        return $this->codigoCuentaEstampillaFk;
    }

    /**
     * Set codigoCuentaAjustePesoFk
     *
     * @param string $codigoCuentaAjustePesoFk
     *
     * @return CarCuentaCobrarTipo
     */
    public function setCodigoCuentaAjustePesoFk($codigoCuentaAjustePesoFk)
    {
        $this->codigoCuentaAjustePesoFk = $codigoCuentaAjustePesoFk;

        return $this;
    }

    /**
     * Get codigoCuentaAjustePesoFk
     *
     * @return string
     */
    public function getCodigoCuentaAjustePesoFk()
    {
        return $this->codigoCuentaAjustePesoFk;
    }

    /**
     * Set codigoCuentaDescuentoFk
     *
     * @param string $codigoCuentaDescuentoFk
     *
     * @return CarCuentaCobrarTipo
     */
    public function setCodigoCuentaDescuentoFk($codigoCuentaDescuentoFk)
    {
        $this->codigoCuentaDescuentoFk = $codigoCuentaDescuentoFk;

        return $this;
    }

    /**
     * Get codigoCuentaDescuentoFk
     *
     * @return string
     */
    public function getCodigoCuentaDescuentoFk()
    {
        return $this->codigoCuentaDescuentoFk;
    }
}
