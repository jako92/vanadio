<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuNuevoConfiguracionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codigoConfiguracionPk')
                ->add('codigoAuxilioTransporte')
                ->add('codigoCredito')
                ->add('codigoSeguro')
                ->add('codigoTiempoSuplementario')
                ->add('codigoHoraDiurnaTrabajada')
                ->add('codigoSalarioIntegral')
                ->add('codigoEntidadExamenIngreso')
                ->add('codigoComprobantePagoNomina')
                ->add('codigoComprobanteProvision')
                ->add('codigoComprobanteLiquidacion')
                ->add('codigoComprobanteVacacion')
                ->add('codigoComprobantePagoBanco')
                ->add('codigoIncapacidad')
                ->add('codigoRetencionFuente')
                ->add('codigoHoraDescanso')
                ->add('codigoHoraNocturna')
                ->add('codigoHoraFestivaDiurna')
                ->add('codigoHoraFestivaNocturna')
                ->add('codigoHoraExtraOrdinariaDiurna')
                ->add('codigoHoraExtraOrdinariaNocturna')
                ->add('codigoHoraExtraFestivaDiurna')
                ->add('codigoHoraExtraFestivaNocturna')
                ->add('codigoHoraRecargoNocturno')
                ->add('codigoHoraRecargoFestivoDiurno')
                ->add('codigoHoraRecargoFestivoNocturno')
                ->add('codigoVacacion')
                ->add('codigoAjusteDevengado')
                ->add('codigoFormatoPago')
                ->add('codigoFormatoLiquidacion')
                ->add('codigoFormatoCarta')
                ->add('codigoFormatoDisciplinario')
                ->add('codigoFormatoDescargo')
                ->add('codigoFormatoFactura')
                ->add('porcentajePensionExtra')
                ->add('vrSalario')
                ->add('anioActual')
                ->add('porcentajeIva')
                ->add('edadMinimaEmpleado')
                ->add('porcentajeBonificacionNoPrestacional')
                ->add('vrAuxilioTransporte')
                ->add('prestacionesPorcentajeCesantias')
                ->add('prestacionesPorcentajeInteresesCesantias')
                ->add('prestacionesPorcentajePrimas')
                ->add('prestacionesPorcentajeVacaciones')
                ->add('prestacionesPorcentajeAporteVacaciones')
                ->add('prestacionesPorcentajeIndemnizacion')
                ->add('aportesPorcentajeCaja')
                ->add('aportesPorcentajeVacaciones')
                ->add('afectaVacacionesParafiscales')
                ->add('tipoBasePagoVacaciones')
                ->add('generaPorcetnajeLiquidacion')
                ->add('correoNomina')
                ->add('imprimirMensajePago')
                ->add('codigoPrima')
                ->add('codigoCesantia')
                ->add('codigoInteresCesantia')
                ->add('prestacionesAplicaPorcentajeSalario')
                ->add('nitSena')
                ->add('nitIcbf')
                ->add('diasAusentismoPrimas')
                ->add('promedioPrimasLaborado')
                ->add('promedioPrimasLaboradoDias')
                ->add('omitirDescuentoEmbargoPrimas')
                ->add('omitirDescuentoEmbargoCesantias')
                ->add('pagarLicenciaSalarioPactado')
                ->add('pagarIncapacidadSalarioPactado')
                ->add('informacionLegalFactura')
                ->add('informacionPagoFactura')
                ->add('informacionContactoFactura')
                ->add('informacionResolucionDianFactura')
                ->add('informacionResolucionSupervigilanciaFactura')
                ->add('horasDomingoNoCompensado')
                ->add('horasDomingoCompensado')
                ->add('horasRecargoNocturnoFestivoCompensado')
                ->add('horasRecargoNocturnoFestivoNoCompensado')
                ->add('liquidarVacacionesSalario')
                ->add('liquidarAuxilioTransportePrima')
                ->add('horasExtraDominicalDiurna')
                ->add('auxilioTransporteNoPrestacional')
                ->add('ordenNombreEmpleado')
                ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'brasa_recursohumanobundle_rhuconfiguracion';
    }

}
