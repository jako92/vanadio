<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurProgramacionSimuladorDetalle
 *
 * @ORM\Table(name="tur_programacion_simulador_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProgramacionSimuladorDetalleRepository")
 */
class TurProgramacionSimuladorDetalle
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_simulador_detalle_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSimuladorDetallePk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_simulacion_fk", type="integer")
     */
    private $codigoSimulacionFk;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var float
     *
     * @ORM\Column(name="ord_diurnas", type="float", nullable=true)
     */
    private $ordDiurnas;

    /**
     * @var float
     *
     * @ORM\Column(name="ord_nocturnas", type="float", nullable=true)
     */
    private $ordNocturnas;

    /**
     * @var float
     *
     * @ORM\Column(name="ord_diu_extras", type="float", nullable=true)
     */
    private $ordDiuExtras;

    /**
     * @var float
     *
     * @ORM\Column(name="ord_noc_extras", type="float", nullable=true)
     */
    private $ordNocExtras;

    /**
     * @var float
     *
     * @ORM\Column(name="fes_diurnas", type="float", nullable=true)
     */
    private $fesDiurnas;

    /**
     * @var float
     *
     * @ORM\Column(name="fes_nocturnas", type="float", nullable=true)
     */
    private $fesNocturnas;

    /**
     * @var float
     *
     * @ORM\Column(name="fes_diu_extras", type="float", nullable=true)
     */
    private $fesDiuExtras;

    /**
     * @var float
     *
     * @ORM\Column(name="fes_noc_extras", type="float", nullable=true)
     */
    private $fesNocExtras;


    /**
     * Get id
     *
     * @return int
     */
    public function getCodigoSimuladorPk()
    {
        return $this->codigoSimuladorDetallePk;
    }

    /**
     * Set codigoSimulacionFk
     *
     * @param integer $codigoSimulacionFk
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setCodigoSimulacionFk($codigoSimulacionFk)
    {
        $this->codigoSimulacionFk = $codigoSimulacionFk;

        return $this;
    }

    /**
     * Get codigoSimulacionFk
     *
     * @return int
     */
    public function getCodigoSimulacionFk()
    {
        return $this->codigoSimulacionFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurProgramacionSimuladorDetalle
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
     * Set ordDiurnas
     *
     * @param float $ordDiurnas
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setOrdDiurnas($ordDiurnas)
    {
        $this->ordDiurnas = $ordDiurnas;

        return $this;
    }

    /**
     * Get ordDiurnas
     *
     * @return float
     */
    public function getOrdDiurnas()
    {
        return $this->ordDiurnas;
    }

    /**
     * Set ordNocturnas
     *
     * @param float $ordNocturnas
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setOrdNocturnas($ordNocturnas)
    {
        $this->ordNocturnas = $ordNocturnas;

        return $this;
    }

    /**
     * Get ordNocturnas
     *
     * @return float
     */
    public function getOrdNocturnas()
    {
        return $this->ordNocturnas;
    }

    /**
     * Set ordDiuExtras
     *
     * @param float $ordDiuExtras
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setOrdDiuExtras($ordDiuExtras)
    {
        $this->ordDiuExtras = $ordDiuExtras;

        return $this;
    }

    /**
     * Get ordDiuExtras
     *
     * @return float
     */
    public function getOrdDiuExtras()
    {
        return $this->ordDiuExtras;
    }

    /**
     * Set ordNocExtras
     *
     * @param float $ordNocExtras
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setOrdNocExtras($ordNocExtras)
    {
        $this->ordNocExtras = $ordNocExtras;

        return $this;
    }

    /**
     * Get ordNocExtras
     *
     * @return float
     */
    public function getOrdNocExtras()
    {
        return $this->ordNocExtras;
    }

    /**
     * Set fesDiurnas
     *
     * @param float $fesDiurnas
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setFesDiurnas($fesDiurnas)
    {
        $this->fesDiurnas = $fesDiurnas;

        return $this;
    }

    /**
     * Get fesDiurnas
     *
     * @return float
     */
    public function getFesDiurnas()
    {
        return $this->fesDiurnas;
    }

    /**
     * Set fesNocturnas
     *
     * @param float $fesNocturnas
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setFesNocturnas($fesNocturnas)
    {
        $this->fesNocturnas = $fesNocturnas;

        return $this;
    }

    /**
     * Get fesNocturnas
     *
     * @return float
     */
    public function getFesNocturnas()
    {
        return $this->fesNocturnas;
    }

    /**
     * Set fesDiuExtras
     *
     * @param float $fesDiuExtras
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setFesDiuExtras($fesDiuExtras)
    {
        $this->fesDiuExtras = $fesDiuExtras;

        return $this;
    }

    /**
     * Get fesDiuExtras
     *
     * @return float
     */
    public function getFesDiuExtras()
    {
        return $this->fesDiuExtras;
    }

    /**
     * Set fesNocExtras
     *
     * @param float $fesNocExtras
     *
     * @return TurProgramacionSimuladorDetalle
     */
    public function setFesNocExtras($fesNocExtras)
    {
        $this->fesNocExtras = $fesNocExtras;

        return $this;
    }

    /**
     * Get fesNocExtras
     *
     * @return float
     */
    public function getFesNocExtras()
    {
        return $this->fesNocExtras;
    }
}

