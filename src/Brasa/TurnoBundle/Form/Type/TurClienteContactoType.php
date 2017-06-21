<?php

namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurClienteContactoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('gerente', TextType::class, array('required' => false))
                ->add('celularGerente', TextType::class, array('required' => false))
                ->add('financiero', TextType::class, array('required' => false))
                ->add('celularFinanciero', TextType::class, array('required' => false))
                ->add('contacto', TextType::class, array('required' => false))
                ->add('celularContacto', TextType::class, array('required' => false))
                ->add('telefonoContacto', TextType::class, array('required' => false))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
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
