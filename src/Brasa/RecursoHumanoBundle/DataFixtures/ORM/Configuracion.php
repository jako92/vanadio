<?php

// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace Brasa\RecursoHumanoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Configuracion implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arRhuConfiguracion = $manager->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        if(!$arRhuConfiguracion) {
            $arRhuConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
            $arRhuConfiguracion->setCodigoConfiguracionPk(1);
            $arRhuConfiguracion->setVrSalario(737717);
            $arRhuConfiguracion->setCodigoAuxilioTransporte(1);
            $arRhuConfiguracion->setVrAuxilioTransporte(83140);
            $arRhuConfiguracion->setCodigoCredito(1);
            $arRhuConfiguracion->setCodigoSeguro(1);
            $arRhuConfiguracion->setCodigoCredito(1);
            $arRhuConfiguracion->setCodigoTiempoSuplementario(1);
            $arRhuConfiguracion->setCodigoHoraDiurnaTrabajada(1);
            $arRhuConfiguracion->setCodigoSalarioIntegral(1);
            $arRhuConfiguracion->setPorcentajePensionExtra(1);
            $arRhuConfiguracion->setCodigoIncapacidad(1);
            $arRhuConfiguracion->setAnioActual(2017);
            $arRhuConfiguracion->setPorcentajeIva(16);
            $arRhuConfiguracion->setCodigoRetencionFuente(1);
            $arRhuConfiguracion->setEdadMinimaEmpleado(18);
            $arRhuConfiguracion->setPorcentajeBonificacionNoPrestacional(40);
            $arRhuConfiguracion->setCodigoEntidadExamenIngreso(1);
            $arRhuConfiguracion->setCodigoComprobantePagoNomina(1);
            $arRhuConfiguracion->setCodigoComprobanteProvision(1);
            $arRhuConfiguracion->setCodigoComprobanteLiquidacion(1);
            $arRhuConfiguracion->setCodigoComprobanteVacacion(0);
            $arRhuConfiguracion->setCodigoComprobantePagoBanco(1);
            $arRhuConfiguracion->setControlPago(0);
            $arRhuConfiguracion->setPrestacionesPorcentajeCesantias("8.33");
            $arRhuConfiguracion->setPrestacionesPorcentajeInteresesCesantias(0);
            $arRhuConfiguracion->setPrestacionesPorcentajePrimas(1);
            $arRhuConfiguracion->setPrestacionesPorcentajeVacaciones("8.33");
            $arRhuConfiguracion->setPrestacionesPorcentajeAporteVacaciones("4.17");
            $arRhuConfiguracion->setPrestacionesPorcentajeIndemnizacion(30);
            $arRhuConfiguracion->setAportesPorcentajeCaja(4);
            $arRhuConfiguracion->setAportesPorcentajeVacaciones(0);
            //$arRhuConfiguracion->setCuentaPago(1);
            $arRhuConfiguracion->setCodigoHoraDescanso(1);
            $arRhuConfiguracion->setCodigoHoraNocturna(1);
            $arRhuConfiguracion->setCodigoHoraFestivaDiurna(1);
            $arRhuConfiguracion->setCodigoHoraFestivaNocturna(1);
            $arRhuConfiguracion->setCodigoHoraExtraOrdinariaDiurna(1);
            $arRhuConfiguracion->setCodigoHoraExtraOrdinariaNocturna(1);
            $arRhuConfiguracion->setCodigoHoraExtraFestivaDiurna(1);
            $arRhuConfiguracion->setCodigoHoraExtraFestivaNocturna(1);
            $arRhuConfiguracion->setCodigoHoraRecargoNocturno(1);
            $arRhuConfiguracion->setCodigoHoraRecargoFestivoDiurno(1);
            $arRhuConfiguracion->setCodigoHoraRecargoFestivoNocturno(1);
            $arRhuConfiguracion->setCodigoVacacion(1);
            $arRhuConfiguracion->setCodigoAjusteDevengado(1);
            $arRhuConfiguracion->setAfectaVacacionesParafiscales(1);
            $arRhuConfiguracion->setCodigoFormatoPago(1);
            $arRhuConfiguracion->setCodigoFormatoLiquidacion(1);
            $arRhuConfiguracion->setCodigoFormatoCarta(0);
            $arRhuConfiguracion->setCodigoFormatoDisciplinario(0);
            $arRhuConfiguracion->setCodigoFormatoDescargo(0);
            $arRhuConfiguracion->setCodigoFormatoFactura(0);
            $arRhuConfiguracion->setTipoBasePagoVacaciones(3);
            $arRhuConfiguracion->setGeneraPorcetnajeLiquidacion(0);
            $arRhuConfiguracion->setImprimirMensajePago(1);
            $arRhuConfiguracion->setCodigoPrima(1);
            $arRhuConfiguracion->setCodigoCesantia(0);
            $arRhuConfiguracion->setCodigoInteresCesantia(0);
            $arRhuConfiguracion->setNitSena(0);
            $arRhuConfiguracion->setNitIcbf(0);
            $arRhuConfiguracion->setHorasDomingoNoCompensado(0);
            $arRhuConfiguracion->setHorasDomingoCompensado(0);
            $arRhuConfiguracion->setHorasRecargoNocturnoFestivoCompensado(0);
            $arRhuConfiguracion->setHorasRecargoNocturnoFestivoNoCompensado(0);
            $arRhuConfiguracion->setHorasExtraDominicalDiurna(0);
            $manager->persist($arRhuConfiguracion);                
        }
        $manager->flush();
        
    }
}

