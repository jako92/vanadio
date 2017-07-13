<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_reclamo_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuReclamoConceptoRepository")
 */
class RhuReclamoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_reclamo_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoReclamoConceptoPk;                    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;        
    
    /**
     * @ORM\OneToMany(targetEntity="RhuReclamo", mappedBy="reclamoConceptoRel")
     */
    protected $reclamosReclamoConceptoRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reclamosReclamoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoReclamoConceptoPk
     *
     * @return integer
     */
    public function getCodigoReclamoConceptoPk()
    {
        return $this->codigoReclamoConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuReclamoConcepto
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
     * Add reclamosReclamoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosReclamoConceptoRel
     *
     * @return RhuReclamoConcepto
     */
    public function addReclamosReclamoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosReclamoConceptoRel)
    {
        $this->reclamosReclamoConceptoRel[] = $reclamosReclamoConceptoRel;

        return $this;
    }

    /**
     * Remove reclamosReclamoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosReclamoConceptoRel
     */
    public function removeReclamosReclamoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuReclamo $reclamosReclamoConceptoRel)
    {
        $this->reclamosReclamoConceptoRel->removeElement($reclamosReclamoConceptoRel);
    }

    /**
     * Get reclamosReclamoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReclamosReclamoConceptoRel()
    {
        return $this->reclamosReclamoConceptoRel;
    }
}
