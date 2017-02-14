<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_costo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCostoRepository")
 */
class TurCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoPk;             
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */    
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;                     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;    
    
    /**
     * @ORM\Column(name="vr_prestaciones", type="float")
     */
    private $vrPrestaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_aportes_sociales", type="float")
     */
    private $vrAportesSociales = 0;    

    /**
     * @ORM\Column(name="vr_costo_total", type="float")
     */
    private $vrCostoTotal = 0;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */    
    private $codigoCentroCostoFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCierreMes", inversedBy="costosCierreMesRel")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $cierreMesRel;  

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", inversedBy="turCostosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\ContabilidadBundle\Entity\CtbCentroCosto", inversedBy="turCostosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;      

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
     * @return TurCosto
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
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCosto
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
     * @return TurCosto
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return TurCosto
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
     * @return TurCosto
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
     * Set vrPrestaciones
     *
     * @param float $vrPrestaciones
     *
     * @return TurCosto
     */
    public function setVrPrestaciones($vrPrestaciones)
    {
        $this->vrPrestaciones = $vrPrestaciones;

        return $this;
    }

    /**
     * Get vrPrestaciones
     *
     * @return float
     */
    public function getVrPrestaciones()
    {
        return $this->vrPrestaciones;
    }

    /**
     * Set vrAportesSociales
     *
     * @param float $vrAportesSociales
     *
     * @return TurCosto
     */
    public function setVrAportesSociales($vrAportesSociales)
    {
        $this->vrAportesSociales = $vrAportesSociales;

        return $this;
    }

    /**
     * Get vrAportesSociales
     *
     * @return float
     */
    public function getVrAportesSociales()
    {
        return $this->vrAportesSociales;
    }

    /**
     * Set vrCostoTotal
     *
     * @param float $vrCostoTotal
     *
     * @return TurCosto
     */
    public function setVrCostoTotal($vrCostoTotal)
    {
        $this->vrCostoTotal = $vrCostoTotal;

        return $this;
    }

    /**
     * Get vrCostoTotal
     *
     * @return float
     */
    public function getVrCostoTotal()
    {
        return $this->vrCostoTotal;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurCosto
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set vrHora
     *
     * @param float $vrHora
     *
     * @return TurCosto
     */
    public function setVrHora($vrHora)
    {
        $this->vrHora = $vrHora;

        return $this;
    }

    /**
     * Get vrHora
     *
     * @return float
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }

    /**
     * Set cierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMes $cierreMesRel
     *
     * @return TurCosto
     */
    public function setCierreMesRel(\Brasa\TurnoBundle\Entity\TurCierreMes $cierreMesRel = null)
    {
        $this->cierreMesRel = $cierreMesRel;

        return $this;
    }

    /**
     * Get cierreMesRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCierreMes
     */
    public function getCierreMesRel()
    {
        return $this->cierreMesRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return TurCosto
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
     * Set codigoCentroCostoFk
     *
     * @param string $codigoCentroCostoFk
     *
     * @return TurCosto
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return string
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel
     *
     * @return TurCosto
     */
    public function setCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
