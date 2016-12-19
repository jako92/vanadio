<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoDetalleRepository")
 */
class AfiCursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_curso_fk", type="integer")
     */    
    private $codigoCursoFk;                
    
    /**
     * @ORM\Column(name="codigo_curso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCursoTipoFk;    

    /**
     * @ORM\Column(name="codigo_proveedor_fk", type="integer", nullable=true)
     */    
    private $codigoProveedorFk;    
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;    

    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;    
    
    /**     
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = false;     
    
    

    /**
     * Get codigoCursoDetallePk
     *
     * @return integer
     */
    public function getCodigoCursoDetallePk()
    {
        return $this->codigoCursoDetallePk;
    }

    /**
     * Set codigoCursoFk
     *
     * @param integer $codigoCursoFk
     *
     * @return AfiCursoDetalle
     */
    public function setCodigoCursoFk($codigoCursoFk)
    {
        $this->codigoCursoFk = $codigoCursoFk;

        return $this;
    }

    /**
     * Get codigoCursoFk
     *
     * @return integer
     */
    public function getCodigoCursoFk()
    {
        return $this->codigoCursoFk;
    }

    /**
     * Set codigoCursoTipoFk
     *
     * @param integer $codigoCursoTipoFk
     *
     * @return AfiCursoDetalle
     */
    public function setCodigoCursoTipoFk($codigoCursoTipoFk)
    {
        $this->codigoCursoTipoFk = $codigoCursoTipoFk;

        return $this;
    }

    /**
     * Get codigoCursoTipoFk
     *
     * @return integer
     */
    public function getCodigoCursoTipoFk()
    {
        return $this->codigoCursoTipoFk;
    }

    /**
     * Set codigoProveedorFk
     *
     * @param integer $codigoProveedorFk
     *
     * @return AfiCursoDetalle
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

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiCursoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiCursoDetalle
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
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return AfiCursoDetalle
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }
}
