<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_adicion_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoAdicionTipoRepository")
 */
class RhuContratoAdicionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_adicion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoAdicionTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=100, nullable=true)
     */    
    private $nombreCorto;     
    
    /**
     * @ORM\Column(name="codigo_contenido_formato_fk", type="integer", nullable=true)
     */    
    private $codigoContenidoFormatoFk;
    
    /**
     * @ORM\Column(name="codigo_contrato_clase_fk", type="integer", nullable=true)
     */    
    private $codigoContratoClaseFk;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContratoAdicion", mappedBy="contratoAdicionTipoRel")
     */
    protected $contratosAdicionesContratoAdicionTipoRel;               
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenContenidoFormato", inversedBy="contratosAdicionesTiposContenidoFormatoRel")
     * @ORM\JoinColumn(name="codigo_contenido_formato_fk", referencedColumnName="codigo_contenido_formato_pk")
     */
    protected $contenidoFormatoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoClase", inversedBy="contratosAdicionesTiposContratoClaseRel")
     * @ORM\JoinColumn(name="codigo_contrato_clase_fk", referencedColumnName="codigo_contrato_clase_pk")
     */
    protected $contratoClaseRel;    
                   
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosAdicionesContratoAdicionTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoAdicionTipoPk
     *
     * @return integer
     */
    public function getCodigoContratoAdicionTipoPk()
    {
        return $this->codigoContratoAdicionTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuContratoAdicionTipo
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuContratoAdicionTipo
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set codigoContenidoFormatoFk
     *
     * @param integer $codigoContenidoFormatoFk
     *
     * @return RhuContratoAdicionTipo
     */
    public function setCodigoContenidoFormatoFk($codigoContenidoFormatoFk)
    {
        $this->codigoContenidoFormatoFk = $codigoContenidoFormatoFk;

        return $this;
    }

    /**
     * Get codigoContenidoFormatoFk
     *
     * @return integer
     */
    public function getCodigoContenidoFormatoFk()
    {
        return $this->codigoContenidoFormatoFk;
    }

    /**
     * Set codigoContratoClaseFk
     *
     * @param integer $codigoContratoClaseFk
     *
     * @return RhuContratoAdicionTipo
     */
    public function setCodigoContratoClaseFk($codigoContratoClaseFk)
    {
        $this->codigoContratoClaseFk = $codigoContratoClaseFk;

        return $this;
    }

    /**
     * Get codigoContratoClaseFk
     *
     * @return integer
     */
    public function getCodigoContratoClaseFk()
    {
        return $this->codigoContratoClaseFk;
    }

    /**
     * Add contratosAdicionesContratoAdicionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicion $contratosAdicionesContratoAdicionTipoRel
     *
     * @return RhuContratoAdicionTipo
     */
    public function addContratosAdicionesContratoAdicionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicion $contratosAdicionesContratoAdicionTipoRel)
    {
        $this->contratosAdicionesContratoAdicionTipoRel[] = $contratosAdicionesContratoAdicionTipoRel;

        return $this;
    }

    /**
     * Remove contratosAdicionesContratoAdicionTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicion $contratosAdicionesContratoAdicionTipoRel
     */
    public function removeContratosAdicionesContratoAdicionTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoAdicion $contratosAdicionesContratoAdicionTipoRel)
    {
        $this->contratosAdicionesContratoAdicionTipoRel->removeElement($contratosAdicionesContratoAdicionTipoRel);
    }

    /**
     * Get contratosAdicionesContratoAdicionTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosAdicionesContratoAdicionTipoRel()
    {
        return $this->contratosAdicionesContratoAdicionTipoRel;
    }

    /**
     * Set contenidoFormatoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel
     *
     * @return RhuContratoAdicionTipo
     */
    public function setContenidoFormatoRel(\Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel = null)
    {
        $this->contenidoFormatoRel = $contenidoFormatoRel;

        return $this;
    }

    /**
     * Get contenidoFormatoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenContenidoFormato
     */
    public function getContenidoFormatoRel()
    {
        return $this->contenidoFormatoRel;
    }

    /**
     * Set contratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoClase $contratoClaseRel
     *
     * @return RhuContratoAdicionTipo
     */
    public function setContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoClase $contratoClaseRel = null)
    {
        $this->contratoClaseRel = $contratoClaseRel;

        return $this;
    }

    /**
     * Get contratoClaseRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoClase
     */
    public function getContratoClaseRel()
    {
        return $this->contratoClaseRel;
    }
}
