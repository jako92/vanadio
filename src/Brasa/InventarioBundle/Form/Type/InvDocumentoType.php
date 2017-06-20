<?php

namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class InvDocumentoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('documentoTipoRel', EntityType::class, array(
                'class' => 'BrasaInventarioBundle:InvDocumentoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                    ->orderBy('d.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))    
                ->add('nombre', TextType::class)
                ->add('abreviatura', TextType::class)
                ->add('operacionInventario', ChoiceType::class, array('choices' => array ('SUMA' => '1', 'RESTA' => '-1' )))
                ->add('operacionComercial', ChoiceType::class, array('choices' => array ('NO APLICA' => '0', 'SUMA' => '1')))
                ->add('generaCartera', CheckboxType::class, array('required' => false))
                ->add('asignarConsecutivoCreacion', CheckboxType::class, array('required' => false))
                ->add('asignarConsecutivoImpresion', CheckboxType::class, array('required' => false))
                ->add('guardar', SubmitType::class)            
                ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\InventarioBundle\Entity\InvDocumento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'brasa_inventariobundle_invdocumento';
    }


}
