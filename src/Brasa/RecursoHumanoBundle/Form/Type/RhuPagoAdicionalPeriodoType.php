<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RhuPagoAdicionalPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder               
            ->add('fecha', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('nombre', TextType::class, array('required' => true))
            ->add('guardar', SubmitType::class);            
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

