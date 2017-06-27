<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuInduccionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fechaDesde', DateType::class)
                ->add('fechaHasta', DateType::class)
                ->add('comentarios', TextareaType::class,array('required'=>false))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class,array('label'=> 'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\RecursoHumanoBundle\Entity\RhuInduccion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'brasa_recursohumanobundle_rhuinduccion';
    }

}
