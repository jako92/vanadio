<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_tiempo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoTiempoRepository")
 */
class RhuTipoTiempo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_tiempo_pk", type="integer")
     */
    private $codigoTipoTiempoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="factor", type="integer", nullable=true)
     */    
    private $factor = 0;    
    
    /**
     * @ORM\Column(name="factor_horas_dia", type="integer", nullable=true)
     */    
    private $factorHorasDia = 8;     
    
    /**
     * @ORM\Column(name="abreviatura", type="string", length=1, nullable=true)
     */    
    private $abreviatura;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="tipoTiempoRel")
     */
    protected $contratosTipoTiempoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="tipoTiempoRel")
     */
    protected $empleadosTipoTiempoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosTipoTiempoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosTipoTiempoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoTipoTiempoPk
     *
     * @param integer $codigoTipoTiempoPk
     *
     * @return RhuTipoTiempo
     */
    public function setCodigoTipoTiempoPk($codigoTipoTiempoPk)
    {
        $this->codigoTipoTiempoPk = $codigoTipoTiempoPk;

        return $this;
    }

    /**
     * Get codigoTipoTiempoPk
     *
     * @return integer
     */
    public function getCodigoTipoTiempoPk()
    {
        return $this->codigoTipoTiempoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTipoTiempo
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
     * Set factor
     *
     * @param integer $factor
     *
     * @return RhuTipoTiempo
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return integer
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set factorHorasDia
     *
     * @param integer $factorHorasDia
     *
     * @return RhuTipoTiempo
     */
    public function setFactorHorasDia($factorHorasDia)
    {
        $this->factorHorasDia = $factorHorasDia;

        return $this;
    }

    /**
     * Get factorHorasDia
     *
     * @return integer
     */
    public function getFactorHorasDia()
    {
        return $this->factorHorasDia;
    }

    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return RhuTipoTiempo
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
     * Add contratosTipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoTiempoRel
     *
     * @return RhuTipoTiempo
     */
    public function addContratosTipoTiempoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoTiempoRel)
    {
        $this->contratosTipoTiempoRel[] = $contratosTipoTiempoRel;

        return $this;
    }

    /**
     * Remove contratosTipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoTiempoRel
     */
    public function removeContratosTipoTiempoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoTiempoRel)
    {
        $this->contratosTipoTiempoRel->removeElement($contratosTipoTiempoRel);
    }

    /**
     * Get contratosTipoTiempoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosTipoTiempoRel()
    {
        return $this->contratosTipoTiempoRel;
    }

    /**
     * Add empleadosTipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoTiempoRel
     *
     * @return RhuTipoTiempo
     */
    public function addEmpleadosTipoTiempoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoTiempoRel)
    {
        $this->empleadosTipoTiempoRel[] = $empleadosTipoTiempoRel;

        return $this;
    }

    /**
     * Remove empleadosTipoTiempoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoTiempoRel
     */
    public function removeEmpleadosTipoTiempoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoTiempoRel)
    {
        $this->empleadosTipoTiempoRel->removeElement($empleadosTipoTiempoRel);
    }

    /**
     * Get empleadosTipoTiempoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosTipoTiempoRel()
    {
        return $this->empleadosTipoTiempoRel;
    }
}
