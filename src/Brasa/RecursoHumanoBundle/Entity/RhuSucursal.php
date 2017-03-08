<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sucursal")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSucursalRepository")
 */
class RhuSucursal {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSucursalPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="estado_activo",type="boolean", nullable=true)
     */
    private $estadoActivo = 0;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="sucursalClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCentroTrabajo", mappedBy="sucursalRel")
     */
    protected $centroTrabajoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="sucursalRel")
     */
    protected $contratoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->centroTrabajoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSucursalPk
     *
     * @return integer
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSucursal
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
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return RhuSucursal
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuSucursal
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuSucursal
     */
    public function setClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add centroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo $centroTrabajoRel
     *
     * @return RhuSucursal
     */
    public function addCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo $centroTrabajoRel)
    {
        $this->centroTrabajoRel[] = $centroTrabajoRel;

        return $this;
    }

    /**
     * Remove centroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo $centroTrabajoRel
     */
    public function removeCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroTrabajo $centroTrabajoRel)
    {
        $this->centroTrabajoRel->removeElement($centroTrabajoRel);
    }

    /**
     * Get centroTrabajoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentroTrabajoRel()
    {
        return $this->centroTrabajoRel;
    }

    /**
     * Add contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuSucursal
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
