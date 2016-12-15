<?php
namespace Brasa\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenAsesorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion', TextType::class, array('required' => true))
            ->add('nombre', TextType::class, array('required' => true))
            ->add('direccion', TextType::class, array('required' => false))    
            ->add('telefono', TextType::class, array('required' => false))    
            ->add('celular', TextType::class, array('required' => false))
            ->add('email', TextType::class, array('required' => false))    
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
