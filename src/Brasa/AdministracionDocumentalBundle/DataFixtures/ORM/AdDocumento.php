<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\AdministracionDocumentalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AdDocumento implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(1);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(1);
            $arDocumento->setNombre("SELECCION");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(2);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(2);
            $arDocumento->setNombre("SELECCION PRUEBAS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(3);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(3);
            $arDocumento->setNombre("EMPLEADOS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(4);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(4);
            $arDocumento->setNombre("CONTRATOS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(5);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(5);
            $arDocumento->setNombre("PROCESO DISCIPLINARIO");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(6);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(6);
            $arDocumento->setNombre("EXAMENES");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(7);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(7);
            $arDocumento->setNombre("PAGOS BANCO");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(8);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(8);
            $arDocumento->setNombre("DOTACION");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(9);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(9);
            $arDocumento->setNombre("EMPLEADO ESTUDIOS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(10);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(10);
            $arDocumento->setNombre("EMPLEADO EXAMENES");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(11);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(11);
            $arDocumento->setNombre("SELECCION VISITAS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(12);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(12);
            $arDocumento->setNombre("PERMISOS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(13);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(13);
            $arDocumento->setNombre("CARTAS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(14);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(14);
            $arDocumento->setNombre("SELECCION ENTREVISTAS");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(15);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(15);
            $arDocumento->setNombre("CURSOS (AFI)");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(16);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(16);
            $arDocumento->setNombre("EMPLEADO (AFI)");
            $manager->persist($arDocumento);                
        }
        
        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(17);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(17);
            $arDocumento->setNombre("CLIENTES (AFI)");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(18);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(18);
            $arDocumento->setNombre("ASPIRANTES");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(19);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(19);
            $arDocumento->setNombre("REQUISICION");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(20);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(20);
            $arDocumento->setNombre("CAPACITACIONES");
            $manager->persist($arDocumento);                
        }

        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(21);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(21);
            $arDocumento->setNombre("ACREDITACION");
            $manager->persist($arDocumento);                
        }    
        $arDocumento = $manager->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find(22);
        if(!$arDocumento) {
            $arDocumento = new \Brasa\AdministracionDocumentalBundle\Entity\AdDocumento();
            $arDocumento->setCodigoDocumentoPk(22);
            $arDocumento->setNombre("REQUISITOS");
            $manager->persist($arDocumento);                
        }  
        $manager->flush();
    }
}

