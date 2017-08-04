<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenSectorComercial implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(1);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setCodigoSectorComercialPk(1);
            $arSectorComercial->setNombre("COMERCIAL");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(2);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("CONSTRUCTOR");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(3);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("DIVERSION");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(4);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("EDUCATIVO");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(5);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("ESTATAL");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(6);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("FINANCIERO");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(7);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("GASTRONOMICO");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(8);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("HOTELERO");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(9);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("INDUSTRIAL");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(10);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("MINERO");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(11);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("RESIDENCIAL");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(12);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("SALUD");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(13);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("SERVICIOS");
            $manager->persist($arSectorComercial);                
        }
        $arSectorComercial = $manager->getRepository('BrasaGeneralBundle:GenSectorComercial')->find(14);
        if(!$arSectorComercial) {
            $arSectorComercial = new \Brasa\GeneralBundle\Entity\GenSectorComercial();
            $arSectorComercial->setNombre("TRANSPORTE TERRESTRE");
            $manager->persist($arSectorComercial);                
        }
        $manager->flush();
    }
}

