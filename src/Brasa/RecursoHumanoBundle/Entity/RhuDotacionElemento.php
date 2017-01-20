<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_elemento")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionElementoRepository")
 */
class RhuDotacionElemento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_elemento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionElementoPk; 
    
    /**
     * @ORM\Column(name="codigo_dotacion_elemento_tipo_fk", type="integer")
     */    
    private $codigoDotacionElementoTipoFk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_item_fk", type="integer", nullable=true)
     */    
    private $codigoItemFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacionElementoTipo", inversedBy="dotacionesElementosDotacionElementoTipoRel")
     * @ORM\JoinColumn(name="codigo_dotacion_elemento_tipo_fk", referencedColumnName="codigo_dotacion_elemento_tipo_pk")
     */
    protected $dotacionElementoTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionDetalle", mappedBy="dotacionElementoRel")
     */
    protected $elementosDotacionesDetalleDotacionElementoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionCargo", mappedBy="dotacionElementoRel")
     */
    protected $dotacionesCargosDotacionElementoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementosDotacionesDetalleDotacionElementoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dotacionesCargosDotacionElementoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDotacionElementoPk
     *
     * @return integer
     */
    public function getCodigoDotacionElementoPk()
    {
        return $this->codigoDotacionElementoPk;
    }

    /**
     * Set codigoDotacionElementoTipoFk
     *
     * @param integer $codigoDotacionElementoTipoFk
     *
     * @return RhuDotacionElemento
     */
    public function setCodigoDotacionElementoTipoFk($codigoDotacionElementoTipoFk)
    {
        $this->codigoDotacionElementoTipoFk = $codigoDotacionElementoTipoFk;

        return $this;
    }

    /**
     * Get codigoDotacionElementoTipoFk
     *
     * @return integer
     */
    public function getCodigoDotacionElementoTipoFk()
    {
        return $this->codigoDotacionElementoTipoFk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDotacionElemento
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
     * Set codigoItemFk
     *
     * @param integer $codigoItemFk
     *
     * @return RhuDotacionElemento
     */
    public function setCodigoItemFk($codigoItemFk)
    {
        $this->codigoItemFk = $codigoItemFk;

        return $this;
    }

    /**
     * Get codigoItemFk
     *
     * @return integer
     */
    public function getCodigoItemFk()
    {
        return $this->codigoItemFk;
    }

    /**
     * Set dotacionElementoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo $dotacionElementoTipoRel
     *
     * @return RhuDotacionElemento
     */
    public function setDotacionElementoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo $dotacionElementoTipoRel = null)
    {
        $this->dotacionElementoTipoRel = $dotacionElementoTipoRel;

        return $this;
    }

    /**
     * Get dotacionElementoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo
     */
    public function getDotacionElementoTipoRel()
    {
        return $this->dotacionElementoTipoRel;
    }

    /**
     * Add elementosDotacionesDetalleDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel
     *
     * @return RhuDotacionElemento
     */
    public function addElementosDotacionesDetalleDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel)
    {
        $this->elementosDotacionesDetalleDotacionElementoRel[] = $elementosDotacionesDetalleDotacionElementoRel;

        return $this;
    }

    /**
     * Remove elementosDotacionesDetalleDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel
     */
    public function removeElementosDotacionesDetalleDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel)
    {
        $this->elementosDotacionesDetalleDotacionElementoRel->removeElement($elementosDotacionesDetalleDotacionElementoRel);
    }

    /**
     * Get elementosDotacionesDetalleDotacionElementoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementosDotacionesDetalleDotacionElementoRel()
    {
        return $this->elementosDotacionesDetalleDotacionElementoRel;
    }

    /**
     * Add dotacionesCargosDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel
     *
     * @return RhuDotacionElemento
     */
    public function addDotacionesCargosDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel)
    {
        $this->dotacionesCargosDotacionElementoRel[] = $dotacionesCargosDotacionElementoRel;

        return $this;
    }

    /**
     * Remove dotacionesCargosDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel
     */
    public function removeDotacionesCargosDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel)
    {
        $this->dotacionesCargosDotacionElementoRel->removeElement($dotacionesCargosDotacionElementoRel);
    }

    /**
     * Get dotacionesCargosDotacionElementoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesCargosDotacionElementoRel()
    {
        return $this->dotacionesCargosDotacionElementoRel;
    }
}
