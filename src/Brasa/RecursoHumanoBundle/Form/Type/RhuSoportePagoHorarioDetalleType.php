<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuSoportePagoHorarioDetalleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                
            ->add('dias', NumberType::class, array('required' => true))
            ->add('horas', NumberType::class, array('required' => true))
            ->add('horasDescanso', NumberType::class, array('required' => true))
            ->add('horasDiurnas', NumberType::class, array('required' => true))
            ->add('horasNocturnas', NumberType::class, array('required' => true))
            ->add('horasFestivasDiurnas', NumberType::class, array('required' => true))
            ->add('horasFestivasNocturnas', NumberType::class, array('required' => true))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
