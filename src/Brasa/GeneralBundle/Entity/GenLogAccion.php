<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_log_accion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenLogAccionRepository")
 */
class GenLogAccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_accion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLogAccionPk;   
     
    /**
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;          

    /**
     * @ORM\OneToMany(targetEntity="GenLog", mappedBy="logAccionRel")
     */
    protected $logsLogAccionRel;      

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logsLogAccionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoLogAccionPk
     *
     * @return integer
     */
    public function getCodigoLogAccionPk()
    {
        return $this->codigoLogAccionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenLogAccion
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
     * Add logsLogAccionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLog $logsLogAccionRel
     *
     * @return GenLogAccion
     */
    public function addLogsLogAccionRel(\Brasa\GeneralBundle\Entity\GenLog $logsLogAccionRel)
    {
        $this->logsLogAccionRel[] = $logsLogAccionRel;

        return $this;
    }

    /**
     * Remove logsLogAccionRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLog $logsLogAccionRel
     */
    public function removeLogsLogAccionRel(\Brasa\GeneralBundle\Entity\GenLog $logsLogAccionRel)
    {
        $this->logsLogAccionRel->removeElement($logsLogAccionRel);
    }

    /**
     * Get logsLogAccionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogsLogAccionRel()
    {
        return $this->logsLogAccionRel;
    }
}
