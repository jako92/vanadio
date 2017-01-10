<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_devolucion_concepto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDevolucionConceptoRepository")
 */
class TurPedidoDevolucionConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_devolucion_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDevolucionConceptoPk;           

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDevolucion", mappedBy="pedidoDevolucionConceptoRel")
     */
    protected $pedidosDevolucionesPedidoDevolucionConceptoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDevolucionesPedidoDevolucionConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoDevolucionConceptoPk
     *
     * @return integer
     */
    public function getCodigoPedidoDevolucionConceptoPk()
    {
        return $this->codigoPedidoDevolucionConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPedidoDevolucionConcepto
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
     * Add pedidosDevolucionesPedidoDevolucionConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionConceptoRel
     *
     * @return TurPedidoDevolucionConcepto
     */
    public function addPedidosDevolucionesPedidoDevolucionConceptoRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionConceptoRel)
    {
        $this->pedidosDevolucionesPedidoDevolucionConceptoRel[] = $pedidosDevolucionesPedidoDevolucionConceptoRel;

        return $this;
    }

    /**
     * Remove pedidosDevolucionesPedidoDevolucionConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionConceptoRel
     */
    public function removePedidosDevolucionesPedidoDevolucionConceptoRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucion $pedidosDevolucionesPedidoDevolucionConceptoRel)
    {
        $this->pedidosDevolucionesPedidoDevolucionConceptoRel->removeElement($pedidosDevolucionesPedidoDevolucionConceptoRel);
    }

    /**
     * Get pedidosDevolucionesPedidoDevolucionConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDevolucionesPedidoDevolucionConceptoRel()
    {
        return $this->pedidosDevolucionesPedidoDevolucionConceptoRel;
    }
}
