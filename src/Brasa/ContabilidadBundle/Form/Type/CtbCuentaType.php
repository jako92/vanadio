<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CtbCuentaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPadreFk', TextType::class, array('required' => true))    
            ->add('codigoCuentaPk', TextType::class, array('required' => true))    
            ->add('nombreCuenta', TextType::class, array('required' => true))    
            ->add('permiteMovimientos', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))
            ->add('exigeNit', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))
            ->add('exigeCentroCostos', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))                            
            ->add('porcentajeRetencion', NumberType::class, array('required' => true))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
