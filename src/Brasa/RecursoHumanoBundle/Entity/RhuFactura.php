<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaRepository")
 */
class RhuFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_factura_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaTipoFk;    

    /**
     * @ORM\Column(name="codigo_factura_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaServicioFk;
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero = 0;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;         
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaVence;          
    
    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $VrBruto = 0;    
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $VrNeto = 0;    

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $VrRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_cree", type="float")
     */
    private $VrRetencionCree = 0;    

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float")
     */
    private $VrRetencionIva = 0;        
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $VrBaseAIU = 0;    
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $VrSubtotal = 0;     
    
    /**
     * @ORM\Column(name="vr_total_administracion", type="float")
     */
    private $VrTotalAdministracion = 0;    
    
    /**
     * @ORM\Column(name="vr_ingreso_mision", type="float")
     */
    private $VrIngresoMision = 0;    
    
    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $VrIva = 0;    
    
    /**
     * @ORM\Column(name="vr_seleccion", type="float")
     */
    private $VrSeleccion = 0;     
    
    /**
     * @ORM\Column(name="vr_examen", type="float")
     */
    private $VrExamen = 0;     
    
    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    

    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;
    
    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;
            
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */
    private $plazoPago = 0;     
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="facturasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuFacturaTipo", inversedBy="facturasFacturaTipoRel")
     * @ORM\JoinColumn(name="codigo_factura_tipo_fk", referencedColumnName="codigo_factura_tipo_pk")
     */
    protected $facturaTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuFacturaServicio", inversedBy="facturasFacturaServicioRel")
     * @ORM\JoinColumn(name="codigo_factura_servicio_fk", referencedColumnName="codigo_factura_servicio_pk")
     */
    protected $facturaServicioRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesFacturaRel;                

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="facturaRel")
     */
    protected $examenesFacturaRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="facturaRel")
     */
    protected $seleccionesFacturaRel;     
    
   

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaPk
     *
     * @return integer
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuFactura
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
     * Set codigoFacturaTipoFk
     *
     * @param integer $codigoFacturaTipoFk
     *
     * @return RhuFactura
     */
    public function setCodigoFacturaTipoFk($codigoFacturaTipoFk)
    {
        $this->codigoFacturaTipoFk = $codigoFacturaTipoFk;

        return $this;
    }

    /**
     * Get codigoFacturaTipoFk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoFk()
    {
        return $this->codigoFacturaTipoFk;
    }

    /**
     * Set codigoFacturaServicioFk
     *
     * @param integer $codigoFacturaServicioFk
     *
     * @return RhuFactura
     */
    public function setCodigoFacturaServicioFk($codigoFacturaServicioFk)
    {
        $this->codigoFacturaServicioFk = $codigoFacturaServicioFk;

        return $this;
    }

    /**
     * Get codigoFacturaServicioFk
     *
     * @return integer
     */
    public function getCodigoFacturaServicioFk()
    {
        return $this->codigoFacturaServicioFk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return RhuFactura
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
     * @return RhuFactura
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return RhuFactura
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return RhuFactura
     */
    public function setVrBruto($vrBruto)
    {
        $this->VrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->VrBruto;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return RhuFactura
     */
    public function setVrNeto($vrNeto)
    {
        $this->VrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->VrNeto;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     *
     * @return RhuFactura
     */
    public function setVrRetencionFuente($vrRetencionFuente)
    {
        $this->VrRetencionFuente = $vrRetencionFuente;

        return $this;
    }

    /**
     * Get vrRetencionFuente
     *
     * @return float
     */
    public function getVrRetencionFuente()
    {
        return $this->VrRetencionFuente;
    }

    /**
     * Set vrRetencionCree
     *
     * @param float $vrRetencionCree
     *
     * @return RhuFactura
     */
    public function setVrRetencionCree($vrRetencionCree)
    {
        $this->VrRetencionCree = $vrRetencionCree;

        return $this;
    }

    /**
     * Get vrRetencionCree
     *
     * @return float
     */
    public function getVrRetencionCree()
    {
        return $this->VrRetencionCree;
    }

    /**
     * Set vrRetencionIva
     *
     * @param float $vrRetencionIva
     *
     * @return RhuFactura
     */
    public function setVrRetencionIva($vrRetencionIva)
    {
        $this->VrRetencionIva = $vrRetencionIva;

        return $this;
    }

    /**
     * Get vrRetencionIva
     *
     * @return float
     */
    public function getVrRetencionIva()
    {
        return $this->VrRetencionIva;
    }

    /**
     * Set vrBaseAIU
     *
     * @param float $vrBaseAIU
     *
     * @return RhuFactura
     */
    public function setVrBaseAIU($vrBaseAIU)
    {
        $this->VrBaseAIU = $vrBaseAIU;

        return $this;
    }

    /**
     * Get vrBaseAIU
     *
     * @return float
     */
    public function getVrBaseAIU()
    {
        return $this->VrBaseAIU;
    }

    /**
     * Set vrSubtotal
     *
     * @param float $vrSubtotal
     *
     * @return RhuFactura
     */
    public function setVrSubtotal($vrSubtotal)
    {
        $this->VrSubtotal = $vrSubtotal;

        return $this;
    }

    /**
     * Get vrSubtotal
     *
     * @return float
     */
    public function getVrSubtotal()
    {
        return $this->VrSubtotal;
    }

    /**
     * Set vrTotalAdministracion
     *
     * @param float $vrTotalAdministracion
     *
     * @return RhuFactura
     */
    public function setVrTotalAdministracion($vrTotalAdministracion)
    {
        $this->VrTotalAdministracion = $vrTotalAdministracion;

        return $this;
    }

    /**
     * Get vrTotalAdministracion
     *
     * @return float
     */
    public function getVrTotalAdministracion()
    {
        return $this->VrTotalAdministracion;
    }

    /**
     * Set vrIngresoMision
     *
     * @param float $vrIngresoMision
     *
     * @return RhuFactura
     */
    public function setVrIngresoMision($vrIngresoMision)
    {
        $this->VrIngresoMision = $vrIngresoMision;

        return $this;
    }

    /**
     * Get vrIngresoMision
     *
     * @return float
     */
    public function getVrIngresoMision()
    {
        return $this->VrIngresoMision;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return RhuFactura
     */
    public function setVrIva($vrIva)
    {
        $this->VrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->VrIva;
    }

    /**
     * Set vrSeleccion
     *
     * @param float $vrSeleccion
     *
     * @return RhuFactura
     */
    public function setVrSeleccion($vrSeleccion)
    {
        $this->VrSeleccion = $vrSeleccion;

        return $this;
    }

    /**
     * Get vrSeleccion
     *
     * @return float
     */
    public function getVrSeleccion()
    {
        return $this->VrSeleccion;
    }

    /**
     * Set vrExamen
     *
     * @param float $vrExamen
     *
     * @return RhuFactura
     */
    public function setVrExamen($vrExamen)
    {
        $this->VrExamen = $vrExamen;

        return $this;
    }

    /**
     * Get vrExamen
     *
     * @return float
     */
    public function getVrExamen()
    {
        return $this->VrExamen;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return RhuFactura
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuFactura
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
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return RhuFactura
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuFactura
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
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return RhuFactura
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return RhuFactura
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
     * Set soporte
     *
     * @param string $soporte
     *
     * @return RhuFactura
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuFactura
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
     * Set facturaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaTipo $facturaTipoRel
     *
     * @return RhuFactura
     */
    public function setFacturaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaTipo $facturaTipoRel = null)
    {
        $this->facturaTipoRel = $facturaTipoRel;

        return $this;
    }

    /**
     * Get facturaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFacturaTipo
     */
    public function getFacturaTipoRel()
    {
        return $this->facturaTipoRel;
    }

    /**
     * Set facturaServicioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaServicio $facturaServicioRel
     *
     * @return RhuFactura
     */
    public function setFacturaServicioRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaServicio $facturaServicioRel = null)
    {
        $this->facturaServicioRel = $facturaServicioRel;

        return $this;
    }

    /**
     * Get facturaServicioRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFacturaServicio
     */
    public function getFacturaServicioRel()
    {
        return $this->facturaServicioRel;
    }

    /**
     * Add facturasDetallesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel
     *
     * @return RhuFactura
     */
    public function addFacturasDetallesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel[] = $facturasDetallesFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel
     */
    public function removeFacturasDetallesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel->removeElement($facturasDetallesFacturaRel);
    }

    /**
     * Get facturasDetallesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesFacturaRel()
    {
        return $this->facturasDetallesFacturaRel;
    }

    /**
     * Add examenesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel
     *
     * @return RhuFactura
     */
    public function addExamenesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel)
    {
        $this->examenesFacturaRel[] = $examenesFacturaRel;

        return $this;
    }

    /**
     * Remove examenesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel
     */
    public function removeExamenesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel)
    {
        $this->examenesFacturaRel->removeElement($examenesFacturaRel);
    }

    /**
     * Get examenesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesFacturaRel()
    {
        return $this->examenesFacturaRel;
    }

    /**
     * Add seleccionesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesFacturaRel
     *
     * @return RhuFactura
     */
    public function addSeleccionesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesFacturaRel)
    {
        $this->seleccionesFacturaRel[] = $seleccionesFacturaRel;

        return $this;
    }

    /**
     * Remove seleccionesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesFacturaRel
     */
    public function removeSeleccionesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesFacturaRel)
    {
        $this->seleccionesFacturaRel->removeElement($seleccionesFacturaRel);
    }

    /**
     * Get seleccionesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesFacturaRel()
    {
        return $this->seleccionesFacturaRel;
    }
}
