<?php

namespace Brasa\SeguridadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserEditarType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('rolRel', EntityType::class, array(
                    'class' => 'BrasaSeguridadBundle:SegRoles',
                    'choice_label' => 'nombre',
                ))
                ->add('grupoRel', EntityType::class, array(
                'class' => 'BrasaSeguridadBundle:SegGrupo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('g')
                    ->orderBy('g.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'placeholder' => '',
                'required'=>false))
                ->add('usuarioActividadRel', EntityType::class, array(
                'class' => 'BrasaSeguridadBundle:SegUsuarioActividad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ua')
                    ->orderBy('ua.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'placeholder' => '',
                'required'=>false))
                ->add('nombreCorto', TextType::class, array('required' => true))
                ->add('username', TextType::class, array('required' => true))
                ->add('email', TextType::class, array('required' => true))
                ->add('cargo', TextType::class, array('required' => true))
                ->add('isActive', CheckboxType::class, array('required' => false, 'label' => 'activo'))
                ->add('fecha', DateType::class, array('placeholder' => '', 'required' => false))
                ->add('cambiarClave', CheckboxType::class, array('required' => false, 'label' => 'Cambiar clave proximo inicio de session'))
                ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix() {
        return 'form';
    }

}
