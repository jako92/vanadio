<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDotacionElementoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dotacion', TextType::class, array('required' => true))
            ->add('dotacionElementoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDotacionElementoTipo',
                'choice_label' => 'nombre',
            ))    
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'))
            ->add('guardarynuevo', SubmitType::class, array('label' => 'Guardar y nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
