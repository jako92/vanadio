<?php

namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class InvOrdenCompraType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ordenCompraDocumentoRel', EntityType::class, array(
                'class' => 'BrasaInventarioBundle:InvOrdenCompraDocumento',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ocd')
                    ->orderBy('ocd.codigoOrdenCompraDocumentoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false))
                ->add('fechaEntrega', DateType::class)
                ->add('soporte', TextType::class, array('required'=> false))
                ->add('comentarios', TextareaType::class, array('required'=> false))
                ->add('guardar', SubmitType::class)            
                ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\InventarioBundle\Entity\InvOrdenCompra'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'brasa_inventariobundle_invordencompra';
    }


}
