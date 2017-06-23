<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenNotificacion
 *
 * @ORM\Table(name="gen_notificacion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenNotificacionRepository")
 */
class GenNotificacion {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_notificacion_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNotificacionPk;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=500)
     */
    private $descripcion;


    /**
     * Get codigoNotificacionPk
     *
     * @return integer
     */
    public function getCodigoNotificacionPk()
    {
        return $this->codigoNotificacionPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return GenNotificacion
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return GenNotificacion
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
