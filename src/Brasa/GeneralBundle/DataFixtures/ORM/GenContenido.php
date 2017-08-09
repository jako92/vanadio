<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GenContenido implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(1);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(1);
            $arContenido->setTitulo("CERTIFICADO VACACIONES");
            $arContenido->setNombre("CERTIFICADO VACACIONES");
            $arContenido->setEtiqueta("#a = Empleado
                                       #b = Fecha actual
                                       #c = Fecha inicia vacaciones
                                       #d = Fecha termina vacaciones
                                       #e = Dia que inicia labores");
            $manager->persist($arContenido);
        } else {
            $arContenido->setTitulo("CERTIFICADO VACACIONES");
            $arContenido->setNombre("CERTIFICADO VACACIONES");
            $arContenido->setEtiqueta("#a = Empleado
                                       #b = Fecha actual
                                       #c = Fecha inicia vacaciones
                                       #d = Fecha termina vacaciones
                                       #e = Dia que inicia labores");
            $manager->persist($arContenido);
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(2);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(2);
            $arContenido->setTitulo("NOTIFICACION CAMBIO SALARIO");
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
            $arContenido->setTitulo("NOTIFICACION CAMBIO SALARIO");
            $arContenido->setNombre("NOTIFICACION CAMBIO SALARIO");
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
                                       #2 = Año para el cual se ejecuta el cambio de salario Ej: 2017
                                       #3 = Fecha inicio Ej: 1 de marzo de 2017
                                       #4 = Valor nuevo salario
                                       #5 = Nombre empresa
                                       #6 = Nombre empleado
                                       #7 = Cargo empleado");
            $manager->persist($arContenido);
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(3);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(3);
            $arContenido->setTitulo("ACEPTACION RENUNCIA VOLUNTARIA");
            $arContenido->setNombre("ACEPTACION RENUNCIA VOLUNTARIA");
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
                                       #2 = Fecha retiro Ej: 1 de marzo de 2017
                                       #3 = Nombre empresa
                                       #4 = Nombre empleado
                                       #5 = Cargo empleado");
            $manager->persist($arContenido);
        } else {
            $arContenido->setTitulo("ACEPTACION RENUNCIA VOLUNTARIA");
            $arContenido->setNombre("ACEPTACION RENUNCIA VOLUNTARIA");
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
                                       #2 = Fecha retiro Ej: 1 de marzo de 2017
                                       #3 = Nombre empresa
                                       #4 = Nombre empleado
                                       #5 = Cargo empleado");
            $manager->persist($arContenido);
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(4);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(4);
            $arContenido->setTitulo("REMISIÓN EXAMEN MEDICO DE EGRESO");
            $arContenido->setNombre("REMISIÓN EXAMEN MEDICO DE EGRESO");
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
                                       #2 = Fecha retiro Ej: 1 de marzo de 2017
                                       #3 = Nombre empresa
                                       #4 = Nombre empleado
                                       #5 = Cargo empleado
                                       #6 = Documento empleado");
            $manager->persist($arContenido);
        } else {
            $arContenido->setTitulo("REMISIÓN EXAMEN MEDICO DE EGRESO");
            $arContenido->setNombre("REMISIÓN EXAMEN MEDICO DE EGRESO");
            $arContenido->setEtiqueta("#1 = Fecha actual Ej: 7 de marzo de 2017
                                       #2 = Fecha retiro Ej: 1 de marzo de 2017
                                       #3 = Nombre empresa
                                       #4 = Nombre empleado
                                       #5 = Cargo empleado
                                       #6 = Documento empleado");
            $manager->persist($arContenido);
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(5);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(5);
            $arContenido->setTitulo("CERTIFICADO LABORAL HISTORICO");
            $arContenido->setNombre("CERTIFICADO LABORAL HISTORICO");
            $arContenido->setEtiqueta("#1 = NOMBRE EMPRESA
                                       #2 = NOMBRE EMPLEADO
                                       #3 = IDENTIFICACION EMPLEADO
                                       #4 = CIUDAD EXPEDIDICON");
            $manager->persist($arContenido);
        } else {
            $arContenido->setTitulo("CERTIFICADO LABORAL HISTORICO");
            $arContenido->setNombre("CERTIFICADO LABORAL HISTORICO");
            $arContenido->setEtiqueta("#1 = NOMBRE EMPRESA
                                       #2 = NOMBRE EMPLEADO
                                       #3 = IDENTIFICACION EMPLEADO
                                       #4 = CIUDAD EXPEDIDICON");
            $manager->persist($arContenido);
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(6);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(6);
            $arContenido->setTitulo("ORDEN DE EXAMEN MEDICO");
            $arContenido->setNombre("ORDEN DE EXAMEN MEDICO");
            $manager->persist($arContenido);
        } else {
            $arContenido->setTitulo("CERTIFICADO LABORAL HISTORICO");
            $arContenido->setNombre("CERTIFICADO LABORAL HISTORICO");
            $manager->persist($arContenido);
        }

        $arContenido = $manager->getRepository('BrasaGeneralBundle:GenContenido')->find(7);
        if (!$arContenido) {
            $arContenido = new \Brasa\GeneralBundle\Entity\GenContenido();
            $arContenido->setCodigoContenidoPk(7);
            $arContenido->setTitulo("REQUISITOS");
            $arContenido->setNombre("REQUISITOS");
            $arContenido->setEtiqueta("#1 = NOMBRE COMPLETO"
                    . " #2 = NUMERO IDENTIFICACION"
                    . " #3 = NOMBRE EMPRESA");
            $manager->persist($arContenido);
        } else {
            $arContenido->setTitulo("REQUISITOS");
            $arContenido->setNombre("REQUISITOS");
            $arContenido->setEtiqueta("#1 = NOMBRE COMPLETO"
                    . " #2 = NUMERO IDENTIFICACION"
                    . " #3 = NOMBRE EMPRESA");
            $manager->persist($arContenido);
        }

        $manager->flush();
    }

}
