<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RhuRequisitoCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                        'property' => 'nombre',
            ))                 
            ->add('requisitoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRequisitoConcepto',
                'choice_label' => 'nombre',
            ))                 
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

