<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenOrigenJudicial implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $arOrigenJudicial = $manager->getRepository('BrasaGeneralBundle:GenOrigenJudicial')->find(1);
        if (!$arOrigenJudicial) {
            $arOrigenJudicial = new \Brasa\GeneralBundle\Entity\GenOrigenJudicial();
            $arOrigenJudicial->setCodigoOrigenJudicialPk(1);
            $arOrigenJudicial->setNombre("PERSONA JURIDICA");
            $manager->persist($arOrigenJudicial);
        }
        $arOrigenJudicial = $manager->getRepository('BrasaGeneralBundle:GenOrigenJudicial')->find(2);
        if (!$arOrigenJudicial) {
            $arOrigenJudicial = new \Brasa\GeneralBundle\Entity\GenOrigenJudicial();
            $arOrigenJudicial->setCodigoOrigenJudicialPk(2);
            $arOrigenJudicial->setNombre("PERSONA NATURAL");
            $manager->persist($arOrigenJudicial);
        }
        $manager->flush();
    }

}
