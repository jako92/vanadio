<?php

namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TurPedidoTipoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nombre', TextType::class, array('required' => true))
                ->add('tipo', TextType::class, array('required' => false))
                ->add('control', CheckboxType::class, array('required' => false))
                ->add('codigoComprobanteFk', TextType::class, array('required' => false))
                ->add('tipoCuentaCartera', TextType::class, array('required' => false))
                ->add('tipoCuentaIva', TextType::class, array('required' => false))
                ->add('tipoCuentaIngreso', TextType::class, array('required' => false))
                ->add('codigoCuentaCarteraFk', TextType::class, array('required' => false))
                ->add('codigoCuentaIvaFk', TextType::class, array('required' => false))
                ->add('codigoCuentaIngresoFk', TextType::class, array('required' => false))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\TurnoBundle\Entity\TurPedidoTipo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'brasa_turnobundle_turpedidotipo';
    }

}
