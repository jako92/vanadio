<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenFormaPago extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $arFormaPago = $manager->getRepository('BrasaGeneralBundle:GenFormaPago')->find(1);
        if(!$arFormaPago) {
            $arFormaPago = new \Brasa\GeneralBundle\Entity\GenFormaPago();
            $arFormaPago->setNombre("CONTADO");
            $manager->persist($arFormaPago);                
        }
        $arFormaPago = $manager->getRepository('BrasaGeneralBundle:GenFormaPago')->find(2);
        if(!$arFormaPago) {
            $arFormaPago = new \Brasa\GeneralBundle\Entity\GenFormaPago();
            $arFormaPago->setNombre("CREDITO");
            $manager->persist($arFormaPago);                
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4; // el orden en el cual serán cargados los accesorios
    }
}

