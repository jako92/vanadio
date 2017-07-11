<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurRecursoTipo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arTurRecursoTipo = $manager->getRepository('BrasaTurnoBundle:TurRecursoTipo')->find(1);
        if(!$arTurRecursoTipo) {
            $arTurRecursoTipo = new \Brasa\TurnoBundle\Entity\TurRecursoTipo();            
            $arTurRecursoTipo->setNombre("FIJO");
            $manager->persist($arTurRecursoTipo);                
        }
        
        $arTurRecursoTipo = $manager->getRepository('BrasaTurnoBundle:TurRecursoTipo')->find(2);
        if(!$arTurRecursoTipo) {
            $arTurRecursoTipo = new \Brasa\TurnoBundle\Entity\TurRecursoTipo();            
            $arTurRecursoTipo->setNombre("APOYO");
            $manager->persist($arTurRecursoTipo);                
        }
        
        $arTurRecursoTipo = $manager->getRepository('BrasaTurnoBundle:TurRecursoTipo')->find(3);
        if(!$arTurRecursoTipo) {
            $arTurRecursoTipo = new \Brasa\TurnoBundle\Entity\TurRecursoTipo();            
            $arTurRecursoTipo->setNombre("RELEVANTE");
            $manager->persist($arTurRecursoTipo);                
        }
        
        $arTurRecursoTipo = $manager->getRepository('BrasaTurnoBundle:TurRecursoTipo')->find(4);
        if(!$arTurRecursoTipo) {
            $arTurRecursoTipo = new \Brasa\TurnoBundle\Entity\TurRecursoTipo();            
            $arTurRecursoTipo->setNombre("SUPERVISOR");
            $manager->persist($arTurRecursoTipo);                
        }
        $manager->flush();
    }
    
}

