<?php
namespace Brasa\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GenCuentaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bancoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenBanco',
                        'choice_label' => 'nombre',))                
            ->add('nombre', TextType::class, array('required' => true))
            ->add('cuenta', TextType::class, array('required' => true))
            ->add('tipo', TextType::class, array('required' => true))
            ->add('codigoCuentaFk', TextType::class, array('required' => true))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
