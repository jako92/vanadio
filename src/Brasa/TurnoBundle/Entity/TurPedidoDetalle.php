<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_pedido_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPedidoDetalleRepository")
 */
class TurPedidoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pedido_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPedidoDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_pedido_fk", type="integer")
     */    
    private $codigoPedidoFk;

    /**
     * @ORM\Column(name="codigo_proyecto_fk", type="integer", nullable=true)
     */    
    private $codigoProyectoFk;    
    
    /**
     * @ORM\Column(name="codigo_grupo_facturacion_fk", type="integer", nullable=true)
     */    
    private $codigoGrupoFacturacionFk;     
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;           
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer")
     */    
    private $codigoConceptoServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_modalidad_servicio_fk", type="integer")
     */    
    private $codigoModalidadServicioFk;           
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;      
    
    /**
     * @ORM\Column(name="codigo_servicio_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoServicioDetalleFk;    
    
    /**
     * @ORM\Column(name="codigo_plantilla_fk", type="integer", nullable=true)
     */    
    private $codigoPlantillaFk;    
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */    
    private $anio = 0;    
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */    
    private $mes = 0;     
    
    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */    
    private $diaDesde = 1;     

    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */    
    private $diaHasta = 1;         
    
    /**     
     * @ORM\Column(name="liquidar_dias_reales", type="boolean")
     */    
    private $liquidarDiasReales = false;    
    
    /**     
     * @ORM\Column(name="compuesto", type="boolean")
     */    
    private $compuesto = false;     
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0; 
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;     

    /**
     * @ORM\Column(name="horas_programadas", type="float")
     */    
    private $horasProgramadas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas_programadas", type="float")
     */    
    private $horasDiurnasProgramadas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas_programadas", type="float")
     */    
    private $horasNocturnasProgramadas = 0; 
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\Column(name="cantidad_recurso", type="integer")
     */    
    private $cantidadRecurso = 0;         
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;
    
    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float")
     */
    private $vrPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;        
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva = 0;    
    
    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0; 

    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $vrIva = 0;    
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0;     
    
    /**
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0; 

    /**
     * @ORM\Column(name="vr_total_detalle_afectado", type="float")
     */
    private $vrTotalDetalleAfectado = 0; 
    
    /**
     * @ORM\Column(name="vr_total_detalle_pendiente", type="float")
     */
    private $vrTotalDetallePendiente = 0;    
    
    /**
     * @ORM\Column(name="vr_total_detalle_devolucion", type="float")
     */
    private $vrTotalDetalleDevolucion = 0;     
    
    /**     
     * @ORM\Column(name="lunes", type="boolean")
     */    
    private $lunes = false;    
    
    /**     
     * @ORM\Column(name="martes", type="boolean")
     */    
    private $martes = false;        
    
    /**     
     * @ORM\Column(name="miercoles", type="boolean")
     */    
    private $miercoles = false;        
    
    /**     
     * @ORM\Column(name="jueves", type="boolean")
     */    
    private $jueves = false;        
    
    /**     
     * @ORM\Column(name="viernes", type="boolean")
     */    
    private $viernes = false;    
    
    /**     
     * @ORM\Column(name="sabado", type="boolean")
     */    
    private $sabado = false;        
    
    /**     
     * @ORM\Column(name="domingo", type="boolean")
     */    
    private $domingo = false;        
    
    /**     
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = false;        
    
    /**     
     * @ORM\Column(name="dia_31", type="boolean")
     */    
    private $dia31 = false;    
    
    /**     
     * @ORM\Column(name="estado_programado", type="boolean")
     */    
    private $estadoProgramado = false; 
    
    /**     
     * @ORM\Column(name="estado_facturado", type="boolean")
     */    
    private $estadoFacturado = false;     
    
    /**
     * @ORM\Column(name="fecha_inicia_plantilla", type="date", nullable=true)
     */    
    private $fechaIniciaPlantilla;    
    
    /**     
     * @ORM\Column(name="marca", type="boolean")
     */    
    private $marca = false;     

    /**     
     * @ORM\Column(name="ajuste_programacion", type="boolean")
     */    
    private $ajusteProgramacion = false;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=300, nullable=true)
     */
    private $detalle;    
    
    /**
     * @ORM\Column(name="detalle_puesto", type="string", length=200, nullable=true)
     */    
    private $detallePuesto;     
    
    /**
     * @ORM\Column(name="fecha_desde_servicio", type="date", nullable=true)
     */    
    private $fechaDesdeServicio;    

    /**
     * @ORM\Column(name="fecha_hasta_servicio", type="date", nullable=true)
     */    
    private $fechaHastaServicio;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPedido", inversedBy="pedidosDetallesPedidoRel")
     * @ORM\JoinColumn(name="codigo_pedido_fk", referencedColumnName="codigo_pedido_pk")
     */
    protected $pedidoRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurProyecto", inversedBy="pedidosDetallesProyectoRel")
     * @ORM\JoinColumn(name="codigo_proyecto_fk", referencedColumnName="codigo_proyecto_pk")
     */
    protected $proyectoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurGrupoFacturacion", inversedBy="pedidosDetallesGrupoFacturacionRel")
     * @ORM\JoinColumn(name="codigo_grupo_facturacion_fk", referencedColumnName="codigo_grupo_facturacion_pk")
     */
    protected $grupoFacturacionRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="pedidosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="pedidosDetallesConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;      

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="pedidosDetallesModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPeriodo", inversedBy="pedidosDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;      
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPlantilla", inversedBy="pedidosDetallesPlantillaRel")
     * @ORM\JoinColumn(name="codigo_plantilla_fk", referencedColumnName="codigo_plantilla_pk")
     */
    protected $plantillaRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TurServicioDetalle", inversedBy="pedidosDetallesServicioDetalleRel")
     * @ORM\JoinColumn(name="codigo_servicio_detalle_fk", referencedColumnName="codigo_servicio_detalle_pk")
     */
    protected $servicioDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleCompuesto", mappedBy="pedidoDetalleRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesCompuestosPedidoDetalleRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleRecurso", mappedBy="pedidoDetalleRel", cascade={"persist", "remove"})
     */
    protected $pedidosDetallesRecursosPedidoDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $programacionesDetallesPedidoDetalleRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $facturasDetallesPedidoDetalleRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $soportesPagosDetallesPedidoDetalleRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="pedidoDetalleRel")
     */
    protected $costosServiciosPedidoDetalleRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDevolucionDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $pedidosDevolucionesDetallesPedidoDetalleRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurCostoDetalle", mappedBy="pedidoDetalleRel")
     */
    protected $costosDetallesPedidoDetalleRel;     
    
}
