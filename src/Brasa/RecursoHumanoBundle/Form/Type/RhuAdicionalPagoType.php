<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuAdicionalPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('pagoConceptoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional <> 0')                    
                    ->orderBy('pc.codigoPagoConceptoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))    
            ->add('detalle', TextType::class, array('required' => true))    
            ->add('empleadoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'choice_label' => 'nombreCorto',
            ))    
            ->add('cantidad', TextType::class, array('required' => true))    
            ->add('valor', TextType::class, array('required' => true))        
            ->add('aplicaDiaLaborado', ChoiceType::class, array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('guardar', SubmitType::class);            
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

