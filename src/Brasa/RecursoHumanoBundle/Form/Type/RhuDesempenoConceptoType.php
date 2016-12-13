<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RhuDesempenoConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('nombre', TextType::class, array('required' => true))
            ->add('desempenoConceptoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDesempenoConceptoTipo',
                'choice_label' => 'nombre',
            ))    
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

