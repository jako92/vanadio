<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_movimiento")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMovimientoRepository")
 */
class InvMovimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMovimientoPk;

    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer")
     */
    private $codigoDocumentoFk; 
    
    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoDocumentoTipoFk;               
    
    /**
     * @ORM\Column(name="codigo_documento_clase_fk", type="integer", nullable=true)
     */      
    private $codigoDocumentoClaseFk;      
    
    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="integer", nullable=true)
     */
    private $codigoFacturaTipoFk;     
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence;
    
    /**
     * @ORM\Column(name="fecha1", type="datetime", nullable=true)
     */    
    private $fecha1;    

    /**
     * @ORM\Column(name="fecha2", type="datetime", nullable=true)
     */    
    private $fecha2;    
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer", nullable=true)
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_direccion_fk", type="integer", nullable=true)
     */    
    private $codigoDireccionFk;        

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;    
    
    /**
     * @ORM\Column(name="codigo_area_fk", type="integer", nullable=true)
     */    
    private $codigoAreaFk;    
    
    /**
     * @ORM\Column(name="soporte", type="string", length=50, nullable=true)
     */    
    private $soporte;     
    
    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)
     */    
    private $vrIva;       
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;
    
    /**
     * @ORM\Column(name="vr_subtotal_operado", type="float")
     */
    private $vrSubtotalOperado = 0;
    
    /**
     * @ORM\Column(name="vr_descuento", type="float")
     */
    private $vrDescuento = 0;

    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $vrBruto = 0;        
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="vr_neto_pagar", type="float")
     */
    private $vrNetoPagar = 0;

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $vrRetencionFuente = 0;    

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float")
     */
    private $vrRetencionIva = 0;     
    
    /**
     * @ORM\Column(name="vr_retencion_cree", type="float")
     */
    private $vrRetencionCree = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_iva_ventas", type="float")
     */
    private $vrRetencionIvaVentas = 0;     
    
    /**
     * @ORM\Column(name="vr_otras_retenciones", type="float")
     */
    private $vrOtrasRetenciones = 0;    

    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;    
    
    /**
     * @ORM\Column(name="vr_descuento_financiero", type="float")
     */
    private $vrDescuentoFinanciero = 0;           

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;   
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;    

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = false;    
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;    

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */    
    private $estadoContabilizado = false;             
    
    /**
     * @ORM\Column(name="operacion_inventario", type="bigint")
     */    
    private $operacionInventario = 0;    

    /**
     * @ORM\Column(name="operacion_comercial", type="bigint")
     */    
    private $operacionComercial = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="InvDocumento", inversedBy="movimientosDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;   
    
    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentoTipo", inversedBy="movimientosDocumentoTipoRel")
     * @ORM\JoinColumn(name="codigo_documento_tipo_fk", referencedColumnName="codigo_documento_tipo_pk")
     */
    protected $documentoTipoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="movimientosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="invMovimientosFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="InvFacturaTipo", inversedBy="movimientosFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    protected $facturaTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvArea", inversedBy="movimientosAreaRel")
     * @ORM\JoinColumn(name="codigo_area_fk", referencedColumnName="codigo_area_pk")
     */
    protected $areaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDetalle", mappedBy="movimientoRel")
     */
    protected $movimientosDetallesMovimientoRel;    

    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDescuentoFinanciero", mappedBy="movimientoRel")
     */
    protected $movimientosDescuentosFinancierosMovimientoRel;     
    
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDetallesMovimientoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movimientosDescuentosFinancierosMovimientoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoMovimientoPk
     *
     * @return integer
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return InvMovimiento
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk)
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set codigoDocumentoTipoFk
     *
     * @param integer $codigoDocumentoTipoFk
     *
     * @return InvMovimiento
     */
    public function setCodigoDocumentoTipoFk($codigoDocumentoTipoFk)
    {
        $this->codigoDocumentoTipoFk = $codigoDocumentoTipoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoTipoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoTipoFk()
    {
        return $this->codigoDocumentoTipoFk;
    }

    /**
     * Set codigoDocumentoClaseFk
     *
     * @param integer $codigoDocumentoClaseFk
     *
     * @return InvMovimiento
     */
    public function setCodigoDocumentoClaseFk($codigoDocumentoClaseFk)
    {
        $this->codigoDocumentoClaseFk = $codigoDocumentoClaseFk;

        return $this;
    }

    /**
     * Get codigoDocumentoClaseFk
     *
     * @return integer
     */
    public function getCodigoDocumentoClaseFk()
    {
        return $this->codigoDocumentoClaseFk;
    }

    /**
     * Set codigoFacturaTipoFk
     *
     * @param integer $codigoFacturaTipoFk
     *
     * @return InvMovimiento
     */
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk)
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;

        return $this;
    }

    /**
     * Get codigoFacturaTipoFk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return InvMovimiento
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return InvMovimiento
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return InvMovimiento
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set fecha1
     *
     * @param \DateTime $fecha1
     *
     * @return InvMovimiento
     */
    public function setFecha1($fecha1)
    {
        $this->fecha1 = $fecha1;

        return $this;
    }

    /**
     * Get fecha1
     *
     * @return \DateTime
     */
    public function getFecha1()
    {
        return $this->fecha1;
    }

    /**
     * Set fecha2
     *
     * @param \DateTime $fecha2
     *
     * @return InvMovimiento
     */
    public function setFecha2($fecha2)
    {
        $this->fecha2 = $fecha2;

        return $this;
    }

    /**
     * Get fecha2
     *
     * @return \DateTime
     */
    public function getFecha2()
    {
        return $this->fecha2;
    }

    /**
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return InvMovimiento
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return InvMovimiento
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
     * Set codigoDireccionFk
     *
     * @param integer $codigoDireccionFk
     *
     * @return InvMovimiento
     */
    public function setCodigoDireccionFk($codigoDireccionFk)
    {
        $this->codigoDireccionFk = $codigoDireccionFk;

        return $this;
    }

    /**
     * Get codigoDireccionFk
     *
     * @return integer
     */
    public function getCodigoDireccionFk()
    {
        return $this->codigoDireccionFk;
    }

    /**
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return InvMovimiento
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set codigoAreaFk
     *
     * @param integer $codigoAreaFk
     *
     * @return InvMovimiento
     */
    public function setCodigoAreaFk($codigoAreaFk)
    {
        $this->codigoAreaFk = $codigoAreaFk;

        return $this;
    }

    /**
     * Get codigoAreaFk
     *
     * @return integer
     */
    public function getCodigoAreaFk()
    {
        return $this->codigoAreaFk;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return InvMovimiento
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
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return InvMovimiento
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
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return InvMovimiento
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
     * Set vrSubtotalOperado
     *
     * @param float $vrSubtotalOperado
     *
     * @return InvMovimiento
     */
    public function setVrSubtotalOperado($vrSubtotalOperado)
    {
        $this->vrSubtotalOperado = $vrSubtotalOperado;

        return $this;
    }

    /**
     * Get vrSubtotalOperado
     *
     * @return float
     */
    public function getVrSubtotalOperado()
    {
        return $this->vrSubtotalOperado;
    }

    /**
     * Set vrDescuento
     *
     * @param float $vrDescuento
     *
     * @return InvMovimiento
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
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return InvMovimiento
     */
    public function setVrBruto($vrBruto)
    {
        $this->vrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->vrBruto;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return InvMovimiento
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
     * Set vrNetoPagar
     *
     * @param float $vrNetoPagar
     *
     * @return InvMovimiento
     */
    public function setVrNetoPagar($vrNetoPagar)
    {
        $this->vrNetoPagar = $vrNetoPagar;

        return $this;
    }

    /**
     * Get vrNetoPagar
     *
     * @return float
     */
    public function getVrNetoPagar()
    {
        return $this->vrNetoPagar;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return InvMovimiento
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     *
     * @return InvMovimiento
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
     * Set vrRetencionIva
     *
     * @param float $vrRetencionIva
     *
     * @return InvMovimiento
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
     * Set vrRetencionCree
     *
     * @param float $vrRetencionCree
     *
     * @return InvMovimiento
     */
    public function setVrRetencionCree($vrRetencionCree)
    {
        $this->vrRetencionCree = $vrRetencionCree;

        return $this;
    }

    /**
     * Get vrRetencionCree
     *
     * @return float
     */
    public function getVrRetencionCree()
    {
        return $this->vrRetencionCree;
    }

    /**
     * Set vrRetencionIvaVentas
     *
     * @param float $vrRetencionIvaVentas
     *
     * @return InvMovimiento
     */
    public function setVrRetencionIvaVentas($vrRetencionIvaVentas)
    {
        $this->vrRetencionIvaVentas = $vrRetencionIvaVentas;

        return $this;
    }

    /**
     * Get vrRetencionIvaVentas
     *
     * @return float
     */
    public function getVrRetencionIvaVentas()
    {
        return $this->vrRetencionIvaVentas;
    }

    /**
     * Set vrOtrasRetenciones
     *
     * @param float $vrOtrasRetenciones
     *
     * @return InvMovimiento
     */
    public function setVrOtrasRetenciones($vrOtrasRetenciones)
    {
        $this->vrOtrasRetenciones = $vrOtrasRetenciones;

        return $this;
    }

    /**
     * Get vrOtrasRetenciones
     *
     * @return float
     */
    public function getVrOtrasRetenciones()
    {
        return $this->vrOtrasRetenciones;
    }

    /**
     * Set vrFlete
     *
     * @param float $vrFlete
     *
     * @return InvMovimiento
     */
    public function setVrFlete($vrFlete)
    {
        $this->vrFlete = $vrFlete;

        return $this;
    }

    /**
     * Get vrFlete
     *
     * @return float
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * Set vrDescuentoFinanciero
     *
     * @param float $vrDescuentoFinanciero
     *
     * @return InvMovimiento
     */
    public function setVrDescuentoFinanciero($vrDescuentoFinanciero)
    {
        $this->vrDescuentoFinanciero = $vrDescuentoFinanciero;

        return $this;
    }

    /**
     * Get vrDescuentoFinanciero
     *
     * @return float
     */
    public function getVrDescuentoFinanciero()
    {
        return $this->vrDescuentoFinanciero;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return InvMovimiento
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return InvMovimiento
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
     * @return InvMovimiento
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return InvMovimiento
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return InvMovimiento
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return InvMovimiento
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * Set operacionInventario
     *
     * @param integer $operacionInventario
     *
     * @return InvMovimiento
     */
    public function setOperacionInventario($operacionInventario)
    {
        $this->operacionInventario = $operacionInventario;

        return $this;
    }

    /**
     * Get operacionInventario
     *
     * @return integer
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentoRel
     *
     * @return InvMovimiento
     */
    public function setDocumentoRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * Set documentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentoTipo $documentoTipoRel
     *
     * @return InvMovimiento
     */
    public function setDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvDocumentoTipo $documentoTipoRel = null)
    {
        $this->documentoTipoRel = $documentoTipoRel;

        return $this;
    }

    /**
     * Get documentoTipoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumentoTipo
     */
    public function getDocumentoTipoRel()
    {
        return $this->documentoTipoRel;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvTercero $terceroRel
     *
     * @return InvMovimiento
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
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return InvMovimiento
     */
    public function setFormaPagoRel(\Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel = null)
    {
        $this->formaPagoRel = $formaPagoRel;

        return $this;
    }

    /**
     * Get formaPagoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormaPago
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * Set facturaTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvFacturaTipo $facturaTipoRel
     *
     * @return InvMovimiento
     */
    public function setFacturaTipoRel(\Brasa\InventarioBundle\Entity\InvFacturaTipo $facturaTipoRel = null)
    {
        $this->facturaTipoRel = $facturaTipoRel;

        return $this;
    }

    /**
     * Get facturaTipoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvFacturaTipo
     */
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * Set areaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvArea $areaRel
     *
     * @return InvMovimiento
     */
    public function setAreaRel(\Brasa\InventarioBundle\Entity\InvArea $areaRel = null)
    {
        $this->areaRel = $areaRel;

        return $this;
    }

    /**
     * Get areaRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvArea
     */
    public function getAreaRel()
    {
        return $this->areaRel;
    }

    /**
     * Add movimientosDetallesMovimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesMovimientoRel
     *
     * @return InvMovimiento
     */
    public function addMovimientosDetallesMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesMovimientoRel)
    {
        $this->movimientosDetallesMovimientoRel[] = $movimientosDetallesMovimientoRel;

        return $this;
    }

    /**
     * Remove movimientosDetallesMovimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesMovimientoRel
     */
    public function removeMovimientosDetallesMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientoDetalle $movimientosDetallesMovimientoRel)
    {
        $this->movimientosDetallesMovimientoRel->removeElement($movimientosDetallesMovimientoRel);
    }

    /**
     * Get movimientosDetallesMovimientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDetallesMovimientoRel()
    {
        return $this->movimientosDetallesMovimientoRel;
    }

    /**
     * Add movimientosDescuentosFinancierosMovimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosMovimientoRel
     *
     * @return InvMovimiento
     */
    public function addMovimientosDescuentosFinancierosMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosMovimientoRel)
    {
        $this->movimientosDescuentosFinancierosMovimientoRel[] = $movimientosDescuentosFinancierosMovimientoRel;

        return $this;
    }

    /**
     * Remove movimientosDescuentosFinancierosMovimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosMovimientoRel
     */
    public function removeMovimientosDescuentosFinancierosMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosMovimientoRel)
    {
        $this->movimientosDescuentosFinancierosMovimientoRel->removeElement($movimientosDescuentosFinancierosMovimientoRel);
    }

    /**
     * Get movimientosDescuentosFinancierosMovimientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDescuentosFinancierosMovimientoRel()
    {
        return $this->movimientosDescuentosFinancierosMovimientoRel;
    }

    /**
     * Set operacionComercial
     *
     * @param integer $operacionComercial
     *
     * @return InvMovimiento
     */
    public function setOperacionComercial($operacionComercial)
    {
        $this->operacionComercial = $operacionComercial;

        return $this;
    }

    /**
     * Get operacionComercial
     *
     * @return integer
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }
}
