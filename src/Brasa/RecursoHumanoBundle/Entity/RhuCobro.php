<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cobro")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCobroRepository")
 */
class RhuCobro
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cobro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCobroPk;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;         
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;       
    
    /**
     * @ORM\Column(name="vr_basico", type="float")
     */
    private $vrBasico = 0;     


    /**
     * Get codigoCobroPk
     *
     * @return integer
     */
    public function getCodigoCobroPk()
    {
        return $this->codigoCobroPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuCobro
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCobro
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
     * Set vrBasico
     *
     * @param float $vrBasico
     *
     * @return RhuCobro
     */
    public function setVrBasico($vrBasico)
    {
        $this->vrBasico = $vrBasico;

        return $this;
    }

    /**
     * Get vrBasico
     *
     * @return float
     */
    public function getVrBasico()
    {
        return $this->vrBasico;
    }
}
