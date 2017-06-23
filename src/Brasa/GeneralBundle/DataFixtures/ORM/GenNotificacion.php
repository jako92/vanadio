<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenNotificacion implements FixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $databaseDriver = $this->container->getParameter('database_driver');
        if ($databaseDriver == "pdo_mysql") {
            $strSql = "DROP TRIGGER IF EXISTS nueva_notificacion;
                   CREATE TRIGGER nueva_notificacion BEFORE INSERT ON gen_notificacion
                    FOR EACH ROW BEGIN
                        UPDATE users SET  notificaciones_pendientes = (notificaciones_pendientes + 1) WHERE id = NEW.codigo_usuario_fk;
                    END";
            $manager->getConnection()->executeQuery($strSql);
        }
        if ($databaseDriver == "pdo_sqlsrv") {
            $strSql = "";
            //$manager->getConnection()->executeQuery($strSql);
        }
    }

}
