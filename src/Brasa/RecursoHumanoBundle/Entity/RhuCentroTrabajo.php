<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_centro_trabajo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCentroTrabajoRepository")
 */
class RhuCentroTrabajo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_trabajo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCentroTrabajoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer", nullable=true)
     */
    private $codigoSucursalFk;

    /**
     * @ORM\Column(name="estado_activo",type="boolean", nullable=true)
     */
    private $estadoActivo = 0;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSucursal", inversedBy="centroTrabajoRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $sucursalRel;

//    /**
//     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="centroTrabajoRel")
//     */
//    protected $contratoRel;

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCentroTrabajoPk
     *
     * @return integer
     */
    public function getCodigoCentroTrabajoPk()
    {
        return $this->codigoCentroTrabajoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCentroTrabajo
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
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuCentroTrabajo
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return integer
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuCentroTrabajo
     */
    public function setEstadoActivo($estadoActivo)
    {
        $this->estadoActivo = $estadoActivo;

        return $this;
    }

    /**
     * Get estadoActivo
     *
     * @return boolean
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * Set sucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSucursal $sucursalRel
     *
     * @return RhuCentroTrabajo
     */
    public function setSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSucursal $sucursalRel = null)
    {
        $this->sucursalRel = $sucursalRel;

        return $this;
    }

    /**
     * Get sucursalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSucursal
     */
    public function getSucursalRel()
    {
        return $this->sucursalRel;
    }

    /**
     * Add contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuCentroTrabajo
     */
    public function addContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel)
    {
        $this->contratoRel[] = $contratoRel;

        return $this;
    }

    /**
     * Remove contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     */
    public function removeContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel)
    {
        $this->contratoRel->removeElement($contratoRel);
    }

    /**
     * Get contratoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }
}
