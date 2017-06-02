<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SegRoles extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $arSegRoles = $manager->getRepository('BrasaSeguridadBundle:SegRoles')->find("ROLE_ADMIN");
        if(!$arSegRoles) {
            $arSegRoles = new \Brasa\SeguridadBundle\Entity\SegRoles();
            $arSegRoles->setCodigoRolPk("ROLE_ADMIN");
            $arSegRoles->setNombre("ADMINISTRADOR");
            $manager->persist($arSegRoles);                
        }
        $this->addReference('role-admin', $arSegRoles);
        $arSegRoles = $manager->getRepository('BrasaSeguridadBundle:SegRoles')->find("ROLE_USER");
        if(!$arSegRoles) {
            $arSegRoles = new \Brasa\SeguridadBundle\Entity\SegRoles();
            $arSegRoles->setCodigoRolPk("ROLE_USER");
            $arSegRoles->setNombre("USUARIO");
            $manager->persist($arSegRoles);                
        }   
        $manager->flush();
        $this->addReference('role-user', $arSegRoles);
    }

    public function getOrder()
    {
        return 1; // el orden en el cual serán cargados los accesorios
    }
}

