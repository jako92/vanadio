<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_contenido")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenContenidoRepository")
 */
class GenContenido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contenido_pk", type="integer")
     */
    private $codigoContenidoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="numero_formato", type="integer", nullable=true)
     */    
    private $numero_formato;    

    /**
     * @ORM\Column(name="titulo", type="string", length=300, nullable=true)
     */    
    private $titulo;     
    
    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */    
    private $contenido;
    
    /**
     * @ORM\Column(name="codigo_formato_iso", type="string", length=300, nullable=true)
     */    
    private $codigoFormatoIso;
    
    /**
     * @ORM\Column(name="version", type="string", length=100, nullable=true)
     */    
    private $version;
    
    /**
     * @ORM\Column(name="fecha_version", type="date", nullable=true)
     */    
    private $fechaVersion;
    
    /**
     * @ORM\Column(name="requiere_formato_iso", type="boolean")
     */    
    private $requiereFormatoIso = false;                
    
   

    /**
     * Set codigoContenidoPk
     *
     * @param integer $codigoContenidoPk
     *
     * @return GenContenido
     */
    public function setCodigoContenidoPk($codigoContenidoPk)
    {
        $this->codigoContenidoPk = $codigoContenidoPk;

        return $this;
    }

    /**
     * Get codigoContenidoPk
     *
     * @return integer
     */
    public function getCodigoContenidoPk()
    {
        return $this->codigoContenidoPk;
    }

    /**
     * Set numeroFormato
     *
     * @param integer $numeroFormato
     *
     * @return GenContenido
     */
    public function setNumeroFormato($numeroFormato)
    {
        $this->numero_formato = $numeroFormato;

        return $this;
    }

    /**
     * Get numeroFormato
     *
     * @return integer
     */
    public function getNumeroFormato()
    {
        return $this->numero_formato;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return GenContenido
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return GenContenido
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
     * Set codigoFormatoIso
     *
     * @param string $codigoFormatoIso
     *
     * @return GenContenido
     */
    public function setCodigoFormatoIso($codigoFormatoIso)
    {
        $this->codigoFormatoIso = $codigoFormatoIso;

        return $this;
    }

    /**
     * Get codigoFormatoIso
     *
     * @return string
     */
    public function getCodigoFormatoIso()
    {
        return $this->codigoFormatoIso;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return GenContenido
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set fechaVersion
     *
     * @param \DateTime $fechaVersion
     *
     * @return GenContenido
     */
    public function setFechaVersion($fechaVersion)
    {
        $this->fechaVersion = $fechaVersion;

        return $this;
    }

    /**
     * Get fechaVersion
     *
     * @return \DateTime
     */
    public function getFechaVersion()
    {
        return $this->fechaVersion;
    }

    /**
     * Set requiereFormatoIso
     *
     * @param boolean $requiereFormatoIso
     *
     * @return GenContenido
     */
    public function setRequiereFormatoIso($requiereFormatoIso)
    {
        $this->requiereFormatoIso = $requiereFormatoIso;

        return $this;
    }

    /**
     * Get requiereFormatoIso
     *
     * @return boolean
     */
    public function getRequiereFormatoIso()
    {
        return $this->requiereFormatoIso;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenContenido
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
