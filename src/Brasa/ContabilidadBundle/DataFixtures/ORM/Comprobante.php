<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\ContabilidadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Comprobante implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arComprobante = $manager->getRepository('BrasaContabilidadBundle:CtbComprobante')->find(1);
        if(!$arComprobante) {
            $arComprobante = new \Brasa\ContabilidadBundle\Entity\CtbComprobante();
            $arComprobante->setCodigoComprobantePk(1);
            $arComprobante->setNombre("INGRESOS");         
            $manager->persist($arComprobante);          
        }
        $manager->flush();
        
    }
}

