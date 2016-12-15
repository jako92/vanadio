<?php
namespace Brasa\SeguridadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SegPermisoDocumentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingreso', CheckboxType::class, array('required'  => false))            
            ->add('nuevo', CheckboxType::class, array('required'  => false))            
            ->add('editar', CheckboxType::class, array('required'  => false))                            
            ->add('eliminar', CheckboxType::class, array('required'  => false))                                            
            ->add('autorizar', CheckboxType::class, array('required'  => false))                                            
            ->add('desautorizar', CheckboxType::class, array('required'  => false))                                            
            ->add('aprobar', CheckboxType::class, array('required'  => false))
            ->add('anular', CheckboxType::class, array('required'  => false))
            ->add('imprimir', CheckboxType::class, array('required'  => false))    
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
