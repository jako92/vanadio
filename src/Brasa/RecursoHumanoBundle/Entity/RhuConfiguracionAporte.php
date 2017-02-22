<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion_aporte")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionAporteRepository")
 */
class RhuConfiguracionAporte
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_aporte_pk", type="integer")
     */
    private $codigoConfiguracionAportePk;

    /**
     * Tipo de planilla pago seguridad social s-sucursal u-unica
     * @ORM\Column(name="forma_presentacion", type="string", length=1, nullable=true)
     */
    private $formaPresentacion;

    /**
     * Obligatorio. Lo suministra el aportante. A. Aportante con 200 o más cotizantes B. Aportante con menos de 200 cotizantes C. Aportante Mipyme que se acoge a Ley 590 de 2000  D. Aportante beneficiario del artículo 5° de la Ley 1429 de 2010  I. Independiente 
     * @ORM\Column(name="clase_aportante", type="string", length=1, nullable=true)
     */
    private $claseAportante;    

    /**
     * Obligatorio. Lo suministra el aportante. 1. Pública  2. Privada  3. Mixta  4. Organismos multilaterales 5. Entidades de derecho público no sometidas a la legislación colombiana 
     * @ORM\Column(name="naturaleza_juridica", type="string", length=1, nullable=true)
     */
    private $naturalezaJuridica;    

    /**
     * N: Natural J: Jurídica Cuando el tipo de persona sea N, los valores válidos son: CC . Cédula de ciudadanía CE . Cédula de extranjería TI.   Tarjeta de identidad PA.  Pasaporte CD.  Carné diplomático
     * @ORM\Column(name="tipo_persona", type="string", length=1, nullable=true)
     */
    private $tipoPersona;     

    /**
     * @ORM\Column(name="direccion_correspondencia", type="string", length=40, nullable=true)
     */
    private $direccionCorrespondencia; 

    /**
     * @ORM\Column(name="codigo_ciudad", type="string", length=3, nullable=true)
     */
    private $codigoCiudad; 
    
    /**
     * @ORM\Column(name="codigo_departamento", type="string", length=2, nullable=true)
     */
    private $codigoDepartamento;     

    /**
     * @ORM\Column(name="nombre_empresa", type="string", length=200, nullable=true)
     */
    private $nombreEmpresa;    

    /**
     * @ORM\Column(name="tipo_identificacion_empresa", type="string", length=2, nullable=true)
     */
    private $tipoIdentificacionEmpresa;     

    /**
     * @ORM\Column(name="identificacion_empresa", type="string", length=2, nullable=true)
     */
    private $identificacionEmpresa;

    /**
     * @ORM\Column(name="digito_verificacion_empresa", type="string", length=1, nullable=true)
     */
    private $digitoVerificacionEmpresa;

    /**
     * @ORM\Column(name="codigo_entidad_riesgos_profesionales", type="string", length=1, nullable=true)
     */
    private $codigoEntidadRiesgosProfesionales;
    
    /**
     * Set codigoConfiguracionAportePk
     *
     * @param integer $codigoConfiguracionAportePk
     *
     * @return RhuConfiguracionAporte
     */
    public function setCodigoConfiguracionAportePk($codigoConfiguracionAportePk)
    {
        $this->codigoConfiguracionAportePk = $codigoConfiguracionAportePk;

        return $this;
    }

    /**
     * Get codigoConfiguracionAportePk
     *
     * @return integer
     */
    public function getCodigoConfiguracionAportePk()
    {
        return $this->codigoConfiguracionAportePk;
    }

    /**
     * Set formaPresentacion
     *
     * @param string $formaPresentacion
     *
     * @return RhuConfiguracionAporte
     */
    public function setFormaPresentacion($formaPresentacion)
    {
        $this->formaPresentacion = $formaPresentacion;

        return $this;
    }

    /**
     * Get formaPresentacion
     *
     * @return string
     */
    public function getFormaPresentacion()
    {
        return $this->formaPresentacion;
    }

    /**
     * Set claseAportante
     *
     * @param string $claseAportante
     *
     * @return RhuConfiguracionAporte
     */
    public function setClaseAportante($claseAportante)
    {
        $this->claseAportante = $claseAportante;

        return $this;
    }

    /**
     * Get claseAportante
     *
     * @return string
     */
    public function getClaseAportante()
    {
        return $this->claseAportante;
    }

    /**
     * Set naturalezaJuridica
     *
     * @param string $naturalezaJuridica
     *
     * @return RhuConfiguracionAporte
     */
    public function setNaturalezaJuridica($naturalezaJuridica)
    {
        $this->naturalezaJuridica = $naturalezaJuridica;

        return $this;
    }

    /**
     * Get naturalezaJuridica
     *
     * @return string
     */
    public function getNaturalezaJuridica()
    {
        return $this->naturalezaJuridica;
    }

    /**
     * Set tipoPersona
     *
     * @param string $tipoPersona
     *
     * @return RhuConfiguracionAporte
     */
    public function setTipoPersona($tipoPersona)
    {
        $this->tipoPersona = $tipoPersona;

        return $this;
    }

    /**
     * Get tipoPersona
     *
     * @return string
     */
    public function getTipoPersona()
    {
        return $this->tipoPersona;
    }

    /**
     * Set direccionCorrespondencia
     *
     * @param string $direccionCorrespondencia
     *
     * @return RhuConfiguracionAporte
     */
    public function setDireccionCorrespondencia($direccionCorrespondencia)
    {
        $this->direccionCorrespondencia = $direccionCorrespondencia;

        return $this;
    }

    /**
     * Get direccionCorrespondencia
     *
     * @return string
     */
    public function getDireccionCorrespondencia()
    {
        return $this->direccionCorrespondencia;
    }

    /**
     * Set codigoCiudad
     *
     * @param string $codigoCiudad
     *
     * @return RhuConfiguracionAporte
     */
    public function setCodigoCiudad($codigoCiudad)
    {
        $this->codigoCiudad = $codigoCiudad;

        return $this;
    }

    /**
     * Get codigoCiudad
     *
     * @return string
     */
    public function getCodigoCiudad()
    {
        return $this->codigoCiudad;
    }

    /**
     * Set codigoDepartamento
     *
     * @param string $codigoDepartamento
     *
     * @return RhuConfiguracionAporte
     */
    public function setCodigoDepartamento($codigoDepartamento)
    {
        $this->codigoDepartamento = $codigoDepartamento;

        return $this;
    }

    /**
     * Get codigoDepartamento
     *
     * @return string
     */
    public function getCodigoDepartamento()
    {
        return $this->codigoDepartamento;
    }

    /**
     * Set nombreEmpresa
     *
     * @param string $nombreEmpresa
     *
     * @return RhuConfiguracionAporte
     */
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;

        return $this;
    }

    /**
     * Get nombreEmpresa
     *
     * @return string
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }

    /**
     * Set tipoIdentificacionEmpresa
     *
     * @param string $tipoIdentificacionEmpresa
     *
     * @return RhuConfiguracionAporte
     */
    public function setTipoIdentificacionEmpresa($tipoIdentificacionEmpresa)
    {
        $this->tipoIdentificacionEmpresa = $tipoIdentificacionEmpresa;

        return $this;
    }

    /**
     * Get tipoIdentificacionEmpresa
     *
     * @return string
     */
    public function getTipoIdentificacionEmpresa()
    {
        return $this->tipoIdentificacionEmpresa;
    }

    /**
     * Set identificacionEmpresa
     *
     * @param string $identificacionEmpresa
     *
     * @return RhuConfiguracionAporte
     */
    public function setIdentificacionEmpresa($identificacionEmpresa)
    {
        $this->identificacionEmpresa = $identificacionEmpresa;

        return $this;
    }

    /**
     * Get identificacionEmpresa
     *
     * @return string
     */
    public function getIdentificacionEmpresa()
    {
        return $this->identificacionEmpresa;
    }

    /**
     * Set digitoVerificacionEmpresa
     *
     * @param string $digitoVerificacionEmpresa
     *
     * @return RhuConfiguracionAporte
     */
    public function setDigitoVerificacionEmpresa($digitoVerificacionEmpresa)
    {
        $this->digitoVerificacionEmpresa = $digitoVerificacionEmpresa;

        return $this;
    }

    /**
     * Get digitoVerificacionEmpresa
     *
     * @return string
     */
    public function getDigitoVerificacionEmpresa()
    {
        return $this->digitoVerificacionEmpresa;
    }

    /**
     * Set codigoEntidadRiesgosProfesionales
     *
     * @param string $codigoEntidadRiesgosProfesionales
     *
     * @return RhuConfiguracionAporte
     */
    public function setCodigoEntidadRiesgosProfesionales($codigoEntidadRiesgosProfesionales)
    {
        $this->codigoEntidadRiesgosProfesionales = $codigoEntidadRiesgosProfesionales;

        return $this;
    }

    /**
     * Get codigoEntidadRiesgosProfesionales
     *
     * @return string
     */
    public function getCodigoEntidadRiesgosProfesionales()
    {
        return $this->codigoEntidadRiesgosProfesionales;
    }
}
