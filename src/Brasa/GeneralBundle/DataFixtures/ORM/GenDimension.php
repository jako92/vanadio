<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenDimension implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $arDimension = $manager->getRepository('BrasaGeneralBundle:GenDimension')->find(1);
        if (!$arDimension) {
            $arDimension = new \Brasa\GeneralBundle\Entity\GenDimension();
            $arDimension->setCodigoDimensionPk(1);
            $arDimension->setNombre("MEDIANA");
            $manager->persist($arDimension);
        }
        $arDimension = $manager->getRepository('BrasaGeneralBundle:GenDimension')->find(2);
        if (!$arDimension) {
            $arDimension = new \Brasa\GeneralBundle\Entity\GenDimension();
            $arDimension->setCodigoDimensionPk(2);
            $arDimension->setNombre("PEQUEÑA");
            $manager->persist($arDimension);
        }
        $arDimension = $manager->getRepository('BrasaGeneralBundle:GenDimension')->find(3);
        if (!$arDimension) {
            $arDimension = new \Brasa\GeneralBundle\Entity\GenDimension();
            $arDimension->setCodigoDimensionPk(3);
            $arDimension->setNombre("GRANDE");
            $manager->persist($arDimension);
        }
        $manager->flush();
    }

}
