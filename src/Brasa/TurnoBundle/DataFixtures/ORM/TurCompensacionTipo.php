<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurCompensacionTipo implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $arCompensacionTipo = $manager->getRepository('BrasaTurnoBundle:TurCompensacionTipo')->find(1);
        if (!$arCompensacionTipo) {
            $arCompensacionTipo = new \Brasa\TurnoBundle\Entity\TurCompensacionTipo();
            $arCompensacionTipo->setCodigoCompensacionTipoPk(1);
            $arCompensacionTipo->setNombre('COMPENSACION 1');
            $manager->persist($arCompensacionTipo);
        }

        $arCompensacionTipo = $manager->getRepository('BrasaTurnoBundle:TurCompensacionTipo')->find(2);
        if (!$arCompensacionTipo) {
            $arCompensacionTipo = new \Brasa\TurnoBundle\Entity\TurCompensacionTipo();
            $arCompensacionTipo->setCodigoCompensacionTipoPk(2);
            $arCompensacionTipo->setNombre('COMPENSACION 2');
            $manager->persist($arCompensacionTipo);
        }
        $arCompensacionTipo = $manager->getRepository('BrasaTurnoBundle:TurCompensacionTipo')->find(3);
        if (!$arCompensacionTipo) {
            $arCompensacionTipo = new \Brasa\TurnoBundle\Entity\TurCompensacionTipo();
            $arCompensacionTipo->setCodigoCompensacionTipoPk(3);
            $arCompensacionTipo->setNombre('COMPENSACION 3');
            $manager->persist($arCompensacionTipo);
        }

        $arCompensacionTipo = $manager->getRepository('BrasaTurnoBundle:TurCompensacionTipo')->find(4);
        if (!$arCompensacionTipo) {
            $arCompensacionTipo = new \Brasa\TurnoBundle\Entity\TurCompensacionTipo();
            $arCompensacionTipo->setCodigoCompensacionTipoPk(4);
            $arCompensacionTipo->setNombre('COMPENSACION 4');
            $manager->persist($arCompensacionTipo);
        }

        $arCompensacionTipo = $manager->getRepository('BrasaTurnoBundle:TurCompensacionTipo')->find(5);
        if (!$arCompensacionTipo) {
            $arCompensacionTipo = new \Brasa\TurnoBundle\Entity\TurCompensacionTipo();
            $arCompensacionTipo->setCodigoCompensacionTipoPk(5);
            $arCompensacionTipo->setNombre('COMPENSACION 5');
            $manager->persist($arCompensacionTipo);
        }

        $arCompensacionTipo = $manager->getRepository('BrasaTurnoBundle:TurCompensacionTipo')->find(6);
        if (!$arCompensacionTipo) {
            $arCompensacionTipo = new \Brasa\TurnoBundle\Entity\TurCompensacionTipo();
            $arCompensacionTipo->setCodigoCompensacionTipoPk(6);
            $arCompensacionTipo->setNombre('COMPENSACION 6');
            $manager->persist($arCompensacionTipo);
        }
        $manager->flush();
    }

}
