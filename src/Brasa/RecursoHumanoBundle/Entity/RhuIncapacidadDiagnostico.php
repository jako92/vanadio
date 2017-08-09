<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad_diagnostico")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadDiagnosticoRepository")
 */
class RhuIncapacidadDiagnostico
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_diagnostico_pk", type="integer")
     */
    private $codigoIncapacidadDiagnosticoPk;                        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=800, nullable=true)
     */    
    private $nombre;     

    /**
     * @ORM\Column(name="codigo", type="string", length=10, nullable=true)
     */    
    private $codigo;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="incapacidadDiagnosticoRel")
     */
    protected $incapacidadesIncapacidadDiagnosticoRel;      

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->incapacidadesIncapacidadDiagnosticoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoIncapacidadDiagnosticoPk
     *
     * @param integer $codigoIncapacidadDiagnosticoPk
     *
     * @return RhuIncapacidadDiagnostico
     */
    public function setCodigoIncapacidadDiagnosticoPk($codigoIncapacidadDiagnosticoPk)
    {
        $this->codigoIncapacidadDiagnosticoPk = $codigoIncapacidadDiagnosticoPk;

        return $this;
    }

    /**
     * Get codigoIncapacidadDiagnosticoPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadDiagnosticoPk()
    {
        return $this->codigoIncapacidadDiagnosticoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuIncapacidadDiagnostico
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
     * Set codigo
     *
     * @param string $codigo
     *
     * @return RhuIncapacidadDiagnostico
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Add incapacidadesIncapacidadDiagnosticoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadDiagnosticoRel
     *
     * @return RhuIncapacidadDiagnostico
     */
    public function addIncapacidadesIncapacidadDiagnosticoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadDiagnosticoRel)
    {
        $this->incapacidadesIncapacidadDiagnosticoRel[] = $incapacidadesIncapacidadDiagnosticoRel;

        return $this;
    }

    /**
     * Remove incapacidadesIncapacidadDiagnosticoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadDiagnosticoRel
     */
    public function removeIncapacidadesIncapacidadDiagnosticoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadDiagnosticoRel)
    {
        $this->incapacidadesIncapacidadDiagnosticoRel->removeElement($incapacidadesIncapacidadDiagnosticoRel);
    }

    /**
     * Get incapacidadesIncapacidadDiagnosticoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesIncapacidadDiagnosticoRel()
    {
        return $this->incapacidadesIncapacidadDiagnosticoRel;
    }
}
