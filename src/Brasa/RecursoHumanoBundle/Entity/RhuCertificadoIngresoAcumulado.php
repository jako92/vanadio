<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_certificado_ingreso_acumulado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCertificadoIngresoAcumuladoRepository")
 */
class RhuCertificadoIngresoAcumulado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_certificado_ingreso_acumulado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCertificadoIngresoAcumuladoPk;
    
    /**
     * @ORM\Column(name="periodo", type="integer")
     */    
    private $periodo;      

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="acumulado_ibp", type="float")
     */    
    private $acumuladoIbp;
    
    /**
     * @ORM\Column(name="acumulado_salud", type="float")
     */    
    private $acumuladoSalud;
    
    /**
     * @ORM\Column(name="acumulado_pension", type="float")
     */    
    private $acumuladoPension;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="certificadosIngresosAcumuladosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    


    /**
     * Get codigoCertificadoIngresoAcumuladoPk
     *
     * @return integer
     */
    public function getCodigoCertificadoIngresoAcumuladoPk()
    {
        return $this->codigoCertificadoIngresoAcumuladoPk;
    }

    /**
     * Set periodo
     *
     * @param integer $periodo
     *
     * @return RhuCertificadoIngresoAcumulado
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuCertificadoIngresoAcumulado
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
     * Set acumuladoIbp
     *
     * @param float $acumuladoIbp
     *
     * @return RhuCertificadoIngresoAcumulado
     */
    public function setAcumuladoIbp($acumuladoIbp)
    {
        $this->acumuladoIbp = $acumuladoIbp;

        return $this;
    }

    /**
     * Get acumuladoIbp
     *
     * @return float
     */
    public function getAcumuladoIbp()
    {
        return $this->acumuladoIbp;
    }

    /**
     * Set acumuladoSalud
     *
     * @param float $acumuladoSalud
     *
     * @return RhuCertificadoIngresoAcumulado
     */
    public function setAcumuladoSalud($acumuladoSalud)
    {
        $this->acumuladoSalud = $acumuladoSalud;

        return $this;
    }

    /**
     * Get acumuladoSalud
     *
     * @return float
     */
    public function getAcumuladoSalud()
    {
        return $this->acumuladoSalud;
    }

    /**
     * Set acumuladoPension
     *
     * @param float $acumuladoPension
     *
     * @return RhuCertificadoIngresoAcumulado
     */
    public function setAcumuladoPension($acumuladoPension)
    {
        $this->acumuladoPension = $acumuladoPension;

        return $this;
    }

    /**
     * Get acumuladoPension
     *
     * @return float
     */
    public function getAcumuladoPension()
    {
        return $this->acumuladoPension;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuCertificadoIngresoAcumulado
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
}
