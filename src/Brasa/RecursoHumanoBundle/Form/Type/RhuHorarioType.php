<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuHorarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('horaEntrada', TimeType::class, array('required' => true))
            ->add('horaSalida', TimeType::class, array('required' => true))
            ->add('generaHoraExtra', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))     
            ->add('controlHorario', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))     
            ->add('lunes', TextType::class, array('required' => true))    
            ->add('martes', TextType::class, array('required' => true))        
            ->add('miercoles', TextType::class, array('required' => true))        
            ->add('jueves', TextType::class, array('required' => true))        
            ->add('viernes', TextType::class, array('required' => true))        
            ->add('sabado', TextType::class, array('required' => true))        
            ->add('domingo', TextType::class, array('required' => true))        
            ->add('festivo', TextType::class, array('required' => true))        
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
