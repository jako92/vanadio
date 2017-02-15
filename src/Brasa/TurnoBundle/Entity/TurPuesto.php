<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_puesto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPuestoRepository")
 */
class TurPuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=65)
     */
    private $nombre;      
            
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;     

    /**
     * @ORM\Column(name="numero_comunicacion", type="string", length=30, nullable=true)
     */
    private $numeroComunicacion;
    
    /**
     * @ORM\Column(name="contacto", type="string", length=90, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=30, nullable=true)
     */
    private $telefonoContacto;    

    /**
     * @ORM\Column(name="celular_contacto", type="string", length=30, nullable=true)
     */
    private $celularContacto;    
    
    /**
     * @ORM\Column(name="costo_dotacion", type="float", nullable=true)
     */    
    private $costoDotacion = 0;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_programador_fk", type="integer", nullable=true)
     */    
    private $codigoProgramadorFk;    
    
    /**
     * @ORM\Column(name="codigo_zona_fk", type="integer", nullable=true)
     */    
    private $codigoZonaFk;     
    
    /**
     * @ORM\Column(name="codigo_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoOperacionFk;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;   
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_contabilidad_fk", type="string", length=20, nullable=true)
     */    
    private $codigoCentroCostoContabilidadFk;     
    
    /**     
     * @ORM\Column(name="control_puesto", type="boolean")
     */    
    private $controlPuesto = false;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="puestosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turPuestosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurProgramador", inversedBy="puestosProgramadorRel")
     * @ORM\JoinColumn(name="codigo_programador_fk", referencedColumnName="codigo_programador_pk")
     */
    protected $programadorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurZona", inversedBy="puestosZonaRel")
     * @ORM\JoinColumn(name="codigo_zona_fk", referencedColumnName="codigo_zona_pk")
     */
    protected $zonaRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurOperacion", inversedBy="puestosOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    protected $operacionRel;                   
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\ContabilidadBundle\Entity\CtbCentroCosto", inversedBy="turPuestosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_contabilidad_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoContabilidadRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleConcepto", mappedBy="puestoRel")
     */
    protected $pedidosDetallesConceptosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="puestoRel")
     */
    protected $serviciosDetallesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleConcepto", mappedBy="puestoRel")
     */
    protected $serviciosDetallesConceptosPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="puestoRel")
     */
    protected $programacionesDetallesPuestoRel;        

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionAlterna", mappedBy="puestoRel")
     */
    protected $programacionesAlternasPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="puestoRel")
     */
    protected $costosServiciosPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="puestoRel")
     */
    protected $puestosDotacionesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurSimulacionDetalle", mappedBy="puestoRel")
     */
    protected $simulacionesDetallesPuestoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="puestoRel")
     */
    protected $facturasDetallesPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="puestoRel")
     */
    protected $rhuEmpleadosPuestoRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurControlPuestoDetalle", mappedBy="puestoRel")
     */
    protected $conrtolesPuestosDetallesPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoDetalle", mappedBy="puestoRel")
     */
    protected $costosDetallesPuestoRel;     
    

}
