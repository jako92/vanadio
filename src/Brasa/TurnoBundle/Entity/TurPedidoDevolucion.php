<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_devolucion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDevolucionRepository")
 */
class TurPedidoDevolucion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_devolucion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDevolucionPk;           
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;                     

    /**
     * @ORM\Column(name="codigo_pedido_devolucion_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDevolucionConceptoFk;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;     
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;         
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;         
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="pedidosDevolucionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;     

    /**
     * @ORM\ManyToOne(targetEntity="TurPedidoDevolucionConcepto", inversedBy="pedidosDevolucionesPedidoDevolucionConceptoRel")
     * @ORM\JoinColumn(name="codigo_pedido_devolucion_concepto_fk", referencedColumnName="codigo_pedido_devolucion_concepto_pk")
     */
    protected $pedidoDevolucionConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDevolucionDetalle", mappedBy="pedidoDevolucionRel", cascade={"persist", "remove"})
     */
    protected $pedidosDevolucionesDetallesPedidoDevolucionRel;    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDevolucionesDetallesPedidoDevolucionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPedidoDevolucionPk
     *
     * @return integer
     */
    public function getCodigoPedidoDevolucionPk()
    {
        return $this->codigoPedidoDevolucionPk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TurPedidoDevolucion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurPedidoDevolucion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurPedidoDevolucion
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurPedidoDevolucion
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurPedidoDevolucion
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurPedidoDevolucion
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurPedidoDevolucion
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurPedidoDevolucion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPedidoDevolucion
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurPedidoDevolucion
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add pedidosDevolucionesDetallesPedidoDevolucionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle $pedidosDevolucionesDetallesPedidoDevolucionRel
     *
     * @return TurPedidoDevolucion
     */
    public function addPedidosDevolucionesDetallesPedidoDevolucionRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle $pedidosDevolucionesDetallesPedidoDevolucionRel)
    {
        $this->pedidosDevolucionesDetallesPedidoDevolucionRel[] = $pedidosDevolucionesDetallesPedidoDevolucionRel;

        return $this;
    }

    /**
     * Remove pedidosDevolucionesDetallesPedidoDevolucionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle $pedidosDevolucionesDetallesPedidoDevolucionRel
     */
    public function removePedidosDevolucionesDetallesPedidoDevolucionRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucionDetalle $pedidosDevolucionesDetallesPedidoDevolucionRel)
    {
        $this->pedidosDevolucionesDetallesPedidoDevolucionRel->removeElement($pedidosDevolucionesDetallesPedidoDevolucionRel);
    }

    /**
     * Get pedidosDevolucionesDetallesPedidoDevolucionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDevolucionesDetallesPedidoDevolucionRel()
    {
        return $this->pedidosDevolucionesDetallesPedidoDevolucionRel;
    }

    /**
     * Set codigoPedidoDevolucionConceptoFk
     *
     * @param integer $codigoPedidoDevolucionConceptoFk
     *
     * @return TurPedidoDevolucion
     */
    public function setCodigoPedidoDevolucionConceptoFk($codigoPedidoDevolucionConceptoFk)
    {
        $this->codigoPedidoDevolucionConceptoFk = $codigoPedidoDevolucionConceptoFk;

        return $this;
    }

    /**
     * Get codigoPedidoDevolucionConceptoFk
     *
     * @return integer
     */
    public function getCodigoPedidoDevolucionConceptoFk()
    {
        return $this->codigoPedidoDevolucionConceptoFk;
    }

    /**
     * Set pedidoDevolucionConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDevolucionConcepto $pedidoDevolucionConceptoRel
     *
     * @return TurPedidoDevolucion
     */
    public function setPedidoDevolucionConceptoRel(\Brasa\TurnoBundle\Entity\TurPedidoDevolucionConcepto $pedidoDevolucionConceptoRel = null)
    {
        $this->pedidoDevolucionConceptoRel = $pedidoDevolucionConceptoRel;

        return $this;
    }

    /**
     * Get pedidoDevolucionConceptoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPedidoDevolucionConcepto
     */
    public function getPedidoDevolucionConceptoRel()
    {
        return $this->pedidoDevolucionConceptoRel;
    }
}
