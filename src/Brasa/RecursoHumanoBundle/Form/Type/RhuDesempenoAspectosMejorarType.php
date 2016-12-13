<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDesempenoAspectosMejorarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder         
            ->add('aspectosMejorar', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::classs);
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}
