<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuEmpleadoInformacionInternaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('accion', ChoiceType::class, array('choices' => array('BLOQUEADO' => '1', 'DESBLOQUEADO' => '0')))    
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

