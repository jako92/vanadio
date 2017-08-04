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

    /**
     * Set dia1
     *
     * @param integer $dia1
     *
     * @return TurProgramacionSimulador
     */
    public function setDia1($dia1)
    {
        $this->dia1 = $dia1;

        return $this;
    }

    /**
     * Get dia1
     *
     * @return integer
     */
    public function getDia1()
    {
        return $this->dia1;
    }

    /**
     * Set dia2
     *
     * @param integer $dia2
     *
     * @return TurProgramacionSimulador
     */
    public function setDia2($dia2)
    {
        $this->dia2 = $dia2;

        return $this;
    }

    /**
     * Get dia2
     *
     * @return integer
     */
    public function getDia2()
    {
        return $this->dia2;
    }

    /**
     * Set dia3
     *
     * @param integer $dia3
     *
     * @return TurProgramacionSimulador
     */
    public function setDia3($dia3)
    {
        $this->dia3 = $dia3;

        return $this;
    }

    /**
     * Get dia3
     *
     * @return integer
     */
    public function getDia3()
    {
        return $this->dia3;
    }

    /**
     * Set dia4
     *
     * @param integer $dia4
     *
     * @return TurProgramacionSimulador
     */
    public function setDia4($dia4)
    {
        $this->dia4 = $dia4;

        return $this;
    }

    /**
     * Get dia4
     *
     * @return integer
     */
    public function getDia4()
    {
        return $this->dia4;
    }

    /**
     * Set dia5
     *
     * @param integer $dia5
     *
     * @return TurProgramacionSimulador
     */
    public function setDia5($dia5)
    {
        $this->dia5 = $dia5;

        return $this;
    }

    /**
     * Get dia5
     *
     * @return integer
     */
    public function getDia5()
    {
        return $this->dia5;
    }

    /**
     * Set dia6
     *
     * @param integer $dia6
     *
     * @return TurProgramacionSimulador
     */
    public function setDia6($dia6)
    {
        $this->dia6 = $dia6;

        return $this;
    }

    /**
     * Get dia6
     *
     * @return integer
     */
    public function getDia6()
    {
        return $this->dia6;
    }

    /**
     * Set dia7
     *
     * @param integer $dia7
     *
     * @return TurProgramacionSimulador
     */
    public function setDia7($dia7)
    {
        $this->dia7 = $dia7;

        return $this;
    }

    /**
     * Get dia7
     *
     * @return integer
     */
    public function getDia7()
    {
        return $this->dia7;
    }

    /**
     * Set dia8
     *
     * @param integer $dia8
     *
     * @return TurProgramacionSimulador
     */
    public function setDia8($dia8)
    {
        $this->dia8 = $dia8;

        return $this;
    }

    /**
     * Get dia8
     *
     * @return integer
     */
    public function getDia8()
    {
        return $this->dia8;
    }

    /**
     * Set dia9
     *
     * @param integer $dia9
     *
     * @return TurProgramacionSimulador
     */
    public function setDia9($dia9)
    {
        $this->dia9 = $dia9;

        return $this;
    }

    /**
     * Get dia9
     *
     * @return integer
     */
    public function getDia9()
    {
        return $this->dia9;
    }

    /**
     * Set dia10
     *
     * @param integer $dia10
     *
     * @return TurProgramacionSimulador
     */
    public function setDia10($dia10)
    {
        $this->dia10 = $dia10;

        return $this;
    }

    /**
     * Get dia10
     *
     * @return integer
     */
    public function getDia10()
    {
        return $this->dia10;
    }

    /**
     * Set dia11
     *
     * @param integer $dia11
     *
     * @return TurProgramacionSimulador
     */
    public function setDia11($dia11)
    {
        $this->dia11 = $dia11;

        return $this;
    }

    /**
     * Get dia11
     *
     * @return integer
     */
    public function getDia11()
    {
        return $this->dia11;
    }

    /**
     * Set dia12
     *
     * @param integer $dia12
     *
     * @return TurProgramacionSimulador
     */
    public function setDia12($dia12)
    {
        $this->dia12 = $dia12;

        return $this;
    }

    /**
     * Get dia12
     *
     * @return integer
     */
    public function getDia12()
    {
        return $this->dia12;
    }

    /**
     * Set dia13
     *
     * @param integer $dia13
     *
     * @return TurProgramacionSimulador
     */
    public function setDia13($dia13)
    {
        $this->dia13 = $dia13;

        return $this;
    }

    /**
     * Get dia13
     *
     * @return integer
     */
    public function getDia13()
    {
        return $this->dia13;
    }

    /**
     * Set dia14
     *
     * @param integer $dia14
     *
     * @return TurProgramacionSimulador
     */
    public function setDia14($dia14)
    {
        $this->dia14 = $dia14;

        return $this;
    }

    /**
     * Get dia14
     *
     * @return integer
     */
    public function getDia14()
    {
        return $this->dia14;
    }

    /**
     * Set dia15
     *
     * @param integer $dia15
     *
     * @return TurProgramacionSimulador
     */
    public function setDia15($dia15)
    {
        $this->dia15 = $dia15;

        return $this;
    }

    /**
     * Get dia15
     *
     * @return integer
     */
    public function getDia15()
    {
        return $this->dia15;
    }

    /**
     * Set dia16
     *
     * @param integer $dia16
     *
     * @return TurProgramacionSimulador
     */
    public function setDia16($dia16)
    {
        $this->dia16 = $dia16;

        return $this;
    }

    /**
     * Get dia16
     *
     * @return integer
     */
    public function getDia16()
    {
        return $this->dia16;
    }

    /**
     * Set dia17
     *
     * @param integer $dia17
     *
     * @return TurProgramacionSimulador
     */
    public function setDia17($dia17)
    {
        $this->dia17 = $dia17;

        return $this;
    }

    /**
     * Get dia17
     *
     * @return integer
     */
    public function getDia17()
    {
        return $this->dia17;
    }

    /**
     * Set dia18
     *
     * @param integer $dia18
     *
     * @return TurProgramacionSimulador
     */
    public function setDia18($dia18)
    {
        $this->dia18 = $dia18;

        return $this;
    }

    /**
     * Get dia18
     *
     * @return integer
     */
    public function getDia18()
    {
        return $this->dia18;
    }

    /**
     * Set dia19
     *
     * @param integer $dia19
     *
     * @return TurProgramacionSimulador
     */
    public function setDia19($dia19)
    {
        $this->dia19 = $dia19;

        return $this;
    }

    /**
     * Get dia19
     *
     * @return integer
     */
    public function getDia19()
    {
        return $this->dia19;
    }

    /**
     * Set dia20
     *
     * @param integer $dia20
     *
     * @return TurProgramacionSimulador
     */
    public function setDia20($dia20)
    {
        $this->dia20 = $dia20;

        return $this;
    }

    /**
     * Get dia20
     *
     * @return integer
     */
    public function getDia20()
    {
        return $this->dia20;
    }

    /**
     * Set dia21
     *
     * @param integer $dia21
     *
     * @return TurProgramacionSimulador
     */
    public function setDia21($dia21)
    {
        $this->dia21 = $dia21;

        return $this;
    }

    /**
     * Get dia21
     *
     * @return integer
     */
    public function getDia21()
    {
        return $this->dia21;
    }

    /**
     * Set dia22
     *
     * @param integer $dia22
     *
     * @return TurProgramacionSimulador
     */
    public function setDia22($dia22)
    {
        $this->dia22 = $dia22;

        return $this;
    }

    /**
     * Get dia22
     *
     * @return integer
     */
    public function getDia22()
    {
        return $this->dia22;
    }

    /**
     * Set dia23
     *
     * @param integer $dia23
     *
     * @return TurProgramacionSimulador
     */
    public function setDia23($dia23)
    {
        $this->dia23 = $dia23;

        return $this;
    }

    /**
     * Get dia23
     *
     * @return integer
     */
    public function getDia23()
    {
        return $this->dia23;
    }

    /**
     * Set dia24
     *
     * @param integer $dia24
     *
     * @return TurProgramacionSimulador
     */
    public function setDia24($dia24)
    {
        $this->dia24 = $dia24;

        return $this;
    }

    /**
     * Get dia24
     *
     * @return integer
     */
    public function getDia24()
    {
        return $this->dia24;
    }

    /**
     * Set dia25
     *
     * @param integer $dia25
     *
     * @return TurProgramacionSimulador
     */
    public function setDia25($dia25)
    {
        $this->dia25 = $dia25;

        return $this;
    }

    /**
     * Get dia25
     *
     * @return integer
     */
    public function getDia25()
    {
        return $this->dia25;
    }

    /**
     * Set dia26
     *
     * @param integer $dia26
     *
     * @return TurProgramacionSimulador
     */
    public function setDia26($dia26)
    {
        $this->dia26 = $dia26;

        return $this;
    }

    /**
     * Get dia26
     *
     * @return integer
     */
    public function getDia26()
    {
        return $this->dia26;
    }

    /**
     * Set dia27
     *
     * @param integer $dia27
     *
     * @return TurProgramacionSimulador
     */
    public function setDia27($dia27)
    {
        $this->dia27 = $dia27;

        return $this;
    }

    /**
     * Get dia27
     *
     * @return integer
     */
    public function getDia27()
    {
        return $this->dia27;
    }

    /**
     * Set dia28
     *
     * @param integer $dia28
     *
     * @return TurProgramacionSimulador
     */
    public function setDia28($dia28)
    {
        $this->dia28 = $dia28;

        return $this;
    }

    /**
     * Get dia28
     *
     * @return integer
     */
    public function getDia28()
    {
        return $this->dia28;
    }

    /**
     * Set dia29
     *
     * @param integer $dia29
     *
     * @return TurProgramacionSimulador
     */
    public function setDia29($dia29)
    {
        $this->dia29 = $dia29;

        return $this;
    }

    /**
     * Get dia29
     *
     * @return integer
     */
    public function getDia29()
    {
        return $this->dia29;
    }

    /**
     * Set dia30
     *
     * @param integer $dia30
     *
     * @return TurProgramacionSimulador
     */
    public function setDia30($dia30)
    {
        $this->dia30 = $dia30;

        return $this;
    }

    /**
     * Get dia30
     *
     * @return integer
     */
    public function getDia30()
    {
        return $this->dia30;
    }

    /**
     * Set dia31
     *
     * @param integer $dia31
     *
     * @return TurProgramacionSimulador
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return integer
     */
    public function getDia31()
    {
        return $this->dia31;
    }
}
