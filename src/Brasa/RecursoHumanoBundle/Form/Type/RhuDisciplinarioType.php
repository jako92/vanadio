<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDisciplinarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disciplinarioTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDisciplinarioTipo',
                'choice_label' => 'nombre',
            ))               
            ->add('disciplinarioMotivoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDisciplinarioMotivo',
                'choice_label' => 'nombre',
                'required' => false,
            ))                 
            ->add('asunto', TextareaType::class, array('required' => false))            
            ->add('diasSuspencion', TextType::class, array('required' => false)) 
            ->add('reentrenamiento', ChoiceType::class, array('choices'   => array('0' => 'NO', '1' => 'SI'))) 
            ->add('puesto', TextType::class, array('required' => false))
            ->add('fechaNotificacion', DateType::class, array('format' => 'yyyyMMdd')) 
            ->add('fechaIncidente', DateType::class, array('format' => 'yyyyMMdd'))    
            ->add('fechaDesdeSancion', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaHastaSancion', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaIngresoTrabajo', DateType::class, array('format' => 'yyyyMMdd'))    
            ->add('estadoSuspension', CheckboxType::class, array('required'  => false))
            ->add('estadoProcede', CheckboxType::class, array('required'  => false))
            ->add('comentarios', TextareaType::class, array('required' => false))            
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

