<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuSedeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'choice_label' => 'nombre',
            ))
            ->add('nombre', TextType::class, array('required' => true))                                            
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

