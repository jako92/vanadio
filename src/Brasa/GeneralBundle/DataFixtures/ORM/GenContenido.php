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
            $arContenido->setEtiqueta("#a = Empleado
#b = Fecha actual
#c = Fecha inicia vacaciones
#d = Fecha termina vacaciones
#e = Dia que inicia labores");            
            $manager->persist($arContenido);                
        } else {
            $arContenido->setEtiqueta("#a = Empleado
#b = Fecha actual
#c = Fecha inicia vacaciones
#d = Fecha termina vacaciones
#e = Dia que inicia labores");            
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(2);
        if(!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(2);
            $arContenido->setNombre("NOTIFICACION CAMBIO SALARIO");
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
#2 = Año para el cual se ejecuta el cambio de salario Ej: 2017
#3 = Fecha inicio Ej: 1 de marzo de 2017
#4 = Valor nuevo salario
#5 = Nombre empresa
#6 = Nombre empleado
#7 = Cargo empleado");            
            $manager->persist($arContenido);                
        } else {
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
#2 = Año para el cual se ejecuta el cambio de salario Ej: 2017
#3 = Fecha inicio Ej: 1 de marzo de 2017
#4 = Valor nuevo salario
#5 = Nombre empresa
#6 = Nombre empleado
#7 = Cargo empleado");
        }        
        
        $manager->flush();
    }
}

