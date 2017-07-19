<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\RecursoHumanoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ConfiguracionCuenta implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(1);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(1);
            $arConfiguracionCuenta->setNombre("LIQUIDACION CESANTIAS");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(2);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(2);
            $arConfiguracionCuenta->setNombre("LIQUIDACION INTERESES CESANTIAS");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(3);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(3);
            $arConfiguracionCuenta->setNombre("LIQUIDACION PRIMA");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(4);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(4);
            $arConfiguracionCuenta->setNombre("LIQUIDACION VACACION");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(5);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(5);
            $arConfiguracionCuenta->setNombre("LIQUIDACION POR PAGAR");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(6);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(6);
            $arConfiguracionCuenta->setNombre("LIQUIDACION INDEMNIZACION");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(7);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(7);
            $arConfiguracionCuenta->setNombre("VACACIONES PAGADAS");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("VACACIONES");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(8);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(8);
            $arConfiguracionCuenta->setNombre("VACACIONES DISFRUTADAS");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("VACACIONES");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(9);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(9);
            $arConfiguracionCuenta->setNombre("SALUD");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("VACACIONES");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(10);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(10);
            $arConfiguracionCuenta->setNombre("PENSION");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("VACACIONES");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(11);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(11);
            $arConfiguracionCuenta->setNombre("FONDOS");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("VACACIONES");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(12);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(12);
            $arConfiguracionCuenta->setNombre("VACACIONES POR PAGAR");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("VACACIONES");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(13);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(13);
            $arConfiguracionCuenta->setNombre("PAGO SS PENSION");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("EGRESO SS");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(14);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(14);
            $arConfiguracionCuenta->setNombre("PAGO SS SALUD");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("EGRESO SS");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(15);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(15);
            $arConfiguracionCuenta->setNombre("PAGO SS RIEGOS");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("EGRESO SS");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(16);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(16);
            $arConfiguracionCuenta->setNombre("PAGO SS PARAFISCALES");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("EGRESO SS");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(17);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(17);
            $arConfiguracionCuenta->setNombre("LIQUIDACION CESANTIA AÑO ANTERIOR");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }
        $arConfiguracionCuenta = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionCuenta')->find(18);
        if (!$arConfiguracionCuenta) {
            $arConfiguracionCuenta = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionCuenta();
            $arConfiguracionCuenta->setCodigoConfiguracionCuentaPk(18);
            $arConfiguracionCuenta->setNombre("LIQUIDACION INTERES CESANTIA AÑO ANTERIOR");
            $arConfiguracionCuenta->setCodigoCuentaFk("1");
            $arConfiguracionCuenta->setTipo("LIQUIDACION");
            $manager->persist($arConfiguracionCuenta);
        }


        $manager->flush();
    }

}
