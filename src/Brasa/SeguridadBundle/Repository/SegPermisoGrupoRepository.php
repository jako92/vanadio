<?php

namespace Brasa\SeguridadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SegPermisoGrupoRepository extends EntityRepository {

    public function asignarPermisosUsuario($codigoGrupo = "", $arUsuario = "") {
        $repuesta = "";
        if ($arUsuario->getCodigoGrupoFk() != "") {
            $em = $this->getEntityManager();
            $arPermisosGrupoDocumentos = new \Brasa\SeguridadBundle\Entity\SegPermisoGrupo();
            $arPermisosGrupoDocumentos = $em->getRepository('BrasaSeguridadBundle:SegPermisoGrupo')->findBy(array('codigoGrupoFk' => $codigoGrupo, 'codigoPermisoEspecialFk' => null));
            $arPermisosGrupoPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoGrupo();
            $arPermisosGrupoPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegPermisoGrupo')->findBy(array('codigoGrupoFk' => $codigoGrupo, 'codigoDocumentoFk' => null));
            foreach ($arPermisosGrupoDocumentos AS $arPermisoGrupoDocumento) {
                $arUsuarioPermisoDocumentoValidar = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                $arUsuarioPermisoDocumentoValidar = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $arUsuario->getId(), 'codigoDocumentoFk' => $arPermisoGrupoDocumento->getCodigoDocumentoFk()));
                if (!$arUsuarioPermisoDocumentoValidar) {
                    $arDocumento = $em->getRepository('BrasaSeguridadBundle:SegDocumento')->find($arPermisoGrupoDocumento->getCodigoDocumentoFk());
                    $arUsuarioPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                    $arUsuarioPermisoDocumento->setDocumentoRel($arDocumento);
                    $arUsuarioPermisoDocumento->setUsuarioRel($arUsuario);
                    $arUsuarioPermisoDocumento->setIngreso($arPermisoGrupoDocumento->getIngreso());
                    $arUsuarioPermisoDocumento->setNuevo($arPermisoGrupoDocumento->getNuevo());
                    $arUsuarioPermisoDocumento->setEditar($arPermisoGrupoDocumento->getEditar());
                    $arUsuarioPermisoDocumento->setEliminar($arPermisoGrupoDocumento->getEliminar());
                    $arUsuarioPermisoDocumento->setAutorizar($arPermisoGrupoDocumento->getAutorizar());
                    $arUsuarioPermisoDocumento->setDesautorizar($arPermisoGrupoDocumento->getDesautorizar());
                    $arUsuarioPermisoDocumento->setAprobar($arPermisoGrupoDocumento->getAprobar());
                    $arUsuarioPermisoDocumento->setDesaprobar($arPermisoGrupoDocumento->getDesaprobar());
                    $arUsuarioPermisoDocumento->setAnular($arPermisoGrupoDocumento->getAnular());
                    $arUsuarioPermisoDocumento->setDesanular($arPermisoGrupoDocumento->getDesanular());
                    $arUsuarioPermisoDocumento->setImprimir($arPermisoGrupoDocumento->getImprimir());
                    $em->persist($arUsuarioPermisoDocumento);
                }
            }
            foreach ($arPermisosGrupoPermisoEspecial AS $arPermisoGrupoPermisoEspecial) {
                $arUsuarioPermisoEspecialValidar = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                $arUsuarioPermisoEspecialValidar = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $arUsuario->getId(), 'codigoPermisoEspecialFk' => $arPermisoGrupoPermisoEspecial->getCodigoPermisoEspecialFk()));
                if (!$arUsuarioPermisoEspecialValidar) {
                    $arPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
                    $arPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find($arPermisoGrupoPermisoEspecial->getCodigoPermisoEspecialFk());
                    $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                    $arUsuarioPermisoEspecial->setPermisoEspecialRel($arPermisoEspecial);
                    $arUsuarioPermisoEspecial->setUsuarioRel($arUsuario);
                    $arUsuarioPermisoEspecial->setPermitir($arPermisoGrupoPermisoEspecial->getPermitir());
                    $em->persist($arUsuarioPermisoEspecial);
                }
            }
            $em->flush();
        } else {
            $respuesta = "Ocurrio un error asignando los permisos";
        }
        return $repuesta;
    }

    public function eliminar($arrSeleccionados) {
        $respuesta = "";
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigo) {
            $arPermisoGrupo = $em->getRepository('BrasaSeguridadBundle:SegPermisoGrupo')->find($codigo);
            $em->remove($arPermisoGrupo);
        }
        $em->flush();

        return $respuesta;
    }

}
