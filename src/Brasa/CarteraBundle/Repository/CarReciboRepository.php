<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CarReciboRepository extends EntityRepository {

    public function listaDql($numero, $codigoCliente = "", $boolEstadoImpreso = "") {
        $dql = "SELECT r FROM BrasaCarteraBundle:CarRecibo r WHERE r.codigoReciboPk <> 0";
        if ($numero != "") {
            $dql .= " AND r.numero = " . $numero;
        }
        if ($codigoCliente != "") {
            $dql .= " AND r.codigoClienteFk = " . $codigoCliente;
        }
        if ($boolEstadoImpreso == 1) {
            $dql .= " AND r.estadoImpreso = 1";
        }
        if ($boolEstadoImpreso == "0") {
            $dql .= " AND r.estadoImpreso = 0";
        }
        $dql .= " ORDER BY r.fecha DESC";
        return $dql;
    }

    public function listaConsultaDql($numero = "", $codigoCliente = "", $codigoReciboTipo = "", $strFechaDesde = "", $strFechaHasta = "") {
        $dql = "SELECT r FROM BrasaCarteraBundle:CarRecibo r WHERE r.codigoReciboPk <> 0 ";
        if ($numero != "") {
            $dql .= " AND r.numero = " . $numero;
        }
        if ($codigoCliente != "") {
            $dql .= " AND r.codigoClienteFk = " . $codigoCliente;
        }
        if ($codigoReciboTipo != "") {
            $dql .= " AND r.codigoReciboTipoFk = " . $codigoReciboTipo;
        }
        if ($strFechaDesde != "") {
            $dql .= " AND r.fecha >='" . $strFechaDesde . "'";
        }
        if ($strFechaHasta != "") {
            $dql .= " AND r.fecha <='" . $strFechaHasta . "'";
        }
        return $dql;
    }

    public function imprimir($codigo) {
        $em = $this->getEntityManager();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
        $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigo);
        if ($arRecibo->getEstadoAutorizado() == 1) {
            if ($arRecibo->getNumero() == 0) {
                $intNumero = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->consecutivo(1);
                $arRecibo->setNumero($intNumero);
                $arRecibo->setEstadoImpreso(1);
                $em->persist($arRecibo);
                $em->flush();
            }
        } else {
            $strResultado = "Debe autorizar la cotizacion para imprimirla";
        }
        return $strResultado;
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                if ($em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigo) <= 0) {
                    $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigo);
                    if ($arRecibo->getEstadoAutorizado() == 0) {
                        $em->remove($arRecibo);
                    }
                }
            }
            $em->flush();
        }
    }
    
    public function contabilizar($arrSeleccionados) {
        $em = $this->getEntityManager();
        $respuesta = "";
        if (count($arrSeleccionados) > 0) {
            $arConfiguracion = new \Brasa\CarteraBundle\Entity\CarConfiguracion();            
            $arConfiguracion = $em->getRepository('BrasaCarteraBundle:CarConfiguracion')->find(1);
            foreach ($arrSeleccionados AS $codigo) {
                $arRecibo = new \Brasa\CarteraBundle\Entity\CarRecibo();
                $arRecibo = $em->getRepository('BrasaCarteraBundle:CarRecibo')->find($codigo);
                if ($arRecibo->getEstadoAutorizado == 1 && $arRecibo->getEstadoContabilizado() == 0 && $arRecibo->getNumero() != 0) {
                    $arTercero = $em->getRepository('BrasaContabilidadBundle:CtbTercero')->findOneBy(array('numeroIdentificacion' => $arRecibo->getClienteRel()->getNit()));
                    if (count($arTercero) <= 0) {
                        $arTercero = new \Brasa\ContabilidadBundle\Entity\CtbTercero();
                        $arTercero->setCiudadRel($arRecibo->getClienteRel()->getCiudadRel());
                        $arTercero->setTipoIdentificacionRel($arRecibo->getClienteRel()->getTipoIdentificacionRel());
                        $arTercero->setNumeroIdentificacion($arRecibo->getClienteRel()->getNit());
                        $arTercero->setDigitoVerificacion($arRecibo->getClienteRel()->getDigitoVerificacion());
                        $arTercero->setNombreCorto($arRecibo->getClienteRel()->getNombreCorto());
                        $arTercero->setNombre1($arRecibo->getClienteRel()->getNombre1());
                        $arTercero->setNombre2($arRecibo->getClienteRel()->getNombre2());
                        $arTercero->setApellido1($arRecibo->getClienteRel()->getApellido1());
                        $arTercero->setApellido2($arRecibo->getClienteRel()->getApellido2());
                        $arTercero->setDireccion($arRecibo->getClienteRel()->getDireccion());
                        $arTercero->setTelefono($arRecibo->getClienteRel()->getTelefono());
                        $arTercero->setCelular($arRecibo->getClienteRel()->getCelular());
                        $arTercero->setEmail($arFactura->getClienteRel()->getEmail());
                        $em->persist($arTercero);
                    }
                    $arComprobanteContable = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->find('');
                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                }
            }
        }
    }
    
    public function listaPendienteContabilizarDql($numeroRecibo = "", $boolEstadoAutorizado = "", $strFechaDesde = "", $strFechaHasta = "", $boolEstadoAnulado = "") {
        $dql = "SELECT r FROM BrasaCarteraBundle:CarRecibo r WHERE r.estadoContabilizado = 0 AND r.numero > 0";
        if ($numeroRecibo != "") {
            $dql .= " AND r.numero = " . $numeroRecibo;
        }
        if ($boolEstadoAutorizado == 1) {
            $dql .= " AND r.estadoAutorizado = 1";
        }
        if ($boolEstadoAutorizado == "0") {
            $dql .= " AND r.estadoAutorizado = 0";
        }
        if ($boolEstadoAnulado == 1) {
            $dql .= " AND r.estadoAnulado = 1";
        }
        if ($boolEstadoAnulado == "0") {
            $dql .= " AND r.estadoAnulado = 0";
        }
        if ($strFechaDesde != "") {
            $dql .= " AND r.fecha >= '" . $strFechaDesde . "";
        }
        if ($strFechaHasta != "") {
            $dql .= " AND r.fecha <= '" . $strFechaHasta . "";
        }
        $dql .= " ORDER BY r.codigoReciboTipoFk, r.fecha DESC, r.numero DESC";
        return $dql;
    }

}
