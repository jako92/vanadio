<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura_servicio")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaServicioRepository")
 */
class RhuFacturaServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaServicioPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                                    

    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva = 0; 
    
    /**
     * @ORM\Column(name="por_base_retencion_fuente", type="float")
     */
    private $porBaseRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="por_retencion_fuente", type="float")
     */
    private $porRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="tipo_retencion_fuente", type="integer")
     */
    private $tipo_retencion_fuente = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_cartera_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaCarteraFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaRetencionFuenteFk;  
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIvaFk;            
    
    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIngresoFk;           
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_devolucion_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIvaDevolucionFk;          
    
    /**
     * @ORM\Column(name="codigo_cuenta_ingreso_devolucion_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaIngresoDevolucionFk;        
        
    /**
     * @ORM\OneToMany(targetEntity="RhuFactura", mappedBy="facturaServicioRel")
     */
    protected $facturasFacturaServicioRel;       

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaServicioPk
     *
     * @return integer
     */
    public function getCodigoFacturaServicioPk()
    {
        return $this->codigoFacturaServicioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuFacturaServicio
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
     * Set porcentajeIva
     *
     * @param float $porcentajeIva
     *
     * @return RhuFacturaServicio
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return float
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set porBaseRetencionFuente
     *
     * @param float $porBaseRetencionFuente
     *
     * @return RhuFacturaServicio
     */
    public function setPorBaseRetencionFuente($porBaseRetencionFuente)
    {
        $this->porBaseRetencionFuente = $porBaseRetencionFuente;

        return $this;
    }

    /**
     * Get porBaseRetencionFuente
     *
     * @return float
     */
    public function getPorBaseRetencionFuente()
    {
        return $this->porBaseRetencionFuente;
    }

    /**
     * Set porRetencionFuente
     *
     * @param float $porRetencionFuente
     *
     * @return RhuFacturaServicio
     */
    public function setPorRetencionFuente($porRetencionFuente)
    {
        $this->porRetencionFuente = $porRetencionFuente;

        return $this;
    }

    /**
     * Get porRetencionFuente
     *
     * @return float
     */
    public function getPorRetencionFuente()
    {
        return $this->porRetencionFuente;
    }

    /**
     * Set tipoRetencionFuente
     *
     * @param integer $tipoRetencionFuente
     *
     * @return RhuFacturaServicio
     */
    public function setTipoRetencionFuente($tipoRetencionFuente)
    {
        $this->tipo_retencion_fuente = $tipoRetencionFuente;

        return $this;
    }

    /**
     * Get tipoRetencionFuente
     *
     * @return integer
     */
    public function getTipoRetencionFuente()
    {
        return $this->tipo_retencion_fuente;
    }

    /**
     * Set codigoCuentaCarteraFk
     *
     * @param string $codigoCuentaCarteraFk
     *
     * @return RhuFacturaServicio
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
     * Set codigoCuentaRetencionFuenteFk
     *
     * @param string $codigoCuentaRetencionFuenteFk
     *
     * @return RhuFacturaServicio
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
     * Set codigoCuentaIvaFk
     *
     * @param string $codigoCuentaIvaFk
     *
     * @return RhuFacturaServicio
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
     * @return RhuFacturaServicio
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
     * Set codigoCuentaIvaDevolucionFk
     *
     * @param string $codigoCuentaIvaDevolucionFk
     *
     * @return RhuFacturaServicio
     */
    public function setCodigoCuentaIvaDevolucionFk($codigoCuentaIvaDevolucionFk)
    {
        $this->codigoCuentaIvaDevolucionFk = $codigoCuentaIvaDevolucionFk;

        return $this;
    }

    /**
     * Get codigoCuentaIvaDevolucionFk
     *
     * @return string
     */
    public function getCodigoCuentaIvaDevolucionFk()
    {
        return $this->codigoCuentaIvaDevolucionFk;
    }

    /**
     * Set codigoCuentaIngresoDevolucionFk
     *
     * @param string $codigoCuentaIngresoDevolucionFk
     *
     * @return RhuFacturaServicio
     */
    public function setCodigoCuentaIngresoDevolucionFk($codigoCuentaIngresoDevolucionFk)
    {
        $this->codigoCuentaIngresoDevolucionFk = $codigoCuentaIngresoDevolucionFk;

        return $this;
    }

    /**
     * Get codigoCuentaIngresoDevolucionFk
     *
     * @return string
     */
    public function getCodigoCuentaIngresoDevolucionFk()
    {
        return $this->codigoCuentaIngresoDevolucionFk;
    }

    /**
     * Add facturasFacturaServicioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaServicioRel
     *
     * @return RhuFacturaServicio
     */
    public function addFacturasFacturaServicioRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaServicioRel)
    {
        $this->facturasFacturaServicioRel[] = $facturasFacturaServicioRel;

        return $this;
    }

    /**
     * Remove facturasFacturaServicioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaServicioRel
     */
    public function removeFacturasFacturaServicioRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasFacturaServicioRel)
    {
        $this->facturasFacturaServicioRel->removeElement($facturasFacturaServicioRel);
    }

    /**
     * Get facturasFacturaServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaServicioRel()
    {
        return $this->facturasFacturaServicioRel;
    }
}
