<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDesempenoObservacionesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder         
            ->add('observaciones', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}
