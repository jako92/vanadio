<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuSeleccionPruebaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seleccionPruebaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionPruebaTipo',
                'choice_label' => 'nombre',
            ))    
            ->add('resultado', TextType::class, array('required' => false))
            ->add('resultadoCuantitativo', NumberType::class, array('required' => false))
            ->add('nombreQuienHacePrueba', TextType::class, array('required' => false))    
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

