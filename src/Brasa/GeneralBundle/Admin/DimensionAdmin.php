<?php
namespace Brasa\GeneralBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DimensionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        $formMapper->add('nombre', 'text');        
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('codigoDimensionPk')
                ->add('nombre');
    }
}