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
     * @ORM\Column(name="tarifa_salud", type="float")
     */    
    private $tarifaSalud = 0;     
    
    /**
     * @ORM\Column(name="tarifa_riesgos", type="float")
     */    
    private $tarifaRiesgos = 0;    
    
    /**
     * @ORM\Column(name="tarifa_caja", type="float")
     */    
    private $tarifaCaja = 0;    
    
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
     * @ORM\Column(name="incapacidad_general", type="boolean")
     */
    private $incapacidadGeneral = false;    

    /**
     * @ORM\Column(name="incapacidad_laboral", type="boolean")
     */
    private $incapacidadLaboral = false;    

    /**
     * @ORM\Column(name="licencia", type="boolean")
     */
    private $licencia = false;    

    /**
     * @ORM\Column(name="licencia_maternidad", type="boolean")
     */
    private $licenciaMaternidad = false;    

    /**
     * @ORM\Column(name="vacaciones", type="boolean")
     */
    private $vacaciones = false;  
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */         
    private $fechaDesde;    

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */         
    private $fechaHasta;    

    /**
     * @ORM\Column(name="fecha_retiro", type="date", nullable=true)
     */         
    private $fechaRetiro;    

    /**
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=true)
     */         
    private $fechaIngreso;
    
    /**
     * @ORM\Column(name="variacion_transitoria_salario", type="string", length=1)
     */
    private $variacionTransitoriaSalario = ' ';   
    
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
     * Set tarifaSalud
     *
     * @param float $tarifaSalud
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setTarifaSalud($tarifaSalud)
    {
        $this->tarifaSalud = $tarifaSalud;

        return $this;
    }

    /**
     * Get tarifaSalud
     *
     * @return float
     */
    public function getTarifaSalud()
    {
        return $this->tarifaSalud;
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
     * Set tarifaCaja
     *
     * @param float $tarifaCaja
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setTarifaCaja($tarifaCaja)
    {
        $this->tarifaCaja = $tarifaCaja;

        return $this;
    }

    /**
     * Get tarifaCaja
     *
     * @return float
     */
    public function getTarifaCaja()
    {
        return $this->tarifaCaja;
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
     * Set incapacidadGeneral
     *
     * @param boolean $incapacidadGeneral
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setIncapacidadGeneral($incapacidadGeneral)
    {
        $this->incapacidadGeneral = $incapacidadGeneral;

        return $this;
    }

    /**
     * Get incapacidadGeneral
     *
     * @return boolean
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * Set incapacidadLaboral
     *
     * @param boolean $incapacidadLaboral
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setIncapacidadLaboral($incapacidadLaboral)
    {
        $this->incapacidadLaboral = $incapacidadLaboral;

        return $this;
    }

    /**
     * Get incapacidadLaboral
     *
     * @return boolean
     */
    public function getIncapacidadLaboral()
    {
        return $this->incapacidadLaboral;
    }

    /**
     * Set licencia
     *
     * @param boolean $licencia
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return boolean
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Set licenciaMaternidad
     *
     * @param boolean $licenciaMaternidad
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setLicenciaMaternidad($licenciaMaternidad)
    {
        $this->licenciaMaternidad = $licenciaMaternidad;

        return $this;
    }

    /**
     * Get licenciaMaternidad
     *
     * @return boolean
     */
    public function getLicenciaMaternidad()
    {
        return $this->licenciaMaternidad;
    }

    /**
     * Set vacaciones
     *
     * @param boolean $vacaciones
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setVacaciones($vacaciones)
    {
        $this->vacaciones = $vacaciones;

        return $this;
    }

    /**
     * Get vacaciones
     *
     * @return boolean
     */
    public function getVacaciones()
    {
        return $this->vacaciones;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
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

    /**
     * Set fechaRetiro
     *
     * @param \DateTime $fechaRetiro
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setFechaRetiro($fechaRetiro)
    {
        $this->fechaRetiro = $fechaRetiro;

        return $this;
    }

    /**
     * Get fechaRetiro
     *
     * @return \DateTime
     */
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return RhuSsoPeriodoEmpleadoDetalle
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }
}
