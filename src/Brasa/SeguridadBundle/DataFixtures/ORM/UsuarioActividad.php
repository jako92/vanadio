<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UsuarioActividad extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $arUsuarioActividad = $manager->getRepository('BrasaSeguridadBundle:SegUsuarioActividad')->find(1);
        if (!$arUsuarioActividad) {
            $arUsuarioActividad = new \Brasa\SeguridadBundle\Entity\SegUsuarioActividad();
            $arUsuarioActividad->setCodigoUsuarioActividadPk(1);
            $arUsuarioActividad->setNombre('NOMINA');
            $manager->persist($arUsuarioActividad);
        }
        $arUsuarioActividad = $manager->getRepository('BrasaSeguridadBundle:SegUsuarioActividad')->find(2);
        if (!$arUsuarioActividad) {
            $arUsuarioActividad = new \Brasa\SeguridadBundle\Entity\SegUsuarioActividad();
            $arUsuarioActividad->setCodigoUsuarioActividadPk(2);
            $arUsuarioActividad->setNombre('PROGRAMACION');
            $manager->persist($arUsuarioActividad);
        }
        $arUsuarioActividad = $manager->getRepository('BrasaSeguridadBundle:SegUsuarioActividad')->find(3);
        if (!$arUsuarioActividad) {
            $arUsuarioActividad = new \Brasa\SeguridadBundle\Entity\SegUsuarioActividad();
            $arUsuarioActividad->setCodigoUsuarioActividadPk(3);
            $arUsuarioActividad->setNombre('SELECCION');
            $manager->persist($arUsuarioActividad);
        }
        $arUsuarioActividad = $manager->getRepository('BrasaSeguridadBundle:SegUsuarioActividad')->find(4);
        if (!$arUsuarioActividad) {
            $arUsuarioActividad = new \Brasa\SeguridadBundle\Entity\SegUsuarioActividad();
            $arUsuarioActividad->setCodigoUsuarioActividadPk(4);
            $arUsuarioActividad->setNombre('CONTRATACION');
            $manager->persist($arUsuarioActividad);
        }
        $manager->flush();
        $arUsuarioActividad = $manager->getRepository('BrasaSeguridadBundle:SegUsuarioActividad')->find(5);
        if (!$arUsuarioActividad) {
            $arUsuarioActividad = new \Brasa\SeguridadBundle\Entity\SegUsuarioActividad();
            $arUsuarioActividad->setCodigoUsuarioActividadPk(5);
            $arUsuarioActividad->setNombre('SEGURIDAD Y SALUD EN EL TRABAJO');
            $manager->persist($arUsuarioActividad);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 2; // el orden en el cual serán cargados los accesorios
    }

}
