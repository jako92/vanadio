<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuPagoAdicionalPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder               
            ->add('fecha', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('guardar', SubmitType::class);            
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

