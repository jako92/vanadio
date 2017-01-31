<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_vacacion_pagar")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVacacionPagarRepository")
 */
class RhuVacacionPagar
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_pagar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionPagarPk;                          
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=false)
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="fecha_corte", type="date")
     */    
    private $fechaCorte;    
    
    /**
     * @ORM\Column(name="fecha_ultimo_pago", type="date")
     */    
    private $fechaUltimoPago;           
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;   



    /**
     * Get codigoVacacionPagarPk
     *
     * @return integer
     */
    public function getCodigoVacacionPagarPk()
    {
        return $this->codigoVacacionPagarPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuVacacionPagar
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuVacacionPagar
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set fechaCorte
     *
     * @param \DateTime $fechaCorte
     *
     * @return RhuVacacionPagar
     */
    public function setFechaCorte($fechaCorte)
    {
        $this->fechaCorte = $fechaCorte;

        return $this;
    }

    /**
     * Get fechaCorte
     *
     * @return \DateTime
     */
    public function getFechaCorte()
    {
        return $this->fechaCorte;
    }

    /**
     * Set fechaUltimoPago
     *
     * @param \DateTime $fechaUltimoPago
     *
     * @return RhuVacacionPagar
     */
    public function setFechaUltimoPago($fechaUltimoPago)
    {
        $this->fechaUltimoPago = $fechaUltimoPago;

        return $this;
    }

    /**
     * Get fechaUltimoPago
     *
     * @return \DateTime
     */
    public function getFechaUltimoPago()
    {
        return $this->fechaUltimoPago;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuVacacionPagar
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
}
