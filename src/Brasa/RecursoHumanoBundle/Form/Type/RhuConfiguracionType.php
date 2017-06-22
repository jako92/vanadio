<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RhuConfiguracionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codigoAuxilioTransporte')
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
                ->add('codigoPrima')
                ->add('codigoCesantia')
                ->add('codigoInteresCesantia')
                ->add('tipoBasePagoVacaciones')
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
                ->add('promedioPrimasLaboradoDias')
                ->add('horasDomingoNoCompensado')
                ->add('horasDomingoCompensado')
                ->add('horasRecargoNocturnoFestivoCompensado')
                ->add('horasRecargoNocturnoFestivoNoCompensado')
                ->add('horasExtraDominicalDiurna')
                ->add('ordenNombreEmpleado')
                ->add('nitSena', TextType::class)
                ->add('nitIcbf', TextType::class)
                ->add('correoNomina', TextType::class)
                ->add('afectaVacacionesParafiscales')
                ->add('imprimirMensajePago', CheckboxType::class)
                ->add('auxilioTransporteNoPrestacional', CheckboxType::class)
                ->add('promedioPrimasLaborado', CheckboxType::class)
                ->add('liquidarAuxilioTransportePrima', CheckboxType::class)
                ->add('liquidarVacacionesSalario', CheckboxType::class)
                ->add('pagarIncapacidadSalarioPactado', CheckboxType::class)
                ->add('pagarLicenciaSalarioPactado', CheckboxType::class)
                ->add('generaPorcetnajeLiquidacion', CheckboxType::class)
                ->add('prestacionesAplicaPorcentajeSalario', CheckboxType::class)
                ->add('diasAusentismoPrimas', CheckboxType::class)
                ->add('omitirDescuentoEmbargoPrimas', CheckboxType::class)
                ->add('omitirDescuentoEmbargoCesantias', CheckboxType::class)
                ->add('requiereRequisitoContratacion', CheckboxType::class)
                ->add('informacionLegalFactura', TextareaType::class)
                ->add('informacionPagoFactura', TextareaType::class)
                ->add('informacionContactoFactura', TextareaType::class)
                ->add('informacionResolucionDianFactura', TextareaType::class)
                ->add('informacionResolucionSupervigilanciaFactura', TextareaType::class)
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
