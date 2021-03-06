<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuBancoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('nit', TextType::class, array('required' => true))    
            ->add('convenioNomina', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
            ->add('numeroDigitos', NumberType::class, array('required' => true))
            ->add('codigoGeneral', TextType::class, array('required' => true))
            ->add('telefono', TextType::class, array('required' => false))
            ->add('direccion', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
