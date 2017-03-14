<?php

namespace Brasa\AdministracionDocumentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ad_documento")
 * @ORM\Entity(repositoryClass="Brasa\AdministracionDocumentalBundle\Repository\AdDocumentoRepository")
 */
class AdDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     */
    private $codigoDocumentoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;  
    
 

    /**
     * Set codigoDocumentoPk
     *
     * @param integer $codigoDocumentoPk
     *
     * @return AdDocumento
     */
    public function setCodigoDocumentoPk($codigoDocumentoPk)
    {
        $this->codigoDocumentoPk = $codigoDocumentoPk;

        return $this;
    }

    /**
     * Get codigoDocumentoPk
     *
     * @return integer
     */
    public function getCodigoDocumentoPk()
    {
        return $this->codigoDocumentoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AdDocumento
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
}
