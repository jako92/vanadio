<?php

// src/AppBundle/DataFixtures/ORM/LoadSegDocumentoData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SegPermisoEspecial implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(1);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(1);
            $arSegPermisoEspecial->setNombre("CREAR CONTRATOS DESPUES DE PAGADO EL PERIODO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(2);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(2);
            $arSegPermisoEspecial->setNombre("VER SALARIO EN EMPLEADOS");
            $arSegPermisoEspecial->setTipo("VER");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(3);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(3);
            $arSegPermisoEspecial->setNombre("GENERAR PEDIDOS AUTOMATICOS");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(4);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(4);
            $arSegPermisoEspecial->setNombre("GENERAR PROGRAMACIONES AUTOMATICAS");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(5);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(5);
            $arSegPermisoEspecial->setNombre("MODIFICAR CONTRATO DESPUES DE TERMINADO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(6);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(6);
            $arSegPermisoEspecial->setNombre("GENERAR FACTURAS");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(7);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(7);
            $arSegPermisoEspecial->setNombre("GENERAR SOPORTE PAGO TURNOS");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(8);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(8);
            $arSegPermisoEspecial->setNombre("GENERAR CIERRE MES");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(9);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(9);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR FACTURAS");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(10);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(10);
            $arSegPermisoEspecial->setNombre("MANTENIMIENTO PEDIDO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(11);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(11);
            $arSegPermisoEspecial->setNombre("TERMINAR CONTRATO DESPUES DE PAGADO EL PERIODO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(12);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(12);
            $arSegPermisoEspecial->setNombre("ADICIONALES AL PAGO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(13);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(13);
            $arSegPermisoEspecial->setNombre("COSTOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(14);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(14);
            $arSegPermisoEspecial->setNombre("EMPLEADO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(15);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(15);
            $arSegPermisoEspecial->setNombre("EMPLEADO INGRESOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(16);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(16);
            $arSegPermisoEspecial->setNombre("EMPLEADO PAGOS Y DEDUCCIONES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(17);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(17);
            $arSegPermisoEspecial->setNombre("EMPLEADO ESTUDIOS VENCIMIENTO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(18);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(18);
            $arSegPermisoEspecial->setNombre("EMPLEADO REQUISITOS PENDIENTES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(19);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(19);
            $arSegPermisoEspecial->setNombre("CREDITOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(20);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(20);
            $arSegPermisoEspecial->setNombre("PAGOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(21);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(21);
            $arSegPermisoEspecial->setNombre("PAGOS DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(22);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(22);
            $arSegPermisoEspecial->setNombre("INCAPACIDADES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(23);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(23);
            $arSegPermisoEspecial->setNombre("INCAPACIDADES POR COBRAR");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(24);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(24);
            $arSegPermisoEspecial->setNombre("SEGURIDAD SOCIAL APORTES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(25);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(25);
            $arSegPermisoEspecial->setNombre("CONTRATO FECHA INGRESO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(26);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(26);
            $arSegPermisoEspecial->setNombre("CONTRATO FECHA TERMINACION");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(27);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(27);
            $arSegPermisoEspecial->setNombre("CONTRATO PERIODO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(28);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(28);
            $arSegPermisoEspecial->setNombre("CONTRATO VACACIONES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(29);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(29);
            $arSegPermisoEspecial->setNombre("CONTRATO FECHA VENCIMIENTO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(30);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(30);
            $arSegPermisoEspecial->setNombre("PAGO BANCO PENDIENTES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(31);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(31);
            $arSegPermisoEspecial->setNombre("EXAMENES VENCIMIENTO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(32);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(32);
            $arSegPermisoEspecial->setNombre("EXAMENES POR COBRAR");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(33);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(33);
            $arSegPermisoEspecial->setNombre("PROCESOS DISCIPLINARIOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(34);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(34);
            $arSegPermisoEspecial->setNombre("PERMISOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(35);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(35);
            $arSegPermisoEspecial->setNombre("REGISTROS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CONTABILIDAD");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(36);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(36);
            $arSegPermisoEspecial->setNombre("BALANCE DE PRUEBA");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CONTABILIDAD");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(37);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(37);
            $arSegPermisoEspecial->setNombre("ASPIRANTE INCONSISTENCIA");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(38);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(38);
            $arSegPermisoEspecial->setNombre("SERVICIO POR COBRAR");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(39);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(39);
            $arSegPermisoEspecial->setNombre("DOTACION PENDIENTE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(40);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(40);
            $arSegPermisoEspecial->setNombre("CONTROL ACCESO EMPLEADO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(41);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(41);
            $arSegPermisoEspecial->setNombre("CONTROL ACCESO VISITANTE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(42);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(42);
            $arSegPermisoEspecial->setNombre("RECURSO DISPONIBLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(43);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(43);
            $arSegPermisoEspecial->setNombre("SERVICIO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(44);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(44);
            $arSegPermisoEspecial->setNombre("SERVICIO DETALLE RECURSO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(45);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(45);
            $arSegPermisoEspecial->setNombre("PEDIDO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(46);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(46);
            $arSegPermisoEspecial->setNombre("PEDIDO PENDIENTE FACTURAR");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(47);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(47);
            $arSegPermisoEspecial->setNombre("PROGRAMACION DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(48);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(48);
            $arSegPermisoEspecial->setNombre("COSTO RECURSO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(49);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(49);
            $arSegPermisoEspecial->setNombre("COSTO SERVICIO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(50);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(50);
            $arSegPermisoEspecial->setNombre("CUENTA COBRAR");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(51);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(51);
            $arSegPermisoEspecial->setNombre("ANTICIPO RESUMEN");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(52);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(52);
            $arSegPermisoEspecial->setNombre("ANTICIPO LISTA");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(53);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(53);
            $arSegPermisoEspecial->setNombre("ANTICIPO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(54);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(54);
            $arSegPermisoEspecial->setNombre("RECIBO RESUMEN");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(55);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(55);
            $arSegPermisoEspecial->setNombre("RECIBO LISTA");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(56);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(56);
            $arSegPermisoEspecial->setNombre("RECIBO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(57);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(57);
            $arSegPermisoEspecial->setNombre("NOTA CREDITO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(58);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(58);
            $arSegPermisoEspecial->setNombre("NOTA CREDITO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(59);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(59);
            $arSegPermisoEspecial->setNombre("NOTA DEBITO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(60);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(60);
            $arSegPermisoEspecial->setNombre("NOTA DEBITO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(61);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(61);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR ASIENTOS");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("CONTABILIDAD");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(62);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(62);
            $arSegPermisoEspecial->setNombre("CIERRE ANUAL");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(63);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(63);
            $arSegPermisoEspecial->setNombre("PROVISION");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(64);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(64);
            $arSegPermisoEspecial->setNombre("GENERAR SOPORTE PAGO HORARIO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(65);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(65);
            $arSegPermisoEspecial->setNombre("CONTROL DE ACCESO GENERAR PERIODO HORARIO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(66);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(66);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR PAGO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(67);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(67);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR PROVISION");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(68);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(68);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR LIQUIDACION");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(69);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(69);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR VACACION");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(70);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(70);
            $arSegPermisoEspecial->setNombre("CONTABILIZAR PAGO BANCO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(71);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(71);
            $arSegPermisoEspecial->setNombre("CALENDARIO");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("GENERAL");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(72);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(72);
            $arSegPermisoEspecial->setNombre("GESTOR DE ARCHIVOS");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("GENERAL");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(73);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(73);
            $arSegPermisoEspecial->setNombre("INTERCAMBIO DE DATOS");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("CONTABILIDAD");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(74);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(74);
            $arSegPermisoEspecial->setNombre("GENERAR PERIODO (PROGRAMACION PAGO)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(75);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(75);
            $arSegPermisoEspecial->setNombre("DESCARGA PAGO MASIVO (PAGOS)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(76);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(76);
            $arSegPermisoEspecial->setNombre("CIERRE PROGRAMACION");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(77);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(77);
            $arSegPermisoEspecial->setNombre("APORTES (SEGURIDAD SOCIAL)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(78);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(78);
            $arSegPermisoEspecial->setNombre("DIAN (MEDIOS MAGNETICOS)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(79);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(79);
            $arSegPermisoEspecial->setNombre("DANE");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(80);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(80);
            $arSegPermisoEspecial->setNombre("PROYECCION");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(81);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(81);
            $arSegPermisoEspecial->setNombre("PARAFISCALES (SUPERVIGILANCIA)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(82);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(82);
            $arSegPermisoEspecial->setNombre("NOTIFICACIONES (CARTAS)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(83);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(83);
            $arSegPermisoEspecial->setNombre("CARTA LABORAL (CARTAS)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(84);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(84);
            $arSegPermisoEspecial->setNombre("CERTIFICADO INGRESO RETENCIONES");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(85);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(85);
            $arSegPermisoEspecial->setNombre("HORARIO ACCESO EMPLEADO");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(86);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(86);
            $arSegPermisoEspecial->setNombre("HORARIO ACCESO VISITANTE");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(87);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(87);
            $arSegPermisoEspecial->setNombre("ANALIZAR INCONSISTENCIA");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(88);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(88);
            $arSegPermisoEspecial->setNombre("DESCARGA MASIVA (PROGRAMACION)");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(89);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(89);
            $arSegPermisoEspecial->setNombre("FACTURA DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(90);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(90);
            $arSegPermisoEspecial->setNombre("CONFIGURACION TURNO");
            $arSegPermisoEspecial->setTipo("CONFIGURACION");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(91);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(91);
            $arSegPermisoEspecial->setNombre("CONFIGURACION CARTERA");
            $arSegPermisoEspecial->setTipo("CONFIGURACION");
            $arSegPermisoEspecial->setModulo("CARTERA");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(92);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(92);
            $arSegPermisoEspecial->setNombre("CONFIGURACION NOMINA");
            $arSegPermisoEspecial->setTipo("CONFIGURACION");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(93);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(93);
            $arSegPermisoEspecial->setNombre("CONFIGURACION GENERAL");
            $arSegPermisoEspecial->setTipo("CONFIGURACION");
            $arSegPermisoEspecial->setModulo("GENERAL");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(94);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(94);
            $arSegPermisoEspecial->setNombre("CONCEPTO PAGO CONSOLIDADO (NOM,PRIM,VAC)");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(95);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(95);
            $arSegPermisoEspecial->setNombre("EDITAR FACTURA");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(96);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(96);
            $arSegPermisoEspecial->setNombre("VISITA FECHA VENCIMIENTO");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(97);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(97);
            $arSegPermisoEspecial->setNombre("EMPLEADO GENERAL");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(98);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(98);
            $arSegPermisoEspecial->setNombre("EDITAR PROGRAMACION EN CONSULTA");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("TURNO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(99);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(99);
            $arSegPermisoEspecial->setNombre("CONTRATOS INGRESOS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(100);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(100);
            $arSegPermisoEspecial->setNombre("CUENTA X COBRAR AFILIACION");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(101);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(101);
            $arSegPermisoEspecial->setNombre("CUENTA X COBRAR PILA");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(102);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(102);
            $arSegPermisoEspecial->setNombre("AFILIACIONES PENDIENTES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(103);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(103);
            $arSegPermisoEspecial->setNombre("AFILIACIONES PAGADAS");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(104);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(104);
            $arSegPermisoEspecial->setNombre("GENERAR PERIODO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(105);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(105);
            $arSegPermisoEspecial->setNombre("ACTUALIZAR CLIENTE CARTERA");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(106);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(106);
            $arSegPermisoEspecial->setNombre("EXPORTAR SOPORTE PAGO VALORES NOMINA");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("TURNOS");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(107);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(107);
            $arSegPermisoEspecial->setNombre("PERIODO DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(108);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(108);
            $arSegPermisoEspecial->setNombre("EMPLEADO GENERAL");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(109);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(109);
            $arSegPermisoEspecial->setNombre("CONTRATO GENERAL");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("AFILIACION");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(110);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(110);
            $arSegPermisoEspecial->setNombre("DESBLOQUEAR ASPIRANTE");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(111);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(111);
            $arSegPermisoEspecial->setNombre("ABRIR REQUISICION");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(112);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(112);
            $arSegPermisoEspecial->setNombre("MODIFICAR INFORMACION VACACION");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(113);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(113);
            $arSegPermisoEspecial->setNombre("TERMINAR CONTRATO");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(114);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(114);
            $arSegPermisoEspecial->setNombre("EDITAR PARAMETROS PRIMA");
            $arSegPermisoEspecial->setTipo("PROCESO");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(115);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(115);
            $arSegPermisoEspecial->setNombre("CONFIGURACION NOMINA PARAMETROS PRESTACIONES");
            $arSegPermisoEspecial->setTipo("CONFIGURACION");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(116);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(116);
            $arSegPermisoEspecial->setNombre("PROYECCION PARAMETROS");
            $arSegPermisoEspecial->setTipo("UTILIDAD");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(117);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(117);
            $arSegPermisoEspecial->setNombre("CAPACITACIONES");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(118);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(118);
            $arSegPermisoEspecial->setNombre("CAPACITACIONES DETALLE");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("RECURSO HUMANO");
            $manager->persist($arSegPermisoEspecial);
        }
        $arSegPermisoEspecial = $manager->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find(119);
        if (!$arSegPermisoEspecial) {
            $arSegPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
            $arSegPermisoEspecial->setCodigoPermisoEspecialPk(119);
            $arSegPermisoEspecial->setNombre("KARDEX");
            $arSegPermisoEspecial->setTipo("CONSULTA");
            $arSegPermisoEspecial->setModulo("INVENTARIO");
            $manager->persist($arSegPermisoEspecial);
        }
        $manager->flush();
    }

}
