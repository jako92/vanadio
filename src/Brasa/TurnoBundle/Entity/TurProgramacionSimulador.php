<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurProgramacionSimulador
 *
 * @ORM\Table(name="tur_programacion_simulador")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProgramacionSimuladorRepository")
 */
class TurProgramacionSimulador {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_simulacion_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSimulacionPk;
    /**
     * @var int
     * @ORM\Column(name="codigo_recurso_fk", type="integer")
     */
    private $codigoRecursoFk;
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="programacionSumulacionesRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    private $recursoRel;
    
    
    /************************************************
     *      Atributos para los días del mes         * 
     ************************************************/
/**
    * @var int
    * @ORM\Column(name="dia_1", type="integer", nullable=true)
    */
    private $dia1;

    /**
    * @var int
    * @ORM\Column(name="dia_2", type="integer", nullable=true)
    */
    private $dia2;

    /**
    * @var int
    * @ORM\Column(name="dia_3", type="integer", nullable=true)
    */
    private $dia3;

    /**
    * @var int
    * @ORM\Column(name="dia_4", type="integer", nullable=true)
    */
    private $dia4;

    /**
    * @var int
    * @ORM\Column(name="dia_5", type="integer", nullable=true)
    */
    private $dia5;

    /**
    * @var int
    * @ORM\Column(name="dia_6", type="integer", nullable=true)
    */
    private $dia6;

    /**
    * @var int
    * @ORM\Column(name="dia_7", type="integer", nullable=true)
    */
    private $dia7;

    /**
    * @var int
    * @ORM\Column(name="dia_8", type="integer", nullable=true)
    */
    private $dia8;

    /**
    * @var int
    * @ORM\Column(name="dia_9", type="integer", nullable=true)
    */
    private $dia9;

    /**
    * @var int
    * @ORM\Column(name="dia_10", type="integer", nullable=true)
    */
    private $dia10;

    /**
    * @var int
    * @ORM\Column(name="dia_11", type="integer", nullable=true)
    */
    private $dia11;

    /**
    * @var int
    * @ORM\Column(name="dia_12", type="integer", nullable=true)
    */
    private $dia12;

    /**
    * @var int
    * @ORM\Column(name="dia_13", type="integer", nullable=true)
    */
    private $dia13;

    /**
    * @var int
    * @ORM\Column(name="dia_14", type="integer", nullable=true)
    */
    private $dia14;

    /**
    * @var int
    * @ORM\Column(name="dia_15", type="integer", nullable=true)
    */
    private $dia15;

    /**
    * @var int
    * @ORM\Column(name="dia_16", type="integer", nullable=true)
    */
    private $dia16;

    /**
    * @var int
    * @ORM\Column(name="dia_17", type="integer", nullable=true)
    */
    private $dia17;

    /**
    * @var int
    * @ORM\Column(name="dia_18", type="integer", nullable=true)
    */
    private $dia18;

    /**
    * @var int
    * @ORM\Column(name="dia_19", type="integer", nullable=true)
    */
    private $dia19;

    /**
    * @var int
    * @ORM\Column(name="dia_20", type="integer", nullable=true)
    */
    private $dia20;

    /**
    * @var int
    * @ORM\Column(name="dia_21", type="integer", nullable=true)
    */
    private $dia21;

    /**
    * @var int
    * @ORM\Column(name="dia_22", type="integer", nullable=true)
    */
    private $dia22;

    /**
    * @var int
    * @ORM\Column(name="dia_23", type="integer", nullable=true)
    */
    private $dia23;

    /**
    * @var int
    * @ORM\Column(name="dia_24", type="integer", nullable=true)
    */
    private $dia24;

    /**
    * @var int
    * @ORM\Column(name="dia_25", type="integer", nullable=true)
    */
    private $dia25;

    /**
    * @var int
    * @ORM\Column(name="dia_26", type="integer", nullable=true)
    */
    private $dia26;

    /**
    * @var int
    * @ORM\Column(name="dia_27", type="integer", nullable=true)
    */
    private $dia27;

    /**
    * @var int
    * @ORM\Column(name="dia_28", type="integer", nullable=true)
    */
    private $dia28;

    /**
    * @var int
    * @ORM\Column(name="dia_29", type="integer", nullable=true)
    */
    private $dia29;

    /**
    * @var int
    * @ORM\Column(name="dia_30", type="integer", nullable=true)
    */
    private $dia30;

    /**
    * @var int
    * @ORM\Column(name="dia_31", type="integer", nullable=true)
    */
    private $dia31;

    /************************************************
     *    Fin Atributos para los días del mes       *
     ************************************************/
    
    public function getCodigoSimulacionPk()
    {
        return $this->codigoSimulacionPk;
    }
    public function setCodigoSimulacionPk($valor)
    {   
        $this->codigoSimulacionPk = $valor;
        return $this;
    }
    
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }
    public function setRecursoRel($valor)
    {   
        $this->recursoRel = $valor;
        return $this;
    }
    
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }
    public function setCodigoRecursoFk($valor)
    {   
        $this->codigoRecursoFk = $valor;
        return $this;
    }
    public function getDia($dia){
        if(property_exists($this, "dia{$dia}")) return $this->{"dia{$dia}"};
        return null;
    }
    public function setDia($dia, $valor){
        if(property_exists($this, "dia{$dia}")) $this->{"dia{$dia}"} = $valor;
    }
}
