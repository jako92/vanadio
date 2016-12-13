<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuDescuentoAdicionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder              
            ->add('empleadoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $options['data']->getCentroCostoRel()->getCodigoCentroCostoPk())
                    ->orderBy('e.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))                                   
            ->add('valor', NumberType::class, array('required' => true))  
            ->add('comentarios', TextareaType::class, array('required' => false))                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

