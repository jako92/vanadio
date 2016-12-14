<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RhuAspiranteType extends AbstractType
{
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
            ->add('estadoCivilRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'choice_label' => 'nombre',
                'required' => true
            ))
            ->add('rhRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'choice_label' => 'tipo',
                'required' => true
            ))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('ciudadExpedicionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('ciudadNacimientoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('zonaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                            
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('nombre1', TextType::class, array('required' => true))
            ->add('nombre2', TextType::class, array('required' => false))
            ->add('apellido1', TextType::class, array('required' => true))
            ->add('apellido2', TextType::class, array('required' => false))
            ->add('correo', TextType::class, array('required' => false))
            ->add('telefono', TextType::class, array('required' => false))
            ->add('codigoTipoLibreta', ChoiceType::class, array('choices' => array('1° CLASE' => '1', '2° CLASE' => '2', 'NO APLICA' => '0')))                
            ->add('celular', TextType::class, array('required' => false))
            ->add('direccion', TextType::class, array('required' => false))
            ->add('barrio', TextType::class, array('required' => false)) 
            ->add('peso', TextType::class, array('required' => false))                
            ->add('estatura', TextType::class, array('required' => false))                
            ->add('numeroIdentificacion', TextType::class, array('required' => true))
            ->add('codigoSexoFk', ChoiceType::class, array('choices'   => array('MASCULINO' => 'M', 'FEMENINO' => 'F')))
            ->add('codigoDisponibilidadFk', ChoiceType::class, array('choices'   => array('TIEMPO COMPLETO' => '1', 'MEDIO TIEMPO' => '2', 'POR HORAS' => '3','DESDE CASA' => '4', 'PRACTICAS' => '5')))                            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('cargoAspira', TextType::class, array('required' => false))                
            ->add('recomendado', TextType::class, array('required' => false))                
            ->add('operacion', TextType::class, array('required' => false))
            ->add('reintegro', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '0')))                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

