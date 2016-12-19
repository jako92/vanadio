<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso_tipo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoTipoRepository")
 */
class AfiCursoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoTipoPk;                   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                             
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;    
    
    /**
     * @ORM\Column(name="codigo_proveedor_fk", type="integer", nullable=true)
     */    
    private $codigoProveedorFk;     
    
    

    /**
     * Get codigoCursoTipoPk
     *
     * @return integer
     */
    public function getCodigoCursoTipoPk()
    {
        return $this->codigoCursoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AfiCursoTipo
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
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiCursoTipo
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set codigoProveedorFk
     *
     * @param integer $codigoProveedorFk
     *
     * @return AfiCursoTipo
     */
    public function setCodigoProveedorFk($codigoProveedorFk)
    {
        $this->codigoProveedorFk = $codigoProveedorFk;

        return $this;
    }

    /**
     * Get codigoProveedorFk
     *
     * @return integer
     */
    public function getCodigoProveedorFk()
    {
        return $this->codigoProveedorFk;
    }
}
