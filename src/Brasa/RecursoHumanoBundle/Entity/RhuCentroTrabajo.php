<?php

namespace Brasa\RecursoHumanoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_centro_trabajo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCentroTrabajoRepository")
 */
class RhuCentroTrabajo
{
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
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="centroTrabajoClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="centroTrabajoRel")
     */
    protected $contratoRel;

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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuCentroTrabajo
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
     * @return RhuCentroTrabajo
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
     * Constructor
     */
    public function __construct()
    {
        $this->contratoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
