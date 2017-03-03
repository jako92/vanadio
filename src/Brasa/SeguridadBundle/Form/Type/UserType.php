<?php
namespace Brasa\SeguridadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rolRel', EntityType::class, array(
                'class' => 'BrasaSeguridadBundle:SegRoles',
                        'choice_label' => 'nombre',
            ))  
            ->add('nombreCorto', TextType::class, array('required' => true))
            ->add('username', TextType::class, array('required' => true))                
            ->add('email', TextType::class, array('required' => true))                
            ->add('password', PasswordType::class, array('required' => true))
            ->add('cargo', TextType::class, array('required' => true)) 
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
