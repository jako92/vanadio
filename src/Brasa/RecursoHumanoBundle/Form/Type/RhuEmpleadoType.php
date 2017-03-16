<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RhuEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('puestoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                  
            ->add('centroCostoContabilidadRel', EntityType::class, array(
                'class' => 'BrasaContabilidadBundle:CtbCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.codigoCentroCostoPk', 'ASC');},
                'choice_label' => function ($centroCosto) {
                    return $centroCosto->getCodigoCentroCostoPk() . '-' . $centroCosto->getNombre();
                },
                'required' => true))  
                            
                            
            ->add('tipoIdentificacionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('bancoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuBanco',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('b')
                    ->orderBy('b.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('estadoCivilRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'choice_label' => 'nombre',
            ))
            ->add('ciudadExpedicionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('ciudadRel', EntityType::class, array(
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
            ->add('subzonaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('empleadoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                            
            ->add('codigoSexoFk', ChoiceType::class, array('choices' => array('MASCULINO' => 'M', 'FEMENINO' => 'F')))
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaExpedicionIdentificacion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('nombre1', TextType::class, array('required' => true))
            ->add('codigoTipoLibreta', ChoiceType::class, array('choices' => array('1° CLASE' => '1', '2° CLASE' => '2', 'NO APLICA' => '0')))
            ->add('nombre2', TextType::class, array('required' => false))
            ->add('apellido1', TextType::class, array('required' => true))
            ->add('apellido2', TextType::class, array('required' => false))
            ->add('telefono', TextType::class, array('required' => false))
            ->add('celular', TextType::class, array('required' => false))
            ->add('direccion', TextType::class, array('required' => false))
            ->add('barrio', TextType::class, array('required' => true))
            ->add('rhRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'choice_label' => 'tipo',
            ))
            ->add('correo', TextType::class, array('required' => false))
            ->add('cuenta', TextType::class, array('required' => false))
            ->add('tipoCuenta', ChoiceType::class, array('choices' => array('AHORRO' => 'S', 'CORRIENTE' => 'D', 'DAVIPLATA' => 'DP')))
            ->add('numeroIdentificacion', TextType::class, array('required' => true))            
            ->add('discapacidad', CheckboxType::class, array('required'  => false))
            ->add('padreFamilia', CheckboxType::class, array('required'  => false))
            ->add('pagadoEntidadSalud', CheckboxType::class, array('required'  => false))            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('camisa', TextType::class, array('required' => false))
            ->add('jeans', TextType::class, array('required' => false))
            ->add('calzado', TextType::class, array('required' => false))
            ->add('empleadoEstudioTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'choice_label' => 'nombre',
            ))
            ->add('horarioRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuHorario',
                'choice_label' => 'nombre',
            ))
            ->add('departamentoEmpresaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'choice_label' => 'nombre',
            ))            
            ->add('centroCostoFijo', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

