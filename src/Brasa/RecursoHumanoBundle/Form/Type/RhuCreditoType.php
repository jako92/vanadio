<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuCreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('creditoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCreditoTipo',
                'choice_label' => 'nombre',
                'required' => true,
            ))
             ->add('creditoTipoPagoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCreditoTipoPago',
                'choice_label' => 'nombre',
                'required' => true,
            ))
            ->add('vrPagar', NumberType::class, array('required' => true))                                                                           
            ->add('numeroCuotas', NumberType::class, array('required' => true))
            ->add('vrCuota', NumberType::class, array('required' => true))                                                                                           
            ->add('vrCuotaPrima', NumberType::class, array('required' => true))                                                                                           
            ->add('fechaInicio', DateType::class, array('required' => true))    
            ->add('fechaCredito', DateType::class, array('required' => true))    
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('seguro', NumberType::class, array('required' => true))
            ->add('numeroLibranza', TextType::class, array('required' => false))
            ->add('validarCuotas', CheckboxType::class, array('required'  => false))
            ->add('aplicarCuotaPrima', CheckboxType::class, array('required'  => false))
            ->add('numeroCuotaActual', NumberType::class, array('required' => false))    
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

