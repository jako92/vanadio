<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RhuPruebaEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pruebaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPruebaTipo',
                'choice_label' => 'nombre',
            ))
            ->add('fecha', DateType::class)
            ->add('resultado', TextType::class, array('required' => false))
            ->add('resultadoCuantitativo', NumberType::class, array('required' => false))
            ->add('nombreQuienHacePrueba', TextType::class,array('required' => true))
            ->add('vrTotal', NumberType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => true, 'attr' => array('cols' => '5', 'rows' => '10')))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

