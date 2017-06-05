<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenTipoIdentificacion implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

          /*$arTipoIdentificacion = $manager->getRepository('BrasaGeneralBundle:GenTipoIdentificacion')->find(1);
        if(!$arTipoIdentificacion) {
            $arTipoIdentificacion = new \Brasa\GeneralBundle\Entity\GenTipoIdentificacion();
            $arTipoIdentificacion->setCodigoTipoIdentificacionPk(1);
            $arTipoIdentificacion->setNombre("");
            $arTipoIdentificacion->setCodigoInterface("");
            $manager->persist($arTipoIdentificacion);                
        }
        $manager->flush();*/
    }
    
}

