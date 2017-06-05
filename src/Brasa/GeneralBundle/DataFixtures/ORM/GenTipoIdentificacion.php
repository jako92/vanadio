<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenTipoIdentificacion extends AbstractFixture implements OrderedFixtureInterface
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

    public function getOrder()
    {
        return 3; // el orden en el cual serán cargados los accesorios
    }
}

