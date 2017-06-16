<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_requisito_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRequisitoTipoRepository")
 */
class RhuRequisitoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoTipoPk;                  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuRequisito", mappedBy="requisitoTipoRel")
     */
    protected $requisitosRequisitoTipoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requisitosRequisitoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRequisitoTipoPk
     *
     * @return integer
     */
    public function getCodigoRequisitoTipoPk()
    {
        return $this->codigoRequisitoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuRequisitoTipo
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
     * Add requisitosRequisitoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosRequisitoTipoRel
     *
     * @return RhuRequisitoTipo
     */
    public function addRequisitosRequisitoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosRequisitoTipoRel)
    {
        $this->requisitosRequisitoTipoRel[] = $requisitosRequisitoTipoRel;

        return $this;
    }

    /**
     * Remove requisitosRequisitoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosRequisitoTipoRel
     */
    public function removeRequisitosRequisitoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosRequisitoTipoRel)
    {
        $this->requisitosRequisitoTipoRel->removeElement($requisitosRequisitoTipoRel);
    }

    /**
     * Get requisitosRequisitoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosRequisitoTipoRel()
    {
        return $this->requisitosRequisitoTipoRel;
    }
}
