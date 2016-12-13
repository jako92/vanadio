<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RhuSeleccionEntrevistaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                
            ->add('seleccionEntrevistaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionEntrevistaTipo',
                'choice_label' => 'nombre',
                'required' => true,
            ))    
            ->add('resultado', TextType::class, array('required' => false))
            ->add('resultadoCuantitativo', NumberType::class, array('required' => false))
            ->add('fecha', DateTimeType::class, array('required' => true, 'data' => new \DateTime('now')))
            ->add('nombreQuienEntrevista', TextType::class, array('required' => false))    
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

