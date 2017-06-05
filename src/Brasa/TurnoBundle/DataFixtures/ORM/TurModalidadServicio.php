<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurModalidadServicio implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arTurModalidadServicio = $manager->getRepository('BrasaTurnoBundle:TurModalidadServicio')->find(1);
        if(!$arTurModalidadServicio) {
            $arTurModalidadServicio = new \Brasa\TurnoBundle\Entity\TurModalidadServicio();            
            $arTurModalidadServicio->setNombre("SIN ARMA");
            $arTurModalidadServicio->setTipo(1);
            $arTurModalidadServicio->setPorcentaje(8);
            $arTurModalidadServicio->setAbreviatura("SA");
            $manager->persist($arTurModalidadServicio);                
        }
        
       $arTurModalidadServicio = $manager->getRepository('BrasaTurnoBundle:TurModalidadServicio')->find(2);
        if(!$arTurModalidadServicio) {
            $arTurModalidadServicio = new \Brasa\TurnoBundle\Entity\TurModalidadServicio();            
            $arTurModalidadServicio->setNombre("CON ARMA");
            $arTurModalidadServicio->setTipo(1);
            $arTurModalidadServicio->setPorcentaje(10);
            $arTurModalidadServicio->setAbreviatura("CA");
            $manager->persist($arTurModalidadServicio);                
        }
        
        $arTurModalidadServicio = $manager->getRepository('BrasaTurnoBundle:TurModalidadServicio')->find(3);
        if(!$arTurModalidadServicio) {
            $arTurModalidadServicio = new \Brasa\TurnoBundle\Entity\TurModalidadServicio();            
            $arTurModalidadServicio->setNombre("CANINO");
            $arTurModalidadServicio->setTipo(1);
            $arTurModalidadServicio->setPorcentaje(11);
            $arTurModalidadServicio->setAbreviatura("CAN");
            $manager->persist($arTurModalidadServicio);                
        }
        $manager->flush();
    }
    
}

