<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurPedidoTipo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {        
        $arTurPedidoTipo = $manager->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(1);
        if(!$arTurPedidoTipo) {
            $arTurPedidoTipo = new \Brasa\TurnoBundle\Entity\TurPedidoTipo();
            $arTurPedidoTipo->setNombre("OCACIONAL");
            $arTurPedidoTipo->setControl(0);
            $manager->persist($arTurPedidoTipo);                
        }
        $arTurPedidoTipo = $manager->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(2);
        if(!$arTurPedidoTipo) {
            $arTurPedidoTipo = new \Brasa\TurnoBundle\Entity\TurPedidoTipo();
            $arTurPedidoTipo->setNombre("PROGRAMADO");
            $arTurPedidoTipo->setControl(1);
            $manager->persist($arTurPedidoTipo);                
        }
        $arTurPedidoTipo = $manager->getRepository('BrasaTurnoBundle:TurPedidoTipo')->find(3);
        if(!$arTurPedidoTipo) {
            $arTurPedidoTipo = new \Brasa\TurnoBundle\Entity\TurPedidoTipo();
            $arTurPedidoTipo->setNombre("REFUERZO");
            $arTurPedidoTipo->setControl(0);
            $manager->persist($arTurPedidoTipo);                
        }
        $manager->flush();
    }
    
}

