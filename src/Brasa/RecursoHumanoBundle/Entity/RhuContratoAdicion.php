<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_adicion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoAdicionRepository")
 */
class RhuContratoAdicion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_adicion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoAdicionPk;        
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;                    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;                                           
    
    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */    
    private $contenido;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
           

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="contratosAdicionalesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     


        

    /**
     * Get codigoContratoAdicionPk
     *
     * @return integer
     */
    public function getCodigoContratoAdicionPk()
    {
        return $this->codigoContratoAdicionPk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuContratoAdicion
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuContratoAdicion
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
     * Set contenido
     *
     * @param string $contenido
     *
     * @return RhuContratoAdicion
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuContratoAdicion
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuContratoAdicion
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }
}
