<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CtbAsientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comprobanteRel', EntityType::class, array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('numeroAsiento', TextType::class, array('required' => true))
            ->add('soporte', TextType::class, array('required' => true))
            ->add('fecha', DateType::class, array('required' => true))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->add('BtnGuardarNuevo', SubmitType::class, array('label' => 'Guardar y nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}
