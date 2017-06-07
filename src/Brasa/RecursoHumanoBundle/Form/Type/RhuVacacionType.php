<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuVacacionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('diasDisfrutados', NumberType::class, array('required' => true))
                ->add('diasPagados', NumberType::class, array('required' => true))
                ->add('vrSalarioPromedioPropuesto', NumberType::class, array('required' => false))
                ->add('fechaDesdeDisfrute', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHastaDisfrute', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaInicioLabor', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('comentarios', TextareaType::class, array('required' => false))
                ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix() {
        return 'form';
    }

}
