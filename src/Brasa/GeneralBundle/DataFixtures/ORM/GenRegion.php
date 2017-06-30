<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenRegion implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arPais = $manager->getRepository('BrasaGeneralBundle:GenPais')->find(169);
        $arRegion = $manager->getRepository('BrasaGeneralBundle:GenRegion')->find(1);
        if(!$arRegion) {
            $arRegion = new \Brasa\GeneralBundle\Entity\GenRegion();
            $arRegion->setCodigoRegionPk(1);
            $arRegion->setPaisRel($arPais);
            $arRegion->setNombre("REGION ANDINA");
            $arRegion->setCodigoInterface(2);
            $manager->persist($arRegion);                
        }
        $arRegion = $manager->getRepository('BrasaGeneralBundle:GenRegion')->find(2);
        if(!$arRegion) {
            $arRegion = new \Brasa\GeneralBundle\Entity\GenRegion();
            $arRegion->setCodigoRegionPk(2);
            $arRegion->setPaisRel($arPais);
            $arRegion->setNombre("REGION AMAZONIA");
            $arRegion->setCodigoInterface(5);
            $manager->persist($arRegion);                
        }
        $arRegion = $manager->getRepository('BrasaGeneralBundle:GenRegion')->find(3);
        if(!$arRegion) {
            $arRegion = new \Brasa\GeneralBundle\Entity\GenRegion();
            $arRegion->setCodigoRegionPk(3);
            $arRegion->setPaisRel($arPais);
            $arRegion->setNombre("REGION ATLANTICO");
            $arRegion->setCodigoInterface(1);
            $manager->persist($arRegion);                
        }
        $arRegion = $manager->getRepository('BrasaGeneralBundle:GenRegion')->find(4);
        if(!$arRegion) {
            $arRegion = new \Brasa\GeneralBundle\Entity\GenRegion();
            $arRegion->setCodigoRegionPk(4);
            $arRegion->setPaisRel($arPais);
            $arRegion->setNombre("REGION ORINOQUIA");
            $arRegion->setCodigoInterface(4);
            $manager->persist($arRegion);               
        }
        $arRegion = $manager->getRepository('BrasaGeneralBundle:GenRegion')->find(5);
        if(!$arRegion) {
            $arRegion = new \Brasa\GeneralBundle\Entity\GenRegion();
            $arRegion->setCodigoRegionPk(5);
            $arRegion->setPaisRel($arPais);
            $arRegion->setNombre("REGION PACIFICA");
            $arRegion->setCodigoInterface(3);
            $manager->persist($arRegion);                
        }
        
        $manager->flush();
    }
}

