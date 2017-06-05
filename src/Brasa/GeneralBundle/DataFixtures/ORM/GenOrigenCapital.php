<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenOrigenCapital implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $arOrigenCapital = $manager->getRepository('BrasaGeneralBundle:GenOrigenCapital')->find(1);
        if (!$arOrigenCapital) {
            $arOrigenCapital = new \Brasa\GeneralBundle\Entity\GenOrigenCapital();
            $arOrigenCapital->setNombre("ESTATAL");
            $manager->persist($arOrigenCapital);
        }
        $arOrigenCapital = $manager->getRepository('BrasaGeneralBundle:GenOrigenCapital')->find(2);
        if (!$arOrigenCapital) {
            $arOrigenCapital = new \Brasa\GeneralBundle\Entity\GenOrigenCapital();
            $arOrigenCapital->setNombre("PRIVADO");
            $manager->persist($arOrigenCapital);
        }
        $manager->flush();
    }

}
