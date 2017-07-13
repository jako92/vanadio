<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_programacion_importar")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProgramacionImportarRepository")
 */
class TurProgramacionImportar {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_importar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionImportarPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */
    private $codigoPuestoFk;

    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */
    private $codigoRecursoFk;

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */
    private $codigoPedidoDetalleFk;

    /**
     * @ORM\Column(name="nombre_cliente", type="string", length=120, nullable=true)
     */
    private $nombreCliente;

    /**
     * @ORM\Column(name="nombre_puesto", type="string", length=300, nullable=true)
     */
    private $nombrePuesto;

    /**
     * @ORM\Column(name="nombre_recurso", type="string", length=120, nullable=true)
     */
    private $nombreRecurso;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="dia_1", type="string", length=5, nullable=true)
     */
    private $dia1;

    /**
     * @ORM\Column(name="dia_2", type="string", length=5, nullable=true)
     */
    private $dia2;

    /**
     * @ORM\Column(name="dia_3", type="string", length=5, nullable=true)
     */
    private $dia3;

    /**
     * @ORM\Column(name="dia_4", type="string", length=5, nullable=true)
     */
    private $dia4;

    /**
     * @ORM\Column(name="dia_5", type="string", length=5, nullable=true)
     */
    private $dia5;

    /**
     * @ORM\Column(name="dia_6", type="string", length=5, nullable=true)
     */
    private $dia6;

    /**
     * @ORM\Column(name="dia_7", type="string", length=5, nullable=true)
     */
    private $dia7;

    /**
     * @ORM\Column(name="dia_8", type="string", length=5, nullable=true)
     */
    private $dia8;

    /**
     * @ORM\Column(name="dia_9", type="string", length=5, nullable=true)
     */
    private $dia9;

    /**
     * @ORM\Column(name="dia_10", type="string", length=5, nullable=true)
     */
    private $dia10;

    /**
     * @ORM\Column(name="dia_11", type="string", length=5, nullable=true)
     */
    private $dia11;

    /**
     * @ORM\Column(name="dia_12", type="string", length=5, nullable=true)
     */
    private $dia12;

    /**
     * @ORM\Column(name="dia_13", type="string", length=5, nullable=true)
     */
    private $dia13;

    /**
     * @ORM\Column(name="dia_14", type="string", length=5, nullable=true)
     */
    private $dia14;

    /**
     * @ORM\Column(name="dia_15", type="string", length=5, nullable=true)
     */
    private $dia15;

    /**
     * @ORM\Column(name="dia_16", type="string", length=5, nullable=true)
     */
    private $dia16;

    /**
     * @ORM\Column(name="dia_17", type="string", length=5, nullable=true)
     */
    private $dia17;

    /**
     * @ORM\Column(name="dia_18", type="string", length=5, nullable=true)
     */
    private $dia18;

    /**
     * @ORM\Column(name="dia_19", type="string", length=5, nullable=true)
     */
    private $dia19;

    /**
     * @ORM\Column(name="dia_20", type="string", length=5, nullable=true)
     */
    private $dia20;

    /**
     * @ORM\Column(name="dia_21", type="string", length=5, nullable=true)
     */
    private $dia21;

    /**
     * @ORM\Column(name="dia_22", type="string", length=5, nullable=true)
     */
    private $dia22;

    /**
     * @ORM\Column(name="dia_23", type="string", length=5, nullable=true)
     */
    private $dia23;

    /**
     * @ORM\Column(name="dia_24", type="string", length=5, nullable=true)
     */
    private $dia24;

    /**
     * @ORM\Column(name="dia_25", type="string", length=5, nullable=true)
     */
    private $dia25;

    /**
     * @ORM\Column(name="dia_26", type="string", length=5, nullable=true)
     */
    private $dia26;

    /**
     * @ORM\Column(name="dia_27", type="string", length=5, nullable=true)
     */
    private $dia27;

    /**
     * @ORM\Column(name="dia_28", type="string", length=5, nullable=true)
     */
    private $dia28;

    /**
     * @ORM\Column(name="dia_29", type="string", length=5, nullable=true)
     */
    private $dia29;

    /**
     * @ORM\Column(name="dia_30", type="string", length=5, nullable=true)
     */
    private $dia30;

    /**
     * @ORM\Column(name="dia_31", type="string", length=5, nullable=true)
     */
    private $dia31;

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
     * @ORM\Column(name="horas_pedido", type="float")
     */
    private $horasPedido = 0;

    /**
     * @ORM\Column(name="horas_diurnas_pedido", type="float")
     */
    private $horasDiurnasPedido = 0;

    /**
     * @ORM\Column(name="horas_nocturnas_pedido", type="float")
     */
    private $horasNocturnasPedido = 0;

    /**
     * @ORM\Column(name="estado_programado", type="boolean")
     */
    private $estadoProgramado = false;


    /**
     * Get codigoProgramacionImportarPk
     *
     * @return integer
     */
    public function getCodigoProgramacionImportarPk()
    {
        return $this->codigoProgramacionImportarPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurProgramacionImportar
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
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurProgramacionImportar
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
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurProgramacionImportar
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set codigoPedidoDetalleFk
     *
     * @param integer $codigoPedidoDetalleFk
     *
     * @return TurProgramacionImportar
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
     * Set nombreCliente
     *
     * @param string $nombreCliente
     *
     * @return TurProgramacionImportar
     */
    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;

        return $this;
    }

    /**
     * Get nombreCliente
     *
     * @return string
     */
    public function getNombreCliente()
    {
        return $this->nombreCliente;
    }

    /**
     * Set nombrePuesto
     *
     * @param string $nombrePuesto
     *
     * @return TurProgramacionImportar
     */
    public function setNombrePuesto($nombrePuesto)
    {
        $this->nombrePuesto = $nombrePuesto;

        return $this;
    }

    /**
     * Get nombrePuesto
     *
     * @return string
     */
    public function getNombrePuesto()
    {
        return $this->nombrePuesto;
    }

    /**
     * Set nombreRecurso
     *
     * @param string $nombreRecurso
     *
     * @return TurProgramacionImportar
     */
    public function setNombreRecurso($nombreRecurso)
    {
        $this->nombreRecurso = $nombreRecurso;

        return $this;
    }

    /**
     * Get nombreRecurso
     *
     * @return string
     */
    public function getNombreRecurso()
    {
        return $this->nombreRecurso;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurProgramacionImportar
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
     * @return TurProgramacionImportar
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
     * Set dia1
     *
     * @param string $dia1
     *
     * @return TurProgramacionImportar
     */
    public function setDia1($dia1)
    {
        $this->dia1 = $dia1;

        return $this;
    }

    /**
     * Get dia1
     *
     * @return string
     */
    public function getDia1()
    {
        return $this->dia1;
    }

    /**
     * Set dia2
     *
     * @param string $dia2
     *
     * @return TurProgramacionImportar
     */
    public function setDia2($dia2)
    {
        $this->dia2 = $dia2;

        return $this;
    }

    /**
     * Get dia2
     *
     * @return string
     */
    public function getDia2()
    {
        return $this->dia2;
    }

    /**
     * Set dia3
     *
     * @param string $dia3
     *
     * @return TurProgramacionImportar
     */
    public function setDia3($dia3)
    {
        $this->dia3 = $dia3;

        return $this;
    }

    /**
     * Get dia3
     *
     * @return string
     */
    public function getDia3()
    {
        return $this->dia3;
    }

    /**
     * Set dia4
     *
     * @param string $dia4
     *
     * @return TurProgramacionImportar
     */
    public function setDia4($dia4)
    {
        $this->dia4 = $dia4;

        return $this;
    }

    /**
     * Get dia4
     *
     * @return string
     */
    public function getDia4()
    {
        return $this->dia4;
    }

    /**
     * Set dia5
     *
     * @param string $dia5
     *
     * @return TurProgramacionImportar
     */
    public function setDia5($dia5)
    {
        $this->dia5 = $dia5;

        return $this;
    }

    /**
     * Get dia5
     *
     * @return string
     */
    public function getDia5()
    {
        return $this->dia5;
    }

    /**
     * Set dia6
     *
     * @param string $dia6
     *
     * @return TurProgramacionImportar
     */
    public function setDia6($dia6)
    {
        $this->dia6 = $dia6;

        return $this;
    }

    /**
     * Get dia6
     *
     * @return string
     */
    public function getDia6()
    {
        return $this->dia6;
    }

    /**
     * Set dia7
     *
     * @param string $dia7
     *
     * @return TurProgramacionImportar
     */
    public function setDia7($dia7)
    {
        $this->dia7 = $dia7;

        return $this;
    }

    /**
     * Get dia7
     *
     * @return string
     */
    public function getDia7()
    {
        return $this->dia7;
    }

    /**
     * Set dia8
     *
     * @param string $dia8
     *
     * @return TurProgramacionImportar
     */
    public function setDia8($dia8)
    {
        $this->dia8 = $dia8;

        return $this;
    }

    /**
     * Get dia8
     *
     * @return string
     */
    public function getDia8()
    {
        return $this->dia8;
    }

    /**
     * Set dia9
     *
     * @param string $dia9
     *
     * @return TurProgramacionImportar
     */
    public function setDia9($dia9)
    {
        $this->dia9 = $dia9;

        return $this;
    }

    /**
     * Get dia9
     *
     * @return string
     */
    public function getDia9()
    {
        return $this->dia9;
    }

    /**
     * Set dia10
     *
     * @param string $dia10
     *
     * @return TurProgramacionImportar
     */
    public function setDia10($dia10)
    {
        $this->dia10 = $dia10;

        return $this;
    }

    /**
     * Get dia10
     *
     * @return string
     */
    public function getDia10()
    {
        return $this->dia10;
    }

    /**
     * Set dia11
     *
     * @param string $dia11
     *
     * @return TurProgramacionImportar
     */
    public function setDia11($dia11)
    {
        $this->dia11 = $dia11;

        return $this;
    }

    /**
     * Get dia11
     *
     * @return string
     */
    public function getDia11()
    {
        return $this->dia11;
    }

    /**
     * Set dia12
     *
     * @param string $dia12
     *
     * @return TurProgramacionImportar
     */
    public function setDia12($dia12)
    {
        $this->dia12 = $dia12;

        return $this;
    }

    /**
     * Get dia12
     *
     * @return string
     */
    public function getDia12()
    {
        return $this->dia12;
    }

    /**
     * Set dia13
     *
     * @param string $dia13
     *
     * @return TurProgramacionImportar
     */
    public function setDia13($dia13)
    {
        $this->dia13 = $dia13;

        return $this;
    }

    /**
     * Get dia13
     *
     * @return string
     */
    public function getDia13()
    {
        return $this->dia13;
    }

    /**
     * Set dia14
     *
     * @param string $dia14
     *
     * @return TurProgramacionImportar
     */
    public function setDia14($dia14)
    {
        $this->dia14 = $dia14;

        return $this;
    }

    /**
     * Get dia14
     *
     * @return string
     */
    public function getDia14()
    {
        return $this->dia14;
    }

    /**
     * Set dia15
     *
     * @param string $dia15
     *
     * @return TurProgramacionImportar
     */
    public function setDia15($dia15)
    {
        $this->dia15 = $dia15;

        return $this;
    }

    /**
     * Get dia15
     *
     * @return string
     */
    public function getDia15()
    {
        return $this->dia15;
    }

    /**
     * Set dia16
     *
     * @param string $dia16
     *
     * @return TurProgramacionImportar
     */
    public function setDia16($dia16)
    {
        $this->dia16 = $dia16;

        return $this;
    }

    /**
     * Get dia16
     *
     * @return string
     */
    public function getDia16()
    {
        return $this->dia16;
    }

    /**
     * Set dia17
     *
     * @param string $dia17
     *
     * @return TurProgramacionImportar
     */
    public function setDia17($dia17)
    {
        $this->dia17 = $dia17;

        return $this;
    }

    /**
     * Get dia17
     *
     * @return string
     */
    public function getDia17()
    {
        return $this->dia17;
    }

    /**
     * Set dia18
     *
     * @param string $dia18
     *
     * @return TurProgramacionImportar
     */
    public function setDia18($dia18)
    {
        $this->dia18 = $dia18;

        return $this;
    }

    /**
     * Get dia18
     *
     * @return string
     */
    public function getDia18()
    {
        return $this->dia18;
    }

    /**
     * Set dia19
     *
     * @param string $dia19
     *
     * @return TurProgramacionImportar
     */
    public function setDia19($dia19)
    {
        $this->dia19 = $dia19;

        return $this;
    }

    /**
     * Get dia19
     *
     * @return string
     */
    public function getDia19()
    {
        return $this->dia19;
    }

    /**
     * Set dia20
     *
     * @param string $dia20
     *
     * @return TurProgramacionImportar
     */
    public function setDia20($dia20)
    {
        $this->dia20 = $dia20;

        return $this;
    }

    /**
     * Get dia20
     *
     * @return string
     */
    public function getDia20()
    {
        return $this->dia20;
    }

    /**
     * Set dia21
     *
     * @param string $dia21
     *
     * @return TurProgramacionImportar
     */
    public function setDia21($dia21)
    {
        $this->dia21 = $dia21;

        return $this;
    }

    /**
     * Get dia21
     *
     * @return string
     */
    public function getDia21()
    {
        return $this->dia21;
    }

    /**
     * Set dia22
     *
     * @param string $dia22
     *
     * @return TurProgramacionImportar
     */
    public function setDia22($dia22)
    {
        $this->dia22 = $dia22;

        return $this;
    }

    /**
     * Get dia22
     *
     * @return string
     */
    public function getDia22()
    {
        return $this->dia22;
    }

    /**
     * Set dia23
     *
     * @param string $dia23
     *
     * @return TurProgramacionImportar
     */
    public function setDia23($dia23)
    {
        $this->dia23 = $dia23;

        return $this;
    }

    /**
     * Get dia23
     *
     * @return string
     */
    public function getDia23()
    {
        return $this->dia23;
    }

    /**
     * Set dia24
     *
     * @param string $dia24
     *
     * @return TurProgramacionImportar
     */
    public function setDia24($dia24)
    {
        $this->dia24 = $dia24;

        return $this;
    }

    /**
     * Get dia24
     *
     * @return string
     */
    public function getDia24()
    {
        return $this->dia24;
    }

    /**
     * Set dia25
     *
     * @param string $dia25
     *
     * @return TurProgramacionImportar
     */
    public function setDia25($dia25)
    {
        $this->dia25 = $dia25;

        return $this;
    }

    /**
     * Get dia25
     *
     * @return string
     */
    public function getDia25()
    {
        return $this->dia25;
    }

    /**
     * Set dia26
     *
     * @param string $dia26
     *
     * @return TurProgramacionImportar
     */
    public function setDia26($dia26)
    {
        $this->dia26 = $dia26;

        return $this;
    }

    /**
     * Get dia26
     *
     * @return string
     */
    public function getDia26()
    {
        return $this->dia26;
    }

    /**
     * Set dia27
     *
     * @param string $dia27
     *
     * @return TurProgramacionImportar
     */
    public function setDia27($dia27)
    {
        $this->dia27 = $dia27;

        return $this;
    }

    /**
     * Get dia27
     *
     * @return string
     */
    public function getDia27()
    {
        return $this->dia27;
    }

    /**
     * Set dia28
     *
     * @param string $dia28
     *
     * @return TurProgramacionImportar
     */
    public function setDia28($dia28)
    {
        $this->dia28 = $dia28;

        return $this;
    }

    /**
     * Get dia28
     *
     * @return string
     */
    public function getDia28()
    {
        return $this->dia28;
    }

    /**
     * Set dia29
     *
     * @param string $dia29
     *
     * @return TurProgramacionImportar
     */
    public function setDia29($dia29)
    {
        $this->dia29 = $dia29;

        return $this;
    }

    /**
     * Get dia29
     *
     * @return string
     */
    public function getDia29()
    {
        return $this->dia29;
    }

    /**
     * Set dia30
     *
     * @param string $dia30
     *
     * @return TurProgramacionImportar
     */
    public function setDia30($dia30)
    {
        $this->dia30 = $dia30;

        return $this;
    }

    /**
     * Get dia30
     *
     * @return string
     */
    public function getDia30()
    {
        return $this->dia30;
    }

    /**
     * Set dia31
     *
     * @param string $dia31
     *
     * @return TurProgramacionImportar
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return string
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * Set horas
     *
     * @param float $horas
     *
     * @return TurProgramacionImportar
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
     * @return TurProgramacionImportar
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
     * @return TurProgramacionImportar
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
     * Set horasPedido
     *
     * @param float $horasPedido
     *
     * @return TurProgramacionImportar
     */
    public function setHorasPedido($horasPedido)
    {
        $this->horasPedido = $horasPedido;

        return $this;
    }

    /**
     * Get horasPedido
     *
     * @return float
     */
    public function getHorasPedido()
    {
        return $this->horasPedido;
    }

    /**
     * Set horasDiurnasPedido
     *
     * @param float $horasDiurnasPedido
     *
     * @return TurProgramacionImportar
     */
    public function setHorasDiurnasPedido($horasDiurnasPedido)
    {
        $this->horasDiurnasPedido = $horasDiurnasPedido;

        return $this;
    }

    /**
     * Get horasDiurnasPedido
     *
     * @return float
     */
    public function getHorasDiurnasPedido()
    {
        return $this->horasDiurnasPedido;
    }

    /**
     * Set horasNocturnasPedido
     *
     * @param float $horasNocturnasPedido
     *
     * @return TurProgramacionImportar
     */
    public function setHorasNocturnasPedido($horasNocturnasPedido)
    {
        $this->horasNocturnasPedido = $horasNocturnasPedido;

        return $this;
    }

    /**
     * Get horasNocturnasPedido
     *
     * @return float
     */
    public function getHorasNocturnasPedido()
    {
        return $this->horasNocturnasPedido;
    }

    /**
     * Set estadoProgramado
     *
     * @param boolean $estadoProgramado
     *
     * @return TurProgramacionImportar
     */
    public function setEstadoProgramado($estadoProgramado)
    {
        $this->estadoProgramado = $estadoProgramado;

        return $this;
    }

    /**
     * Get estadoProgramado
     *
     * @return boolean
     */
    public function getEstadoProgramado()
    {
        return $this->estadoProgramado;
    }
}
