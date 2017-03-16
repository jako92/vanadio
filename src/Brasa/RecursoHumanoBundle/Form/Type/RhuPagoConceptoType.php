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
            ->add('componeSalario', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('componePorcentaje', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('componeValor', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('porPorcentaje', NumberType::class, array('required' => true))
            ->add('prestacional', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('generaIngresoBasePrestacion', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('generaIngresoBaseCotizacion', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('operacion', NumberType::class, array('required' => true))
            ->add('conceptoAdicion', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('conceptoIncapacidad', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))            
            ->add('conceptoAuxilioTransporte', CheckboxType::class, array('required'  => false))     
            ->add('codigoInterface', TextType::class, array('required' => false))                
            ->add('conceptoPension', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('conceptoSalud', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('recargoNocturno', CheckboxType::class, array('required'  => false))     
            ->add('provisionIndemnizacion', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('provisionVacacion', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))                
            ->add('tipoAdicional', NumberType::class, array('required' => true))
            ->add('conceptoFondoSolidaridadPensional', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))    
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
