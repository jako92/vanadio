<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionRepository")
 */
class RhuConfiguracion {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoConfiguracionPk = 1;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $vrSalario;

    /**
     * @ORM\Column(name="codigo_auxilio_transporte", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoAuxilioTransporte;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $vrAuxilioTransporte;

    /**
     * @ORM\Column(name="codigo_credito", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoCredito;

    /**
     * @ORM\Column(name="codigo_seguro", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoSeguro;

    /**
     * @ORM\Column(name="codigo_tiempo_suplementario", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoTiempoSuplementario;

    /**
     * @ORM\Column(name="codigo_hora_diurna_trabajada", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraDiurnaTrabajada;

    /**
     * @ORM\Column(name="codigo_salario_integral", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoSalarioIntegral;

    /**
     * @ORM\Column(name="porcentaje_pension_extra", type="float")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $porcentajePensionExtra;

    /**
     * @ORM\Column(name="codigo_incapacidad", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoIncapacidad;

    /**
     * @ORM\Column(name="anio_actual", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $anioActual;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $porcentajeIva;

    /**
     * @ORM\Column(name="codigo_retencion_fuente", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoRetencionFuente;

    /**
     * @ORM\Column(name="edad_minima_empleado", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $edadMinimaEmpleado;

    /**
     * @ORM\Column(name="porcentaje_bonificacion_no_prestacional", type="float")
     */
    private $porcentajeBonificacionNoPrestacional = 40;

    /**
     * @ORM\Column(name="codigo_entidad_examen_ingreso", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoEntidadExamenIngreso;

    /**
     * @ORM\Column(name="codigo_comprobante_pago_nomina", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoComprobantePagoNomina;

    /**
     * @ORM\Column(name="codigo_comprobante_provision", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoComprobanteProvision;

    /**
     * @ORM\Column(name="codigo_comprobante_liquidacion", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoComprobanteLiquidacion;

    /**
     * @ORM\Column(name="codigo_comprobante_vacacion", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoComprobanteVacacion;

    /**
     * @ORM\Column(name="codigo_comprobante_pago_banco", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoComprobantePagoBanco;

    /**
     * @ORM\Column(name="control_pago", type="boolean")
     */
    private $controlPago = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_cesantias", type="float")
     */
    private $prestacionesPorcentajeCesantias = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_intereses_cesantias", type="float")
     */
    private $prestacionesPorcentajeInteresesCesantias = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_primas", type="float")
     */
    private $prestacionesPorcentajePrimas = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_vacaciones", type="float")
     */
    private $prestacionesPorcentajeVacaciones = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_aporte_vacaciones", type="float")
     */
    private $prestacionesPorcentajeAporteVacaciones = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_indemnizacion", type="float")
     */
    private $prestacionesPorcentajeIndemnizacion = 0;

    /**
     * @ORM\Column(name="aportes_porcentaje_caja", type="float")
     */
    private $aportesPorcentajeCaja = 0;

    /**
     * @ORM\Column(name="aportes_porcentaje_vacaciones", type="float")
     */
    private $aportesPorcentajeVacaciones = 0;

    /**
     * @ORM\Column(name="codigo_hora_descanso", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraDescanso;

    /**
     * @ORM\Column(name="codigo_hora_nocturna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraNocturna;

    /**
     * @ORM\Column(name="codigo_hora_festiva_diurna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraFestivaDiurna;

    /**
     * @ORM\Column(name="codigo_hora_festiva_nocturna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraFestivaNocturna;

    /**
     * @ORM\Column(name="codigo_hora_extra_ordinaria_diurna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraExtraOrdinariaDiurna;

    /**
     * @ORM\Column(name="codigo_hora_extra_ordinaria_nocturna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraExtraOrdinariaNocturna;

    /**
     * @ORM\Column(name="codigo_hora_extra_festiva_diurna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraExtraFestivaDiurna;

    /**
     * @ORM\Column(name="codigo_hora_extra_festiva_nocturna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraExtraFestivaNocturna;

    /**
     * @ORM\Column(name="codigo_hora_recargo_nocturno", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraRecargoNocturno;

    /**
     * @ORM\Column(name="codigo_hora_recargo_festivo_diurno", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraRecargoFestivoDiurno;

    /**
     * @ORM\Column(name="codigo_hora_recargo_festivo_nocturno", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoHoraRecargoFestivoNocturno;

    /**
     * @ORM\Column(name="codigo_vacacion", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoVacacion;

    /**
     * @ORM\Column(name="codigo_ajuste_devengado", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoAjusteDevengado;

    /**
     * @ORM\Column(name="afecta_vacaciones_parafiscales", type="boolean")
     */
    private $afectaVacacionesParafiscales = false;

    /**
     * @ORM\Column(name="codigo_formato_pago", type="integer")
     */
    private $codigoFormatoPago = 0;

    /**
     * @ORM\Column(name="codigo_formato_liquidacion", type="integer")
     */
    private $codigoFormatoLiquidacion = 0;

    /**
     * @ORM\Column(name="codigo_formato_carta", type="integer")
     */
    private $codigoFormatoCarta = 0;

    /**
     * @ORM\Column(name="codigo_formato_disciplinario", type="integer")
     */
    private $codigoFormatoDisciplinario = 0;

    /**
     * @ORM\Column(name="codigo_formato_descargo", type="integer")
     */
    private $codigoFormatoDescargo = 0;

    /**
     * @ORM\Column(name="codigo_formato_factura", type="integer")
     */
    private $codigoFormatoFactura = 0;

    /**
     * Tipo de base para la liquidacion de vacaciones 1-salario 2-salario+prestaciones 3-salario+recargos
     * @ORM\Column(name="tipo_base_pago_vacaciones", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $tipoBasePagoVacaciones;

    /**
     * Se activa cuando el cliente maneja porcentajes en las liquidaciones
     * @ORM\Column(name="genera_porcentaje_liquidacion", type="boolean")
     */
    private $generaPorcetnajeLiquidacion = false;

    /**
     * @ORM\Column(name="correo_nomina", type="string", length=100, nullable=true)
     */
    private $correoNomina;

    /**
     * Si esta activado muestra el mensaje en la colilla de pago
     * @ORM\Column(name="imprimir_mensaje_pago", type="boolean")
     */
    private $imprimirMensajePago = false;

    /**
     * @ORM\Column(name="codigo_prima", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoPrima;

    /**
     * @ORM\Column(name="codigo_cesantia", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoCesantia;

    /**
     * @ORM\Column(name="codigo_interes_cesantia", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $codigoInteresCesantia;

    /**
     * Si en el pago de primas se aplica un porcentaje en el salario
     * @ORM\Column(name="prestaciones_aplicar_porcentaje_salario", type="boolean")
     */
    private $prestacionesAplicaPorcentajeSalario = false;

    /**
     * @ORM\Column(name="nit_sena", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nitSena;

    /**
     * @ORM\Column(name="nit_icbf", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nitIcbf;

    /**
     * Si se tiene en cuenta o no los dias de ausentismo en primas
     * @ORM\Column(name="dias_ausentismo_primas", type="boolean")
     */
    private $diasAusentismoPrimas = false;

    /**
     * Promedio primas utilizado por seracis
     * @ORM\Column(name="promedio_primas_laborado", type="boolean")
     */
    private $promedioPrimasLaborado = false;

    /**
     * Si estos dias tiene valor el sistema divide el promedio en estos dias
     * @ORM\Column(name="promedio_primas_laborado_dias", type="integer")
     */
    private $promedioPrimasLaboradoDias = 0;

    /**
     * Promedio primas utilizado por seracis
     * @ORM\Column(name="omitir_descuento_embargo_primas", type="boolean")
     */
    private $omitirDescuentoEmbargoPrimas = false;

    /**
     * Promedio primas utilizado por seracis
     * @ORM\Column(name="omitir_descuento_embargo_cesantias", type="boolean")
     */
    private $omitirDescuentoEmbargoCesantias = false;

    /**
     * @ORM\Column(name="direccion_servidor_ardid", type="string", length=800, nullable=true)
     */
    private $direccionServidorArdid;

    /**
     * @ORM\Column(name="codigo_empresa_ardid", type="integer")
     */
    private $codigoEmpresaArdid = 0;

    /**
     * @ORM\Column(name="pagar_licencia_salario_pactado", type="boolean")
     */
    private $pagarLicenciaSalarioPactado = false;

    /**
     * @ORM\Column(name="pagar_incapacidad_salario_pactado", type="boolean")
     */
    private $pagarIncapacidadSalarioPactado = false;

    /**
     * @ORM\Column(name="informacion_legal_factura", type="text", nullable=true)
     */
    private $informacionLegalFactura;

    /**
     * @ORM\Column(name="informacion_pago_factura", type="text", nullable=true)
     */
    private $informacionPagoFactura;

    /**
     * @ORM\Column(name="informacion_contacto_factura", type="text", nullable=true)
     */
    private $informacionContactoFactura;

    /**
     * @ORM\Column(name="informacion_resolucion_dian_factura", type="text", nullable=true)
     */
    private $informacionResolucionDianFactura;

    /**
     * @ORM\Column(name="informacion_resolucion_supervigilancia_factura", type="text", nullable=true)
     */
    private $informacionResolucionSupervigilanciaFactura;

    /**
     * @ORM\Column(name="horas_domingo_no_compensado", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $horasDomingoNoCompensado;

    /**
     * @ORM\Column(name="horas_domingo_compensado", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $horasDomingoCompensado;

    /**
     * @ORM\Column(name="horas_recargo_nocturno_festivo_compensado", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $horasRecargoNocturnoFestivoCompensado;

    /**
     * @ORM\Column(name="horas_recargo_nocturno_festivo_no_compensado", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $horasRecargoNocturnoFestivoNoCompensado;

    /**
     * @ORM\Column(name="liquidar_vacaciones_salario", type="boolean")
     */
    private $liquidarVacacionesSalario = false;

    /**
     * @ORM\Column(name="liquidar_auxilio_transporte_prima", type="boolean")
     */
    private $liquidarAuxilioTransportePrima = false;

    /**
     * @ORM\Column(name="horas_extra_dominical_diurna", type="integer")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $horasExtraDominicalDiurna;

    /**
     * @ORM\Column(name="auxilio_transporte_no_prestacional", type="boolean")
     */
    private $auxilioTransporteNoPrestacional = false;

    /**
     * @ORM\Column(name="orden_nombre_empleado", type="integer")
     */
    private $ordenNombreEmpleado = 0;

    /**
     * @ORM\Column(name="requiere_requisito_contratacion", type="boolean", options={"default":false}, nullable=true)
     */
    private $requiereRequisitoContratacion = false;

    /**
     * @ORM\Column(name="generar_novedad_vacaciones_turnos", type="boolean")
     */
    private $generarNovedadVacacionesTurnos = false;     
    
    /**
     * @ORM\Column(name="tipo_novedad_vacacion_turno", type="integer", nullable=true)
     */
    private $tipoNovedadVacacionTurno;
    
    /**
     * @ORM\Column(name="generar_novedad_incapacidad_turnos", type="boolean")
     */
    private $generarNovedadIncapacidadTurnos = false;


    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return RhuConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuConfiguracion
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

    /**
     * Set codigoAuxilioTransporte
     *
     * @param integer $codigoAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAuxilioTransporte($codigoAuxilioTransporte)
    {
        $this->codigoAuxilioTransporte = $codigoAuxilioTransporte;

        return $this;
    }

    /**
     * Get codigoAuxilioTransporte
     *
     * @return integer
     */
    public function getCodigoAuxilioTransporte()
    {
        return $this->codigoAuxilioTransporte;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set codigoCredito
     *
     * @param integer $codigoCredito
     *
     * @return RhuConfiguracion
     */
    public function setCodigoCredito($codigoCredito)
    {
        $this->codigoCredito = $codigoCredito;

        return $this;
    }

    /**
     * Get codigoCredito
     *
     * @return integer
     */
    public function getCodigoCredito()
    {
        return $this->codigoCredito;
    }

    /**
     * Set codigoSeguro
     *
     * @param integer $codigoSeguro
     *
     * @return RhuConfiguracion
     */
    public function setCodigoSeguro($codigoSeguro)
    {
        $this->codigoSeguro = $codigoSeguro;

        return $this;
    }

    /**
     * Get codigoSeguro
     *
     * @return integer
     */
    public function getCodigoSeguro()
    {
        return $this->codigoSeguro;
    }

    /**
     * Set codigoTiempoSuplementario
     *
     * @param integer $codigoTiempoSuplementario
     *
     * @return RhuConfiguracion
     */
    public function setCodigoTiempoSuplementario($codigoTiempoSuplementario)
    {
        $this->codigoTiempoSuplementario = $codigoTiempoSuplementario;

        return $this;
    }

    /**
     * Get codigoTiempoSuplementario
     *
     * @return integer
     */
    public function getCodigoTiempoSuplementario()
    {
        return $this->codigoTiempoSuplementario;
    }

    /**
     * Set codigoHoraDiurnaTrabajada
     *
     * @param integer $codigoHoraDiurnaTrabajada
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraDiurnaTrabajada($codigoHoraDiurnaTrabajada)
    {
        $this->codigoHoraDiurnaTrabajada = $codigoHoraDiurnaTrabajada;

        return $this;
    }

    /**
     * Get codigoHoraDiurnaTrabajada
     *
     * @return integer
     */
    public function getCodigoHoraDiurnaTrabajada()
    {
        return $this->codigoHoraDiurnaTrabajada;
    }

    /**
     * Set codigoSalarioIntegral
     *
     * @param integer $codigoSalarioIntegral
     *
     * @return RhuConfiguracion
     */
    public function setCodigoSalarioIntegral($codigoSalarioIntegral)
    {
        $this->codigoSalarioIntegral = $codigoSalarioIntegral;

        return $this;
    }

    /**
     * Get codigoSalarioIntegral
     *
     * @return integer
     */
    public function getCodigoSalarioIntegral()
    {
        return $this->codigoSalarioIntegral;
    }

    /**
     * Set porcentajePensionExtra
     *
     * @param float $porcentajePensionExtra
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajePensionExtra($porcentajePensionExtra)
    {
        $this->porcentajePensionExtra = $porcentajePensionExtra;

        return $this;
    }

    /**
     * Get porcentajePensionExtra
     *
     * @return float
     */
    public function getPorcentajePensionExtra()
    {
        return $this->porcentajePensionExtra;
    }

    /**
     * Set codigoIncapacidad
     *
     * @param integer $codigoIncapacidad
     *
     * @return RhuConfiguracion
     */
    public function setCodigoIncapacidad($codigoIncapacidad)
    {
        $this->codigoIncapacidad = $codigoIncapacidad;

        return $this;
    }

    /**
     * Get codigoIncapacidad
     *
     * @return integer
     */
    public function getCodigoIncapacidad()
    {
        return $this->codigoIncapacidad;
    }

    /**
     * Set anioActual
     *
     * @param integer $anioActual
     *
     * @return RhuConfiguracion
     */
    public function setAnioActual($anioActual)
    {
        $this->anioActual = $anioActual;

        return $this;
    }

    /**
     * Get anioActual
     *
     * @return integer
     */
    public function getAnioActual()
    {
        return $this->anioActual;
    }

    /**
     * Set porcentajeIva
     *
     * @param float $porcentajeIva
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return float
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set codigoRetencionFuente
     *
     * @param integer $codigoRetencionFuente
     *
     * @return RhuConfiguracion
     */
    public function setCodigoRetencionFuente($codigoRetencionFuente)
    {
        $this->codigoRetencionFuente = $codigoRetencionFuente;

        return $this;
    }

    /**
     * Get codigoRetencionFuente
     *
     * @return integer
     */
    public function getCodigoRetencionFuente()
    {
        return $this->codigoRetencionFuente;
    }

    /**
     * Set edadMinimaEmpleado
     *
     * @param integer $edadMinimaEmpleado
     *
     * @return RhuConfiguracion
     */
    public function setEdadMinimaEmpleado($edadMinimaEmpleado)
    {
        $this->edadMinimaEmpleado = $edadMinimaEmpleado;

        return $this;
    }

    /**
     * Get edadMinimaEmpleado
     *
     * @return integer
     */
    public function getEdadMinimaEmpleado()
    {
        return $this->edadMinimaEmpleado;
    }

    /**
     * Set porcentajeBonificacionNoPrestacional
     *
     * @param float $porcentajeBonificacionNoPrestacional
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeBonificacionNoPrestacional($porcentajeBonificacionNoPrestacional)
    {
        $this->porcentajeBonificacionNoPrestacional = $porcentajeBonificacionNoPrestacional;

        return $this;
    }

    /**
     * Get porcentajeBonificacionNoPrestacional
     *
     * @return float
     */
    public function getPorcentajeBonificacionNoPrestacional()
    {
        return $this->porcentajeBonificacionNoPrestacional;
    }

    /**
     * Set codigoEntidadExamenIngreso
     *
     * @param integer $codigoEntidadExamenIngreso
     *
     * @return RhuConfiguracion
     */
    public function setCodigoEntidadExamenIngreso($codigoEntidadExamenIngreso)
    {
        $this->codigoEntidadExamenIngreso = $codigoEntidadExamenIngreso;

        return $this;
    }

    /**
     * Get codigoEntidadExamenIngreso
     *
     * @return integer
     */
    public function getCodigoEntidadExamenIngreso()
    {
        return $this->codigoEntidadExamenIngreso;
    }

    /**
     * Set codigoComprobantePagoNomina
     *
     * @param integer $codigoComprobantePagoNomina
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobantePagoNomina($codigoComprobantePagoNomina)
    {
        $this->codigoComprobantePagoNomina = $codigoComprobantePagoNomina;

        return $this;
    }

    /**
     * Get codigoComprobantePagoNomina
     *
     * @return integer
     */
    public function getCodigoComprobantePagoNomina()
    {
        return $this->codigoComprobantePagoNomina;
    }

    /**
     * Set codigoComprobanteProvision
     *
     * @param integer $codigoComprobanteProvision
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobanteProvision($codigoComprobanteProvision)
    {
        $this->codigoComprobanteProvision = $codigoComprobanteProvision;

        return $this;
    }

    /**
     * Get codigoComprobanteProvision
     *
     * @return integer
     */
    public function getCodigoComprobanteProvision()
    {
        return $this->codigoComprobanteProvision;
    }

    /**
     * Set codigoComprobanteLiquidacion
     *
     * @param integer $codigoComprobanteLiquidacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobanteLiquidacion($codigoComprobanteLiquidacion)
    {
        $this->codigoComprobanteLiquidacion = $codigoComprobanteLiquidacion;

        return $this;
    }

    /**
     * Get codigoComprobanteLiquidacion
     *
     * @return integer
     */
    public function getCodigoComprobanteLiquidacion()
    {
        return $this->codigoComprobanteLiquidacion;
    }

    /**
     * Set codigoComprobanteVacacion
     *
     * @param integer $codigoComprobanteVacacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobanteVacacion($codigoComprobanteVacacion)
    {
        $this->codigoComprobanteVacacion = $codigoComprobanteVacacion;

        return $this;
    }

    /**
     * Get codigoComprobanteVacacion
     *
     * @return integer
     */
    public function getCodigoComprobanteVacacion()
    {
        return $this->codigoComprobanteVacacion;
    }

    /**
     * Set codigoComprobantePagoBanco
     *
     * @param integer $codigoComprobantePagoBanco
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobantePagoBanco($codigoComprobantePagoBanco)
    {
        $this->codigoComprobantePagoBanco = $codigoComprobantePagoBanco;

        return $this;
    }

    /**
     * Get codigoComprobantePagoBanco
     *
     * @return integer
     */
    public function getCodigoComprobantePagoBanco()
    {
        return $this->codigoComprobantePagoBanco;
    }

    /**
     * Set controlPago
     *
     * @param boolean $controlPago
     *
     * @return RhuConfiguracion
     */
    public function setControlPago($controlPago)
    {
        $this->controlPago = $controlPago;

        return $this;
    }

    /**
     * Get controlPago
     *
     * @return boolean
     */
    public function getControlPago()
    {
        return $this->controlPago;
    }

    /**
     * Set prestacionesPorcentajeCesantias
     *
     * @param float $prestacionesPorcentajeCesantias
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeCesantias($prestacionesPorcentajeCesantias)
    {
        $this->prestacionesPorcentajeCesantias = $prestacionesPorcentajeCesantias;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeCesantias
     *
     * @return float
     */
    public function getPrestacionesPorcentajeCesantias()
    {
        return $this->prestacionesPorcentajeCesantias;
    }

    /**
     * Set prestacionesPorcentajeInteresesCesantias
     *
     * @param float $prestacionesPorcentajeInteresesCesantias
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeInteresesCesantias($prestacionesPorcentajeInteresesCesantias)
    {
        $this->prestacionesPorcentajeInteresesCesantias = $prestacionesPorcentajeInteresesCesantias;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeInteresesCesantias
     *
     * @return float
     */
    public function getPrestacionesPorcentajeInteresesCesantias()
    {
        return $this->prestacionesPorcentajeInteresesCesantias;
    }

    /**
     * Set prestacionesPorcentajePrimas
     *
     * @param float $prestacionesPorcentajePrimas
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajePrimas($prestacionesPorcentajePrimas)
    {
        $this->prestacionesPorcentajePrimas = $prestacionesPorcentajePrimas;

        return $this;
    }

    /**
     * Get prestacionesPorcentajePrimas
     *
     * @return float
     */
    public function getPrestacionesPorcentajePrimas()
    {
        return $this->prestacionesPorcentajePrimas;
    }

    /**
     * Set prestacionesPorcentajeVacaciones
     *
     * @param float $prestacionesPorcentajeVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeVacaciones($prestacionesPorcentajeVacaciones)
    {
        $this->prestacionesPorcentajeVacaciones = $prestacionesPorcentajeVacaciones;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeVacaciones
     *
     * @return float
     */
    public function getPrestacionesPorcentajeVacaciones()
    {
        return $this->prestacionesPorcentajeVacaciones;
    }

    /**
     * Set prestacionesPorcentajeAporteVacaciones
     *
     * @param float $prestacionesPorcentajeAporteVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeAporteVacaciones($prestacionesPorcentajeAporteVacaciones)
    {
        $this->prestacionesPorcentajeAporteVacaciones = $prestacionesPorcentajeAporteVacaciones;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeAporteVacaciones
     *
     * @return float
     */
    public function getPrestacionesPorcentajeAporteVacaciones()
    {
        return $this->prestacionesPorcentajeAporteVacaciones;
    }

    /**
     * Set prestacionesPorcentajeIndemnizacion
     *
     * @param float $prestacionesPorcentajeIndemnizacion
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeIndemnizacion($prestacionesPorcentajeIndemnizacion)
    {
        $this->prestacionesPorcentajeIndemnizacion = $prestacionesPorcentajeIndemnizacion;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeIndemnizacion
     *
     * @return float
     */
    public function getPrestacionesPorcentajeIndemnizacion()
    {
        return $this->prestacionesPorcentajeIndemnizacion;
    }

    /**
     * Set aportesPorcentajeCaja
     *
     * @param float $aportesPorcentajeCaja
     *
     * @return RhuConfiguracion
     */
    public function setAportesPorcentajeCaja($aportesPorcentajeCaja)
    {
        $this->aportesPorcentajeCaja = $aportesPorcentajeCaja;

        return $this;
    }

    /**
     * Get aportesPorcentajeCaja
     *
     * @return float
     */
    public function getAportesPorcentajeCaja()
    {
        return $this->aportesPorcentajeCaja;
    }

    /**
     * Set aportesPorcentajeVacaciones
     *
     * @param float $aportesPorcentajeVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setAportesPorcentajeVacaciones($aportesPorcentajeVacaciones)
    {
        $this->aportesPorcentajeVacaciones = $aportesPorcentajeVacaciones;

        return $this;
    }

    /**
     * Get aportesPorcentajeVacaciones
     *
     * @return float
     */
    public function getAportesPorcentajeVacaciones()
    {
        return $this->aportesPorcentajeVacaciones;
    }

    /**
     * Set codigoHoraDescanso
     *
     * @param integer $codigoHoraDescanso
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraDescanso($codigoHoraDescanso)
    {
        $this->codigoHoraDescanso = $codigoHoraDescanso;

        return $this;
    }

    /**
     * Get codigoHoraDescanso
     *
     * @return integer
     */
    public function getCodigoHoraDescanso()
    {
        return $this->codigoHoraDescanso;
    }

    /**
     * Set codigoHoraNocturna
     *
     * @param integer $codigoHoraNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraNocturna($codigoHoraNocturna)
    {
        $this->codigoHoraNocturna = $codigoHoraNocturna;

        return $this;
    }

    /**
     * Get codigoHoraNocturna
     *
     * @return integer
     */
    public function getCodigoHoraNocturna()
    {
        return $this->codigoHoraNocturna;
    }

    /**
     * Set codigoHoraFestivaDiurna
     *
     * @param integer $codigoHoraFestivaDiurna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraFestivaDiurna($codigoHoraFestivaDiurna)
    {
        $this->codigoHoraFestivaDiurna = $codigoHoraFestivaDiurna;

        return $this;
    }

    /**
     * Get codigoHoraFestivaDiurna
     *
     * @return integer
     */
    public function getCodigoHoraFestivaDiurna()
    {
        return $this->codigoHoraFestivaDiurna;
    }

    /**
     * Set codigoHoraFestivaNocturna
     *
     * @param integer $codigoHoraFestivaNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraFestivaNocturna($codigoHoraFestivaNocturna)
    {
        $this->codigoHoraFestivaNocturna = $codigoHoraFestivaNocturna;

        return $this;
    }

    /**
     * Get codigoHoraFestivaNocturna
     *
     * @return integer
     */
    public function getCodigoHoraFestivaNocturna()
    {
        return $this->codigoHoraFestivaNocturna;
    }

    /**
     * Set codigoHoraExtraOrdinariaDiurna
     *
     * @param integer $codigoHoraExtraOrdinariaDiurna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraOrdinariaDiurna($codigoHoraExtraOrdinariaDiurna)
    {
        $this->codigoHoraExtraOrdinariaDiurna = $codigoHoraExtraOrdinariaDiurna;

        return $this;
    }

    /**
     * Get codigoHoraExtraOrdinariaDiurna
     *
     * @return integer
     */
    public function getCodigoHoraExtraOrdinariaDiurna()
    {
        return $this->codigoHoraExtraOrdinariaDiurna;
    }

    /**
     * Set codigoHoraExtraOrdinariaNocturna
     *
     * @param integer $codigoHoraExtraOrdinariaNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraOrdinariaNocturna($codigoHoraExtraOrdinariaNocturna)
    {
        $this->codigoHoraExtraOrdinariaNocturna = $codigoHoraExtraOrdinariaNocturna;

        return $this;
    }

    /**
     * Get codigoHoraExtraOrdinariaNocturna
     *
     * @return integer
     */
    public function getCodigoHoraExtraOrdinariaNocturna()
    {
        return $this->codigoHoraExtraOrdinariaNocturna;
    }

    /**
     * Set codigoHoraExtraFestivaDiurna
     *
     * @param integer $codigoHoraExtraFestivaDiurna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraFestivaDiurna($codigoHoraExtraFestivaDiurna)
    {
        $this->codigoHoraExtraFestivaDiurna = $codigoHoraExtraFestivaDiurna;

        return $this;
    }

    /**
     * Get codigoHoraExtraFestivaDiurna
     *
     * @return integer
     */
    public function getCodigoHoraExtraFestivaDiurna()
    {
        return $this->codigoHoraExtraFestivaDiurna;
    }

    /**
     * Set codigoHoraExtraFestivaNocturna
     *
     * @param integer $codigoHoraExtraFestivaNocturna
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraExtraFestivaNocturna($codigoHoraExtraFestivaNocturna)
    {
        $this->codigoHoraExtraFestivaNocturna = $codigoHoraExtraFestivaNocturna;

        return $this;
    }

    /**
     * Get codigoHoraExtraFestivaNocturna
     *
     * @return integer
     */
    public function getCodigoHoraExtraFestivaNocturna()
    {
        return $this->codigoHoraExtraFestivaNocturna;
    }

    /**
     * Set codigoHoraRecargoNocturno
     *
     * @param integer $codigoHoraRecargoNocturno
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraRecargoNocturno($codigoHoraRecargoNocturno)
    {
        $this->codigoHoraRecargoNocturno = $codigoHoraRecargoNocturno;

        return $this;
    }

    /**
     * Get codigoHoraRecargoNocturno
     *
     * @return integer
     */
    public function getCodigoHoraRecargoNocturno()
    {
        return $this->codigoHoraRecargoNocturno;
    }

    /**
     * Set codigoHoraRecargoFestivoDiurno
     *
     * @param integer $codigoHoraRecargoFestivoDiurno
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraRecargoFestivoDiurno($codigoHoraRecargoFestivoDiurno)
    {
        $this->codigoHoraRecargoFestivoDiurno = $codigoHoraRecargoFestivoDiurno;

        return $this;
    }

    /**
     * Get codigoHoraRecargoFestivoDiurno
     *
     * @return integer
     */
    public function getCodigoHoraRecargoFestivoDiurno()
    {
        return $this->codigoHoraRecargoFestivoDiurno;
    }

    /**
     * Set codigoHoraRecargoFestivoNocturno
     *
     * @param integer $codigoHoraRecargoFestivoNocturno
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraRecargoFestivoNocturno($codigoHoraRecargoFestivoNocturno)
    {
        $this->codigoHoraRecargoFestivoNocturno = $codigoHoraRecargoFestivoNocturno;

        return $this;
    }

    /**
     * Get codigoHoraRecargoFestivoNocturno
     *
     * @return integer
     */
    public function getCodigoHoraRecargoFestivoNocturno()
    {
        return $this->codigoHoraRecargoFestivoNocturno;
    }

    /**
     * Set codigoVacacion
     *
     * @param integer $codigoVacacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoVacacion($codigoVacacion)
    {
        $this->codigoVacacion = $codigoVacacion;

        return $this;
    }

    /**
     * Get codigoVacacion
     *
     * @return integer
     */
    public function getCodigoVacacion()
    {
        return $this->codigoVacacion;
    }

    /**
     * Set codigoAjusteDevengado
     *
     * @param integer $codigoAjusteDevengado
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAjusteDevengado($codigoAjusteDevengado)
    {
        $this->codigoAjusteDevengado = $codigoAjusteDevengado;

        return $this;
    }

    /**
     * Get codigoAjusteDevengado
     *
     * @return integer
     */
    public function getCodigoAjusteDevengado()
    {
        return $this->codigoAjusteDevengado;
    }

    /**
     * Set afectaVacacionesParafiscales
     *
     * @param boolean $afectaVacacionesParafiscales
     *
     * @return RhuConfiguracion
     */
    public function setAfectaVacacionesParafiscales($afectaVacacionesParafiscales)
    {
        $this->afectaVacacionesParafiscales = $afectaVacacionesParafiscales;

        return $this;
    }

    /**
     * Get afectaVacacionesParafiscales
     *
     * @return boolean
     */
    public function getAfectaVacacionesParafiscales()
    {
        return $this->afectaVacacionesParafiscales;
    }

    /**
     * Set codigoFormatoPago
     *
     * @param integer $codigoFormatoPago
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoPago($codigoFormatoPago)
    {
        $this->codigoFormatoPago = $codigoFormatoPago;

        return $this;
    }

    /**
     * Get codigoFormatoPago
     *
     * @return integer
     */
    public function getCodigoFormatoPago()
    {
        return $this->codigoFormatoPago;
    }

    /**
     * Set codigoFormatoLiquidacion
     *
     * @param integer $codigoFormatoLiquidacion
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoLiquidacion($codigoFormatoLiquidacion)
    {
        $this->codigoFormatoLiquidacion = $codigoFormatoLiquidacion;

        return $this;
    }

    /**
     * Get codigoFormatoLiquidacion
     *
     * @return integer
     */
    public function getCodigoFormatoLiquidacion()
    {
        return $this->codigoFormatoLiquidacion;
    }

    /**
     * Set codigoFormatoCarta
     *
     * @param integer $codigoFormatoCarta
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoCarta($codigoFormatoCarta)
    {
        $this->codigoFormatoCarta = $codigoFormatoCarta;

        return $this;
    }

    /**
     * Get codigoFormatoCarta
     *
     * @return integer
     */
    public function getCodigoFormatoCarta()
    {
        return $this->codigoFormatoCarta;
    }

    /**
     * Set codigoFormatoDisciplinario
     *
     * @param integer $codigoFormatoDisciplinario
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoDisciplinario($codigoFormatoDisciplinario)
    {
        $this->codigoFormatoDisciplinario = $codigoFormatoDisciplinario;

        return $this;
    }

    /**
     * Get codigoFormatoDisciplinario
     *
     * @return integer
     */
    public function getCodigoFormatoDisciplinario()
    {
        return $this->codigoFormatoDisciplinario;
    }

    /**
     * Set codigoFormatoDescargo
     *
     * @param integer $codigoFormatoDescargo
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoDescargo($codigoFormatoDescargo)
    {
        $this->codigoFormatoDescargo = $codigoFormatoDescargo;

        return $this;
    }

    /**
     * Get codigoFormatoDescargo
     *
     * @return integer
     */
    public function getCodigoFormatoDescargo()
    {
        return $this->codigoFormatoDescargo;
    }

    /**
     * Set codigoFormatoFactura
     *
     * @param integer $codigoFormatoFactura
     *
     * @return RhuConfiguracion
     */
    public function setCodigoFormatoFactura($codigoFormatoFactura)
    {
        $this->codigoFormatoFactura = $codigoFormatoFactura;

        return $this;
    }

    /**
     * Get codigoFormatoFactura
     *
     * @return integer
     */
    public function getCodigoFormatoFactura()
    {
        return $this->codigoFormatoFactura;
    }

    /**
     * Set tipoBasePagoVacaciones
     *
     * @param integer $tipoBasePagoVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setTipoBasePagoVacaciones($tipoBasePagoVacaciones)
    {
        $this->tipoBasePagoVacaciones = $tipoBasePagoVacaciones;

        return $this;
    }

    /**
     * Get tipoBasePagoVacaciones
     *
     * @return integer
     */
    public function getTipoBasePagoVacaciones()
    {
        return $this->tipoBasePagoVacaciones;
    }

    /**
     * Set generaPorcetnajeLiquidacion
     *
     * @param boolean $generaPorcetnajeLiquidacion
     *
     * @return RhuConfiguracion
     */
    public function setGeneraPorcetnajeLiquidacion($generaPorcetnajeLiquidacion)
    {
        $this->generaPorcetnajeLiquidacion = $generaPorcetnajeLiquidacion;

        return $this;
    }

    /**
     * Get generaPorcetnajeLiquidacion
     *
     * @return boolean
     */
    public function getGeneraPorcetnajeLiquidacion()
    {
        return $this->generaPorcetnajeLiquidacion;
    }

    /**
     * Set correoNomina
     *
     * @param string $correoNomina
     *
     * @return RhuConfiguracion
     */
    public function setCorreoNomina($correoNomina)
    {
        $this->correoNomina = $correoNomina;

        return $this;
    }

    /**
     * Get correoNomina
     *
     * @return string
     */
    public function getCorreoNomina()
    {
        return $this->correoNomina;
    }

    /**
     * Set imprimirMensajePago
     *
     * @param boolean $imprimirMensajePago
     *
     * @return RhuConfiguracion
     */
    public function setImprimirMensajePago($imprimirMensajePago)
    {
        $this->imprimirMensajePago = $imprimirMensajePago;

        return $this;
    }

    /**
     * Get imprimirMensajePago
     *
     * @return boolean
     */
    public function getImprimirMensajePago()
    {
        return $this->imprimirMensajePago;
    }

    /**
     * Set codigoPrima
     *
     * @param integer $codigoPrima
     *
     * @return RhuConfiguracion
     */
    public function setCodigoPrima($codigoPrima)
    {
        $this->codigoPrima = $codigoPrima;

        return $this;
    }

    /**
     * Get codigoPrima
     *
     * @return integer
     */
    public function getCodigoPrima()
    {
        return $this->codigoPrima;
    }

    /**
     * Set codigoCesantia
     *
     * @param integer $codigoCesantia
     *
     * @return RhuConfiguracion
     */
    public function setCodigoCesantia($codigoCesantia)
    {
        $this->codigoCesantia = $codigoCesantia;

        return $this;
    }

    /**
     * Get codigoCesantia
     *
     * @return integer
     */
    public function getCodigoCesantia()
    {
        return $this->codigoCesantia;
    }

    /**
     * Set codigoInteresCesantia
     *
     * @param integer $codigoInteresCesantia
     *
     * @return RhuConfiguracion
     */
    public function setCodigoInteresCesantia($codigoInteresCesantia)
    {
        $this->codigoInteresCesantia = $codigoInteresCesantia;

        return $this;
    }

    /**
     * Get codigoInteresCesantia
     *
     * @return integer
     */
    public function getCodigoInteresCesantia()
    {
        return $this->codigoInteresCesantia;
    }

    /**
     * Set prestacionesAplicaPorcentajeSalario
     *
     * @param boolean $prestacionesAplicaPorcentajeSalario
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesAplicaPorcentajeSalario($prestacionesAplicaPorcentajeSalario)
    {
        $this->prestacionesAplicaPorcentajeSalario = $prestacionesAplicaPorcentajeSalario;

        return $this;
    }

    /**
     * Get prestacionesAplicaPorcentajeSalario
     *
     * @return boolean
     */
    public function getPrestacionesAplicaPorcentajeSalario()
    {
        return $this->prestacionesAplicaPorcentajeSalario;
    }

    /**
     * Set nitSena
     *
     * @param string $nitSena
     *
     * @return RhuConfiguracion
     */
    public function setNitSena($nitSena)
    {
        $this->nitSena = $nitSena;

        return $this;
    }

    /**
     * Get nitSena
     *
     * @return string
     */
    public function getNitSena()
    {
        return $this->nitSena;
    }

    /**
     * Set nitIcbf
     *
     * @param string $nitIcbf
     *
     * @return RhuConfiguracion
     */
    public function setNitIcbf($nitIcbf)
    {
        $this->nitIcbf = $nitIcbf;

        return $this;
    }

    /**
     * Get nitIcbf
     *
     * @return string
     */
    public function getNitIcbf()
    {
        return $this->nitIcbf;
    }

    /**
     * Set diasAusentismoPrimas
     *
     * @param boolean $diasAusentismoPrimas
     *
     * @return RhuConfiguracion
     */
    public function setDiasAusentismoPrimas($diasAusentismoPrimas)
    {
        $this->diasAusentismoPrimas = $diasAusentismoPrimas;

        return $this;
    }

    /**
     * Get diasAusentismoPrimas
     *
     * @return boolean
     */
    public function getDiasAusentismoPrimas()
    {
        return $this->diasAusentismoPrimas;
    }

    /**
     * Set promedioPrimasLaborado
     *
     * @param boolean $promedioPrimasLaborado
     *
     * @return RhuConfiguracion
     */
    public function setPromedioPrimasLaborado($promedioPrimasLaborado)
    {
        $this->promedioPrimasLaborado = $promedioPrimasLaborado;

        return $this;
    }

    /**
     * Get promedioPrimasLaborado
     *
     * @return boolean
     */
    public function getPromedioPrimasLaborado()
    {
        return $this->promedioPrimasLaborado;
    }

    /**
     * Set promedioPrimasLaboradoDias
     *
     * @param integer $promedioPrimasLaboradoDias
     *
     * @return RhuConfiguracion
     */
    public function setPromedioPrimasLaboradoDias($promedioPrimasLaboradoDias)
    {
        $this->promedioPrimasLaboradoDias = $promedioPrimasLaboradoDias;

        return $this;
    }

    /**
     * Get promedioPrimasLaboradoDias
     *
     * @return integer
     */
    public function getPromedioPrimasLaboradoDias()
    {
        return $this->promedioPrimasLaboradoDias;
    }

    /**
     * Set omitirDescuentoEmbargoPrimas
     *
     * @param boolean $omitirDescuentoEmbargoPrimas
     *
     * @return RhuConfiguracion
     */
    public function setOmitirDescuentoEmbargoPrimas($omitirDescuentoEmbargoPrimas)
    {
        $this->omitirDescuentoEmbargoPrimas = $omitirDescuentoEmbargoPrimas;

        return $this;
    }

    /**
     * Get omitirDescuentoEmbargoPrimas
     *
     * @return boolean
     */
    public function getOmitirDescuentoEmbargoPrimas()
    {
        return $this->omitirDescuentoEmbargoPrimas;
    }

    /**
     * Set omitirDescuentoEmbargoCesantias
     *
     * @param boolean $omitirDescuentoEmbargoCesantias
     *
     * @return RhuConfiguracion
     */
    public function setOmitirDescuentoEmbargoCesantias($omitirDescuentoEmbargoCesantias)
    {
        $this->omitirDescuentoEmbargoCesantias = $omitirDescuentoEmbargoCesantias;

        return $this;
    }

    /**
     * Get omitirDescuentoEmbargoCesantias
     *
     * @return boolean
     */
    public function getOmitirDescuentoEmbargoCesantias()
    {
        return $this->omitirDescuentoEmbargoCesantias;
    }

    /**
     * Set direccionServidorArdid
     *
     * @param string $direccionServidorArdid
     *
     * @return RhuConfiguracion
     */
    public function setDireccionServidorArdid($direccionServidorArdid)
    {
        $this->direccionServidorArdid = $direccionServidorArdid;

        return $this;
    }

    /**
     * Get direccionServidorArdid
     *
     * @return string
     */
    public function getDireccionServidorArdid()
    {
        return $this->direccionServidorArdid;
    }

    /**
     * Set codigoEmpresaArdid
     *
     * @param integer $codigoEmpresaArdid
     *
     * @return RhuConfiguracion
     */
    public function setCodigoEmpresaArdid($codigoEmpresaArdid)
    {
        $this->codigoEmpresaArdid = $codigoEmpresaArdid;

        return $this;
    }

    /**
     * Get codigoEmpresaArdid
     *
     * @return integer
     */
    public function getCodigoEmpresaArdid()
    {
        return $this->codigoEmpresaArdid;
    }

    /**
     * Set pagarLicenciaSalarioPactado
     *
     * @param boolean $pagarLicenciaSalarioPactado
     *
     * @return RhuConfiguracion
     */
    public function setPagarLicenciaSalarioPactado($pagarLicenciaSalarioPactado)
    {
        $this->pagarLicenciaSalarioPactado = $pagarLicenciaSalarioPactado;

        return $this;
    }

    /**
     * Get pagarLicenciaSalarioPactado
     *
     * @return boolean
     */
    public function getPagarLicenciaSalarioPactado()
    {
        return $this->pagarLicenciaSalarioPactado;
    }

    /**
     * Set pagarIncapacidadSalarioPactado
     *
     * @param boolean $pagarIncapacidadSalarioPactado
     *
     * @return RhuConfiguracion
     */
    public function setPagarIncapacidadSalarioPactado($pagarIncapacidadSalarioPactado)
    {
        $this->pagarIncapacidadSalarioPactado = $pagarIncapacidadSalarioPactado;

        return $this;
    }

    /**
     * Get pagarIncapacidadSalarioPactado
     *
     * @return boolean
     */
    public function getPagarIncapacidadSalarioPactado()
    {
        return $this->pagarIncapacidadSalarioPactado;
    }

    /**
     * Set informacionLegalFactura
     *
     * @param string $informacionLegalFactura
     *
     * @return RhuConfiguracion
     */
    public function setInformacionLegalFactura($informacionLegalFactura)
    {
        $this->informacionLegalFactura = $informacionLegalFactura;

        return $this;
    }

    /**
     * Get informacionLegalFactura
     *
     * @return string
     */
    public function getInformacionLegalFactura()
    {
        return $this->informacionLegalFactura;
    }

    /**
     * Set informacionPagoFactura
     *
     * @param string $informacionPagoFactura
     *
     * @return RhuConfiguracion
     */
    public function setInformacionPagoFactura($informacionPagoFactura)
    {
        $this->informacionPagoFactura = $informacionPagoFactura;

        return $this;
    }

    /**
     * Get informacionPagoFactura
     *
     * @return string
     */
    public function getInformacionPagoFactura()
    {
        return $this->informacionPagoFactura;
    }

    /**
     * Set informacionContactoFactura
     *
     * @param string $informacionContactoFactura
     *
     * @return RhuConfiguracion
     */
    public function setInformacionContactoFactura($informacionContactoFactura)
    {
        $this->informacionContactoFactura = $informacionContactoFactura;

        return $this;
    }

    /**
     * Get informacionContactoFactura
     *
     * @return string
     */
    public function getInformacionContactoFactura()
    {
        return $this->informacionContactoFactura;
    }

    /**
     * Set informacionResolucionDianFactura
     *
     * @param string $informacionResolucionDianFactura
     *
     * @return RhuConfiguracion
     */
    public function setInformacionResolucionDianFactura($informacionResolucionDianFactura)
    {
        $this->informacionResolucionDianFactura = $informacionResolucionDianFactura;

        return $this;
    }

    /**
     * Get informacionResolucionDianFactura
     *
     * @return string
     */
    public function getInformacionResolucionDianFactura()
    {
        return $this->informacionResolucionDianFactura;
    }

    /**
     * Set informacionResolucionSupervigilanciaFactura
     *
     * @param string $informacionResolucionSupervigilanciaFactura
     *
     * @return RhuConfiguracion
     */
    public function setInformacionResolucionSupervigilanciaFactura($informacionResolucionSupervigilanciaFactura)
    {
        $this->informacionResolucionSupervigilanciaFactura = $informacionResolucionSupervigilanciaFactura;

        return $this;
    }

    /**
     * Get informacionResolucionSupervigilanciaFactura
     *
     * @return string
     */
    public function getInformacionResolucionSupervigilanciaFactura()
    {
        return $this->informacionResolucionSupervigilanciaFactura;
    }

    /**
     * Set horasDomingoNoCompensado
     *
     * @param integer $horasDomingoNoCompensado
     *
     * @return RhuConfiguracion
     */
    public function setHorasDomingoNoCompensado($horasDomingoNoCompensado)
    {
        $this->horasDomingoNoCompensado = $horasDomingoNoCompensado;

        return $this;
    }

    /**
     * Get horasDomingoNoCompensado
     *
     * @return integer
     */
    public function getHorasDomingoNoCompensado()
    {
        return $this->horasDomingoNoCompensado;
    }

    /**
     * Set horasDomingoCompensado
     *
     * @param integer $horasDomingoCompensado
     *
     * @return RhuConfiguracion
     */
    public function setHorasDomingoCompensado($horasDomingoCompensado)
    {
        $this->horasDomingoCompensado = $horasDomingoCompensado;

        return $this;
    }

    /**
     * Get horasDomingoCompensado
     *
     * @return integer
     */
    public function getHorasDomingoCompensado()
    {
        return $this->horasDomingoCompensado;
    }

    /**
     * Set horasRecargoNocturnoFestivoCompensado
     *
     * @param integer $horasRecargoNocturnoFestivoCompensado
     *
     * @return RhuConfiguracion
     */
    public function setHorasRecargoNocturnoFestivoCompensado($horasRecargoNocturnoFestivoCompensado)
    {
        $this->horasRecargoNocturnoFestivoCompensado = $horasRecargoNocturnoFestivoCompensado;

        return $this;
    }

    /**
     * Get horasRecargoNocturnoFestivoCompensado
     *
     * @return integer
     */
    public function getHorasRecargoNocturnoFestivoCompensado()
    {
        return $this->horasRecargoNocturnoFestivoCompensado;
    }

    /**
     * Set horasRecargoNocturnoFestivoNoCompensado
     *
     * @param integer $horasRecargoNocturnoFestivoNoCompensado
     *
     * @return RhuConfiguracion
     */
    public function setHorasRecargoNocturnoFestivoNoCompensado($horasRecargoNocturnoFestivoNoCompensado)
    {
        $this->horasRecargoNocturnoFestivoNoCompensado = $horasRecargoNocturnoFestivoNoCompensado;

        return $this;
    }

    /**
     * Get horasRecargoNocturnoFestivoNoCompensado
     *
     * @return integer
     */
    public function getHorasRecargoNocturnoFestivoNoCompensado()
    {
        return $this->horasRecargoNocturnoFestivoNoCompensado;
    }

    /**
     * Set liquidarVacacionesSalario
     *
     * @param boolean $liquidarVacacionesSalario
     *
     * @return RhuConfiguracion
     */
    public function setLiquidarVacacionesSalario($liquidarVacacionesSalario)
    {
        $this->liquidarVacacionesSalario = $liquidarVacacionesSalario;

        return $this;
    }

    /**
     * Get liquidarVacacionesSalario
     *
     * @return boolean
     */
    public function getLiquidarVacacionesSalario()
    {
        return $this->liquidarVacacionesSalario;
    }

    /**
     * Set liquidarAuxilioTransportePrima
     *
     * @param boolean $liquidarAuxilioTransportePrima
     *
     * @return RhuConfiguracion
     */
    public function setLiquidarAuxilioTransportePrima($liquidarAuxilioTransportePrima)
    {
        $this->liquidarAuxilioTransportePrima = $liquidarAuxilioTransportePrima;

        return $this;
    }

    /**
     * Get liquidarAuxilioTransportePrima
     *
     * @return boolean
     */
    public function getLiquidarAuxilioTransportePrima()
    {
        return $this->liquidarAuxilioTransportePrima;
    }

    /**
     * Set horasExtraDominicalDiurna
     *
     * @param integer $horasExtraDominicalDiurna
     *
     * @return RhuConfiguracion
     */
    public function setHorasExtraDominicalDiurna($horasExtraDominicalDiurna)
    {
        $this->horasExtraDominicalDiurna = $horasExtraDominicalDiurna;

        return $this;
    }

    /**
     * Get horasExtraDominicalDiurna
     *
     * @return integer
     */
    public function getHorasExtraDominicalDiurna()
    {
        return $this->horasExtraDominicalDiurna;
    }

    /**
     * Set auxilioTransporteNoPrestacional
     *
     * @param boolean $auxilioTransporteNoPrestacional
     *
     * @return RhuConfiguracion
     */
    public function setAuxilioTransporteNoPrestacional($auxilioTransporteNoPrestacional)
    {
        $this->auxilioTransporteNoPrestacional = $auxilioTransporteNoPrestacional;

        return $this;
    }

    /**
     * Get auxilioTransporteNoPrestacional
     *
     * @return boolean
     */
    public function getAuxilioTransporteNoPrestacional()
    {
        return $this->auxilioTransporteNoPrestacional;
    }

    /**
     * Set ordenNombreEmpleado
     *
     * @param integer $ordenNombreEmpleado
     *
     * @return RhuConfiguracion
     */
    public function setOrdenNombreEmpleado($ordenNombreEmpleado)
    {
        $this->ordenNombreEmpleado = $ordenNombreEmpleado;

        return $this;
    }

    /**
     * Get ordenNombreEmpleado
     *
     * @return integer
     */
    public function getOrdenNombreEmpleado()
    {
        return $this->ordenNombreEmpleado;
    }

    /**
     * Set requiereRequisitoContratacion
     *
     * @param boolean $requiereRequisitoContratacion
     *
     * @return RhuConfiguracion
     */
    public function setRequiereRequisitoContratacion($requiereRequisitoContratacion)
    {
        $this->requiereRequisitoContratacion = $requiereRequisitoContratacion;

        return $this;
    }

    /**
     * Get requiereRequisitoContratacion
     *
     * @return boolean
     */
    public function getRequiereRequisitoContratacion()
    {
        return $this->requiereRequisitoContratacion;
    }

    /**
     * Set generarNovedadVacacionesTurnos
     *
     * @param boolean $generarNovedadVacacionesTurnos
     *
     * @return RhuConfiguracion
     */
    public function setGenerarNovedadVacacionesTurnos($generarNovedadVacacionesTurnos)
    {
        $this->generarNovedadVacacionesTurnos = $generarNovedadVacacionesTurnos;

        return $this;
    }

    /**
     * Get generarNovedadVacacionesTurnos
     *
     * @return boolean
     */
    public function getGenerarNovedadVacacionesTurnos()
    {
        return $this->generarNovedadVacacionesTurnos;
    }

    /**
     * Set tipoNovedadVacacionTurno
     *
     * @param integer $tipoNovedadVacacionTurno
     *
     * @return RhuConfiguracion
     */
    public function setTipoNovedadVacacionTurno($tipoNovedadVacacionTurno)
    {
        $this->tipoNovedadVacacionTurno = $tipoNovedadVacacionTurno;

        return $this;
    }

    /**
     * Get tipoNovedadVacacionTurno
     *
     * @return integer
     */
    public function getTipoNovedadVacacionTurno()
    {
        return $this->tipoNovedadVacacionTurno;
    }

    /**
     * Set generarNovedadIncapacidadTurnos
     *
     * @param boolean $generarNovedadIncapacidadTurnos
     *
     * @return RhuConfiguracion
     */
    public function setGenerarNovedadIncapacidadTurnos($generarNovedadIncapacidadTurnos)
    {
        $this->generarNovedadIncapacidadTurnos = $generarNovedadIncapacidadTurnos;

        return $this;
    }

    /**
     * Get generarNovedadIncapacidadTurnos
     *
     * @return boolean
     */
    public function getGenerarNovedadIncapacidadTurnos()
    {
        return $this->generarNovedadIncapacidadTurnos;
    }
}
