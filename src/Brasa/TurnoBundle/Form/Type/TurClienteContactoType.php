<?php

namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class TurClienteContactoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nombre', TextType::class, array('label' => 'Nombre:'))
                ->add('cargo', TextType::class, array('label' => 'Cargo:'))
                ->add('celular', TextType::class, array('label' => 'Celular:'))
                ->add('telefono', TextType::class, array('label' => 'Telefono:'))
                ->add('fechaNacimiento', BirthdayType::class, array('label' => 'Fecha cumpleaños:'))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label' => 'Guardar y Nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\TurnoBundle\Entity\TurClienteContacto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'brasa_turnobundle_turclientecontacto';
    }

}
