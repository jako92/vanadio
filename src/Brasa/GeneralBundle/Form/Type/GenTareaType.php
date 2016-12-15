<?php
namespace Brasa\GeneralBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GenTareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder    
            ->add('usuarioTareaFk', EntityType::class, array(
                'class' => 'BrasaSeguridadBundle:User',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))                 
            ->add('asunto', TextType::class, array('required' => true))               
            ->add('fechaProgramada', DateType::class, array('format' => 'yyyyMMdd'))                
            ->add('hora', TimeType::class)
            ->add('comentarios', TextareaType::class, array('required' => false))                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

