<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenCobertura implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arCobertura = $manager->getRepository('BrasaGeneralBundle:GenCobertura')->find(1);
        if(!$arCobertura) {
            $arCobertura = new \Brasa\GeneralBundle\Entity\GenCobertura();
            $arCobertura->setCodigoCoberturaPk(1);
            $arCobertura->setNombre("LOCAL");
            $manager->persist($arCobertura);                
        }
        $arCobertura = $manager->getRepository('BrasaGeneralBundle:GenCobertura')->find(2);
        if(!$arCobertura) {
            $arCobertura = new \Brasa\GeneralBundle\Entity\GenCobertura();
            $arCobertura->setCodigoCoberturaPk(2);
            $arCobertura->setNombre("INTERNACIONAL");
            $manager->persist($arCobertura);                
        }
        $arCobertura = $manager->getRepository('BrasaGeneralBundle:GenCobertura')->find(3);
        if(!$arCobertura) {
            $arCobertura = new \Brasa\GeneralBundle\Entity\GenCobertura();
            $arCobertura->setCodigoCoberturaPk(3);
            $arCobertura->setNombre("NACIONAL");
            $manager->persist($arCobertura);                
        }
        $manager->flush();
    }
}

