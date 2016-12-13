<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuPagoBancoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('cuentaRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCuenta',
                'choice_label' => 'nombre',
            ))
            ->add('pagoBancoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoBancoTipo',
                'choice_label' => 'nombre',
            ))                
            ->add('descripcion', TextType::class, array('required' => false))
            ->add('fechaTrasmision', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('secuencia', ChoiceType::class, array('choices' => array('A' => 'A', 'B' => 'B','C' => 'C', 'D' => 'D','E' => 'E', 'F' => 'F','G' => 'G', 'H' => 'H','I' => 'I', 'J' => 'J','K' => 'K', 'L' => 'L','M' => 'M', 'N' => 'N','O' => 'O', 'P' => 'P','Q' => 'Q', 'R' => 'R','S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z'),))
            ->add('fechaAplicacion', DateType::class, array('format' => 'yyyyMMdd'))    
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

