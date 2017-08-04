<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenFormaPago implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $arFormaPago = $manager->getRepository('BrasaGeneralBundle:GenFormaPago')->find(1);
        if(!$arFormaPago) {
            $arFormaPago = new \Brasa\GeneralBundle\Entity\GenFormaPago();
            $arFormaPago->setCodigoFormaPagoPk(1);
            $arFormaPago->setNombre("CONTADO");
            $manager->persist($arFormaPago);                
        }
        $arFormaPago = $manager->getRepository('BrasaGeneralBundle:GenFormaPago')->find(2);
        if(!$arFormaPago) {
            $arFormaPago = new \Brasa\GeneralBundle\Entity\GenFormaPago();
            $arFormaPago->setCodigoFormaPagoPk(2);
            $arFormaPago->setNombre("CREDITO");
            $manager->persist($arFormaPago);                
        }
        $manager->flush();
    }
}

