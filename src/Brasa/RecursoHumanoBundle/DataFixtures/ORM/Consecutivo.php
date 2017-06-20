<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\RecursoHumanoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Consecutivo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arConsecutivo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->find(1);
        if(!$arConsecutivo) {
            $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(1);
            $arConsecutivo->setNombre("PAGOS");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);                
        }
        $arConsecutivo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConsecutivo')->find(2);
        if(!$arConsecutivo) {
            $arConsecutivo = new \Brasa\RecursoHumanoBundle\Entity\RhuConsecutivo();
            $arConsecutivo->setCodigoConsecutivoPk(2);
            $arConsecutivo->setNombre("PROVISION");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);                
        }
        
        $manager->flush();
        
    }
}

