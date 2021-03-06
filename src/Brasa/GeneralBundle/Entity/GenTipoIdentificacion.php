<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_tipo_identificacion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTipoIdentificacionRepository")
 */
class GenTipoIdentificacion {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_identificacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoIdentificacionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuEmpleadosTipoIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuSeleccionesTipoIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\ContabilidadBundle\Entity\CtbTercero", mappedBy="tipoIdentificacionRel")
     */
    protected $cbtTercerosTipoIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuAspirante", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuAspirantesTipoIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuPoligrafiasTipoIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="tipoIdentificacionRel")
     */
    protected $turClientesTipoIdentificacionRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuCliente", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuClientesTipoIdentificacionRel;
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuPrueba", mappedBy="tipoIdentificacionRel")
     */
    protected $rhupruebasIdentificacionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuVisita", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuvisitasIdentificacionRel;
    /**
     * Constructor
     */
    public function __construct() {
        $this->rhuEmpleadosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuSeleccionesTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cbtTercerosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuAspirantesTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuPoligrafiasTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->turClientesTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoIdentificacionPk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionPk()
    {
        return $this->codigoTipoIdentificacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenTipoIdentificacion
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return GenTipoIdentificacion
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Add rhuEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel)
    {
        $this->rhuEmpleadosTipoIdentificacionRel[] = $rhuEmpleadosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel
     */
    public function removeRhuEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel)
    {
        $this->rhuEmpleadosTipoIdentificacionRel->removeElement($rhuEmpleadosTipoIdentificacionRel);
    }

    /**
     * Get rhuEmpleadosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosTipoIdentificacionRel()
    {
        return $this->rhuEmpleadosTipoIdentificacionRel;
    }

    /**
     * Add rhuSeleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel)
    {
        $this->rhuSeleccionesTipoIdentificacionRel[] = $rhuSeleccionesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel
     */
    public function removeRhuSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel)
    {
        $this->rhuSeleccionesTipoIdentificacionRel->removeElement($rhuSeleccionesTipoIdentificacionRel);
    }

    /**
     * Get rhuSeleccionesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesTipoIdentificacionRel()
    {
        return $this->rhuSeleccionesTipoIdentificacionRel;
    }

    /**
     * Add cbtTercerosTipoIdentificacionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addCbtTercerosTipoIdentificacionRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel)
    {
        $this->cbtTercerosTipoIdentificacionRel[] = $cbtTercerosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove cbtTercerosTipoIdentificacionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel
     */
    public function removeCbtTercerosTipoIdentificacionRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel)
    {
        $this->cbtTercerosTipoIdentificacionRel->removeElement($cbtTercerosTipoIdentificacionRel);
    }

    /**
     * Get cbtTercerosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCbtTercerosTipoIdentificacionRel()
    {
        return $this->cbtTercerosTipoIdentificacionRel;
    }

    /**
     * Add rhuAspirantesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuAspirantesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel)
    {
        $this->rhuAspirantesTipoIdentificacionRel[] = $rhuAspirantesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuAspirantesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel
     */
    public function removeRhuAspirantesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel)
    {
        $this->rhuAspirantesTipoIdentificacionRel->removeElement($rhuAspirantesTipoIdentificacionRel);
    }

    /**
     * Get rhuAspirantesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuAspirantesTipoIdentificacionRel()
    {
        return $this->rhuAspirantesTipoIdentificacionRel;
    }

    /**
     * Add rhuPoligrafiasTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $rhuPoligrafiasTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuPoligrafiasTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $rhuPoligrafiasTipoIdentificacionRel)
    {
        $this->rhuPoligrafiasTipoIdentificacionRel[] = $rhuPoligrafiasTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuPoligrafiasTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $rhuPoligrafiasTipoIdentificacionRel
     */
    public function removeRhuPoligrafiasTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $rhuPoligrafiasTipoIdentificacionRel)
    {
        $this->rhuPoligrafiasTipoIdentificacionRel->removeElement($rhuPoligrafiasTipoIdentificacionRel);
    }

    /**
     * Get rhuPoligrafiasTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuPoligrafiasTipoIdentificacionRel()
    {
        return $this->rhuPoligrafiasTipoIdentificacionRel;
    }

    /**
     * Add turClientesTipoIdentificacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addTurClientesTipoIdentificacionRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel)
    {
        $this->turClientesTipoIdentificacionRel[] = $turClientesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove turClientesTipoIdentificacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel
     */
    public function removeTurClientesTipoIdentificacionRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel)
    {
        $this->turClientesTipoIdentificacionRel->removeElement($turClientesTipoIdentificacionRel);
    }

    /**
     * Get turClientesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesTipoIdentificacionRel()
    {
        return $this->turClientesTipoIdentificacionRel;
    }

    /**
     * Add rhuClientesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuClientesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesTipoIdentificacionRel)
    {
        $this->rhuClientesTipoIdentificacionRel[] = $rhuClientesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuClientesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesTipoIdentificacionRel
     */
    public function removeRhuClientesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesTipoIdentificacionRel)
    {
        $this->rhuClientesTipoIdentificacionRel->removeElement($rhuClientesTipoIdentificacionRel);
    }

    /**
     * Get rhuClientesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuClientesTipoIdentificacionRel()
    {
        return $this->rhuClientesTipoIdentificacionRel;
    }

    /**
     * Add rhupruebasIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $rhupruebasIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhupruebasIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $rhupruebasIdentificacionRel)
    {
        $this->rhupruebasIdentificacionRel[] = $rhupruebasIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhupruebasIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $rhupruebasIdentificacionRel
     */
    public function removeRhupruebasIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $rhupruebasIdentificacionRel)
    {
        $this->rhupruebasIdentificacionRel->removeElement($rhupruebasIdentificacionRel);
    }

    /**
     * Get rhupruebasIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhupruebasIdentificacionRel()
    {
        return $this->rhupruebasIdentificacionRel;
    }

    /**
     * Add rhuvisitasIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $rhuvisitasIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuvisitasIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $rhuvisitasIdentificacionRel)
    {
        $this->rhuvisitasIdentificacionRel[] = $rhuvisitasIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuvisitasIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $rhuvisitasIdentificacionRel
     */
    public function removeRhuvisitasIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $rhuvisitasIdentificacionRel)
    {
        $this->rhuvisitasIdentificacionRel->removeElement($rhuvisitasIdentificacionRel);
    }

    /**
     * Get rhuvisitasIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuvisitasIdentificacionRel()
    {
        return $this->rhuvisitasIdentificacionRel;
    }
}
