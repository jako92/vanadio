<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_puesto_adicional")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPuestoAdicionalRepository")
 */
class TurPuestoAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoAdicionalPk;        

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;           
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="puestosAdicionalesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;         



    /**
     * Get codigoPuestoAdicionalPk
     *
     * @return integer
     */
    public function getCodigoPuestoAdicionalPk()
    {
        return $this->codigoPuestoAdicionalPk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurPuestoAdicional
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurPuestoAdicional
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return TurPuestoAdicional
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurPuestoAdicional
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
}
