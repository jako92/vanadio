<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvMovimientoFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('facturaTipoRel', EntityType::class, array(
                'class' => 'BrasaInventarioBundle:InvFacturaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                    ->orderBy('m.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('soporte', TextType::class, array('required' => false))                                
            ->add('comentarios', TextareaType::class, array('required' => false))                                
            ->add('guardar', SubmitType::class)            
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

