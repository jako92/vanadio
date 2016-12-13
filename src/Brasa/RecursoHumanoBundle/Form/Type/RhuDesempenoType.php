<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDesempenoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('dependenciaEvaluado', TextType::class, array('required' => true))
            ->add('dependenciaEvalua', TextType::class, array('required' => true))
            ->add('jefeEvalua', TextType::class, array('required' => true))
            ->add('cargoJefeEvalua', TextType::class, array('required' => true))
            ->add('fecha', DateType::class, array('required' => true))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

