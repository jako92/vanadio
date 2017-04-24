<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaConceptoRepository")
 */
class RhuFacturaConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaConceptoPk;                      
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;                                

    /**
     * @ORM\Column(name="por_base_iva", type="integer")
     */
    private $porBaseIva = 0; 
    
    /**
     * @ORM\Column(name="por_iva", type="integer")
     */
    private $porIva = 0;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="facturaConceptoRel")
     */
    protected $facturasDetallesFacturaConceptoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesFacturaConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaConceptoPk
     *
     * @return integer
     */
    public function getCodigoFacturaConceptoPk()
    {
        return $this->codigoFacturaConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuFacturaConcepto
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
     * Set porBaseIva
     *
     * @param integer $porBaseIva
     *
     * @return RhuFacturaConcepto
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
     * @param integer $porIva
     *
     * @return RhuFacturaConcepto
     */
    public function setPorIva($porIva)
    {
        $this->porIva = $porIva;

        return $this;
    }

    /**
     * Get porIva
     *
     * @return integer
     */
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * Add facturasDetallesFacturaConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaConceptoRel
     *
     * @return RhuFacturaConcepto
     */
    public function addFacturasDetallesFacturaConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaConceptoRel)
    {
        $this->facturasDetallesFacturaConceptoRel[] = $facturasDetallesFacturaConceptoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaConceptoRel
     */
    public function removeFacturasDetallesFacturaConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaConceptoRel)
    {
        $this->facturasDetallesFacturaConceptoRel->removeElement($facturasDetallesFacturaConceptoRel);
    }

    /**
     * Get facturasDetallesFacturaConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesFacturaConceptoRel()
    {
        return $this->facturasDetallesFacturaConceptoRel;
    }
}
