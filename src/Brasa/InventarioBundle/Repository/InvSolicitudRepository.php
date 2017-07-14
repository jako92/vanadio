<?php

namespace Brasa\InventarioBundle\Repository;

/**
 * InvSolicitudRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvSolicitudRepository extends \Doctrine\ORM\EntityRepository {

    public function listaDql($strCodigo = '', $srtNumero = '') {
        $dql = "SELECT s FROM BrasaInventarioBundle:InvSolicitud s WHERE s.codigoSolicitudPk <> 0";
        if ($strCodigo != "") {
            $dql .= " AND s.codigoSolicitudPk = '" . $strCodigo . "'";
        }
        if ($srtNumero != "") {
            $dql .= " AND s.numero = '" . $srtNumero . "'";
        }
        return $dql;
    }

    public function eliminar($arrSeleccionados = '') {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoSolicitud) {
                $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
                $em->remove($arSolicitud);
                $em->flush();
            }
        }
    }
    
    public function autorizar($codigoSolicitud) {
        $em = $this->getEntityManager();
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
        $arSolicitudDetalle = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
        $arSolicitudDetalle = $em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->findBy(array('codigoSolicitudFk' => $codigoSolicitud));
        $strResultado = "";
        if ($arSolicitudDetalle) {
            if ($arSolicitud->getEstadoAutorizado() == 0) {
                $arSolicitud->setEstadoAutorizado(1);
                $em->persist($arSolicitud);
                $em->flush();
            } else {
                $strResultado = "Ya esta autorizado";
            }
        }else{
            $strResultado = "La solicitud no tiene detalles";
        }
        return $strResultado;
    }

    public function desAutorizar($codigoSolicitud) {
        $em = $this->getEntityManager();
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
        $strResultado = "";
        if ($arSolicitud->getEstadoAutorizado() == 1 && $arSolicitud->getEstadoImpreso() == 0) {
            $arSolicitud->setEstadoAutorizado(0);
            $em->persist($arSolicitud);
            $em->flush();
        } else {
            $strResultado = "No se puede des-autorizar la solicitud si esta impresa";
        }
        return $strResultado;
    }
    
    public function imprimir($codigoSolicitud) {
        $em = $this->getEntityManager();
        $respuesta = "";
        if ($respuesta == "") {
            $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
            $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
            $arSolicitudDocumento = new \Brasa\InventarioBundle\Entity\InvSolicitudDocumento();
            $arSolicitudDocumento = $em->getRepository('BrasaInventarioBundle:InvSolicitudDocumento')->find($arSolicitud->getCodigoSolicitudDocumentoFk());
            if ($arSolicitud->getNumero() <= 0) {
                $consecutivo = $arSolicitudDocumento->getConsecutivo();
                $arSolicitudDocumento->setConsecutivo($consecutivo + 1);
                $em->persist($arSolicitudDocumento);
                $arSolicitud->setNumero($consecutivo);
            }
            $arSolicitud->setEstadoImpreso(1);
            $em->persist($arSolicitud);
            $em->flush();
        }
        return $respuesta;
    }
    
    public function liquidar($codigoSolicitud) {
        $em = $this->getEntityManager();
        $arSolicitud = new \Brasa\InventarioBundle\Entity\InvSolicitud();
        $arSolicitud = $em->getRepository('BrasaInventarioBundle:InvSolicitud')->find($codigoSolicitud);
        $subtotal = 0;
        $iva = 0;
        $total = 0;
        $arSolicitudDetalle = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
        $arSolicitudDetalle = $em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->findBy(array('codigoSolicitudFk' => $codigoSolicitud));
        foreach ($arSolicitudDetalle as $arSolicitudDetalle) {
            $ordenCompraDetalleAct = new \Brasa\InventarioBundle\Entity\InvSolicitudDetalle();
            $ordenCompraDetalleAct = $em->getRepository('BrasaInventarioBundle:InvSolicitudDetalle')->find($arSolicitudDetalle->getCodigoDetalleSolicitudPk());
            $subtotalDetalle = $arSolicitudDetalle->getValor() * $arSolicitudDetalle->getCantidad();
            $ivaDetalle = ($subtotalDetalle * $ordenCompraDetalleAct->getPorcentajeIva()) / 100;
            $totalDetalle = $subtotalDetalle + $ivaDetalle;
            $ordenCompraDetalleAct->setVrIva($ivaDetalle);
            $ordenCompraDetalleAct->setVrSubtotal($subtotalDetalle);
            $ordenCompraDetalleAct->setVrTotal($totalDetalle);
            $em->persist($ordenCompraDetalleAct);
            $subtotal += $subtotalDetalle;
            $iva += $ivaDetalle;
            $total += $totalDetalle;
        }

        $subtotal = round($subtotal);
        $iva = round($iva);
        $totalNeto = $subtotal + $iva;
        $arSolicitud->setVrSubtotal($subtotal);
        $arSolicitud->setVrIva($iva);
        $arSolicitud->setVrNeto($totalNeto);
        $em->persist($arSolicitud);
        $em->flush();
        return true;
    }

}