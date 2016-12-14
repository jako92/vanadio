<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CtbTerceroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIdentificacionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('digitoVerificacion', NumberType::class, array('required' => false))
            ->add('numeroIdentificacion', TextType::class, array('required' => true))
            ->add('nombre1', TextType::class, array('required' => false))
            ->add('nombre2', TextType::class, array('required' => false))
            ->add('apellido1', TextType::class, array('required' => false))    
            ->add('apellido2', TextType::class, array('required' => false))
            ->add('razonSocial', TextType::class, array('required' => false))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))   
            ->add('direccion', TextType::class, array('required' => false))    
            ->add('telefono', TextType::class, array('required' => false))    
            ->add('celular', TextType::class, array('required' => false))    
            ->add('fax', TextType::class, array('required' => false))        
            ->add('email', TextType::class, array('required' => false))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
