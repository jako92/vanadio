<?php

namespace Brasa\InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class InvOrdenCompraDetalleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('itemRel', EntityType::class,
                array('class' => 'BrasaInventarioBundle:InvItem',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true
                ))  ->add('codigoItemFk')
                ->add('codigoOrdenCompraFk')
                ->add('cantidad', NumberType::class)
                ->add('valor', NumberType::class)
                ->add('ordenCompraRel');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\InventarioBundle\Entity\InvOrdenCompraDetalle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'brasa_inventariobundle_invordencompradetalle';
    }


}
