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
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="centroTrabajoRel")
     */
    protected $contratoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCobro", mappedBy="centroTrabajoRel")
     */
    protected $cobrosCentroTrabajoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="centroTrabajoRel")
     */
    protected $serviciosCobrarCentroTrabajoRel;    
       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosCobrarCentroTrabajoRel = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add serviciosCobrarCentroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroTrabajoRel
     *
     * @return RhuCentroTrabajo
     */
    public function addServiciosCobrarCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroTrabajoRel)
    {
        $this->serviciosCobrarCentroTrabajoRel[] = $serviciosCobrarCentroTrabajoRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarCentroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroTrabajoRel
     */
    public function removeServiciosCobrarCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCentroTrabajoRel)
    {
        $this->serviciosCobrarCentroTrabajoRel->removeElement($serviciosCobrarCentroTrabajoRel);
    }

    /**
     * Get serviciosCobrarCentroTrabajoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarCentroTrabajoRel()
    {
        return $this->serviciosCobrarCentroTrabajoRel;
    }

    /**
     * Add cobrosCentroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCentroTrabajoRel
     *
     * @return RhuCentroTrabajo
     */
    public function addCobrosCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCentroTrabajoRel)
    {
        $this->cobrosCentroTrabajoRel[] = $cobrosCentroTrabajoRel;

        return $this;
    }

    /**
     * Remove cobrosCentroTrabajoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCentroTrabajoRel
     */
    public function removeCobrosCentroTrabajoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosCentroTrabajoRel)
    {
        $this->cobrosCentroTrabajoRel->removeElement($cobrosCentroTrabajoRel);
    }

    /**
     * Get cobrosCentroTrabajoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCobrosCentroTrabajoRel()
    {
        return $this->cobrosCentroTrabajoRel;
    }
}
