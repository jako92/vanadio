<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuLicenciaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('nombre', TextType::class, array('required' => true))
            ->add('pagoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'choice_label' => 'nombre',
            ))
            ->add('afectaSalud', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))    
            ->add('ausentismo', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))    
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

