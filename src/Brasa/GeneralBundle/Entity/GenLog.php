<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_log")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenLogRepository")
 */
class GenLog
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLogPk;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;    
    
    /**
     * @ORM\Column(name="codigo_log_accion_fk", type="integer")
     */
    private $codigoLogAccionFk;          
    
    /**
     * @ORM\Column(name="fecha", type="datetime")
     */    
    private $fecha;  
    
    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer")
     */
    private $codigoDocumentoFk;    
    
    /**
     * @ORM\Column(name="id", type="integer")
     */
    private $id;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\SegDocumento", inversedBy="logsDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;              
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="logsUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenLogAccion", inversedBy="logsLogAccionRel")
     * @ORM\JoinColumn(name="codigo_log_accion_fk", referencedColumnName="codigo_log_accion_pk")
     */
    protected $logAccionRel; 



    /**
     * Get codigoLogPk
     *
     * @return integer
     */
    public function getCodigoLogPk()
    {
        return $this->codigoLogPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return GenLog
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return integer
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set codigoLogAccionFk
     *
     * @param integer $codigoLogAccionFk
     *
     * @return GenLog
     */
    public function setCodigoLogAccionFk($codigoLogAccionFk)
    {
        $this->codigoLogAccionFk = $codigoLogAccionFk;

        return $this;
    }

    /**
     * Get codigoLogAccionFk
     *
     * @return integer
     */
    public function getCodigoLogAccionFk()
    {
        return $this->codigoLogAccionFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return GenLog
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
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return GenLog
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk)
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel
     *
     * @return GenLog
     */
    public function setDocumentoRel(\Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     *
     * @return GenLog
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel = null)
    {
        $this->usuarioRel = $usuarioRel;

        return $this;
    }

    /**
     * Get usuarioRel
     *
     * @return \Brasa\SeguridadBundle\Entity\User
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * Set logAccionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLogAccion $logAccionRel
     *
     * @return GenLog
     */
    public function setLogAccionRel(\Brasa\GeneralBundle\Entity\GenLogAccion $logAccionRel = null)
    {
        $this->logAccionRel = $logAccionRel;

        return $this;
    }

    /**
     * Get logAccionRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenLogAccion
     */
    public function getLogAccionRel()
    {
        return $this->logAccionRel;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return GenLog
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
