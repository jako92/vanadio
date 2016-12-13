<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuRegistroVisitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            
            ->add('departamentoEmpresaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'choice_label' => 'nombre',
                'required' => true))   
            ->add('motivo', TextType::class, array('required' => false))        
            ->add('codigoEscarapela', TextType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('buscar', SubmitType::class)    
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

