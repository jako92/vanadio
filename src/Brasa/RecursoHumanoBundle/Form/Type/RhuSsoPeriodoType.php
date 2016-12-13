<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuSsoPeriodoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anio', TextType::class, array('required' => true))
            ->add('mes', TextType::class, array('required' => true))
            ->add('anioPago', TextType::class, array('required' => true))
            ->add('mesPago', TextType::class, array('required' => true))    
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now'))) 
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaPago', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))    
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
