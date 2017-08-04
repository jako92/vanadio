<?php

namespace Brasa\InventarioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DocumentoTipo implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $arDocumentoTipo = $manager->getRepository('BrasaInventarioBundle:InvDocumentoTipo')->find(1);
        if (!$arDocumentoTipo) {
            $arDocumentoTipo = new \Brasa\InventarioBundle\Entity\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk(1);
            $arDocumentoTipo->setNombre('ENTRADA');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('BrasaInventarioBundle:InvDocumentoTipo')->find(2);
        if (!$arDocumentoTipo) {
            $arDocumentoTipo = new \Brasa\InventarioBundle\Entity\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk(2);
            $arDocumentoTipo->setNombre('SALIDA');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('BrasaInventarioBundle:InvDocumentoTipo')->find(3);
        if (!$arDocumentoTipo) {
            $arDocumentoTipo = new \Brasa\InventarioBundle\Entity\InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk(3);
            $arDocumentoTipo->setNombre('FACTURA');
            $manager->persist($arDocumentoTipo);
        }
        $manager->flush();
    }

}
