<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RhuVisitaClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuVisitaTipo',
                'choice_label' => 'nombre',
            ))
            ->add('tipoIdentificacionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true)) 
            ->add('validarVencimiento', CheckboxType::class, array('required'  => false))    
            ->add('nombreCorto', TextType::class, array('required' => true, 'attr' => array('cols' => '5', 'rows' => '25')))
            ->add('numeroIdentificacion', TextType::class, array('required' => true, 'attr' => array('cols' => '5', 'rows' => '25')))
            ->add('comentarios', TextareaType::class, array('required' => true, 'attr' => array('cols' => '5', 'rows' => '25')))
            ->add('fecha', DateTimeType::class, array('required' => true, 'data' => new \DateTime('now')))
            ->add('fechaVence', DateType::class, array('required' => true, 'data' => new \DateTime('now')))
            ->add('nombreQuienVisita', TextType::class,array('required' => true))
            ->add('vrTotal', NumberType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

