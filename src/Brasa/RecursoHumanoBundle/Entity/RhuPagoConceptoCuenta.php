<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_concepto_cuenta")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoConceptoCuentaRepository")
 */
class RhuPagoConceptoCuenta
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_concepto_cuenta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoConceptoCuentaPk;

    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;  
    
    /**
     * @ORM\Column(name="codigo_empleado_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoTipoFk;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;     

    /**
     * @ORM\Column(name="tipo_cuenta", type="bigint")
     */     
    private $tipoCuenta = 1;     

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosConceptosCuentasPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoTipo", inversedBy="pagosConceptosCuentasEmpleadoTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_tipo_fk", referencedColumnName="codigo_empleado_tipo_pk")
     */
    protected $empleadoTipoRel;    



    /**
     * Get codigoPagoConceptoCuentaPk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoCuentaPk()
    {
        return $this->codigoPagoConceptoCuentaPk;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuPagoConceptoCuenta
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set codigoEmpleadoTipoFk
     *
     * @param integer $codigoEmpleadoTipoFk
     *
     * @return RhuPagoConceptoCuenta
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
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return RhuPagoConceptoCuenta
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
     * @return RhuPagoConceptoCuenta
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
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuPagoConceptoCuenta
     */
    public function setPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel = null)
    {
        $this->pagoConceptoRel = $pagoConceptoRel;

        return $this;
    }

    /**
     * Get pagoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoRel()
    {
        return $this->pagoConceptoRel;
    }

    /**
     * Set empleadoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoTipo $empleadoTipoRel
     *
     * @return RhuPagoConceptoCuenta
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
}
