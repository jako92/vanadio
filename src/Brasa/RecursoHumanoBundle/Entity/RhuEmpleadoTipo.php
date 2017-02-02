<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoTipoRepository")
 */
class RhuEmpleadoTipo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;                      
    
    /**
     * @ORM\Column(name="operativo", type="boolean")
     */    
    private $operativo = false;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="empleadoTipoRel")
     */
    protected $empleadosEmpleadoTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoConceptoCuenta", mappedBy="empleadoTipoRel")
     */
    protected $pagosConceptosCuentasEmpleadoTipoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracionProvision", mappedBy="empleadoTipoRel")
     */
    protected $configuracionesProvisionesEmpleadoTipoRel; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEmpleadoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosConceptosCuentasEmpleadoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoTipoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoTipoPk()
    {
        return $this->codigoEmpleadoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEmpleadoTipo
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
     * Add empleadosEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel
     *
     * @return RhuEmpleadoTipo
     */
    public function addEmpleadosEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel)
    {
        $this->empleadosEmpleadoTipoRel[] = $empleadosEmpleadoTipoRel;

        return $this;
    }

    /**
     * Remove empleadosEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel
     */
    public function removeEmpleadosEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEmpleadoTipoRel)
    {
        $this->empleadosEmpleadoTipoRel->removeElement($empleadosEmpleadoTipoRel);
    }

    /**
     * Get empleadosEmpleadoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEmpleadoTipoRel()
    {
        return $this->empleadosEmpleadoTipoRel;
    }

    /**
     * Add pagosConceptosCuentasEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConceptoCuenta $pagosConceptosCuentasEmpleadoTipoRel
     *
     * @return RhuEmpleadoTipo
     */
    public function addPagosConceptosCuentasEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConceptoCuenta $pagosConceptosCuentasEmpleadoTipoRel)
    {
        $this->pagosConceptosCuentasEmpleadoTipoRel[] = $pagosConceptosCuentasEmpleadoTipoRel;

        return $this;
    }

    /**
     * Remove pagosConceptosCuentasEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConceptoCuenta $pagosConceptosCuentasEmpleadoTipoRel
     */
    public function removePagosConceptosCuentasEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConceptoCuenta $pagosConceptosCuentasEmpleadoTipoRel)
    {
        $this->pagosConceptosCuentasEmpleadoTipoRel->removeElement($pagosConceptosCuentasEmpleadoTipoRel);
    }

    /**
     * Get pagosConceptosCuentasEmpleadoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosConceptosCuentasEmpleadoTipoRel()
    {
        return $this->pagosConceptosCuentasEmpleadoTipoRel;
    }

    /**
     * Add configuracionesProvisionesEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision $configuracionesProvisionesEmpleadoTipoRel
     *
     * @return RhuEmpleadoTipo
     */
    public function addConfiguracionesProvisionesEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision $configuracionesProvisionesEmpleadoTipoRel)
    {
        $this->configuracionesProvisionesEmpleadoTipoRel[] = $configuracionesProvisionesEmpleadoTipoRel;

        return $this;
    }

    /**
     * Remove configuracionesProvisionesEmpleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision $configuracionesProvisionesEmpleadoTipoRel
     */
    public function removeConfiguracionesProvisionesEmpleadoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionProvision $configuracionesProvisionesEmpleadoTipoRel)
    {
        $this->configuracionesProvisionesEmpleadoTipoRel->removeElement($configuracionesProvisionesEmpleadoTipoRel);
    }

    /**
     * Get configuracionesProvisionesEmpleadoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfiguracionesProvisionesEmpleadoTipoRel()
    {
        return $this->configuracionesProvisionesEmpleadoTipoRel;
    }

    /**
     * Set operativo
     *
     * @param boolean $operativo
     *
     * @return RhuEmpleadoTipo
     */
    public function setOperativo($operativo)
    {
        $this->operativo = $operativo;

        return $this;
    }

    /**
     * Get operativo
     *
     * @return boolean
     */
    public function getOperativo()
    {
        return $this->operativo;
    }
}
