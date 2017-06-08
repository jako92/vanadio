<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PruebaTipo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(1);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("CMT");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(2);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("TEST CARAS");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(3);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("IBP");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(4);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("16PF");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(5);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("MACHOVER");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(6);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("VALANTI");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(7);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("WARTEG");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(8);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("ITPC");
            $manager->persist($arPruebaTipo);                
        }
        $arPruebaTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPruebaTipo')->find(9);
        if(!$arPruebaTipo) {
            $arPruebaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPruebaTipo();
            $arPruebaTipo->setNombre("CCV");
            $manager->persist($arPruebaTipo);                
        }
        $manager->flush();
    }
}

