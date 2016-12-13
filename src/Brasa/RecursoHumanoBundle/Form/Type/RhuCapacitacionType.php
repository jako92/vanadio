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

class RhuCapacitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('capacitacionTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCapacitacionTipo',
                        'choice_label' => 'nombre',
            ))
            ->add('capacitacionMetodologiaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCapacitacionMetodologia',
                        'choice_label' => 'nombre',
            ))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                        'choice_label' => 'nombre',
            ))    
            ->add('fechaCapacitacion', DateTimeType::class, array('format' => 'yyyyMMdd'))
            ->add('tema', TextType::class, array('required' => true))
            ->add('numeroPersonasCapacitar', NumberType::class, array('required' => true))
            ->add('vrCapacitacion', TextType::class, array('required' => false))
            ->add('lugar', TextType::class, array('required' => true))    
            ->add('duracion', TextType::class, array('required' => true))
            ->add('objetivo', TextareaType::class, array('required' => true))
            ->add('contenido', TextareaType::class, array('required' => true))
            ->add('facilitador', TextType::class, array('required' => true))
            ->add('numeroIdentificacionFacilitador', TextType::class, array('required' => true))    
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

