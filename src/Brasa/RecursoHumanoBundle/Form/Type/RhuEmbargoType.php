<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuEmbargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder   
            ->add('embargoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmbargoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('numero', TextType::class, array('required' => false))   
            ->add('valor', NumberType::class, array('required' => true))   
            ->add('porcentaje', NumberType::class, array('required' => false))                               
            ->add('valorFijo', CheckboxType::class, array('required'  => false))                
            ->add('porcentajeDevengado', CheckboxType::class, array('required'  => false))                
            ->add('porcentajeDevengadoPrestacional', CheckboxType::class, array('required'  => false))                
            ->add('porcentajeDevengadoMenosDescuentoLey', CheckboxType::class, array('required'  => false))                
            ->add('porcentajeExcedaSalarioMinimo', CheckboxType::class, array('required'  => false))                                            
            ->add('estadoActivo', CheckboxType::class, array('required'  => false))                
            ->add('partesExcedaSalarioMinimo', CheckboxType::class, array('required'  => false))                                            
            ->add('partes', NumberType::class, array('required' => false))                                                           
            ->add('comentarios', TextareaType::class, array('required' => false))                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

