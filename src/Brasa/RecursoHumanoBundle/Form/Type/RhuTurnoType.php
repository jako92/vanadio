<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuTurnoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTurnoPk', TextType::class, array('required' => true))
            ->add('nombre', TextType::class, array('required' => true))
            ->add('horaDesde', TimeType::class, array('required' => true))
            ->add('horaHasta', TimeType::class, array('required' => true))
            ->add('horas', TextType::class, array('required' => true))    
            ->add('horasDiurnas', NumberType::class, array('required' => true))        
            ->add('horasNocturnas', NumberType::class, array('required' => true))        
            ->add('horasPausa', NumberType::class, array('required' => true))        
            ->add('descanso', CheckboxType::class, array('required'  => false))                
            ->add('novedad', CheckboxType::class, array('required'  => false))                
            ->add('incapacidad', CheckboxType::class, array('required'  => false))                
            ->add('licencia', CheckboxType::class, array('required'  => false))                
            ->add('vacacion', CheckboxType::class, array('required'  => false))                
            ->add('salidaDiaSiguiente', CheckboxType::class, array('required'  => false))
            ->add('comentarios', TextareaType::class, array('required' => false))        
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
