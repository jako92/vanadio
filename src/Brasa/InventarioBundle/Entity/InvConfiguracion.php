<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvConfiguracionRepository")
 */
class InvConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="informacion_legal_movimiento", type="text", nullable=true)
     */    
    private $informacionLegalMovimiento; 

    /**
     * @ORM\Column(name="informacion_pago_movimiento", type="text", nullable=true)
     */    
    private $informacionPagoMovimiento;     
    
    /**
     * @ORM\Column(name="informacion_contacto_movimiento", type="text", nullable=true)
     */    
    private $informacionContactoMovimiento;    
    
    /**
     * @ORM\Column(name="informacion_resolucion_dian_movimiento", type="text", nullable=true)
     */    
    private $informacionResolucionDianMovimiento;                              
    
    /**
     * @ORM\Column(name="codigo_formato_movimiento", type="integer")
     */    
    private $codigoFormatoMovimiento = 0;    



    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set informacionLegalMovimiento
     *
     * @param string $informacionLegalMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionLegalMovimiento($informacionLegalMovimiento)
    {
        $this->informacionLegalMovimiento = $informacionLegalMovimiento;

        return $this;
    }

    /**
     * Get informacionLegalMovimiento
     *
     * @return string
     */
    public function getInformacionLegalMovimiento()
    {
        return $this->informacionLegalMovimiento;
    }

    /**
     * Set informacionPagoMovimiento
     *
     * @param string $informacionPagoMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionPagoMovimiento($informacionPagoMovimiento)
    {
        $this->informacionPagoMovimiento = $informacionPagoMovimiento;

        return $this;
    }

    /**
     * Get informacionPagoMovimiento
     *
     * @return string
     */
    public function getInformacionPagoMovimiento()
    {
        return $this->informacionPagoMovimiento;
    }

    /**
     * Set informacionContactoMovimiento
     *
     * @param string $informacionContactoMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionContactoMovimiento($informacionContactoMovimiento)
    {
        $this->informacionContactoMovimiento = $informacionContactoMovimiento;

        return $this;
    }

    /**
     * Get informacionContactoMovimiento
     *
     * @return string
     */
    public function getInformacionContactoMovimiento()
    {
        return $this->informacionContactoMovimiento;
    }

    /**
     * Set informacionResolucionDianMovimiento
     *
     * @param string $informacionResolucionDianMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionResolucionDianMovimiento($informacionResolucionDianMovimiento)
    {
        $this->informacionResolucionDianMovimiento = $informacionResolucionDianMovimiento;

        return $this;
    }

    /**
     * Get informacionResolucionDianMovimiento
     *
     * @return string
     */
    public function getInformacionResolucionDianMovimiento()
    {
        return $this->informacionResolucionDianMovimiento;
    }

    /**
     * Set codigoFormatoMovimiento
     *
     * @param integer $codigoFormatoMovimiento
     *
     * @return InvConfiguracion
     */
    public function setCodigoFormatoMovimiento($codigoFormatoMovimiento)
    {
        $this->codigoFormatoMovimiento = $codigoFormatoMovimiento;

        return $this;
    }

    /**
     * Get codigoFormatoMovimiento
     *
     * @return integer
     */
    public function getCodigoFormatoMovimiento()
    {
        return $this->codigoFormatoMovimiento;
    }
}
