<?php

namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RhuPoligrafiaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('poligrafiaTipoRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuPoligrafiaTipo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('pt')
                                ->orderBy('pt.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => false))
                ->add('tipoIdentificacionRel', EntityType::class, array(
                    'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('TI')
                                ->orderBy('TI.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => true))
                ->add('clienteRel', EntityType::class, array(
                    'class' => 'BrasaRecursoHumanoBundle:RhuCliente',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('C')
                                ->orderBy('C.nombreCorto', 'ASC');
                    },
                    'choice_label' => 'nombreCorto',
                    'required' => true))
                ->add('numeroIdentificacion', TextType::class, array('required' => true))
                ->add('nombreCorto', TextType::class, array('required' => true))
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
