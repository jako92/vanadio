<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RhuSoportePagoHorarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'choice_label' => 'nombre',
            ))                 
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
