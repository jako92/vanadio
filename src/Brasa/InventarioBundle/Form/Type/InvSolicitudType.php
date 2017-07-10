<?php

namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InvSolicitudType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('solicitudDocumentoRel', EntityType::class, array(
                    'class' => 'BrasaInventarioBundle:InvSolicitudDocumento',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('sd')
                                ->orderBy('sd.codigoSolicitudDocumentoPk', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => false))
                ->add('fechaEntrega', DateType::class)
                ->add('soporte', TextType::class, array('required' => false))
                ->add('comentarios', TextareaType::class, array('required' => false))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label' => 'Guardar y Nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\InventarioBundle\Entity\InvSolicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'brasa_inventariobundle_invsolicitud';
    }

}
