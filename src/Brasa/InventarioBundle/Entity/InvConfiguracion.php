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
     * @ORM\Column(name="informacion_resolucion_supervigilancia_Movimiento", type="text", nullable=true)
     */    
    private $informacionResolucionSupervigilanciaMovimiento;    
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_descanso_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasDescansoFk;     
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_diurnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasDiurnasFk;    

    /**
     * @ORM\Column(name="codigo_concepto_horas_nocturnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasNocturnasFk; 
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_festivas_diurnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasFestivasDiurnasFk;     
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_festivas_nocturnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasFestivasNocturnasFk;     
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_extras_ordinarias_diurnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasExtrasOrdinariasDiurnasFk;     
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_extras_ordinarias_nocturnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasExtrasOrdinariasNocturnasFk;   
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_extras_festivas_diurnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasExtrasFestivasDiurnasFk;     
    
    /**
     * @ORM\Column(name="codigo_concepto_horas_extras_festivas_nocturnas_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoHorasExtrasFestivasNocturnasFk;     
              
    
    /**
     * @ORM\Column(name="codigo_formato_movimiento", type="integer")
     */    
    private $codigoFormatoMovimiento = 0;    

    /**
     * @ORM\Column(name="codigo_formato_programacion", type="integer")
     */    
    private $codigoFormatoProgramacion = 0;
    
    

    

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
     * Set informacionResolucionSupervigilanciaMovimiento
     *
     * @param string $informacionResolucionSupervigilanciaMovimiento
     *
     * @return InvConfiguracion
     */
    public function setInformacionResolucionSupervigilanciaMovimiento($informacionResolucionSupervigilanciaMovimiento)
    {
        $this->informacionResolucionSupervigilanciaMovimiento = $informacionResolucionSupervigilanciaMovimiento;

        return $this;
    }

    /**
     * Get informacionResolucionSupervigilanciaMovimiento
     *
     * @return string
     */
    public function getInformacionResolucionSupervigilanciaMovimiento()
    {
        return $this->informacionResolucionSupervigilanciaMovimiento;
    }

    /**
     * Set codigoConceptoHorasDescansoFk
     *
     * @param integer $codigoConceptoHorasDescansoFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasDescansoFk($codigoConceptoHorasDescansoFk)
    {
        $this->codigoConceptoHorasDescansoFk = $codigoConceptoHorasDescansoFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasDescansoFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasDescansoFk()
    {
        return $this->codigoConceptoHorasDescansoFk;
    }

    /**
     * Set codigoConceptoHorasDiurnasFk
     *
     * @param integer $codigoConceptoHorasDiurnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasDiurnasFk($codigoConceptoHorasDiurnasFk)
    {
        $this->codigoConceptoHorasDiurnasFk = $codigoConceptoHorasDiurnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasDiurnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasDiurnasFk()
    {
        return $this->codigoConceptoHorasDiurnasFk;
    }

    /**
     * Set codigoConceptoHorasNocturnasFk
     *
     * @param integer $codigoConceptoHorasNocturnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasNocturnasFk($codigoConceptoHorasNocturnasFk)
    {
        $this->codigoConceptoHorasNocturnasFk = $codigoConceptoHorasNocturnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasNocturnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasNocturnasFk()
    {
        return $this->codigoConceptoHorasNocturnasFk;
    }

    /**
     * Set codigoConceptoHorasFestivasDiurnasFk
     *
     * @param integer $codigoConceptoHorasFestivasDiurnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasFestivasDiurnasFk($codigoConceptoHorasFestivasDiurnasFk)
    {
        $this->codigoConceptoHorasFestivasDiurnasFk = $codigoConceptoHorasFestivasDiurnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasFestivasDiurnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasFestivasDiurnasFk()
    {
        return $this->codigoConceptoHorasFestivasDiurnasFk;
    }

    /**
     * Set codigoConceptoHorasFestivasNocturnasFk
     *
     * @param integer $codigoConceptoHorasFestivasNocturnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasFestivasNocturnasFk($codigoConceptoHorasFestivasNocturnasFk)
    {
        $this->codigoConceptoHorasFestivasNocturnasFk = $codigoConceptoHorasFestivasNocturnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasFestivasNocturnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasFestivasNocturnasFk()
    {
        return $this->codigoConceptoHorasFestivasNocturnasFk;
    }

    /**
     * Set codigoConceptoHorasExtrasOrdinariasDiurnasFk
     *
     * @param integer $codigoConceptoHorasExtrasOrdinariasDiurnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasExtrasOrdinariasDiurnasFk($codigoConceptoHorasExtrasOrdinariasDiurnasFk)
    {
        $this->codigoConceptoHorasExtrasOrdinariasDiurnasFk = $codigoConceptoHorasExtrasOrdinariasDiurnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasExtrasOrdinariasDiurnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasExtrasOrdinariasDiurnasFk()
    {
        return $this->codigoConceptoHorasExtrasOrdinariasDiurnasFk;
    }

    /**
     * Set codigoConceptoHorasExtrasOrdinariasNocturnasFk
     *
     * @param integer $codigoConceptoHorasExtrasOrdinariasNocturnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasExtrasOrdinariasNocturnasFk($codigoConceptoHorasExtrasOrdinariasNocturnasFk)
    {
        $this->codigoConceptoHorasExtrasOrdinariasNocturnasFk = $codigoConceptoHorasExtrasOrdinariasNocturnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasExtrasOrdinariasNocturnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasExtrasOrdinariasNocturnasFk()
    {
        return $this->codigoConceptoHorasExtrasOrdinariasNocturnasFk;
    }

    /**
     * Set codigoConceptoHorasExtrasFestivasDiurnasFk
     *
     * @param integer $codigoConceptoHorasExtrasFestivasDiurnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasExtrasFestivasDiurnasFk($codigoConceptoHorasExtrasFestivasDiurnasFk)
    {
        $this->codigoConceptoHorasExtrasFestivasDiurnasFk = $codigoConceptoHorasExtrasFestivasDiurnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasExtrasFestivasDiurnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasExtrasFestivasDiurnasFk()
    {
        return $this->codigoConceptoHorasExtrasFestivasDiurnasFk;
    }

    /**
     * Set codigoConceptoHorasExtrasFestivasNocturnasFk
     *
     * @param integer $codigoConceptoHorasExtrasFestivasNocturnasFk
     *
     * @return InvConfiguracion
     */
    public function setCodigoConceptoHorasExtrasFestivasNocturnasFk($codigoConceptoHorasExtrasFestivasNocturnasFk)
    {
        $this->codigoConceptoHorasExtrasFestivasNocturnasFk = $codigoConceptoHorasExtrasFestivasNocturnasFk;

        return $this;
    }

    /**
     * Get codigoConceptoHorasExtrasFestivasNocturnasFk
     *
     * @return integer
     */
    public function getCodigoConceptoHorasExtrasFestivasNocturnasFk()
    {
        return $this->codigoConceptoHorasExtrasFestivasNocturnasFk;
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

    /**
     * Set codigoFormatoProgramacion
     *
     * @param integer $codigoFormatoProgramacion
     *
     * @return InvConfiguracion
     */
    public function setCodigoFormatoProgramacion($codigoFormatoProgramacion)
    {
        $this->codigoFormatoProgramacion = $codigoFormatoProgramacion;

        return $this;
    }

    /**
     * Get codigoFormatoProgramacion
     *
     * @return integer
     */
    public function getCodigoFormatoProgramacion()
    {
        return $this->codigoFormatoProgramacion;
    }
}
