<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurSector implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arTurSector = $manager->getRepository('BrasaTurnoBundle:TurSector')->find(1);
        if(!$arTurSector) {
            $arTurSector = new \Brasa\TurnoBundle\Entity\TurSector();            
            $arTurSector->setNombre("COMERCIAL");
            $arTurSector->setPorcentaje("8.8");
            $arTurSector->setComentarios("");
            $manager->persist($arTurSector);                
        }
        
       $arTurSector = $manager->getRepository('BrasaTurnoBundle:TurSector')->find(2);
        if(!$arTurSector) {
            $arTurSector = new \Brasa\TurnoBundle\Entity\TurSector();            
            $arTurSector->setNombre("RESIDENCIAL");
            $arTurSector->setPorcentaje("8.6");
            $arTurSector->setComentarios("");
            $manager->persist($arTurSector);
        }
        $manager->flush();
    }
    
}

