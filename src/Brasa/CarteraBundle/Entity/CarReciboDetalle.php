<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_recibo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarReciboDetalleRepository")
 */
class CarReciboDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboDetallePk;           
    
    /**
     * @ORM\Column(name="codigo_recibo_fk", type="integer", nullable=true)
     */     
    private $codigoReciboFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_aplicacion_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarAplicacionFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCuentaCobrarTipoFk;
    
    /**
     * @ORM\Column(name="numero_factura", type="integer", nullable=true)
     */     
    private $numeroFactura;

    /**
     * @ORM\Column(name="numero_documento_aplicacion", type="integer", nullable=true)
     */     
    private $numeroDocumentoAplicacion;    
    
    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */    
    private $vrDescuento = 0;
    
    /**
     * @ORM\Column(name="vr_ajuste_peso", type="float")
     */    
    private $vrAjustePeso = 0;
    
    /**
     * @ORM\Column(name="vr_retencion_ica", type="float")
     */    
    private $vrRetencionIca = 0;
    
     /**
     * @ORM\Column(name="vr_retencion_iva", type="float")
     */    
    private $vrRetencionIva = 0;
    
    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */    
    private $vrRetencionFuente = 0;
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */    
    private $vrPago = 0;   
    
    /**
     * @ORM\Column(name="vr_pago_afectar", type="float")
     */    
    private $vrPagoAfectar = 0;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**
     * @ORM\Column(name="operacion", type="integer")
     */    
    private $operacion = 0;    
    
    /**
     * @ORM\Column(name="vr_contribucion", type="float")
     */    
    private $vrContribucion = 0;
    
    /**
     * @ORM\Column(name="vr_estampilla", type="float")
     */    
    private $vrEstampilla = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarRecibo", inversedBy="recibosDetallesRecibosRel")
     * @ORM\JoinColumn(name="codigo_recibo_fk", referencedColumnName="codigo_recibo_pk")
     */
    protected $reciboRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="recibosDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="recibosDetallesCuentaCobrarAplicacionRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_aplicacion_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarAplicacionRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrarTipo", inversedBy="cuentasCobrarTiposReciboDetalleRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;
   

    /**
     * Get codigoReciboDetallePk
     *
     * @return integer
     */
    public function getCodigoReciboDetallePk()
    {
        return $this->codigoReciboDetallePk;
    }

    /**
     * Set codigoReciboFk
     *
     * @param integer $codigoReciboFk
     *
     * @return CarReciboDetalle
     */
    public function setCodigoReciboFk($codigoReciboFk)
    {
        $this->codigoReciboFk = $codigoReciboFk;

        return $this;
    }

    /**
     * Get codigoReciboFk
     *
     * @return integer
     */
    public function getCodigoReciboFk()
    {
        return $this->codigoReciboFk;
    }

    /**
     * Set codigoCuentaCobrarFk
     *
     * @param integer $codigoCuentaCobrarFk
     *
     * @return CarReciboDetalle
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk)
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;

        return $this;
    }

    /**
     * Get codigoCuentaCobrarFk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * Set codigoCuentaCobrarAplicacionFk
     *
     * @param integer $codigoCuentaCobrarAplicacionFk
     *
     * @return CarReciboDetalle
     */
    public function setCodigoCuentaCobrarAplicacionFk($codigoCuentaCobrarAplicacionFk)
    {
        $this->codigoCuentaCobrarAplicacionFk = $codigoCuentaCobrarAplicacionFk;

        return $this;
    }

    /**
     * Get codigoCuentaCobrarAplicacionFk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarAplicacionFk()
    {
        return $this->codigoCuentaCobrarAplicacionFk;
    }

    /**
     * Set codigoCuentaCobrarTipoFk
     *
     * @param integer $codigoCuentaCobrarTipoFk
     *
     * @return CarReciboDetalle
     */
    public function setCodigoCuentaCobrarTipoFk($codigoCuentaCobrarTipoFk)
    {
        $this->codigoCuentaCobrarTipoFk = $codigoCuentaCobrarTipoFk;

        return $this;
    }

    /**
     * Get codigoCuentaCobrarTipoFk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarTipoFk()
    {
        return $this->codigoCuentaCobrarTipoFk;
    }

    /**
     * Set numeroFactura
     *
     * @param integer $numeroFactura
     *
     * @return CarReciboDetalle
     */
    public function setNumeroFactura($numeroFactura)
    {
        $this->numeroFactura = $numeroFactura;

        return $this;
    }

    /**
     * Get numeroFactura
     *
     * @return integer
     */
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * Set numeroDocumentoAplicacion
     *
     * @param integer $numeroDocumentoAplicacion
     *
     * @return CarReciboDetalle
     */
    public function setNumeroDocumentoAplicacion($numeroDocumentoAplicacion)
    {
        $this->numeroDocumentoAplicacion = $numeroDocumentoAplicacion;

        return $this;
    }

    /**
     * Get numeroDocumentoAplicacion
     *
     * @return integer
     */
    public function getNumeroDocumentoAplicacion()
    {
        return $this->numeroDocumentoAplicacion;
    }

    /**
     * Set vrDescuento
     *
     * @param float $vrDescuento
     *
     * @return CarReciboDetalle
     */
    public function setVrDescuento($vrDescuento)
    {
        $this->vrDescuento = $vrDescuento;

        return $this;
    }

    /**
     * Get vrDescuento
     *
     * @return float
     */
    public function getVrDescuento()
    {
        return $this->vrDescuento;
    }

    /**
     * Set vrAjustePeso
     *
     * @param float $vrAjustePeso
     *
     * @return CarReciboDetalle
     */
    public function setVrAjustePeso($vrAjustePeso)
    {
        $this->vrAjustePeso = $vrAjustePeso;

        return $this;
    }

    /**
     * Get vrAjustePeso
     *
     * @return float
     */
    public function getVrAjustePeso()
    {
        return $this->vrAjustePeso;
    }

    /**
     * Set vrRetencionIca
     *
     * @param float $vrRetencionIca
     *
     * @return CarReciboDetalle
     */
    public function setVrRetencionIca($vrRetencionIca)
    {
        $this->vrRetencionIca = $vrRetencionIca;

        return $this;
    }

    /**
     * Get vrRetencionIca
     *
     * @return float
     */
    public function getVrRetencionIca()
    {
        return $this->vrRetencionIca;
    }

    /**
     * Set vrRetencionIva
     *
     * @param float $vrRetencionIva
     *
     * @return CarReciboDetalle
     */
    public function setVrRetencionIva($vrRetencionIva)
    {
        $this->vrRetencionIva = $vrRetencionIva;

        return $this;
    }

    /**
     * Get vrRetencionIva
     *
     * @return float
     */
    public function getVrRetencionIva()
    {
        return $this->vrRetencionIva;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     *
     * @return CarReciboDetalle
     */
    public function setVrRetencionFuente($vrRetencionFuente)
    {
        $this->vrRetencionFuente = $vrRetencionFuente;

        return $this;
    }

    /**
     * Get vrRetencionFuente
     *
     * @return float
     */
    public function getVrRetencionFuente()
    {
        return $this->vrRetencionFuente;
    }

    /**
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return CarReciboDetalle
     */
    public function setVrPago($vrPago)
    {
        $this->vrPago = $vrPago;

        return $this;
    }

    /**
     * Get vrPago
     *
     * @return float
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * Set vrPagoAfectar
     *
     * @param float $vrPagoAfectar
     *
     * @return CarReciboDetalle
     */
    public function setVrPagoAfectar($vrPagoAfectar)
    {
        $this->vrPagoAfectar = $vrPagoAfectar;

        return $this;
    }

    /**
     * Get vrPagoAfectar
     *
     * @return float
     */
    public function getVrPagoAfectar()
    {
        return $this->vrPagoAfectar;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return CarReciboDetalle
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set reciboRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $reciboRel
     *
     * @return CarReciboDetalle
     */
    public function setReciboRel(\Brasa\CarteraBundle\Entity\CarRecibo $reciboRel = null)
    {
        $this->reciboRel = $reciboRel;

        return $this;
    }

    /**
     * Get reciboRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarRecibo
     */
    public function getReciboRel()
    {
        return $this->reciboRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel
     *
     * @return CarReciboDetalle
     */
    public function setCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel = null)
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;

        return $this;
    }

    /**
     * Get cuentaCobrarRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrar
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * Set cuentaCobrarAplicacionRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarAplicacionRel
     *
     * @return CarReciboDetalle
     */
    public function setCuentaCobrarAplicacionRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarAplicacionRel = null)
    {
        $this->cuentaCobrarAplicacionRel = $cuentaCobrarAplicacionRel;

        return $this;
    }

    /**
     * Get cuentaCobrarAplicacionRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrar
     */
    public function getCuentaCobrarAplicacionRel()
    {
        return $this->cuentaCobrarAplicacionRel;
    }

    /**
     * Set cuentaCobrarTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo $cuentaCobrarTipoRel
     *
     * @return CarReciboDetalle
     */
    public function setCuentaCobrarTipoRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo $cuentaCobrarTipoRel = null)
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;

        return $this;
    }

    /**
     * Get cuentaCobrarTipoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }

    /**
     * Set vrPagoOperado
     *
     * @param float $vrPagoOperado
     *
     * @return CarReciboDetalle
     */
    public function setVrPagoOperado($vrPagoOperado)
    {
        $this->vrPagoOperado = $vrPagoOperado;

        return $this;
    }

    /**
     * Get vrPagoOperado
     *
     * @return float
     */
    public function getVrPagoOperado()
    {
        return $this->vrPagoOperado;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return CarReciboDetalle
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
     * Set vrContribucion
     *
     * @param float $vrContribucion
     *
     * @return CarReciboDetalle
     */
    public function setVrContribucion($vrContribucion)
    {
        $this->vrContribucion = $vrContribucion;

        return $this;
    }

    /**
     * Get vrContribucion
     *
     * @return float
     */
    public function getVrContribucion()
    {
        return $this->vrContribucion;
    }

    /**
     * Set vrEstampilla
     *
     * @param float $vrEstampilla
     *
     * @return CarReciboDetalle
     */
    public function setVrEstampilla($vrEstampilla)
    {
        $this->vrEstampilla = $vrEstampilla;

        return $this;
    }

    /**
     * Get vrEstampilla
     *
     * @return float
     */
    public function getVrEstampilla()
    {
        return $this->vrEstampilla;
    }
}
