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
            $arConsecutivo->setNombre("PAGOS");
            $arConsecutivo->setConsecutivo(1);
            $manager->persist($arConsecutivo);                
        }
        $manager->flush();
        
    }
}

