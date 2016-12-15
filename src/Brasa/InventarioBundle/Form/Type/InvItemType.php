<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('marcaRel', EntityType::class, array(
                'class' => 'BrasaInventarioBundle:InvMarca',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                    ->orderBy('m.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('nombre', TextType::class, array('required' => true))            
            ->add('porcentajeIva', NumberType::class, array('required' => false))
            ->add('vrCostoPredeterminado', NumberType::class, array('required' => false))                                                                        
            ->add('guardar', SubmitType::class)            
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

