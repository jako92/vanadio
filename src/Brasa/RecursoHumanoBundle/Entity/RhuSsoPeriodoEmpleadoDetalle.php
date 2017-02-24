<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_periodo_empleado_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoPeriodoEmpleadoDetalleRepository")
 */
class RhuSsoPeriodoEmpleadoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_empleado_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoEmpleadoDetallePk;   
    
    /**
     * @ORM\Column(name="codigo_periodo_empleado_fk", type="integer")
     */    
    private $codigoPeriodoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */    
    private $vrSalario = 0;   
    
    /**
     * @ORM\Column(name="ibc", type="float")
     */    
    private $Ibc = 0;    
    
    /**
     * @ORM\Column(name="vr_vacaciones", type="float")
     */    
    private $vrVacaciones = 0;    
    
    /**
     * @ORM\Column(name="ingreso", type="string", length=1)
     */
    private $ingreso = ' ';    

    /**
     * @ORM\Column(name="retiro", type="string", length=1)
     */
    private $retiro = ' ';    
    
    /**
     * @ORM\Column(name="salario_integral", type="string", length=1)
     */
    private $salarioIntegral = ' ';    
    
    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1)
     */
    private $variacionTransitoriaSalario = ' ';    
    
    /**
     * @ORM\Column(name="dias_licencia", type="integer")
     */    
    private $diasLicencia = 0;    
    
    /**
     * @ORM\Column(name="dias_incapacidad_general", type="integer")
     */    
    private $diasIncapacidadGeneral = 0;    

    /**
     * @ORM\Column(name="dias_licencia_maternidad", type="integer")
     */    
    private $diasLicenciaMaternidad = 0;    
    
    /**
     * @ORM\Column(name="dias_incapacidad_laboral", type="integer")
     */    
    private $diasIncapacidadLaboral = 0;    

    /**
     * @ORM\Column(name="dias_vacaciones", type="integer")
     */    
    private $diasVacaciones = 0;    
    
    /**
     * @ORM\Column(name="tarifa_pension", type="float")
     */    
    private $tarifaPension = 0;    
    
    /**
     * @ORM\Column(name="tarifa_riesgos", type="float")
     */    
    private $tarifaRiesgos = 0;    
    
    /**
     * @ORM\Column(name="codigo_entidad_pension_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadPensionPertenece;    
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadSaludPertenece;    
    
    /**
     * @ORM\Column(name="codigo_entidad_caja_pertenece", type="string", length=6, nullable=true)
     */
    private $codigoEntidadCajaPertenece;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodoEmpleado", inversedBy="ssoPeriodosEmpleadosDetallesSsoPeriodoEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_periodo_empleado_fk", referencedColumnName="codigo_periodo_empleado_pk")
     */
    protected $ssoPeriodoEmpleadoRel;    
       


    /**
     * Get codigoPeriodoEmpleadoDetallePk
     *
     * @return integer
     */
    public function getCodigoPeriodoEmpleadoDetallePk()
    {
        return $this->codigoPeriodoEmpleadoDetallePk;
    }

    /**
     * Set codigoPeriodoEmpleadoFk
     *
     * @param integer $codigoPeriodoEmpleadoFk
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setCodigoPeriodoEmpleadoFk($codigoPeriodoEmpleadoFk)
    {
        $this->codigoPeriodoEmpleadoFk = $codigoPeriodoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoEmpleadoFk()
    {
        return $this->codigoPeriodoEmpleadoFk;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
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
     * Set ibc
     *
     * @param float $ibc
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setIbc($ibc)
    {
        $this->Ibc = $ibc;

        return $this;
    }

    /**
     * Get ibc
     *
     * @return float
     */
    public function getIbc()
    {
        return $this->Ibc;
    }

    /**
     * Set vrVacaciones
     *
     * @param float $vrVacaciones
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setVrVacaciones($vrVacaciones)
    {
        $this->vrVacaciones = $vrVacaciones;

        return $this;
    }

    /**
     * Get vrVacaciones
     *
     * @return float
     */
    public function getVrVacaciones()
    {
        return $this->vrVacaciones;
    }

    /**
     * Set ingreso
     *
     * @param string $ingreso
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param string $retiro
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return string
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set salarioIntegral
     *
     * @param string $salarioIntegral
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return string
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * Set variacionTransitoriaSalario
     *
     * @param string $variacionTransitoriaSalario
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setVariacionTransitoriaSalario($variacionTransitoriaSalario)
    {
        $this->variacionTransitoriaSalario = $variacionTransitoriaSalario;

        return $this;
    }

    /**
     * Get variacionTransitoriaSalario
     *
     * @return string
     */
    public function getVariacionTransitoriaSalario()
    {
        return $this->variacionTransitoriaSalario;
    }

    /**
     * Set diasLicencia
     *
     * @param integer $diasLicencia
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setDiasLicencia($diasLicencia)
    {
        $this->diasLicencia = $diasLicencia;

        return $this;
    }

    /**
     * Get diasLicencia
     *
     * @return integer
     */
    public function getDiasLicencia()
    {
        return $this->diasLicencia;
    }

    /**
     * Set diasIncapacidadGeneral
     *
     * @param integer $diasIncapacidadGeneral
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setDiasIncapacidadGeneral($diasIncapacidadGeneral)
    {
        $this->diasIncapacidadGeneral = $diasIncapacidadGeneral;

        return $this;
    }

    /**
     * Get diasIncapacidadGeneral
     *
     * @return integer
     */
    public function getDiasIncapacidadGeneral()
    {
        return $this->diasIncapacidadGeneral;
    }

    /**
     * Set diasLicenciaMaternidad
     *
     * @param integer $diasLicenciaMaternidad
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setDiasLicenciaMaternidad($diasLicenciaMaternidad)
    {
        $this->diasLicenciaMaternidad = $diasLicenciaMaternidad;

        return $this;
    }

    /**
     * Get diasLicenciaMaternidad
     *
     * @return integer
     */
    public function getDiasLicenciaMaternidad()
    {
        return $this->diasLicenciaMaternidad;
    }

    /**
     * Set diasIncapacidadLaboral
     *
     * @param integer $diasIncapacidadLaboral
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setDiasIncapacidadLaboral($diasIncapacidadLaboral)
    {
        $this->diasIncapacidadLaboral = $diasIncapacidadLaboral;

        return $this;
    }

    /**
     * Get diasIncapacidadLaboral
     *
     * @return integer
     */
    public function getDiasIncapacidadLaboral()
    {
        return $this->diasIncapacidadLaboral;
    }

    /**
     * Set diasVacaciones
     *
     * @param integer $diasVacaciones
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setDiasVacaciones($diasVacaciones)
    {
        $this->diasVacaciones = $diasVacaciones;

        return $this;
    }

    /**
     * Get diasVacaciones
     *
     * @return integer
     */
    public function getDiasVacaciones()
    {
        return $this->diasVacaciones;
    }

    /**
     * Set tarifaPension
     *
     * @param float $tarifaPension
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setTarifaPension($tarifaPension)
    {
        $this->tarifaPension = $tarifaPension;

        return $this;
    }

    /**
     * Get tarifaPension
     *
     * @return float
     */
    public function getTarifaPension()
    {
        return $this->tarifaPension;
    }

    /**
     * Set tarifaRiesgos
     *
     * @param float $tarifaRiesgos
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setTarifaRiesgos($tarifaRiesgos)
    {
        $this->tarifaRiesgos = $tarifaRiesgos;

        return $this;
    }

    /**
     * Get tarifaRiesgos
     *
     * @return float
     */
    public function getTarifaRiesgos()
    {
        return $this->tarifaRiesgos;
    }

    /**
     * Set codigoEntidadPensionPertenece
     *
     * @param string $codigoEntidadPensionPertenece
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setCodigoEntidadPensionPertenece($codigoEntidadPensionPertenece)
    {
        $this->codigoEntidadPensionPertenece = $codigoEntidadPensionPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadPensionPertenece
     *
     * @return string
     */
    public function getCodigoEntidadPensionPertenece()
    {
        return $this->codigoEntidadPensionPertenece;
    }

    /**
     * Set codigoEntidadSaludPertenece
     *
     * @param string $codigoEntidadSaludPertenece
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setCodigoEntidadSaludPertenece($codigoEntidadSaludPertenece)
    {
        $this->codigoEntidadSaludPertenece = $codigoEntidadSaludPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadSaludPertenece
     *
     * @return string
     */
    public function getCodigoEntidadSaludPertenece()
    {
        return $this->codigoEntidadSaludPertenece;
    }

    /**
     * Set codigoEntidadCajaPertenece
     *
     * @param string $codigoEntidadCajaPertenece
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setCodigoEntidadCajaPertenece($codigoEntidadCajaPertenece)
    {
        $this->codigoEntidadCajaPertenece = $codigoEntidadCajaPertenece;

        return $this;
    }

    /**
     * Get codigoEntidadCajaPertenece
     *
     * @return string
     */
    public function getCodigoEntidadCajaPertenece()
    {
        return $this->codigoEntidadCajaPertenece;
    }

    /**
     * Set ssoPeriodoEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodoEmpleadoRel
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setSsoPeriodoEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodoEmpleadoRel = null)
    {
        $this->ssoPeriodoEmpleadoRel = $ssoPeriodoEmpleadoRel;

        return $this;
    }

    /**
     * Get ssoPeriodoEmpleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado
     */
    public function getSsoPeriodoEmpleadoRel()
    {
        return $this->ssoPeriodoEmpleadoRel;
    }
}
