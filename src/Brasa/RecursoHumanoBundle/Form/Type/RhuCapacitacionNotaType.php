<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RhuCapacitacionNotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
            ->add('nota', TextareaType::class, array('required' => false))             
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

