<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuProgramacionPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder             
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('pagoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('pt')
                    ->orderBy('pt.codigoPagoTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                         
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd'))                
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaHastaReal', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('dias', NumberType::class, array('required' => true)) 
            ->add('mensajePago', TextareaType::class, array('required' => false))                                            
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

