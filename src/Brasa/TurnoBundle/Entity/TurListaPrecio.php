<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_lista_precio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurListaPrecioRepository")
 */
class TurListaPrecio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_precio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoListaPrecioPk;                  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;     

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;
    
    /**
     * @ORM\Column(name="vr_hora_diurna", type="float")
     */
    private $vrHoraDiurna = 0;            

    /**
     * @ORM\Column(name="vr_hora_nocturna", type="float")
     */
    private $vrHoraNocturna = 0;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCliente", mappedBy="listaPrecioRel")
     */
    protected $clientesListaPrecioRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clientesListaPrecioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoListaPrecioPk
     *
     * @return integer
     */
    public function getCodigoListaPrecioPk()
    {
        return $this->codigoListaPrecioPk;
    }

    /**
     * Set vrHoraDiurna
     *
     * @param float $vrHoraDiurna
     *
     * @return TurListaPrecio
     */
    public function setVrHoraDiurna($vrHoraDiurna)
    {
        $this->vrHoraDiurna = $vrHoraDiurna;

        return $this;
    }

    /**
     * Get vrHoraDiurna
     *
     * @return float
     */
    public function getVrHoraDiurna()
    {
        return $this->vrHoraDiurna;
    }

    /**
     * Set vrHoraNocturna
     *
     * @param float $vrHoraNocturna
     *
     * @return TurListaPrecio
     */
    public function setVrHoraNocturna($vrHoraNocturna)
    {
        $this->vrHoraNocturna = $vrHoraNocturna;

        return $this;
    }

    /**
     * Get vrHoraNocturna
     *
     * @return float
     */
    public function getVrHoraNocturna()
    {
        return $this->vrHoraNocturna;
    }

    /**
     * Add clientesListaPrecioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clientesListaPrecioRel
     *
     * @return TurListaPrecio
     */
    public function addClientesListaPrecioRel(\Brasa\TurnoBundle\Entity\TurCliente $clientesListaPrecioRel)
    {
        $this->clientesListaPrecioRel[] = $clientesListaPrecioRel;

        return $this;
    }

    /**
     * Remove clientesListaPrecioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clientesListaPrecioRel
     */
    public function removeClientesListaPrecioRel(\Brasa\TurnoBundle\Entity\TurCliente $clientesListaPrecioRel)
    {
        $this->clientesListaPrecioRel->removeElement($clientesListaPrecioRel);
    }

    /**
     * Get clientesListaPrecioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientesListaPrecioRel()
    {
        return $this->clientesListaPrecioRel;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurListaPrecio
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return TurListaPrecio
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }
}
