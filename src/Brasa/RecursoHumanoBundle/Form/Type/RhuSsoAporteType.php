<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuSsoAporteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingreso', TextType::class, array('required' => false))    
            ->add('retiro', TextType::class, array('required' => false))    
            ->add('ibcPension', NumberType::class, array('required' => false))    
            ->add('ibcSalud', NumberType::class, array('required' => false))    
            ->add('ibcRiesgosProfesionales', NumberType::class, array('required' => false))                    
            ->add('ibcCaja', NumberType::class, array('required' => false))                    
            ->add('diasCotizadosPension', NumberType::class, array('required' => false))    
            ->add('diasCotizadosSalud', NumberType::class, array('required' => false))    
            ->add('diasCotizadosRiesgosProfesionales', NumberType::class, array('required' => false))                    
            ->add('diasCotizadosCajaCompensacion', NumberType::class, array('required' => false))                    
            ->add('cotizacionPension', NumberType::class, array('required' => false))    
            ->add('cotizacionSalud', NumberType::class, array('required' => false))    
            ->add('cotizacionRiesgos', NumberType::class, array('required' => false))                    
            ->add('cotizacionCaja', NumberType::class, array('required' => false))                                    
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
