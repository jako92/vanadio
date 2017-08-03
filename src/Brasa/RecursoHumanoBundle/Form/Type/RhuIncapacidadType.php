<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuIncapacidadType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('incapacidadTipoRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuIncapacidadTipo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('it')
                                ->orderBy('it.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => true))
                ->add('numeroEps', TextType::class, array('required' => true))
                ->add('fechaDesde', DateType::class)
                ->add('fechaHasta', DateType::class)
                ->add('estadoTranscripcion', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
                ->add('estadoCobrar', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
                ->add('estadoCobrarCliente', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
                ->add('estadoProrroga', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
                ->add('comentarios', TextareaType::class, array('required' => false))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label' => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix() {
        return 'form';
    }

}
