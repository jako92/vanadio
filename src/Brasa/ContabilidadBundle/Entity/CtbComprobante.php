<?php

namespace Brasa\ContabilidadBundle\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_comprobante")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbComprobanteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoComprobantePk"},message="Ya existe el código del comprobante")
 */

class CtbComprobante
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_comprobante_pk", type="integer")
     */    
    private $codigoComprobantePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;      
    
    /**
     * @ORM\Column(name="adicionar_digito_verificacion_intercambio_datos", type="boolean")
     */    
    private $adicionarDigitoVerificacionIntercambioDatos = false;     
    
    /**
     * @ORM\OneToMany(targetEntity="CtbAsiento", mappedBy="comprobanteRel")
     */
    protected $asientosComprobanteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CtbRegistro", mappedBy="comprobanteRel")
     */
    protected $registrosComprobanteRel;        
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->asientosComprobanteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registrosComprobanteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoComprobantePk
     *
     * @param integer $codigoComprobantePk
     *
     * @return CtbComprobante
     */
    public function setCodigoComprobantePk($codigoComprobantePk)
    {
        $this->codigoComprobantePk = $codigoComprobantePk;

        return $this;
    }

    /**
     * Get codigoComprobantePk
     *
     * @return integer
     */
    public function getCodigoComprobantePk()
    {
        return $this->codigoComprobantePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbComprobante
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
     * Set adicionarDigitoVerificacionIntercambioDatos
     *
     * @param boolean $adicionarDigitoVerificacionIntercambioDatos
     *
     * @return CtbComprobante
     */
    public function setAdicionarDigitoVerificacionIntercambioDatos($adicionarDigitoVerificacionIntercambioDatos)
    {
        $this->adicionarDigitoVerificacionIntercambioDatos = $adicionarDigitoVerificacionIntercambioDatos;

        return $this;
    }

    /**
     * Get adicionarDigitoVerificacionIntercambioDatos
     *
     * @return boolean
     */
    public function getAdicionarDigitoVerificacionIntercambioDatos()
    {
        return $this->adicionarDigitoVerificacionIntercambioDatos;
    }

    /**
     * Add asientosComprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsiento $asientosComprobanteRel
     *
     * @return CtbComprobante
     */
    public function addAsientosComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbAsiento $asientosComprobanteRel)
    {
        $this->asientosComprobanteRel[] = $asientosComprobanteRel;

        return $this;
    }

    /**
     * Remove asientosComprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbAsiento $asientosComprobanteRel
     */
    public function removeAsientosComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbAsiento $asientosComprobanteRel)
    {
        $this->asientosComprobanteRel->removeElement($asientosComprobanteRel);
    }

    /**
     * Get asientosComprobanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsientosComprobanteRel()
    {
        return $this->asientosComprobanteRel;
    }

    /**
     * Add registrosComprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosComprobanteRel
     *
     * @return CtbComprobante
     */
    public function addRegistrosComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosComprobanteRel)
    {
        $this->registrosComprobanteRel[] = $registrosComprobanteRel;

        return $this;
    }

    /**
     * Remove registrosComprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosComprobanteRel
     */
    public function removeRegistrosComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbRegistro $registrosComprobanteRel)
    {
        $this->registrosComprobanteRel->removeElement($registrosComprobanteRel);
    }

    /**
     * Get registrosComprobanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosComprobanteRel()
    {
        return $this->registrosComprobanteRel;
    }
}
