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
     * @ORM\Column(name="codigo_cobro_fk", type="integer", nullable=true)
     */    
    private $codigoCobroFk;    
    
    /**
     * @ORM\Column(name="codigo_servicio_cobrar_fk", type="integer", nullable=true)
     */    
    private $codigoServicioCobrarFk;     
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;         
    
    /**
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */    
    private $estadoCobrado = 0;     
    
    
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
     * Set codigoServicioCobrarFk
     *
     * @param integer $codigoServicioCobrarFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoServicioCobrarFk($codigoServicioCobrarFk)
    {
        $this->codigoServicioCobrarFk = $codigoServicioCobrarFk;

        return $this;
    }

    /**
     * Get codigoServicioCobrarFk
     *
     * @return integer
     */
    public function getCodigoServicioCobrarFk()
    {
        return $this->codigoServicioCobrarFk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuFacturaDetalle
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuFacturaDetalle
     */
    public function setEstadoCobrado($estadoCobrado)
    {
        $this->estadoCobrado = $estadoCobrado;

        return $this;
    }

    /**
     * Get estadoCobrado
     *
     * @return boolean
     */
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
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
}
