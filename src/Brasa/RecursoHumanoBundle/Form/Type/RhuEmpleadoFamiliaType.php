<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuEmpleadoFamiliaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleadoFamiliaParentescoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoFamiliaParentesco',
                'choice_label' => 'nombre',
            ))               
            ->add('nombres', TextType::class, array('required' => true))
            ->add('entidadSaludRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'choice_label' => 'nombre',
                'required' => false,
            ))    
            ->add('entidadCajaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'choice_label' => 'nombre',
                'required' => false,
            ))   
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false, 'attr' => array('class' => 'date',)))                
            ->add('ocupacion', TextType::class, array('required' => true))
            ->add('telefono', TextType::class, array('required' => false))
            ->add('codigoSexoFk', ChoiceType::class, array('choices'   => array('MASCULINO' => 'M', 'FEMENINO' => 'F')))
            ->add('guardar', SubmitType::class)  
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

