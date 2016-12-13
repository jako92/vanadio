<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuEmpleadoInformacionInternaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacion', TextType::class, array('required' => true))
            ->add('informacionInternaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo',
                'choice_label' => 'nombre',
            ))
            ->add('fecha', DateType::class, array('data' => new \DateTime('now'), 'required' => true))    
            ->add('comentarios', TextareaType::class, array('required' => false))    
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

