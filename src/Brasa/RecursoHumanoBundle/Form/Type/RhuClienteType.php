<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RhuClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                
            ->add('formaPagoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('fp')
                    ->orderBy('fp.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                              
            ->add('asesorRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('a')
                    ->orderBy('a.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                            
            ->add('nit', NumberType::class, array('required' => true))
            ->add('digitoVerificacion', TextType::class, array('required' => false))  
            ->add('nombreCorto', TextType::class, array('required' => true))              
            ->add('plazoPago', NumberType::class, array('required' => false)) 
            ->add('direccion', TextType::class, array('required' => false))  
            ->add('barrio', TextType::class, array('required' => false))  
            ->add('telefono', TextType::class, array('required' => false))                              
            ->add('celular', TextType::class, array('required' => false))                                          
            ->add('email', TextType::class, array('required' => false))                              
            ->add('gerente', TextType::class, array('required' => false))                  
            ->add('celularGerente', TextType::class, array('required' => false))                  
            ->add('financiero', TextType::class, array('required' => false))                  
            ->add('celularFinanciero', TextType::class, array('required' => false))                                  
            ->add('contacto', TextType::class, array('required' => false))                  
            ->add('celularContacto', TextType::class, array('required' => false))  
            ->add('telefonoContacto', TextType::class, array('required' => false))  
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('cobroExamen', ChoiceType::class, array('choices' => array('CLIENTE' => 'C', 'EMPLEADO' => 'E', 'NO COBRAR' => 'N')))                                            
            ->add('autoretenedor', CheckboxType::class, array('required'  => false))
            ->add('regimenSimplificado', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

