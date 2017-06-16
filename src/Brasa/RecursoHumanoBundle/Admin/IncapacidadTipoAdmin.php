<?php
namespace Brasa\RecursoHumanoBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class IncapacidadTipoAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        $formMapper->add('nombre', 'text')
                   ->add('pagoConceptoRel', 'entity', array(
                         'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                         'choice_label' => 'nombre',
                         ))
                ->add('generaPago', 'checkbox')
                ->add('generaIbc', 'checkbox')
                ->add('tipo', 'number')
                ->add('abreviatura', 'text');        
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('codigoIncapacidadTipoPk')
                ->add('nombre')
                ->add('pagoConceptoRel.nombre')
                ->add('generaPago')
                ->add('generaIbc')
                ->add('tipo')
                ->add('abreviatura');
    }
    
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : $object->getNombre();
    }
}