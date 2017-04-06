<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
class TurFacturaEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('facturaTipoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurFacturaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->where('ft.tipo <> :tipo')
                    ->setParameter('tipo', 2)                            
                    ->orderBy('ft.codigoFacturaTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                         
            ->add('numero', NumberType::class, array('required' => true))                            
            ->add('fecha', DateType::class, array('format' => 'yyyyMMdd')) 
            ->add('fechaVence', DateType::class, array('format' => 'yyyyMMdd')) 
            ->add('descripcion', TextType::class, array('required' => false))                             
            ->add('guardar', SubmitType::class)
            ->add('reliquidar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

