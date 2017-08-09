<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RhuSeleccionRequisitoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('estadoCivilRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'choice_label' => 'nombre',
                'required' => false
            ))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false))
            ->add('estudioTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'choice_label' => 'nombre',
            ))
            ->add('experienciaRequisicionRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionRequisicionExperiencia',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ;},
                'choice_label' => 'nombre',
                'required' => true))   
            ->add('zonaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('seleccionRequisitoMotivoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionRequisitoMotivo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('srm')
                    ->orderBy('srm.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                              
            ->add('codigoDisponibilidadFk', ChoiceType::class, array('choices'   => array('TIEMPO COMPLETO' => '1', 'MEDIO TIEMPO' => '2', 'POR HORAS' => '3','DESDE CASA' => '4', 'PRACTICAS' => '5', 'NO APLICA' => '0')))
            //->add('codigoExperienciaFk', 'choice', array('choices'   => array('1 AÑO' => '1', '2 AÑOS' => '2', '3-4 AÑOS' => '3','5-10 AÑOS' => '4', 'GRADUADO' => '5', 'SIN EXPERIENCIA' => '6')))
            ->add('codigoSexoFk', ChoiceType::class, array('choices'   => array('MASCULINO' => 'M', 'FEMENINO' => 'F', 'INDIFERENTE' => 'I')))
            ->add('codigoTipoVehiculoFk', ChoiceType::class, array('choices'   => array('CARRO' => '1', 'MOTO' => '2', 'NO APLICA' => '0')))
            ->add('codigoLicenciaCarroFk', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '2', 'NO APLICA' => '0')))
            ->add('codigoLicenciaMotoFk', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '2', 'NO APLICA' => '0')))
            ->add('nombre', TextType::class, array('required' => true))
            ->add('numeroHijos', NumberType::class, array('required' => false))
            ->add('edadMinima', TextType::class, array('required' => false))
            ->add('edadMaxima', TextType::class, array('required' => false))
            ->add('codigoReligionFk', ChoiceType::class, array('choices'   => array('CATOLICO' => '1', 'CRISTIANO' => '1', 'PROTESTANTE' => '3', 'INDIFERENTE' => '4')))
            ->add('cantidadSolicitada', NumberType::class, array('label' => 'Cantidad Solicitada', 'required' => true))
            ->add('vrSalario', NumberType::class, array('label' => 'Cantidad Solicitada', 'required' => true))
            ->add('vrNoSalarial', NumberType::class, array('label' => 'Cantidad Solicitada', 'required' => false))
            ->add('porcentajeArl', NumberType::class, array('required' => false))
            ->add('salarioFijo', CheckboxType::class, array('required'  => false))
            ->add('salarioVariable', CheckboxType::class, array('required'  => false))
            ->add('clienteReferencia', TextType::class, array('required' => true))
            ->add('fechaPosibleContratacion', DateType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

