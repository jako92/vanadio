<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_costo_recurso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCostoRecursoDetalleRepository")
 */
class TurCostoRecursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_recurso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoRecursoDetallePk;             
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */    
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;                     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    

    /**
     * @ORM\Column(name="codigo_pedido_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoPedidoDetalleFk;    
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;         

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;     
    
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
     * @ORM\Column(name="horas_festivas_diurnas", type="float")
     */    
    private $horasFestivasDiurnas = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas", type="float")
     */    
    private $horasFestivasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="float")
     */    
    private $horasExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="float")
     */    
    private $horasExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="float")
     */    
    private $horasExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="float")
     */    
    private $horasExtrasFestivasNocturnas = 0;    

    /**
     * @ORM\Column(name="horas_recargo_nocturno", type="float")
     */    
    private $horasRecargoNocturno = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno", type="float")
     */    
    private $horasRecargoFestivoDiurno = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno", type="float")
     */    
    private $horasRecargoFestivoNocturno = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso", type="float")
     */    
    private $horasDescanso = 0;     
    
    /**
     * @ORM\Column(name="horas_diurnas_costo", type="float")
     */    
    private $horasDiurnasCosto = 0;     

    /**
     * @ORM\Column(name="horas_nocturnas_costo", type="float")
     */    
    private $horasNocturnasCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_festivas_diurnas_costo", type="float")
     */    
    private $horasFestivasDiurnasCosto = 0;     

    /**
     * @ORM\Column(name="horas_festivas_nocturnas_costo", type="float")
     */    
    private $horasFestivasNocturnasCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas_costo", type="float")
     */    
    private $horasExtrasOrdinariasDiurnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas_costo", type="float")
     */    
    private $horasExtrasOrdinariasNocturnasCosto = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas_costo", type="float")
     */    
    private $horasExtrasFestivasDiurnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas_costo", type="float")
     */    
    private $horasExtrasFestivasNocturnasCosto = 0;    

    /**
     * @ORM\Column(name="horas_recargo_nocturno_costo", type="float")
     */    
    private $horasRecargoNocturnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_diurno_costo", type="float")
     */    
    private $horasRecargoFestivoDiurnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_recargo_festivo_nocturno_costo", type="float")
     */    
    private $horasRecargoFestivoNocturnoCosto = 0;    
    
    /**
     * @ORM\Column(name="horas_descanso_costo", type="float")
     */    
    private $horasDescansoCosto = 0;    
    
    /**
     * @ORM\Column(name="peso", type="float")
     */    
    private $peso = 0;     

    /**
     * @ORM\Column(name="participacion", type="float")
     */    
    private $participacion = 0;     
    
    /**
     * @ORM\Column(name="costo", type="float")
     */    
    private $costo = 0;     

    /**
     * @ORM\Column(name="costo_nomina", type="float")
     */    
    private $costoNomina = 0;    
    
    /**
     * @ORM\Column(name="costo_seguridad_social", type="float")
     */    
    private $costoSeguridadSocial = 0;
    
    /**
     * @ORM\Column(name="costo_prestaciones", type="float")
     */    
    private $costoPrestaciones = 0;          
    
                   
}
