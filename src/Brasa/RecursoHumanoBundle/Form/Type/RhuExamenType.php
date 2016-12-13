<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('examenClaseRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenClase',
                'choice_label' => 'nombre',
            ))
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'choice_label' => 'nombre',
            ))    
            ->add('entidadExamenRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'choice_label' => 'nombre',
            ))                          
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'choice_label' => 'nombre',
            ))           
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('codigoSexoFk', ChoiceType::class, array('action'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('identificacion', NumberType::class, array('required' => true))
            ->add('nombreCorto', TextType::class, array('required' => true))
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('controlPago', ChoiceType::class, array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

