<?php
namespace Brasa\ContabilidadBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SucursalAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('codigoSucursalPk', 'text')
                ->add('nombre', 'text')
                ->add('codigoSucursalFk', 'text',array('required'=>false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('codigoSucursalPk')
                ->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('codigoSucursalPk')
                    ->add('nombre')
                    ->add('codigoSucursalFk');
    }
    
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : $object->getNombre();
    }
}