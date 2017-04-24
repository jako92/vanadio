<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaDetalleRepository")
 */
class RhuFacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_factura_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaConceptoFk;    
    
    /**
     * @ORM\Column(name="codigo_cobro_fk", type="integer", nullable=true)
     */    
    private $codigoCobroFk;            
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;    
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;    
    
    /**
     * @ORM\Column(name="vr_subtotal_operado", type="float")
     */
    private $vrSubtotalOperado = 0;    
    
    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva = 0;    
    
    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;    

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;                        
    
    /**
     * @ORM\Column(name="vr_administracion", type="float")
     */
    private $vrAdministracion = 0;     
    
    /**
     * @ORM\Column(name="vr_operacion", type="float")
     */
    private $vrOperacion = 0;     
    
    /**
     * @ORM\Column(name="por_base_iva", type="integer")
     */
    private $porBaseIva = 0; 
    
    /**
     * @ORM\Column(name="por_iva", type="float")
     */
    private $porIva = 0;     
    
    /**
     * @ORM\Column(name="vr_base_iva", type="integer")
     */
    private $vrBaseIva = 0;    
    
    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;      
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCobro", inversedBy="facturasDetallesCobroRel")
     * @ORM\JoinColumn(name="codigo_cobro_fk", referencedColumnName="codigo_cobro_pk")
     */
    protected $cobroRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuFacturaConcepto", inversedBy="facturasDetallesFacturaConceptoRel")
     * @ORM\JoinColumn(name="codigo_factura_concepto_fk", referencedColumnName="codigo_factura_concepto_pk")
     */
    protected $facturaConceptoRel; 



    /**
     * Get codigoFacturaDetallePk
     *
     * @return integer
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoFacturaConceptoFk
     *
     * @param integer $codigoFacturaConceptoFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoFacturaConceptoFk($codigoFacturaConceptoFk)
    {
        $this->codigoFacturaConceptoFk = $codigoFacturaConceptoFk;

        return $this;
    }

    /**
     * Get codigoFacturaConceptoFk
     *
     * @return integer
     */
    public function getCodigoFacturaConceptoFk()
    {
        return $this->codigoFacturaConceptoFk;
    }

    /**
     * Set codigoCobroFk
     *
     * @param integer $codigoCobroFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoCobroFk($codigoCobroFk)
    {
        $this->codigoCobroFk = $codigoCobroFk;

        return $this;
    }

    /**
     * Get codigoCobroFk
     *
     * @return integer
     */
    public function getCodigoCobroFk()
    {
        return $this->codigoCobroFk;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return RhuFacturaDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return RhuFacturaDetalle
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return RhuFacturaDetalle
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
     * @return RhuFacturaDetalle
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
     * Set porcentajeIva
     *
     * @param float $porcentajeIva
     *
     * @return RhuFacturaDetalle
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
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return RhuFacturaDetalle
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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuFacturaDetalle
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set vrAdministracion
     *
     * @param float $vrAdministracion
     *
     * @return RhuFacturaDetalle
     */
    public function setVrAdministracion($vrAdministracion)
    {
        $this->vrAdministracion = $vrAdministracion;

        return $this;
    }

    /**
     * Get vrAdministracion
     *
     * @return float
     */
    public function getVrAdministracion()
    {
        return $this->vrAdministracion;
    }

    /**
     * Set vrOperacion
     *
     * @param float $vrOperacion
     *
     * @return RhuFacturaDetalle
     */
    public function setVrOperacion($vrOperacion)
    {
        $this->vrOperacion = $vrOperacion;

        return $this;
    }

    /**
     * Get vrOperacion
     *
     * @return float
     */
    public function getVrOperacion()
    {
        return $this->vrOperacion;
    }

    /**
     * Set porBaseIva
     *
     * @param integer $porBaseIva
     *
     * @return RhuFacturaDetalle
     */
    public function setPorBaseIva($porBaseIva)
    {
        $this->porBaseIva = $porBaseIva;

        return $this;
    }

    /**
     * Get porBaseIva
     *
     * @return integer
     */
    public function getPorBaseIva()
    {
        return $this->porBaseIva;
    }

    /**
     * Set porIva
     *
     * @param float $porIva
     *
     * @return RhuFacturaDetalle
     */
    public function setPorIva($porIva)
    {
        $this->porIva = $porIva;

        return $this;
    }

    /**
     * Get porIva
     *
     * @return float
     */
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * Set vrBaseIva
     *
     * @param integer $vrBaseIva
     *
     * @return RhuFacturaDetalle
     */
    public function setVrBaseIva($vrBaseIva)
    {
        $this->vrBaseIva = $vrBaseIva;

        return $this;
    }

    /**
     * Get vrBaseIva
     *
     * @return integer
     */
    public function getVrBaseIva()
    {
        return $this->vrBaseIva;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return RhuFacturaDetalle
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
     * Set facturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel
     *
     * @return RhuFacturaDetalle
     */
    public function setFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set cobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobroRel
     *
     * @return RhuFacturaDetalle
     */
    public function setCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobroRel = null)
    {
        $this->cobroRel = $cobroRel;

        return $this;
    }

    /**
     * Get cobroRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCobro
     */
    public function getCobroRel()
    {
        return $this->cobroRel;
    }

    /**
     * Set facturaConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaConcepto $facturaConceptoRel
     *
     * @return RhuFacturaDetalle
     */
    public function setFacturaConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaConcepto $facturaConceptoRel = null)
    {
        $this->facturaConceptoRel = $facturaConceptoRel;

        return $this;
    }

    /**
     * Get facturaConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFacturaConcepto
     */
    public function getFacturaConceptoRel()
    {
        return $this->facturaConceptoRel;
    }
}
