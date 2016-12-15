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

class RhuAccidenteTrabajoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('codigoFurat', NumberType::class, array('required' => true))
            ->add('fechaAccidente', DateType::class, array('required' => true ,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('tipoAccidenteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoAccidente',
                        'choice_label' => 'nombre',))
            ->add('fechaEnviaInvestigacion', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaIncapacidadDesde', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaIncapacidadHasta', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('dias', NumberType::class, array('required' => false))
            ->add('cie10', TextType::class, array('required' => false))
            ->add('diagnostico', TextType::class, array('required' => false))
            ->add('naturalezaLesion', TextType::class, array('required' => false))
            ->add('cuerpoAfectado', TextType::class, array('required' => false))
            ->add('agente', TextType::class, array('required' => false))
            ->add('mecanismoAccidente', TextType::class, array('required' => false))
            ->add('lugarAccidente', TextType::class, array('required' => false))
            ->add('coordinadorEncargado', TextType::class, array('required' => false))                
            ->add('cargoCoordinadorEncargado', TextType::class, array('required' => false))                                
            ->add('tiempoServicioEmpleado', TextType::class, array('required' => false))                                
            ->add('tareaDesarrolladaMomentoAccidente', ChoiceType::class, array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('oficioHabitual', TextType::class, array('required' => false))                                
            ->add('descripcionAccidente', TextareaType::class, array('required' => false))
            ->add('actoInseguro', TextareaType::class, array('required' => false))
            ->add('condicionInsegura', TextareaType::class, array('required' => false))
            ->add('factorPersonal', TextareaType::class, array('required' => false))
            ->add('factorTrabajo', TextareaType::class, array('required' => false))
            ->add('planAccion1', TextareaType::class, array('required' => false))
            ->add('tipoControlUnoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
                        'choice_label' => 'nombre',))
            ->add('fechaVerificacion1', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable1', TextType::class, array('required' => false))
            ->add('planAccion2', TextareaType::class, array('required' => false))
            ->add('tipoControlDosRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
                        'choice_label' => 'nombre',))
            ->add('fechaVerificacion2', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable2', TextType::class, array('required' => false))                
            ->add('planAccion3', TextareaType::class, array('required' => false))
            ->add('tipoControlTresRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
                        'choice_label' => 'nombre',))
            ->add('fechaVerificacion3', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable3', TextType::class, array('required' => false))
            ->add('participanteInvestigacion1', TextType::class, array('required' => false))                
            ->add('cargoParticipanteInvestigacion1', TextType::class, array('required' => false))
            ->add('participanteInvestigacion2', TextType::class, array('required' => false))                
            ->add('cargoParticipanteInvestigacion2', TextType::class, array('required' => false))                
            ->add('participanteInvestigacion3', TextType::class, array('required' => false))                
            ->add('cargoParticipanteInvestigacion3', TextType::class, array('required' => false))                
            ->add('representanteLegal', TextType::class, array('required' => false))                
            ->add('cargoRepresentanteLegal', TextType::class, array('required' => false))
            ->add('licencia', TextType::class, array('required' => false))
            ->add('fechaVerificacion', DateType::class, array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('responsableVerificacion', TextType::class, array('required' => false))                
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
