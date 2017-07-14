<?php

// src/AppBundle/DataFixtures/ORM/LoadSegDocumentoData.php

namespace Brasa\SeguridadBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SegDocumento implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(1);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PROGRAMACION PAGO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(2);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PAGO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(3);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("REQUISICION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(4);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SELECCION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(5);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("EXAMEN");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(6);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PAGO EXAMEN");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(7);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("REQUISITOS");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(8);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PAGO BANCO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(9);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("LIQUIDACION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(10);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ADICIONAL PAGO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(11);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("LICENCIA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(12);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("INCAPACIDAD");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(13);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PAGO INCAPACIDAD");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(14);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("VACACIONES");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(15);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CREDITO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(16);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("FACTURA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(17);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("DOTACION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(18);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ACCIDENTE TRABAJO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(19);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("INFORMACION INTERNA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(20);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PROCESO DISCIPLINARIO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(21);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CAPACITACION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(22);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("GESTION DESEMPEÃ‘O");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(23);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTROL ACCESO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(24);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PERMISO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(25);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("COTIZACION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNOS");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(26);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SERVICIO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNOS");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(27);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PEDIDO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNOS");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(28);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PROGRAMACION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNOS");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(29);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("FACTURA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNOS");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(30);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("NOVEDAD");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNOS");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(31);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CENTRO COSTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(32);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("EMPLEADO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(33);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTRATO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(34);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ADICIONAL PAGO FECHA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(35);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ASPIRANTE");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(36);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ESTUDIO/CONTROL");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(37);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CREDITO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(38);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO ESTUDIO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(39);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO EXAMEN");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(40);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("EXAMEN POR CARGO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(41);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO LICENCIA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(42);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CAPACITACION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(43);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CONTRATO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(44);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO PROCESOS DISCIPLINARIOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(45);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CARTAS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(46);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO INFORMACION INTERNA EMPLEADOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(47);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CONCEPTO GESTION DESEMPENO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(48);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONCEPTO PAGO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(49);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO PRUEBA SELECCION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(50);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO ENTREVISTA SELECCION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(51);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO PERMISO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(52);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ACADEMIA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(53);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO ACREDITACION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(54);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CARGOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(55);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONCEPTOS REQUISITOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(56);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("REQUISITOS POR CARGOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(57);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("BANCOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(58);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("DEPARTAMENTO EMPRESA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(59);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("HORARIO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(60);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TURNO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(61);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SUCURSALES APORTES");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(62);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SALUD");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(63);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PENSION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(64);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CAJA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(65);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO SALUD");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(66);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO PENSION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(67);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CLASIFICACION RIESGOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(68);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("RIESGOS PROFESIONALES");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(69);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO DOTACION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(70);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ELEMENTOS DOTACION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(71);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("DOTACION POR CARGO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(72);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ZONA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(73);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SUBZONA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(74);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CLIENTE");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(75);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTRATO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(76);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PUESTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(77);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("RECURSO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(78);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CENTRO COSTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(79);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("GRUPO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(80);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO RECURSO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(81);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TURNO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(82);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PLANTILLA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(83);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PROYECTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(84);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("OPERACION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(85);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PROSPECTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(86);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONCEPTO SERVICIO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(87);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONCEPTO FACTURA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(88);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO NOVEDAD");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(89);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ELEMENTO DOTACION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(90);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TERCEROS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CONTABILIDAD");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(91);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("COMPROBANTES");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CONTABILIDAD");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(92);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CUENTAS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CONTABILIDAD");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(93);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CENTROS COSTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CONTABILIDAD");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(94);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CLIENTE");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(95);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("DOTACION TIPO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(96);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ENTIDAD EXAMEN");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(97);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SEDE");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(98);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("EMBARGO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(99);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("INDUCCION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(100);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ASESORES");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(101);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TERCEROS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(102);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("BANCOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(103);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CUENTAS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(104);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTENIDOS FORMATOS PPAL");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(105);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTENIDOS FORMATOS SEG");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(106);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CLIENTE");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(107);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("RECIBO TIPO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(108);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CUENTA COBRAR TIPO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(109);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("NOTA CREDITO CONCEPTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(110);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("NOTA DEBITO CONCEPTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(111);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TAREAS");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(112);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("REGISTRO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CONTABILIDAD");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(113);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ASIENTOS");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CONTABILIDAD");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(114);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CUENTA COBRAR");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(115);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ANTICIPO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(116);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("RECIBO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(117);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("NOTA CREDITO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(118);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();
            $arSegDocumento->setNombre("NOTA DEBITO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(119);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO FACTURA");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(120);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("VISITAS");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("CARTERA");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(121);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CLIENTE");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(122);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("EMPLEADO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(123);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ENTIDAD ENTRENAMIENTO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(124);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CURSO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(125);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("SUCURSALES");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(126);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CURSO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(127);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PAGO CURSO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(128);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PERIODO SEGURIDAD SOCIAL");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(129);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("NOVEDAD");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(130);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("FACTURA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("AFILIACION");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(131);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("MOTIVO CIERRE SELECCION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(132);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("MOTIVO DESCARTE ASPIRANTE");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(133);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ITEM");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("INVENTARIO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(134);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("MOVIMIENTO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("INVENTARIO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(135);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TERCERO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("INVENTARIO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(136);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("INCAPACIDAD DIAGNOSTICOS");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(137);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("TIPO CONTRATO ADICION");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(138);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTENIDO");
            $arSegDocumento->setTipo("ADMINISTRACION");
            $arSegDocumento->setModulo("GENERAL");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(139);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("ACREDITACION");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(140);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("CONTROL PUESTO");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("TURNO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(141);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("POLIGRAFIA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $arSegDocumento = $manager->getRepository('BrasaSeguridadBundle:SegDocumento')->find(142);
        if(!$arSegDocumento) {
            $arSegDocumento = new \Brasa\SeguridadBundle\Entity\SegDocumento();          
            $arSegDocumento->setNombre("PRUEBA");
            $arSegDocumento->setTipo("MOVIMIENTO");
            $arSegDocumento->setModulo("RECURSO HUMANO");
            $manager->persist($arSegDocumento);                
        }
        $manager->flush();
    }
    
}

