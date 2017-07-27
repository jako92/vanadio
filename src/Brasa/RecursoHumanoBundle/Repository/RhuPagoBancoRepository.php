<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuPagoBancoRepository extends EntityRepository {

    public function listaDQL($strFechaDesde = "", $strFechaHasta = "", $codigoPagoBancoTipo = "", $estadoAutorizado = "", $estadoGenerado = "") {
        $dql = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.codigoPagoBancoPk <> 0";
        if ($strFechaDesde != "") {
            $dql .= " AND pb.fechaAplicacion >= '" . $strFechaDesde . "'";
        }
        if ($strFechaHasta != "") {
            $dql .= " AND pb.fechaAplicacion <= '" . $strFechaHasta . "'";
        }
        if ($codigoPagoBancoTipo != "") {
            $dql .= " AND pb.codigoPagoBancoTipoFk = " . $codigoPagoBancoTipo;
        }
        if ($estadoGenerado == 1) {
            $dql .= " AND pb.estadoGenerado = 1";
        }
        if ($estadoGenerado == '0') {
            $dql .= " AND pb.estadoGenerado = 0";
        }
        if ($estadoAutorizado == 1) {
            $dql .= " AND pb.estadoAutorizado = 1";
        }
        if ($estadoAutorizado == '0') {
            $dql .= " AND pb.estadoAutorizado = 0";
        }
        $dql .= " ORDER BY pb.codigoPagoBancoPk DESC";
        return $dql;
    }

    public function liquidar($codigoPagoBanco) {
        $em = $this->getEntityManager();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        $arPagoBancoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigoPagoBanco));
        $douTotal = 0;
        $intNumeroRegistros = 0;
        foreach ($arPagoBancoDetalles AS $arPagoBancoDetalle) {
            $douTotal += $arPagoBancoDetalle->getVrPago();
            $intNumeroRegistros++;
        }
        $arPagoBanco->setVrTotalPago($douTotal);
        $arPagoBanco->setNumeroRegistros($intNumeroRegistros);
        $em->persist($arPagoBanco);
        $em->flush();
    }

    public function pendientesContabilizarDql() {
        $dql = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.estadoContabilizado = 0 AND pb.estadoAutorizado = 1 AND pb.estadoGenerado = 1 ";
        $dql .= " ORDER BY pb.codigoPagoBancoPk DESC";
        return $dql;
    }

    public function contabilizadosPagoBancoDql($intPagoDesde = 0, $intPagoHasta = 0, $strDesde = "", $strHasta = "") {
        $em = $this->getEntityManager();
        $dql = "SELECT pb FROM BrasaRecursoHumanoBundle:RhuPagoBanco pb WHERE pb.estadoContabilizado = 1";
        if ($intPagoDesde != "" || $intPagoDesde != 0) {
            $dql .= " AND pb.numero >= " . $intPagoDesde;
        }
        if ($intPagoHasta != "" || $intPagoHasta != 0) {
            $dql .= " AND pb.numero <= " . $intPagoHasta;
        }
        if ($strDesde != "" || $strDesde != 0) {
            $dql .= " AND pb.fecha >='" . date_format($strDesde, ('Y-m-d')) . "'";
        }
        if ($strHasta != "" || $strHasta != 0) {
            $dql .= " AND pb.fecha <='" . date_format($strHasta, ('Y-m-d')) . "'";
        }

        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        return $arrayResultado;
    }

    public function anular($codigoPagoBanco) {
        $strResultado = "";
        $em = $this->getEntityManager();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        if ($arPagoBanco->getEstadoAutorizado() == 1 && $arPagoBanco->getEstadoAnulado() == 0 && $arPagoBanco->getNumero() != 0 && $arPagoBanco->getEstadoContabilizado() == 0) {

            $arPagoBancoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle();
            $arPagoBancoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBancoDetalle')->findBy(array('codigoPagoBancoFk' => $codigoPagoBanco));
            if ($arPagoBancoDetalles) {
                foreach ($arPagoBancoDetalles as $arPagoBancoDetalle) {
                    //Validar que el tipo de pago banco sea de tipo vacacion
                    if ($arPagoBanco->getCodigoPagoBancoTipoFk() == 2) {
                        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($arPagoBancoDetalle->getCodigoVacacionFk());
                        $arVacacion->setEstadoPagoBanco(0);
                        $arVacacion->setEstadoPagoGenerado(0);
                    }
                    $arPagoBancoDetalle->setVrPago(0);
                    $em->persist($arPagoBancoDetalle);
                }
            }
            $arPagoBanco->setVrTotalPago(0);
            $arPagoBanco->setEstadoAnulado(1);
            $em->persist($arPagoBanco);
        } else {
            $strResultado = "El pago banco debe estar autorizada e impresa, no puede estar previamente anulada ni contabilizada";
        }
        $em->flush();
        return $strResultado;
    }

}
