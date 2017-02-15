<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cliente")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurClienteRepository")
 */
class TurCliente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;    
    
    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer")
     */    
    private $codigoTipoIdentificacionFk;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=3, nullable=true)
     */
    private $digitoVerificacion;             
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=60)
     */
    private $nombreCorto;                         
    
    /**
     * @ORM\Column(name="nombre_completo", type="string", length=200, nullable=true)
     */
    private $nombreCompleto;    
    
    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     */    
    private $nombre1;        
    
    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     */    
    private $nombre2;    
    
    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     */    
    private $apellido1;    

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     */    
    private $apellido2;    
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer")
     */    
    private $codigoSectorFk;     
    
    /**
     * @ORM\Column(name="codigo_lista_precio_fk", type="integer", nullable=true)
     */    
    private $codigoListaPrecioFk;    
    
    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;    
    
    /**
     * @ORM\Column(name="estrato", type="string", length=5, nullable=true)
     */
    private $estrato;                
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;     

    /**
     * @ORM\Column(name="codigo_sector_comercial_fk", type="integer", nullable=true)
     */    
    private $codigoSectorComercialFk;
    
    /**
     * @ORM\Column(name="codigo_cobertura_fk", type="integer", nullable=true)
     */    
    private $codigoCoberturaFk;    
    
    /**
     * @ORM\Column(name="codigo_dimension_fk", type="integer", nullable=true)
     */    
    private $codigoDimensionFk;    
    
    /**
     * @ORM\Column(name="codigo_origen_capital_fk", type="integer", nullable=true)
     */    
    private $codigoOrigenCapitalFk;    
    
    /**
     * @ORM\Column(name="codigo_origen_judicial_fk", type="integer", nullable=true)
     */    
    private $codigoOrigenJudicialFk;     
    
    /**
     * @ORM\Column(name="codigo_sector_economico_fk", type="integer", nullable=true)
     */    
    private $codigoSectorEconomicoFk;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=120, nullable=true)
     */
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;     
    
    /**
     * @ORM\Column(name="gerente", type="string", length=80, nullable=true)
     */
    private $gerente;    
    
    /**
     * @ORM\Column(name="calular_gerente", type="string", length=20, nullable=true)
     */
    private $celularGerente;  
    
    /**
     * @ORM\Column(name="financiero", type="string", length=80, nullable=true)
     */
    private $financiero;    
    
    /**
     * @ORM\Column(name="calular_financiero", type="string", length=20, nullable=true)
     */
    private $celularFinanciero;     
    
    /**
     * @ORM\Column(name="contacto", type="string", length=80, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="calular_contacto", type="string", length=20, nullable=true)
     */
    private $celularContacto;     

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=20, nullable=true)
     */
    private $telefonoContacto;    
    
    /**     
     * @ORM\Column(name="factura_agrupada", type="boolean")
     */    
    private $facturaAgrupada = false;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
            
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                    
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;    
    
    /**     
     * @ORM\Column(name="regimen_simplificado", type="boolean")
     */    
    private $regimenSimplificado = false;      
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="clientesSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurListaPrecio", inversedBy="clientesListaPrecioRel")
     * @ORM\JoinColumn(name="codigo_lista_precio_fk", referencedColumnName="codigo_lista_precio_pk")
     */
    protected $listaPrecioRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="turClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="turClientesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;     

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenSectorComercial", inversedBy="turClientesSectorComercialRel")
     * @ORM\JoinColumn(name="codigo_sector_comercial_fk", referencedColumnName="codigo_sector_comercial_pk")
     */
    protected $sectorComercialRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCobertura", inversedBy="turClientesCoberturaRel")
     * @ORM\JoinColumn(name="codigo_cobertura_fk", referencedColumnName="codigo_cobertura_pk")
     */
    protected $coberturaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenDimension", inversedBy="turClientesDimensionRel")
     * @ORM\JoinColumn(name="codigo_dimension_fk", referencedColumnName="codigo_dimension_pk")
     */
    protected $dimensionRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenOrigenCapital", inversedBy="turClientesOrigenCapitalRel")
     * @ORM\JoinColumn(name="codigo_origen_capital_fk", referencedColumnName="codigo_origen_capital_pk")
     */
    protected $origenCapitalRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenOrigenJudicial", inversedBy="turClientesOrigenJudicialRel")
     * @ORM\JoinColumn(name="codigo_origen_judicial_fk", referencedColumnName="codigo_origen_judicial_pk")
     */
    protected $origenJudicialRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenSectorEconomico", inversedBy="turClientesSectorEconomicoRel")
     * @ORM\JoinColumn(name="codigo_sector_economico_fk", referencedColumnName="codigo_sector_economico_pk")
     */
    protected $sectorEconomicoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTipoIdentificacion", inversedBy="turClientesTipoIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_tipo_identificacion_fk", referencedColumnName="codigo_tipo_identificacion_pk")
     */
    protected $tipoIdentificacionRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacion", mappedBy="clienteRel")
     */
    protected $cotizacionesClienteRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="clienteRel")
     */
    protected $pedidosClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDevolucion", mappedBy="clienteRel")
     */
    protected $pedidosDevolucionesClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicio", mappedBy="clienteRel")
     */
    protected $serviciosClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacion", mappedBy="clienteRel")
     */
    protected $programacionesClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="clienteRel")
     */
    protected $puestosClienteRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurOperacion", mappedBy="clienteRel")
     */
    protected $operacionesClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProyecto", mappedBy="clienteRel")
     */
    protected $proyectosClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurGrupoFacturacion", mappedBy="clienteRel")
     */
    protected $gruposFacturacionesClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurContrato", mappedBy="clienteRel")
     */
    protected $contratosClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurClienteDireccion", mappedBy="clienteRel")
     */
    protected $clientesDireccionesClienteRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="clienteRel")
     */
    protected $costosServiciosClienteRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="clienteRel")
     */
    protected $puestosDotacionesClienteRel;           

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionAlterna", mappedBy="clienteRel")
     */
    protected $programacionesAlternasClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurCostoDetalle", mappedBy="clienteRel")
     */
    protected $costosDetallesClienteRel;     
    

}
