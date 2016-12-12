<?php
namespace Brasa\SeguridadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rolRel', 'entity', array(
                'class' => 'BrasaSeguridadBundle:SegRoles',
                        'property' => 'nombre',
            ))  
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('username', 'text', array('required' => true))                
            ->add('email', 'text', array('required' => true))                
            ->add('password', 'password', array('required' => true))            
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
