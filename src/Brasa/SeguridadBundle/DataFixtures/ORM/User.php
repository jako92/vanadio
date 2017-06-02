<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class User extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arUsers = $manager->getRepository('BrasaSeguridadBundle:User')->find(1);
        if(!$arUsers) {
            $arUsers = new \Brasa\SeguridadBundle\Entity\User();
            $arUsers->setUsername("SOGA");
            $arUsers->setNombreCorto("SOGA");
            $arUsers->setSalt("9f86f39cd7c299efc10a2cbd375ad2d4");
            $arUsers->setPassword("QVMBXCvFHaltPm/ebXycQ7rOvgbgLFfQQvSxZVa/I/FvtsO9Ps9k9B84YP6rC+1aEio86UKY0ZxwsAEV/se7+Q==");
            $arUsers->setEmail("sogaimplementacion@gmail.com");
            $arUsers->setIsActive(1);
            $arUsers->setEmpresa("APPSOGA");
            $arUsers->setRolRel($this->getReference('role-admin'));
            $arUsers->setTareasPendientes(0);
            $arUsers->setCargo("ADMINISTRADOR");
            $manager->persist($arUsers);                
        }              
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 2; // el orden en el cual serán cargados los accesorios
    }
}

