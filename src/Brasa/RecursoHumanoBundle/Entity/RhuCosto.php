<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_costo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCostoRepository")
 */
class RhuCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoPk;               

    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesFk;                
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;                 

    /**
     * @ORM\Column(name="vr_seguridad_social", type="float")
     */
    private $vrSeguridadSocial = 0;
    
    /**
     * @ORM\Column(name="vr_prestacion", type="float")
     */
    private $vrPrestacion = 0;    

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0; 
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */    
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */    
    private $mes = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="costosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;        
    


    /**
     * Get codigoCostoPk
     *
     * @return integer
     */
    public function getCodigoCostoPk()
    {
        return $this->codigoCostoPk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return RhuCosto
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuCosto
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set vrNomina
     *
     * @param float $vrNomina
     *
     * @return RhuCosto
     */
    public function setVrNomina($vrNomina)
    {
        $this->vrNomina = $vrNomina;

        return $this;
    }

    /**
     * Get vrNomina
     *
     * @return float
     */
    public function getVrNomina()
    {
        return $this->vrNomina;
    }

    /**
     * Set vrSeguridadSocial
     *
     * @param float $vrSeguridadSocial
     *
     * @return RhuCosto
     */
    public function setVrSeguridadSocial($vrSeguridadSocial)
    {
        $this->vrSeguridadSocial = $vrSeguridadSocial;

        return $this;
    }

    /**
     * Get vrSeguridadSocial
     *
     * @return float
     */
    public function getVrSeguridadSocial()
    {
        return $this->vrSeguridadSocial;
    }

    /**
     * Set vrPrestacion
     *
     * @param float $vrPrestacion
     *
     * @return RhuCosto
     */
    public function setVrPrestacion($vrPrestacion)
    {
        $this->vrPrestacion = $vrPrestacion;

        return $this;
    }

    /**
     * Get vrPrestacion
     *
     * @return float
     */
    public function getVrPrestacion()
    {
        return $this->vrPrestacion;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuCosto
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return RhuCosto
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuCosto
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuCosto
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
}
