<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDotacionCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                        'choice_label' => 'nombre',
            ))                 
            ->add('dotacionElementoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDotacionElemento',
                'choice_label' => 'dotacion',
            ))
            ->add('cantidadAsignada', NumberType::classs, array('required' => true))    
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

