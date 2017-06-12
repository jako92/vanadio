<?php
namespace Brasa\GeneralBundle\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CiudadAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        $formMapper->add('nombre', 'text')
                ->add('codigoRutaPredeterminadaFk','number',array('label'=>'Ruta','required'=>false))
                   ->add('departamentoRel', 'entity', array(
                         'class' => 'BrasaGeneralBundle:GenDepartamento',
                         'choice_label' => 'nombre',
                         'label' => 'Departamento'
                        ))
                  ->add('codigoInterface', 'text')
                  ->add('codigoDane', 'text')
                ->add('porcentajeRetencionIca', 'number',array('required'=>false));      
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('codigoCiudadPk')
                    ->add('codigoRutaPredeterminadaPk')
                    ->add('departamentoRel.nombre')
                    ->add('nombre')
                    ->add('codigoInterface')
                    ->add('codigoDane')
                ->add('porcentajeRetencionIca');
    }
    
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : $object->getNombre();
    }
}