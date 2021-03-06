<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RhuFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  
            ->add('facturaTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuFacturaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('facturaServicioRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuFacturaServicio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('fs')
                    ->orderBy('fs.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                             
            ->add('clienteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))        
            ->add('plazoPago', NumberType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))                                
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

