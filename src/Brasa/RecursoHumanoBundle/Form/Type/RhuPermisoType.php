<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RhuPermisoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('permisoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPermisoTipo',
                'choice_label' => 'nombre',
                'required' => true))
            ->add('fechaPermiso', DateType::class, array('format' => 'yyyyMMdd'))    
            ->add('horaSalida', TimeType::class, array('required' => true))
            ->add('horaLlegada', TimeType::class, array('required' => true))                
            ->add('motivo', TextareaType::class, array('required' => true))
            ->add('jefeAutoriza', TextType::class, array('required' => true))
            ->add('observaciones', TextareaType::class, array('required' => false))
            ->add('afectaHorario', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

