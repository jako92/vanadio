<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurConsecutivo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {        
        $arTurConsecutivo = $manager->getRepository('BrasaTurnoBundle:TurConsecutivo')->find(1);
        if(!$arTurConsecutivo) {
            $arTurConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
            $arTurConsecutivo->setNombre("PEDIDO");
            $arTurConsecutivo->setConsecutivo(1);
            $manager->persist($arTurConsecutivo);                
        }
        $arTurConsecutivo = $manager->getRepository('BrasaTurnoBundle:TurConsecutivo')->find(2);
        if(!$arTurConsecutivo) {
            $arTurConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
            $arTurConsecutivo->setNombre("FACTURA");
            $arTurConsecutivo->setConsecutivo(1);
            $manager->persist($arTurConsecutivo);                
        }
        $arTurConsecutivo = $manager->getRepository('BrasaTurnoBundle:TurConsecutivo')->find(3);
        if(!$arTurConsecutivo) {
            $arTurConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
            $arTurConsecutivo->setNombre("COTIZACION");
            $arTurConsecutivo->setConsecutivo(1);
            $manager->persist($arTurConsecutivo);                
        }
        $manager->flush();
    }
    
}

