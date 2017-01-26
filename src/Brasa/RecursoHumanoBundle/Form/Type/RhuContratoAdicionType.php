<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RhuContratoAdicionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateType::class)
            ->add('contenido', TextareaType::class, array('required' => false))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->add('contratoAdicionTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoAdicionTipo',
                'choice_label' => 'nombre',
            ))    ;            
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
