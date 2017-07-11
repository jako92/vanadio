<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurPeriodo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arTurPeriodo = $manager->getRepository('BrasaTurnoBundle:TurPeriodo')->find(1);
        if(!$arTurPeriodo) {
            $arTurPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();            
            $arTurPeriodo->setNombre("MES");
            $arTurPeriodo->setComentarios("");
            $manager->persist($arTurPeriodo);                
        }
        
        $arTurPeriodo = $manager->getRepository('BrasaTurnoBundle:TurPeriodo')->find(2);
        if(!$arTurPeriodo) {
            $arTurPeriodo = new \Brasa\TurnoBundle\Entity\TurPeriodo();            
            $arTurPeriodo->setNombre("DIA");
            $arTurPeriodo->setComentarios("");
            $manager->persist($arTurPeriodo);                
        }
        $manager->flush();
    }
    
}

