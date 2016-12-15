<?php
namespace Brasa\GeneralBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class GenContenidoFormatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('titulo', TextType::class, array('required' => true))
            ->add('codigoFormatoIso', TextType::class, array('required' => false))
            ->add('requiereFormatoIso', ChoiceType::class, array('choices'   => array('NO' => '0', 'SI' => '1')))    
            ->add('version', TextType::class, array('required' => false))
            ->add('fechaVersion',DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('contenido', TextareaType::class, array('required' => true))                                
            ->add('guardar', SubmitType::class);        
    }
 
    public function getName()
    {
        return 'form';
    }
}
