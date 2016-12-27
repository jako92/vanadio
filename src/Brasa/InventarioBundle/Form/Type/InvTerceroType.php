<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class InvTerceroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('formaPagoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('fp')
                    ->orderBy('fp.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))   
             ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))   
            ->add('nit', NumberType::class, array('required' => true))
            ->add('digitoVerificacion', NumberType::class, array('required' => true))    
            ->add('nombreCorto', TextType::class, array('required' => true))    
            ->add('nombres', TextType::class, array('required' => false))
            ->add('apellido1', TextType::class, array('required' => false))    
            ->add('apellido2', TextType::class, array('required' => false))                
            ->add('direccion', TextType::class, array('required' => false))    
            ->add('telefono', TextType::class, array('required' => false))    
            ->add('celular', TextType::class, array('required' => false))    
            ->add('fax', TextType::class, array('required' => false))        
            ->add('email', TextType::class, array('required' => false))            
            ->add('guardar', SubmitType::class)            
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
