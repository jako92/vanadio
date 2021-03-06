<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDotacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder                                            
            ->add('dotacionTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDotacionTipo',
                        'choice_label' => 'nombre',
            ))
            ->add('fechaEntrega', DateType::class)                
            ->add('codigoInternoReferencia', NumberType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))              
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

