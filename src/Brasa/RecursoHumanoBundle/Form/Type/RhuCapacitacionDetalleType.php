<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuCapacitacionDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
            ->add('numeroIdentificacion', TextType::class, array('required' => true))
            ->add('nombreCorto', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

