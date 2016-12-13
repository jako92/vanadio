<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuLiquidacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrCesantias', NumberType::class, array('required' => true))
            ->add('vrInteresesCesantias', NumberType::class, array('required' => true))
            ->add('vrPrima', NumberType::class, array('required' => true))
            ->add('vrSalarioVacaciones', NumberType::class, array('required' => true))
            ->add('vrVacaciones', NumberType::class, array('required' => true))
            ->add('diasCesantias', NumberType::class, array('required' => true))
            ->add('diasVacaciones', NumberType::class, array('required' => true))
            ->add('diasPrimas', NumberType::class, array('required' => true))                
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

