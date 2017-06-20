<?php

namespace Brasa\RecursoHumanoBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FacturaServicioAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->add('nombre', 'text')
                ->add('porcentajeIva', 'number')
                ->add('porBaseRetencionFuente', 'number')
                ->add('codigoCuentaCarteraFk', 'number')
                ->add('codigoCuentaRetencionFuenteFk', 'number')
                ->add('codigoCuentaIvaFk', 'number')
                ->add('codigoCuentaRetencionIvaFk', 'number')
                ->add('codigoCuentaIngresoFk', 'number')
                ->add('codigoCuentaBaseAiuFk', 'number')
                ->add('codigoCuentaBaseAiuContrapartidaFk', 'number');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('nombre');
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('codigoFacturaServicioPk')
                ->add('nombre')
                ->add('porcentajeIva')
                ->add('porBaseRetencionFuente')
                ->add('codigoCuentaCarteraFk')
                ->add('codigoCuentaRetencionFuenteFk')
                ->add('codigoCuentaIvaFk')
                ->add('codigoCuentaRetencionIvaFk')
                ->add('codigoCuentaIngresoFk')
                ->add('codigoCuentaBaseAiuFk')
                ->add('codigoCuentaBaseAiuContrapartidaFk');
    }

    public function toString($object) {
        return $object instanceof BlogPost ? $object->getTitle() : $object->getNombre();
    }

}
