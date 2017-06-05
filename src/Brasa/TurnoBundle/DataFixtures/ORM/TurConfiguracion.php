<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\TurnoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TurConfiguracion implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {        
        $arTurConfiguracion = $manager->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        if(!$arTurConfiguracion) {
            $arTurConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
            $arTurConfiguracion->setCodigoConfiguracionPk(1);
            $arTurConfiguracion->setInformacionLegalFactura("Esta factura de venta es un titulo valor negociable (Ley 1231 de 2008) y se asimila en sus efectos legales a una letra de cambio segun articulo 779 del Codigo de Comercio. Acepto el contenido y las condiciones estipuladas en esta factura de venta (Ley 1231/17 de julio de 2008). Se cobran intereses por mora a la tasa máxima legal vigente.\"\r\n* NO SOMOS GRANDES CONTRUBUYENTES NI RETENEDORES DEL IVA. COD ICA 302 TARIFA 3/1000','REALIZAR PAGO EN LA CUENTA CORRIENTE BANCOLOMBIA NUMERO 1111111111 A NOMBRE DE EMPRESA LTDA");
            $arTurConfiguracion->setInformacionPagoFactura("CRA  MEDELLIN TEL 4444444 e-mail: empresa@gmail.com");
            $arTurConfiguracion->setInformacionResolucionDianFactura("Numeracion de 0001 al 1000 de Factura de Venta, autorizada por la DIAN\r\nResolucion: 110000627560 de 2015/05/05. Tipo 02. Factura Computador.");
            $arTurConfiguracion->setInformacionResolucionSupervigilanciaFactura("Resolucion Supervigilancia 20137200053817 del 4 Agosto 2013");
            $manager->persist($arTurConfiguracion);                
        }
        $manager->flush();
    }
    
}

