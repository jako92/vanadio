<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TurPuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoContabilidadRel', EntityType::class, array(
                'class' => 'BrasaContabilidadBundle:CtbCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))    
            ->add('programadorRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurProgramador',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('zonaRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurZona',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))  
            ->add('operacionRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurOperacion',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('o')
                    ->orderBy('o.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('estadoCivilRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'choice_label' => 'nombre',
                'required' => false
            ))
            ->add('centroOperacionRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurCentroOperacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('co')
                    ->orderBy('co.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false))
            ->add('nombre', TextType::class, array('required'  => true))
            ->add('direccion', TextType::class, array('required'  => false))
            ->add('telefono', TextType::class, array('required'  => false))
            ->add('celular', TextType::class, array('required'  => false))
            ->add('contacto', TextType::class, array('required'  => false))
            ->add('numeroComunicacion', TextType::class, array('required'  => false))
            ->add('telefonoContacto', TextType::class, array('required'  => false))
            ->add('celularContacto', TextType::class, array('required'  => false))
            ->add('controlPuesto', CheckboxType::class, array('required'  => false))
            ->add('edadMinima', TextType::class, array('required'  => false))
            ->add('edadMaxima', TextType::class, array('required'  => false))
            ->add('estaturaMinima', TextType::class, array('required'  => false))
            ->add('estaturaMaxima', TextType::class, array('required'  => false))
            ->add('codigoSexoFk', ChoiceType::class, array('choices'   => array('INDIFERENTE' => '0', 'MASCULINO' => 'M', 'FEMENINO' => 'F')))                
            ->add('pesoMinimo', TextType::class, array('required'  => false))
            ->add('pesoMaximo', TextType::class, array('required'  => false))
            ->add('horaInicio', TimeType::class, array('required' => true))
            ->add('horaFinal', TimeType::class, array('required' => true))
            ->add('ubicacionGps', TextType::class, array('required'  => false))
            ->add('codigoTipoLibretaMilitar', ChoiceType::class, array('choices' => array('INDIFERENTE' => '0', '1° CLASE' => '1', '2° CLASE' => '2')))                                
            ->add('moto', ChoiceType::class, array('choices'   => array('NO' => '0', 'SI' => '1')))                                
            ->add('carro', ChoiceType::class, array('choices'   => array('NO' => '0', 'SI' => '1'))) 
            ->add('comentarios', TextareaType::class, array('required'  => false))
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

