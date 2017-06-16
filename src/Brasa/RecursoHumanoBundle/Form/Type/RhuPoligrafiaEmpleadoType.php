<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RhuPoligrafiaEmpleadoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('poligrafiaTipoRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuPoligrafiaTipo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('pt')
                                ->orderBy('pt.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => true))
                ->add('fecha', DateTimeType::class)
                ->add('vrTotal', NumberType::class, array('required' => false))
                ->add('comentarios', TextareaType::class, array('required' => false, 'attr' => array('cols' => '5', 'rows' => '10')))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label' => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix() {
        return 'form';
    }

}
