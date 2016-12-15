<?php
namespace Brasa\TransporteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listaPrecioRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteListaPrecio',
                'property' => 'nombre',
            ))
            ->add('nit', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('liquidarAutomaticamenteFlete','choice',array('choices' => array('NO' => '0', 'SI' => '0')))    
            ->add('liquidarAutomaticamenteManejo','choice',array('choices' => array('NO' => '0', 'SI' => '0')))        
            ->add('porcentajeManejo', 'text', array('required' => false))
            ->add('vrManejoMinimoUnidad', 'text', array('required' => false))    
            ->add('vrManejoMinimoDespacho', 'text', array('required' => false))        
            ->add('descuentoKilos', 'text', array('required' => false))            
            ->add('ctPesoMinimoUnidad', 'text', array('required' => false))            
            ->add('PagaManejoCorriente','choice',array('choices' => array('NO' => '0', 'SI' => '1')))        
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}

