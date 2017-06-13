<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\RecursoHumanoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PagoConcepto implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arPagoConcepto = $manager->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(1);
        if(!$arPagoConcepto) {
            $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
            $arPagoConcepto->setNombre("Salario nomina");
            $arPagoConcepto->setComponeSalario(1);            
            $manager->persist($arPagoConcepto);          
        }
        $manager->flush();
        
    }
}

