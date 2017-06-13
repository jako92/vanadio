<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\RecursoHumanoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EntidadExamen implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arEntidadExamen = $manager->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find(1);
        if(!$arEntidadExamen) {
            $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
            $arEntidadExamen->setNombre("OMNISALUD");            
            $manager->persist($arEntidadExamen);          
        }
        $manager->flush();
        
    }
}

