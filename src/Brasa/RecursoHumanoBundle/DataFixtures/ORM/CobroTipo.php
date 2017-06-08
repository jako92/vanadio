<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CobroTipo implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /*$arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("E");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("E");
            $arCobroTipo->setNombre("EXAMEN");
            $manager->persist($arCobroTipo);                
        }
        $arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("I");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("I");
            $arCobroTipo->setNombre("INCAPACIDAD");
            $manager->persist($arCobroTipo);                
        }
        $arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("N");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("N");
            $arCobroTipo->setNombre("NOMINA");
            $manager->persist($arCobroTipo);                
        }
        $arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("P");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("P");
            $arCobroTipo->setNombre("PRUEBA PSICOTECNICA");
            $manager->persist($arCobroTipo);                
        }
        $arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("S");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("S");
            $arCobroTipo->setNombre("SELECCION");
            $manager->persist($arCobroTipo);                
        }
        $arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("V");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("V");
            $arCobroTipo->setNombre("VISITA");
            $manager->persist($arCobroTipo);                
        }
        $arCobroTipo = $manager->getRepository('BrasaRecursoHumanoBundle:RhuCobroTipo')->find("L");
        if(!$arCobroTipo) {
            $arCobroTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCobroTipo();
            $arCobroTipo->setCodigoCobroTipoPk("L");
            $arCobroTipo->setNombre("POLIGRAFIA");
            $manager->persist($arCobroTipo);                
        }
        $manager->flush();*/
    }
}

