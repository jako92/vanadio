<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurCierreMesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('anio', NumberType::class, array('required' => true))
            ->add('mes', NumberType::class, array('required' => true))
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

