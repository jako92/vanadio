<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion_provision")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionProvisionRepository")
 */
class RhuConfiguracionProvision
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_provision_pk", type="integer")
     */
    private $codigoConfiguracionProvisionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;     

    /**
     * @ORM\Column(name="tipo_cuenta", type="bigint")
     */     
    private $tipoCuenta = 1; 

    /**
     * @ORM\Column(name="codigo_empleado_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoTipoFk;    
    
        /**
     * @ORM\Column(name="tipo", type="bigint")
     */     
    private $tipo = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoTipo", inversedBy="configuracionesProvisionesEmpleadoTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_tipo_fk", referencedColumnName="codigo_empleado_tipo_pk")
     */
    protected $empleadoTipoRel;    

    /**
     * Set codigoConfiguracionProvisionPk
     *
     * @param integer $codigoConfiguracionProvisionPk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoConfiguracionProvisionPk($codigoConfiguracionProvisionPk)
    {
        $this->codigoConfiguracionProvisionPk = $codigoConfiguracionProvisionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionProvisionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionProvisionPk()
    {
        return $this->codigoConfiguracionProvisionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuConfiguracionProvision
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
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set tipoCuenta
     *
     * @param integer $tipoCuenta
     *
     * @return RhuConfiguracionProvision
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    /**
     * Get tipoCuenta
     *
     * @return integer
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Set codigoEmpleadoTipoFk
     *
     * @param integer $codigoEmpleadoTipoFk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoEmpleadoTipoFk($codigoEmpleadoTipoFk)
    {
        $this->codigoEmpleadoTipoFk = $codigoEmpleadoTipoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoTipoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoTipoFk()
    {
        return $this->codigoEmpleadoTipoFk;
    }

    /**
     * Set empleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo $empleadoTipoRel
     *
     * @return RhuConfiguracionProvision
     */
    public function setEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo $empleadoTipoRel = null)
    {
        $this->empleadoTipoRel = $empleadoTipoRel;

        return $this;
    }

    /**
     * Get empleadoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo
     */
    public function getEmpleadoTipoRel()
    {
        return $this->empleadoTipoRel;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return RhuConfiguracionProvision
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
