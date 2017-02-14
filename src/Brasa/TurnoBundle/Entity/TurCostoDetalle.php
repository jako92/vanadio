<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_costo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCostoDetalleRepository")
 */
class TurCostoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoDetallePk;             
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */    
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;                     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleFk;    
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;         

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;     
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */    
    private $horasFestivasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */    
    private $horasFestivasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */    
    private $horasExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */    
    private $horasExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */    
    private $horasExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */    
    private $horasExtrasFestivasNocturnas = 0;    

    /**
     * @ORM\Column(name="horas_recargo_nocturno", type="float")
     */    
    private $horasRecargoNocturno = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno", type="float")
     */    
    private $horasRecargoFestivoDiurno = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno", type="float")
     */    
    private $horasRecargoFestivoNocturno = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */    
    private $horasDescanso = 0;     
    
    /**
     * @ORM\Column(name="horas_diurnas_costo", type="float")
     */    
    private $horasDiurnasCosto = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas_costo", type="float")
     */    
    private $horasNocturnasCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_festivas_diurnas_costo", type="float")
     */    
    private $horasFestivasDiurnasCosto = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_costo", type="float")
     */    
    private $horasFestivasNocturnasCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_costo", type="float")
     */    
    private $horasExtrasOrdinariasDiurnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_costo", type="float")
     */    
    private $horasExtrasOrdinariasNocturnasCosto = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_costo", type="float")
     */    
    private $horasExtrasFestivasDiurnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_costo", type="float")
     */    
    private $horasExtrasFestivasNocturnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_recargo_nocturno_costo", type="float")
     */    
    private $horasRecargoNocturnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno_costo", type="float")
     */    
    private $horasRecargoFestivoDiurnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno_costo", type="float")
     */    
    private $horasRecargoFestivoNocturnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso_costo", type="float")
     */    
    private $horasDescansoCosto = 0;    
    
    /**
     * @ORM\Column(name="peso", type="float")
     */    
    private $peso = 0;     

    /**
     * @ORM\Column(name="participacion", type="float")
     */    
    private $participacion = 0;     
    
    /**
     * @ORM\Column(name="costo", type="float")
     */    
    private $costo = 0;     

    /**
     * @ORM\Column(name="costo_nomina", type="float")
     */    
    private $costoNomina = 0;    
    
    /**
     * @ORM\Column(name="costo_seguridad_social", type="float")
     */    
    private $costoSeguridadSocial = 0;
    
    /**
     * @ORM\Column(name="costo_prestaciones", type="float")
     */    
    private $costoPrestaciones = 0;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="string", length=20, nullable=true)
     */    
    private $codigoCentroCostoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", inversedBy="turCostosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="costosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;             
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDetalle", inversedBy="costosDetallesPedidoDetalleRel")
     * @ORM\JoinColumn(name="codigo_pedido_detalle_fk", referencedColumnName="codigo_pedido_detalle_pk")
     */
    protected $pedidoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="costosDetallesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\ContabilidadBundle\Entity\CtbCentroCosto", inversedBy="turCostosDetallesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;     


    /**
     * Get codigoCostoDetallePk
     *
     * @return integer
     */
    public function getCodigoCostoDetallePk()
    {
        return $this->codigoCostoDetallePk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return TurCostoDetalle
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCostoDetalle
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return TurCostoDetalle
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return TurCostoDetalle
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
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurCostoDetalle
     */
    public function setCodigoPedidoDetalleFk($codigoPedidoDetalleFk)
    {
        $this->codigoPedidoDetalleFk = $codigoPedidoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPedidoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPedidoDetalleFk()
    {
        return $this->codigoPedidoDetalleFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurCostoDetalle
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurCostoDetalle
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
     * Set horas
     *
     * @param float $horas
     *
     * @return TurCostoDetalle
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return float
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param float $horasNocturnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return float
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set horasFestivasDiurnas
     *
     * @param float $horasFestivasDiurnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasFestivasDiurnas($horasFestivasDiurnas)
    {
        $this->horasFestivasDiurnas = $horasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasFestivasDiurnas
     *
     * @return float
     */
    public function getHorasFestivasDiurnas()
    {
        return $this->horasFestivasDiurnas;
    }

    /**
     * Set horasFestivasNocturnas
     *
     * @param float $horasFestivasNocturnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasFestivasNocturnas($horasFestivasNocturnas)
    {
        $this->horasFestivasNocturnas = $horasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasFestivasNocturnas
     *
     * @return float
     */
    public function getHorasFestivasNocturnas()
    {
        return $this->horasFestivasNocturnas;
    }

    /**
     * Set horasExtrasOrdinariasDiurnas
     *
     * @param float $horasExtrasOrdinariasDiurnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas)
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnas
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * Set horasExtrasOrdinariasNocturnas
     *
     * @param float $horasExtrasOrdinariasNocturnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas)
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnas
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * Set horasExtrasFestivasDiurnas
     *
     * @param float $horasExtrasFestivasDiurnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas)
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnas
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * Set horasExtrasFestivasNocturnas
     *
     * @param float $horasExtrasFestivasNocturnas
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas)
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnas
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * Set horasRecargoNocturno
     *
     * @param float $horasRecargoNocturno
     *
     * @return TurCostoDetalle
     */
    public function setHorasRecargoNocturno($horasRecargoNocturno)
    {
        $this->horasRecargoNocturno = $horasRecargoNocturno;

        return $this;
    }

    /**
     * Get horasRecargoNocturno
     *
     * @return float
     */
    public function getHorasRecargoNocturno()
    {
        return $this->horasRecargoNocturno;
    }

    /**
     * Set horasRecargoFestivoDiurno
     *
     * @param float $horasRecargoFestivoDiurno
     *
     * @return TurCostoDetalle
     */
    public function setHorasRecargoFestivoDiurno($horasRecargoFestivoDiurno)
    {
        $this->horasRecargoFestivoDiurno = $horasRecargoFestivoDiurno;

        return $this;
    }

    /**
     * Get horasRecargoFestivoDiurno
     *
     * @return float
     */
    public function getHorasRecargoFestivoDiurno()
    {
        return $this->horasRecargoFestivoDiurno;
    }

    /**
     * Set horasRecargoFestivoNocturno
     *
     * @param float $horasRecargoFestivoNocturno
     *
     * @return TurCostoDetalle
     */
    public function setHorasRecargoFestivoNocturno($horasRecargoFestivoNocturno)
    {
        $this->horasRecargoFestivoNocturno = $horasRecargoFestivoNocturno;

        return $this;
    }

    /**
     * Get horasRecargoFestivoNocturno
     *
     * @return float
     */
    public function getHorasRecargoFestivoNocturno()
    {
        return $this->horasRecargoFestivoNocturno;
    }

    /**
     * Set horasDescanso
     *
     * @param float $horasDescanso
     *
     * @return TurCostoDetalle
     */
    public function setHorasDescanso($horasDescanso)
    {
        $this->horasDescanso = $horasDescanso;

        return $this;
    }

    /**
     * Get horasDescanso
     *
     * @return float
     */
    public function getHorasDescanso()
    {
        return $this->horasDescanso;
    }

    /**
     * Set horasDiurnasCosto
     *
     * @param float $horasDiurnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasDiurnasCosto($horasDiurnasCosto)
    {
        $this->horasDiurnasCosto = $horasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasDiurnasCosto
     *
     * @return float
     */
    public function getHorasDiurnasCosto()
    {
        return $this->horasDiurnasCosto;
    }

    /**
     * Set horasNocturnasCosto
     *
     * @param float $horasNocturnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasNocturnasCosto($horasNocturnasCosto)
    {
        $this->horasNocturnasCosto = $horasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasNocturnasCosto
     *
     * @return float
     */
    public function getHorasNocturnasCosto()
    {
        return $this->horasNocturnasCosto;
    }

    /**
     * Set horasFestivasDiurnasCosto
     *
     * @param float $horasFestivasDiurnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasFestivasDiurnasCosto($horasFestivasDiurnasCosto)
    {
        $this->horasFestivasDiurnasCosto = $horasFestivasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasFestivasDiurnasCosto
     *
     * @return float
     */
    public function getHorasFestivasDiurnasCosto()
    {
        return $this->horasFestivasDiurnasCosto;
    }

    /**
     * Set horasFestivasNocturnasCosto
     *
     * @param float $horasFestivasNocturnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasFestivasNocturnasCosto($horasFestivasNocturnasCosto)
    {
        $this->horasFestivasNocturnasCosto = $horasFestivasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasFestivasNocturnasCosto
     *
     * @return float
     */
    public function getHorasFestivasNocturnasCosto()
    {
        return $this->horasFestivasNocturnasCosto;
    }

    /**
     * Set horasExtrasOrdinariasDiurnasCosto
     *
     * @param float $horasExtrasOrdinariasDiurnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasOrdinariasDiurnasCosto($horasExtrasOrdinariasDiurnasCosto)
    {
        $this->horasExtrasOrdinariasDiurnasCosto = $horasExtrasOrdinariasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnasCosto
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasDiurnasCosto()
    {
        return $this->horasExtrasOrdinariasDiurnasCosto;
    }

    /**
     * Set horasExtrasOrdinariasNocturnasCosto
     *
     * @param float $horasExtrasOrdinariasNocturnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasOrdinariasNocturnasCosto($horasExtrasOrdinariasNocturnasCosto)
    {
        $this->horasExtrasOrdinariasNocturnasCosto = $horasExtrasOrdinariasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnasCosto
     *
     * @return float
     */
    public function getHorasExtrasOrdinariasNocturnasCosto()
    {
        return $this->horasExtrasOrdinariasNocturnasCosto;
    }

    /**
     * Set horasExtrasFestivasDiurnasCosto
     *
     * @param float $horasExtrasFestivasDiurnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasFestivasDiurnasCosto($horasExtrasFestivasDiurnasCosto)
    {
        $this->horasExtrasFestivasDiurnasCosto = $horasExtrasFestivasDiurnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnasCosto
     *
     * @return float
     */
    public function getHorasExtrasFestivasDiurnasCosto()
    {
        return $this->horasExtrasFestivasDiurnasCosto;
    }

    /**
     * Set horasExtrasFestivasNocturnasCosto
     *
     * @param float $horasExtrasFestivasNocturnasCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasExtrasFestivasNocturnasCosto($horasExtrasFestivasNocturnasCosto)
    {
        $this->horasExtrasFestivasNocturnasCosto = $horasExtrasFestivasNocturnasCosto;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnasCosto
     *
     * @return float
     */
    public function getHorasExtrasFestivasNocturnasCosto()
    {
        return $this->horasExtrasFestivasNocturnasCosto;
    }

    /**
     * Set horasRecargoNocturnoCosto
     *
     * @param float $horasRecargoNocturnoCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasRecargoNocturnoCosto($horasRecargoNocturnoCosto)
    {
        $this->horasRecargoNocturnoCosto = $horasRecargoNocturnoCosto;

        return $this;
    }

    /**
     * Get horasRecargoNocturnoCosto
     *
     * @return float
     */
    public function getHorasRecargoNocturnoCosto()
    {
        return $this->horasRecargoNocturnoCosto;
    }

    /**
     * Set horasRecargoFestivoDiurnoCosto
     *
     * @param float $horasRecargoFestivoDiurnoCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasRecargoFestivoDiurnoCosto($horasRecargoFestivoDiurnoCosto)
    {
        $this->horasRecargoFestivoDiurnoCosto = $horasRecargoFestivoDiurnoCosto;

        return $this;
    }

    /**
     * Get horasRecargoFestivoDiurnoCosto
     *
     * @return float
     */
    public function getHorasRecargoFestivoDiurnoCosto()
    {
        return $this->horasRecargoFestivoDiurnoCosto;
    }

    /**
     * Set horasRecargoFestivoNocturnoCosto
     *
     * @param float $horasRecargoFestivoNocturnoCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasRecargoFestivoNocturnoCosto($horasRecargoFestivoNocturnoCosto)
    {
        $this->horasRecargoFestivoNocturnoCosto = $horasRecargoFestivoNocturnoCosto;

        return $this;
    }

    /**
     * Get horasRecargoFestivoNocturnoCosto
     *
     * @return float
     */
    public function getHorasRecargoFestivoNocturnoCosto()
    {
        return $this->horasRecargoFestivoNocturnoCosto;
    }

    /**
     * Set horasDescansoCosto
     *
     * @param float $horasDescansoCosto
     *
     * @return TurCostoDetalle
     */
    public function setHorasDescansoCosto($horasDescansoCosto)
    {
        $this->horasDescansoCosto = $horasDescansoCosto;

        return $this;
    }

    /**
     * Get horasDescansoCosto
     *
     * @return float
     */
    public function getHorasDescansoCosto()
    {
        return $this->horasDescansoCosto;
    }

    /**
     * Set peso
     *
     * @param float $peso
     *
     * @return TurCostoDetalle
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return float
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set participacion
     *
     * @param float $participacion
     *
     * @return TurCostoDetalle
     */
    public function setParticipacion($participacion)
    {
        $this->participacion = $participacion;

        return $this;
    }

    /**
     * Get participacion
     *
     * @return float
     */
    public function getParticipacion()
    {
        return $this->participacion;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return TurCostoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set costoNomina
     *
     * @param float $costoNomina
     *
     * @return TurCostoDetalle
     */
    public function setCostoNomina($costoNomina)
    {
        $this->costoNomina = $costoNomina;

        return $this;
    }

    /**
     * Get costoNomina
     *
     * @return float
     */
    public function getCostoNomina()
    {
        return $this->costoNomina;
    }

    /**
     * Set costoSeguridadSocial
     *
     * @param float $costoSeguridadSocial
     *
     * @return TurCostoDetalle
     */
    public function setCostoSeguridadSocial($costoSeguridadSocial)
    {
        $this->costoSeguridadSocial = $costoSeguridadSocial;

        return $this;
    }

    /**
     * Get costoSeguridadSocial
     *
     * @return float
     */
    public function getCostoSeguridadSocial()
    {
        return $this->costoSeguridadSocial;
    }

    /**
     * Set costoPrestaciones
     *
     * @param float $costoPrestaciones
     *
     * @return TurCostoDetalle
     */
    public function setCostoPrestaciones($costoPrestaciones)
    {
        $this->costoPrestaciones = $costoPrestaciones;

        return $this;
    }

    /**
     * Get costoPrestaciones
     *
     * @return float
     */
    public function getCostoPrestaciones()
    {
        return $this->costoPrestaciones;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return TurCostoDetalle
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

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurCostoDetalle
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set pedidoDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel
     *
     * @return TurCostoDetalle
     */
    public function setPedidoDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidoDetalleRel = null)
    {
        $this->pedidoDetalleRel = $pedidoDetalleRel;

        return $this;
    }

    /**
     * Get pedidoDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDetalle
     */
    public function getPedidoDetalleRel()
    {
        return $this->pedidoDetalleRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurCostoDetalle
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param string $codigoCentroCostoFk
     *
     * @return TurCostoDetalle
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return string
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel
     *
     * @return TurCostoDetalle
     */
    public function setCentroCostoRel(\Brasa\ContabilidadBundle\Entity\CtbCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }
}
