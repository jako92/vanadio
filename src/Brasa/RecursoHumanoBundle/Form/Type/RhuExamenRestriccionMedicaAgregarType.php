<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuExamenRestriccionMedicaAgregarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('examenRevisionMedicaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenRevisionMedicaTipo',
                'choice_label' => 'nombre',
            ))
            ->add('dias', NumberType::class, array('required' => true))    
            //->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

