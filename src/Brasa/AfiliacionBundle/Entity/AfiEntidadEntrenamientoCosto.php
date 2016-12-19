<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_entidad_entrenamiento_costo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiEntidadEntrenamientoCostoRepository")
 */
class AfiEntidadEntrenamientoCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_entrenamiento_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadEntrenamientoCostoPk;        

    /**
     * @ORM\Column(name="codigo_entidad_entrenamiento_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadEntrenamientoFk;
    
    /**
     * @ORM\Column(name="codigo_curso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCursoTipoFk;    

    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;        

    
    

    /**
     * Get codigoEntidadEntrenamientoCostoPk
     *
     * @return integer
     */
    public function getCodigoEntidadEntrenamientoCostoPk()
    {
        return $this->codigoEntidadEntrenamientoCostoPk;
    }

    /**
     * Set codigoEntidadEntrenamientoFk
     *
     * @param integer $codigoEntidadEntrenamientoFk
     *
     * @return AfiEntidadEntrenamientoCosto
     */
    public function setCodigoEntidadEntrenamientoFk($codigoEntidadEntrenamientoFk)
    {
        $this->codigoEntidadEntrenamientoFk = $codigoEntidadEntrenamientoFk;

        return $this;
    }

    /**
     * Get codigoEntidadEntrenamientoFk
     *
     * @return integer
     */
    public function getCodigoEntidadEntrenamientoFk()
    {
        return $this->codigoEntidadEntrenamientoFk;
    }

    /**
     * Set codigoCursoTipoFk
     *
     * @param integer $codigoCursoTipoFk
     *
     * @return AfiEntidadEntrenamientoCosto
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
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiEntidadEntrenamientoCosto
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
}
