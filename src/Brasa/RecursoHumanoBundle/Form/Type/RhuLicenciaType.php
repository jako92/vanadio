<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuLicenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder                  
            ->add('licenciaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuLicenciaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('lt')
                    ->orderBy('lt.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                            
            ->add('fechaDesde', DateType::class)                
            ->add('fechaHasta', DateType::class)  
            ->add('afectaTransporte', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))                                            
            ->add('comentarios', TextareaType::class, array('required' => false))                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

