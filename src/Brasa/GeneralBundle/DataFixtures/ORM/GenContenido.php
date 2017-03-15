<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenContenido implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(1);
        if(!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(1);
            $arContenido->setNombre("CERTIFICADO VACACIONES");
            $manager->persist($arContenido);                
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(2);
        if(!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(2);
            $arContenido->setNombre("NOTIFICACION CAMBIO SALARIO");
            $manager->persist($arContenido);                
        }        
        
        $manager->flush();
    }
}

