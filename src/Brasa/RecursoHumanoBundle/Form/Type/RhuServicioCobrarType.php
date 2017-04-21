<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RhuServicioCobrarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                          
            ->add('vrSalario', NumberType::class, array('required' => false))            
            ->add('vrPrestacional', NumberType::class, array('required' => false))
            ->add('vrNoPrestacional', NumberType::class, array('required' => false))
            ->add('vrAuxilioTransporte', NumberType::class, array('required' => false))
            ->add('vrPension', NumberType::class, array('required' => false))
            ->add('vrSalud', NumberType::class, array('required' => false))
            ->add('vrRiesgos', NumberType::class, array('required' => false))
            ->add('porcentajeRiesgos', NumberType::class, array('required' => false))
            ->add('vrCaja', NumberType::class, array('required' => false))
            ->add('vrSena', NumberType::class, array('required' => false))
            ->add('vrIcbf', NumberType::class, array('required' => false))
            ->add('vrPrestaciones', NumberType::class, array('required' => false))
            ->add('vrVacaciones', NumberType::class, array('required' => false))
            ->add('vrAporteParafiscales', NumberType::class, array('required' => false))
            ->add('vrAdministracion', NumberType::class, array('required' => false))
            ->add('porcentajeAdministracion', NumberType::class, array('required' => false))
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

