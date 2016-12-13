<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuHorarioAccesoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder             
            ->add('fechaEntrada', DateTimeType::class, array('format' => 'yyyyMMdd'))                            
            ->add('fechaSalida', DateTimeType::class, array('format' => 'yyyyMMdd'))                            
            ->add('estadoEntrada', CheckboxType::class, array('required'  => false))                                              
            ->add('entradaTarde', CheckboxType::class, array('required'  => false))                                              
            ->add('estadoSalida', CheckboxType::class, array('required'  => false))                                        
            ->add('salidaAntes', CheckboxType::class, array('required'  => false))                                        
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

