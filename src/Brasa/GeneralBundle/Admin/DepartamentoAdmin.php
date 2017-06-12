<?php
namespace Brasa\GeneralBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class DepartamentoAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        $formMapper->add('CodigoDepartamentoPk', 'number',array('label'=>'Codigo'))
                   ->add('nombre', 'text')
                   ->add('paisRel', 'entity', array(
                         'class' => 'BrasaGeneralBundle:GenPais',
                         'choice_label' => 'pais',
                         'label' => 'Pais'
                        ))
                   ->add('codigoDane', 'text',array('label'=>'Codigo dane'));        
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('codigoDepartamentoPk')
                    ->add('paisRel.pais')
                    ->add('nombre')
                    ->add('codigoDane');
    }
    
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : $object->getNombre();
    }
}