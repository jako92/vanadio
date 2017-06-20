<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_consecutivo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConsecutivoRepository")
 */
class RhuConsecutivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_consecutivo_pk", type="integer")
     */
    private $codigoConsecutivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;       
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */    
    private $consecutivo;    
    

    /**
     * Set codigoConsecutivoPk
     *
     * @param integer $codigoConsecutivoPk
     *
     * @return RhuConsecutivo
     */
    public function setCodigoConsecutivoPk($codigoConsecutivoPk)
    {
        $this->codigoConsecutivoPk = $codigoConsecutivoPk;

        return $this;
    }

    /**
     * Get codigoConsecutivoPk
     *
     * @return integer
     */
    public function getCodigoConsecutivoPk()
    {
        return $this->codigoConsecutivoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuConsecutivo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return RhuConsecutivo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }
}
