<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_contrato")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiContratoRepository")
 */
class AfiContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;                   
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;           
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;
    
    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer", nullable=true)
     */    
    private $codigoSucursalFk;    
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;                
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;           
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = true;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**     
     * @ORM\Column(name="indefinido", type="boolean")
     */    
    private $indefinido = false;                                  
    
    /**
     * @ORM\Column(name="codigo_clasificacion_riesgo_fk", type="integer")
     */    
    private $codigoClasificacionRiesgoFk;    
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer")
     */    
    private $codigoCargoFk;     
    
    /**
     * @ORM\Column(name="codigo_tipo_cotizante_fk", type="integer", nullable=false)
     */    
    private $codigoTipoCotizanteFk;    

    /**
     * @ORM\Column(name="codigo_subtipo_cotizante_fk", type="integer", nullable=false)
     */    
    private $codigoSubtipoCotizanteFk;     
    
    /**     
     * @ORM\Column(name="salario_integral", type="boolean")
     */    
    private $salarioIntegral = false;  
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadSaludFk;    

    /**
     * @ORM\Column(name="codigo_entidad_pension_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadPensionFk;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;

     /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadCajaFk;
    
    /**     
     * @ORM\Column(name="genera_salud", type="boolean")
     */    
    private $genera_salud = false;

    /**
     * @ORM\Column(name="porcentaje_salud", type="float")
     */
    private $porcentajeSalud = 0;    
    
    /**     
     * @ORM\Column(name="genera_pension", type="boolean")
     */    
    private $genera_pension = false;        

    /**
     * @ORM\Column(name="porcentaje_pension", type="float")
     */
    private $porcentajePension = 0;    
    
    /**     
     * @ORM\Column(name="genera_caja", type="boolean")
     */    
    private $genera_caja = false;            
    
    /**
     * @ORM\Column(name="porcentaje_caja", type="float")
     */
    private $porcentajeCaja = 0;     
    
    /**     
     * @ORM\Column(name="genera_riesgos", type="boolean")
     */    
    private $genera_riesgos = false;        
    
    /**     
     * @ORM\Column(name="genera_sena", type="boolean")
     */    
    private $genera_sena = false;     
    
    
    /**     
     * @ORM\Column(name="genera_icbf", type="boolean")
     */    
    private $genera_icbf = false;   
    
    /**     
     * @ORM\Column(name="estado_generado_cta_cobrar", type="boolean")
     */    
    private $estadoGeneradoCtaCobrar = false;
    
    /**     
     * @ORM\Column(name="estado_historial_contrato", type="boolean")
     */    
    private $estadoHistorialContrato = false;
    
                  
    

    /**
     * Get codigoContratoPk
     *
     * @return integer
     */
    public function getCodigoContratoPk()
    {
        return $this->codigoContratoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AfiContrato
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return AfiContrato
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiContrato
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return AfiContrato
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return integer
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return AfiContrato
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
     * @return AfiContrato
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
     * Set numero
     *
     * @param string $numero
     *
     * @return AfiContrato
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return AfiContrato
     */
    public function setVrSalario($vrSalario)
    {
        $this->VrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return AfiContrato
     */
    public function setEstadoActivo($estadoActivo)
    {
        $this->estadoActivo = $estadoActivo;

        return $this;
    }

    /**
     * Get estadoActivo
     *
     * @return boolean
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiContrato
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set indefinido
     *
     * @param boolean $indefinido
     *
     * @return AfiContrato
     */
    public function setIndefinido($indefinido)
    {
        $this->indefinido = $indefinido;

        return $this;
    }

    /**
     * Get indefinido
     *
     * @return boolean
     */
    public function getIndefinido()
    {
        return $this->indefinido;
    }

    /**
     * Set codigoClasificacionRiesgoFk
     *
     * @param integer $codigoClasificacionRiesgoFk
     *
     * @return AfiContrato
     */
    public function setCodigoClasificacionRiesgoFk($codigoClasificacionRiesgoFk)
    {
        $this->codigoClasificacionRiesgoFk = $codigoClasificacionRiesgoFk;

        return $this;
    }

    /**
     * Get codigoClasificacionRiesgoFk
     *
     * @return integer
     */
    public function getCodigoClasificacionRiesgoFk()
    {
        return $this->codigoClasificacionRiesgoFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return AfiContrato
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set codigoTipoCotizanteFk
     *
     * @param integer $codigoTipoCotizanteFk
     *
     * @return AfiContrato
     */
    public function setCodigoTipoCotizanteFk($codigoTipoCotizanteFk)
    {
        $this->codigoTipoCotizanteFk = $codigoTipoCotizanteFk;

        return $this;
    }

    /**
     * Get codigoTipoCotizanteFk
     *
     * @return integer
     */
    public function getCodigoTipoCotizanteFk()
    {
        return $this->codigoTipoCotizanteFk;
    }

    /**
     * Set codigoSubtipoCotizanteFk
     *
     * @param integer $codigoSubtipoCotizanteFk
     *
     * @return AfiContrato
     */
    public function setCodigoSubtipoCotizanteFk($codigoSubtipoCotizanteFk)
    {
        $this->codigoSubtipoCotizanteFk = $codigoSubtipoCotizanteFk;

        return $this;
    }

    /**
     * Get codigoSubtipoCotizanteFk
     *
     * @return integer
     */
    public function getCodigoSubtipoCotizanteFk()
    {
        return $this->codigoSubtipoCotizanteFk;
    }

    /**
     * Set salarioIntegral
     *
     * @param boolean $salarioIntegral
     *
     * @return AfiContrato
     */
    public function setSalarioIntegral($salarioIntegral)
    {
        $this->salarioIntegral = $salarioIntegral;

        return $this;
    }

    /**
     * Get salarioIntegral
     *
     * @return boolean
     */
    public function getSalarioIntegral()
    {
        return $this->salarioIntegral;
    }

    /**
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return AfiContrato
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk)
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * Set codigoEntidadPensionFk
     *
     * @param integer $codigoEntidadPensionFk
     *
     * @return AfiContrato
     */
    public function setCodigoEntidadPensionFk($codigoEntidadPensionFk)
    {
        $this->codigoEntidadPensionFk = $codigoEntidadPensionFk;

        return $this;
    }

    /**
     * Get codigoEntidadPensionFk
     *
     * @return integer
     */
    public function getCodigoEntidadPensionFk()
    {
        return $this->codigoEntidadPensionFk;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return AfiContrato
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set codigoEntidadCajaFk
     *
     * @param integer $codigoEntidadCajaFk
     *
     * @return AfiContrato
     */
    public function setCodigoEntidadCajaFk($codigoEntidadCajaFk)
    {
        $this->codigoEntidadCajaFk = $codigoEntidadCajaFk;

        return $this;
    }

    /**
     * Get codigoEntidadCajaFk
     *
     * @return integer
     */
    public function getCodigoEntidadCajaFk()
    {
        return $this->codigoEntidadCajaFk;
    }

    /**
     * Set generaSalud
     *
     * @param boolean $generaSalud
     *
     * @return AfiContrato
     */
    public function setGeneraSalud($generaSalud)
    {
        $this->genera_salud = $generaSalud;

        return $this;
    }

    /**
     * Get generaSalud
     *
     * @return boolean
     */
    public function getGeneraSalud()
    {
        return $this->genera_salud;
    }

    /**
     * Set porcentajeSalud
     *
     * @param float $porcentajeSalud
     *
     * @return AfiContrato
     */
    public function setPorcentajeSalud($porcentajeSalud)
    {
        $this->porcentajeSalud = $porcentajeSalud;

        return $this;
    }

    /**
     * Get porcentajeSalud
     *
     * @return float
     */
    public function getPorcentajeSalud()
    {
        return $this->porcentajeSalud;
    }

    /**
     * Set generaPension
     *
     * @param boolean $generaPension
     *
     * @return AfiContrato
     */
    public function setGeneraPension($generaPension)
    {
        $this->genera_pension = $generaPension;

        return $this;
    }

    /**
     * Get generaPension
     *
     * @return boolean
     */
    public function getGeneraPension()
    {
        return $this->genera_pension;
    }

    /**
     * Set porcentajePension
     *
     * @param float $porcentajePension
     *
     * @return AfiContrato
     */
    public function setPorcentajePension($porcentajePension)
    {
        $this->porcentajePension = $porcentajePension;

        return $this;
    }

    /**
     * Get porcentajePension
     *
     * @return float
     */
    public function getPorcentajePension()
    {
        return $this->porcentajePension;
    }

    /**
     * Set generaCaja
     *
     * @param boolean $generaCaja
     *
     * @return AfiContrato
     */
    public function setGeneraCaja($generaCaja)
    {
        $this->genera_caja = $generaCaja;

        return $this;
    }

    /**
     * Get generaCaja
     *
     * @return boolean
     */
    public function getGeneraCaja()
    {
        return $this->genera_caja;
    }

    /**
     * Set porcentajeCaja
     *
     * @param float $porcentajeCaja
     *
     * @return AfiContrato
     */
    public function setPorcentajeCaja($porcentajeCaja)
    {
        $this->porcentajeCaja = $porcentajeCaja;

        return $this;
    }

    /**
     * Get porcentajeCaja
     *
     * @return float
     */
    public function getPorcentajeCaja()
    {
        return $this->porcentajeCaja;
    }

    /**
     * Set generaRiesgos
     *
     * @param boolean $generaRiesgos
     *
     * @return AfiContrato
     */
    public function setGeneraRiesgos($generaRiesgos)
    {
        $this->genera_riesgos = $generaRiesgos;

        return $this;
    }

    /**
     * Get generaRiesgos
     *
     * @return boolean
     */
    public function getGeneraRiesgos()
    {
        return $this->genera_riesgos;
    }

    /**
     * Set generaSena
     *
     * @param boolean $generaSena
     *
     * @return AfiContrato
     */
    public function setGeneraSena($generaSena)
    {
        $this->genera_sena = $generaSena;

        return $this;
    }

    /**
     * Get generaSena
     *
     * @return boolean
     */
    public function getGeneraSena()
    {
        return $this->genera_sena;
    }

    /**
     * Set generaIcbf
     *
     * @param boolean $generaIcbf
     *
     * @return AfiContrato
     */
    public function setGeneraIcbf($generaIcbf)
    {
        $this->genera_icbf = $generaIcbf;

        return $this;
    }

    /**
     * Get generaIcbf
     *
     * @return boolean
     */
    public function getGeneraIcbf()
    {
        return $this->genera_icbf;
    }

    /**
     * Set estadoGeneradoCtaCobrar
     *
     * @param boolean $estadoGeneradoCtaCobrar
     *
     * @return AfiContrato
     */
    public function setEstadoGeneradoCtaCobrar($estadoGeneradoCtaCobrar)
    {
        $this->estadoGeneradoCtaCobrar = $estadoGeneradoCtaCobrar;

        return $this;
    }

    /**
     * Get estadoGeneradoCtaCobrar
     *
     * @return boolean
     */
    public function getEstadoGeneradoCtaCobrar()
    {
        return $this->estadoGeneradoCtaCobrar;
    }

    /**
     * Set estadoHistorialContrato
     *
     * @param boolean $estadoHistorialContrato
     *
     * @return AfiContrato
     */
    public function setEstadoHistorialContrato($estadoHistorialContrato)
    {
        $this->estadoHistorialContrato = $estadoHistorialContrato;

        return $this;
    }

    /**
     * Get estadoHistorialContrato
     *
     * @return boolean
     */
    public function getEstadoHistorialContrato()
    {
        return $this->estadoHistorialContrato;
    }
}
