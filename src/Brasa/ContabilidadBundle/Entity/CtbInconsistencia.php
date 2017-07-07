<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtbInconsistencia
 *
 * @ORM\Table(name="ctb_inconsistencia")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbInconsistenciaRepository")
 */
class CtbInconsistencia
{
    /**
     * @var int
     *
     * @ORM\Column(name="codigo_inconsistencia_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoInconsistenciaPk;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=500)
     */
    private $descripcion;
    

    /**
     * Get codigoInconsistenciaPk
     *
     * @return integer
     */
    public function getCodigoInconsistenciaPk()
    {
        return $this->codigoInconsistenciaPk;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CtbInconsistencias
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
}
