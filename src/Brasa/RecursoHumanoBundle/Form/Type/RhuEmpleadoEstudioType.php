<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuEmpleadoEstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleadoEstudioTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'choice_label' => 'nombre',
                'required' => true
            ))
            ->add('institucion', TextType::class, array('required' => false))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('gradoBachillerRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuGradoBachiller',
                'query_builder' => function (EntityRepository $er)  {
                return $er->createQueryBuilder('c');},
                'choice_label' => 'grado',
                'required' => false))
            ->add('fechaInicio', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false,'attr' => array('class' => 'date',)))
            ->add('fechaTerminacion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false,'attr' => array('class' => 'date',)))
            ->add('validarVencimiento', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
            ->add('fechaVencimientoCurso', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false,'attr' => array('class' => 'date',)))
            ->add('graduado', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))                
            ->add('titulo', TextType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('numeroRegistro', TextType::class, array('required' => false))                    
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

