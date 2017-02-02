<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RhuPagoConceptoCuentaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('empleadoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('codigoCuentaFk', TextType::class, array('required' => true))            
            ->add('tipoCuenta', NumberType::class, array('required' => true))                                
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
