<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaTipoRepository")
 */
class RhuFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                
    
    /**
     * @ORM\Column(name="tipo", type="integer")
     */    
    private $tipo = 0;      

    /**
     * @ORM\Column(name="operacion", type="integer")
     */    
    private $operacion = 0;    
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */    
    private $consecutivo = 0; 
    
    /**
     * @ORM\Column(name="documento_cartera", type="integer", nullable=true)
     */    
    private $documentoCartera;     
    
    /**
     * @ORM\Column(name="abreviatura", type="string", length=10)
     */
    private $abreviatura;            
    
    /**
     * @ORM\Column(name="codigo_centro_costo_contabilidad", type="integer", nullable=true)
     */    
    private $codigoCentroCostoContabilidad;    
    
    /**
     * @ORM\Column(name="codigo_comprobante", type="integer", nullable=true)
     */    
    private $codigoComprobante;     
    
    /**
     * @ORM\Column(name="tipo_cuenta_cartera", type="bigint")
     */     
    private $tipoCuentaCartera = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_fuente", type="bigint")
     */     
    private $tipoCuentaRetencionFuente = 1;

    /**
     * @ORM\Column(name="tipo_cuenta_iva", type="bigint")
     */     
    private $tipoCuentaIva = 1; 

    /**
     * @ORM\Column(name="tipo_cuenta_ingreso", type="bigint")
     */     
    private $tipoCuentaIngreso = 1;         
    
    /**
     * @ORM\Column(name="codigo_documento_cartera", type="integer", nullable=true)
     */    
    private $codigoDocumentoCartera;     

    /**
     * @ORM\OneToMany(targetEntity="RhuFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaTipoPk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuFacturaTipo
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
     * @param integer $tipo
     *
     * @return RhuFacturaTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return RhuFacturaTipo
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
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return RhuFacturaTipo
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
     * Set documentoCartera
     *
     * @param integer $documentoCartera
     *
     * @return RhuFacturaTipo
     */
    public function setDocumentoCartera($documentoCartera)
    {
        $this->documentoCartera = $documentoCartera;

        return $this;
    }

    /**
     * Get documentoCartera
     *
     * @return integer
     */
    public function getDocumentoCartera()
    {
        return $this->documentoCartera;
    }

    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return RhuFacturaTipo
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set codigoCentroCostoContabilidad
     *
     * @param integer $codigoCentroCostoContabilidad
     *
     * @return RhuFacturaTipo
     */
    public function setCodigoCentroCostoContabilidad($codigoCentroCostoContabilidad)
    {
        $this->codigoCentroCostoContabilidad = $codigoCentroCostoContabilidad;

        return $this;
    }

    /**
     * Get codigoCentroCostoContabilidad
     *
     * @return integer
     */
    public function getCodigoCentroCostoContabilidad()
    {
        return $this->codigoCentroCostoContabilidad;
    }

    /**
     * Set codigoComprobante
     *
     * @param integer $codigoComprobante
     *
     * @return RhuFacturaTipo
     */
    public function setCodigoComprobante($codigoComprobante)
    {
        $this->codigoComprobante = $codigoComprobante;

        return $this;
    }

    /**
     * Get codigoComprobante
     *
     * @return integer
     */
    public function getCodigoComprobante()
    {
        return $this->codigoComprobante;
    }

    /**
     * Set tipoCuentaCartera
     *
     * @param integer $tipoCuentaCartera
     *
     * @return RhuFacturaTipo
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
     * Set tipoCuentaRetencionFuente
     *
     * @param integer $tipoCuentaRetencionFuente
     *
     * @return RhuFacturaTipo
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

    /**
     * Set tipoCuentaIva
     *
     * @param integer $tipoCuentaIva
     *
     * @return RhuFacturaTipo
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
     * @return RhuFacturaTipo
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
     * Set codigoDocumentoCartera
     *
     * @param integer $codigoDocumentoCartera
     *
     * @return RhuFacturaTipo
     */
    public function setCodigoDocumentoCartera($codigoDocumentoCartera)
    {
        $this->codigoDocumentoCartera = $codigoDocumentoCartera;

        return $this;
    }

    /**
     * Get codigoDocumentoCartera
     *
     * @return integer
     */
    public function getCodigoDocumentoCartera()
    {
        return $this->codigoDocumentoCartera;
    }

    /**
     * Add facturasFacturaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaTipoRel
     *
     * @return RhuFacturaTipo
     */
    public function addFacturasFacturaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel[] = $facturasFacturaTipoRel;

        return $this;
    }

    /**
     * Remove facturasFacturaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaTipoRel
     */
    public function removeFacturasFacturaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel->removeElement($facturasFacturaTipoRel);
    }

    /**
     * Get facturasFacturaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaTipoRel()
    {
        return $this->facturasFacturaTipoRel;
    }
}
