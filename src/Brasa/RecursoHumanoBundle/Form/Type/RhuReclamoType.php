<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RhuReclamoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  
            ->add('reclamoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuReclamoConcepto',
                        'choice_label' => 'nombre',
            ))                
            ->add('reclamo', TextareaType::class, array('required' => false))            
            ->add('comentarios', TextareaType::class, array('required' => false))            
            ->add('fecha', DateType::class, array('format' => 'yyyyMMdd'))   
            ->add('responsable', TextType::class, array('required' => false))                
            ->add('puesto', TextType::class, array('required' => false))                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

