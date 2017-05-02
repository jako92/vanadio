<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RhuCobroType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('cobroTipoRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuCobroTipo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('ct')
                                ->orderBy('ct.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => true))
                ->add('centroCostoRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('cc')
                                ->orderBy('cc.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => true))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('comentarios', TextareaType::class, array('required' => false))
                ->add('guardar', SubmitType::class)
                ->add('guardarnuevo', SubmitType::class, array('label' => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix() {
        return 'form';
    }

}
