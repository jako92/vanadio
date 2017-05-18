<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuFacturaDetalleNuevoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder                            
            ->add('facturaConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuFacturaConcepto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('fc')                    
                    ->orderBy('fc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('cantidad', NumberType::class)                            
            ->add('vrPrecio', NumberType::class)
            ->add('VrOperacion', NumberType::class)
            ->add('VrAdministracion', NumberType::class)
            ->add('detalle', TextareaType::class,array('required'=>false)) 
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

