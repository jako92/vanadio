<?php

namespace Brasa\RecursoHumanoBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FacturaTipoAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->add('nombre', 'text')
                ->add('tipo', 'number')
                ->add('operacion', 'number')
                ->add('consecutivo', 'number')
                ->add('documentoCartera', 'text')
                ->add('codigoCentroCostocontabilidad', 'number')
                ->add('codigoComprobante', 'number')
                ->add('codigoDocumentoCartera', 'number')
                ->add('tipoCuentacartera', 'number')
                ->add('tipocuentaRetencionFuente', 'number')
                ->add('tipoCuentaIva', 'number')
                ->add('tipoCuentaingreso', 'number')
                ->add('tipoCuentaBaseAiu', 'number')
                ->add('tipoCuentaBaseAiuContrapartida', 'number');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('codigoFacturaTipoPk')
                ->add('nombre')
                ->add('tipo')
                ->add('operacion')
                ->add('consecutivo')
                ->add('documentoCartera')
                ->add('codigoCentroCostocontabilidad')
                ->add('codigoComprobante')
                ->add('codigoDocumentoCartera')
                ->add('tipoCuentacartera')
                ->add('tipocuentaRetencionFuente')
                ->add('tipoCuentaIva')
                ->add('tipoCuentaingreso')
                ->add('tipoCuentaBaseAiu')
                ->add('tipoCuentaBaseAiuContrapartida');
    }

    public function toString($object) {
        return $object instanceof BlogPost ? $object->getTitle() : $object->getNombre();
    }

}
