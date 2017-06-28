<?php
namespace Brasa\TurnoBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ModalidadServicioAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        $formMapper->add('nombre', 'text')
                   ->add('abreviatura', 'text')
                   ->add('tipo', 'number')
                   ->add('porcentaje', 'number')
                   ->add('comentarios', 'textarea', array('required'=>false));      
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('codigoModalidadServicioPk')
                    ->add('nombre')
                    ->add('abreviatura')
                    ->add('tipo')
                    ->add('porcentaje');
    }
    
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : $object->getNombre();
    }
}