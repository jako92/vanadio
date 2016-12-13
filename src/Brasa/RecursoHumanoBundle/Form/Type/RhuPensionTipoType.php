<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuPensionTipoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('porcentajeEmpleado', TextType::class, array('required' => true))
            ->add('porcentajeEmpleador', TextType::class, array('required' => true))
            ->add('pagoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'choice_label' => 'nombre',
            ))    
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
