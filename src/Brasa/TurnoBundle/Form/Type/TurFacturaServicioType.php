<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurFacturaServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                      
            ->add('nombre', TextType::class, array('required' => true))             
            ->add('porcentajeIva', NumberType::class)                
            ->add('porBaseRetencionFuente', NumberType::class)                
            ->add('porRetencionFuente', NumberType::class)                
            ->add('codigoCuentaIngresoFk', TextType::class, array('required' => true))                 
            ->add('codigoCuentaCarteraFk', TextType::class, array('required' => true))                 
            ->add('codigoCuentaIvaFk', TextType::class, array('required' => true))                 
            ->add('codigoCuentaRetencionFuenteFk', TextType::class, array('required' => true))
            ->add('codigoCuentaRetencionIvaFk', TextType::class, array('required' => true))
            ->add('codigoCuentaIngresoDevolucionFk', TextType::class, array('required' => true))                 
            ->add('codigoCuentaIvaDevolucionFk', TextType::class, array('required' => true))                                 
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

