<?php
namespace Brasa\RecursoHumanoBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class EmpleadoEstudioTipoAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        $formMapper->add('nombre', 'text')
                ->add('validarVencimiento', 'checkbox',array('required'=>false));      
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('empleadoEstudioTipoPk')
                    ->add('nombre')
                    ->add('validarVencimiento');
    }
    
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : $object->getNombre();
    }
}