<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RhuPagoConceptoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('componeSalario', CheckboxType::class, array('required' => false))
            ->add('componeValor', CheckboxType::class, array('required' => false))
            ->add('porPorcentaje', NumberType::class, array('required' => true))
            ->add('porPorcentajeTiempoExtra', NumberType::class, array('required' => true))
            ->add('prestacional', CheckboxType::class, array('required' => false))
            ->add('generaIngresoBasePrestacion', CheckboxType::class, array('required' => false))
            ->add('generaIngresoBaseCotizacion', CheckboxType::class, array('required' => false))            
            ->add('operacion', ChoiceType::class, array('choices' => array('SUMA' => '1', 'RESTA' => '-1', 'NEUTRO' => '0')))                                
            ->add('conceptoAdicion', CheckboxType::class, array('required' => false))
             ->add('conceptoIncapacidad', CheckboxType::class, array('required' => false))                
            ->add('conceptoAuxilioTransporte', CheckboxType::class, array('required'  => false))            
            ->add('conceptoPension', CheckboxType::class, array('required'  => false))
            ->add('conceptoSalud', CheckboxType::class, array('required'  => false))
            ->add('provisionIndemnizacion', CheckboxType::class, array('required'  => false))
            ->add('provisionVacacion', CheckboxType::class, array('required'  => false))
            ->add('recargoNocturno', CheckboxType::class, array('required'  => false))
            ->add('horaExtra', CheckboxType::class, array('required'  => false))
            ->add('conceptoFondoSolidaridadPensional', CheckboxType::class, array('required'  => false))
            ->add('conceptoCesantia', CheckboxType::class, array('required'  => false))
            ->add('conceptoRetencion', CheckboxType::class, array('required'  => false))
            ->add('tipoAdicional', ChoiceType::class, array('choices' => array('BONIFICACION' => '1', 'DESCUENTO' => '2', 'NO APLICA' => '0')))                                
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
