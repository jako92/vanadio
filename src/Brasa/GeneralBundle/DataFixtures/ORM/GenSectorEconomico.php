<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenSectorEconomico implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(1);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();  
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(1);
            $arGenSectorEconomico->setNombre("COMERCIO");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(2);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(2);            
            $arGenSectorEconomico->setNombre("CONSTRUCCION");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(3);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();            
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(3);
            $arGenSectorEconomico->setNombre("EDUCATIVO");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(4);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();            
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(4);
            $arGenSectorEconomico->setNombre("INDUSTRIAL");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(5);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();            
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(5);
            $arGenSectorEconomico->setNombre("MINERO Y ENERGETICO");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(6);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();            
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(6);
            $arGenSectorEconomico->setNombre("PROPIEDAD HORIZONTAL");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(7);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();            
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(7);
            $arGenSectorEconomico->setNombre("RESIDENCIAL");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(8);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();            
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(8);
            $arGenSectorEconomico->setNombre("SALUD");
            $manager->persist($arGenSectorEconomico);                
        }
        
        $arGenSectorEconomico = $manager->getRepository('BrasaGeneralBundle:GenSectorEconomico')->find(9);
        if(!$arGenSectorEconomico) {
            $arGenSectorEconomico = new \Brasa\GeneralBundle\Entity\GenSectorEconomico();
            $arGenSectorEconomico->setCodigoSectorEconomicoPk(9);
            $arGenSectorEconomico->setNombre("SERVICIOS");
            $manager->persist($arGenSectorEconomico);                
        }
        $manager->flush();
    }
    
}

