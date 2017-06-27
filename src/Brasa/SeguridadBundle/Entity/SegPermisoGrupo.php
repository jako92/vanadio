<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="seg_permiso_grupo")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegPermisoGrupoRepository")
 */
class SegPermisoGrupo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoGrupoPk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer", nullable=true)
     */
    private $codigoDocumentoFk;

    /**
     * @ORM\Column(name="codigo_permiso_especial_fk", type="integer", nullable=true)
     */
    private $codigoPermisoEspecialFk;

    /**
     * @ORM\Column(name="ingreso", type="boolean", nullable=false)
     */
    private $ingreso = 0;

    /**
     * @ORM\Column(name="nuevo", type="boolean", nullable=false)
     */
    private $nuevo = 0;

    /**
     * @ORM\Column(name="editar", type="boolean", nullable=false)
     */
    private $editar = 0;

    /**
     * @ORM\Column(name="eliminar", type="boolean", nullable=false)
     */
    private $eliminar = 0;

    /**
     * @ORM\Column(name="autorizar", type="boolean", nullable=false)
     */
    private $autorizar = 0;

    /**
     * @ORM\Column(name="desautorizar", type="boolean", nullable=false)
     */
    private $desautorizar = 0;

    /**
     * @ORM\Column(name="aprobar", type="boolean", nullable=false)
     */
    private $aprobar = 0;

    /**
     * @ORM\Column(name="desaprobar", type="boolean", nullable=false)
     */
    private $desaprobar = 0;

    /**
     * @ORM\Column(name="anular", type="boolean", nullable=false)
     */
    private $anular = 0;

    /**
     * @ORM\Column(name="desanular", type="boolean", nullable=false)
     */
    private $desanular = 0;

    /**
     * @ORM\Column(name="imprimir", type="boolean", nullable=false)
     */
    private $imprimir = 0;
    
    /**
     * @ORM\Column(name="permitir", type="boolean", nullable=false)
     */
    private $permitir = 0;

    /**
     * @ORM\ManyToOne(targetEntity="SegGrupo", inversedBy="permisosGruposGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     * @Assert\NotNull(message="Seleccione un elemento")
     */
    protected $grupoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="SegDocumento", inversedBy="permisosGruposDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="SegPermisoEspecial", inversedBy="permisosGruposPermisoEspecialRel")
     * @ORM\JoinColumn(name="codigo_permiso_especial_fk", referencedColumnName="codigo_permiso_especial_pk")
     */
    protected $permisoEspecialRel;

    /**
     * Get codigoPermisoGrupoPk
     *
     * @return integer
     */
    public function getCodigoPermisoGrupoPk() {
        return $this->codigoPermisoGrupoPk;
    }

    /**
     * Set codigoGrupoFk
     *
     * @param integer $codigoGrupoFk
     *
     * @return SegPermisoGrupo
     */
    public function setCodigoGrupoFk($codigoGrupoFk) {
        $this->codigoGrupoFk = $codigoGrupoFk;

        return $this;
    }

    /**
     * Get codigoGrupoFk
     *
     * @return integer
     */
    public function getCodigoGrupoFk() {
        return $this->codigoGrupoFk;
    }

    /**
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return SegPermisoGrupo
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk) {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoFk() {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set codigoPermisoEspecialFk
     *
     * @param integer $codigoPermisoEspecialFk
     *
     * @return SegPermisoGrupo
     */
    public function setCodigoPermisoEspecialFk($codigoPermisoEspecialFk) {
        $this->codigoPermisoEspecialFk = $codigoPermisoEspecialFk;

        return $this;
    }

    /**
     * Get codigoPermisoEspecialFk
     *
     * @return integer
     */
    public function getCodigoPermisoEspecialFk() {
        return $this->codigoPermisoEspecialFk;
    }

    /**
     * Set ingreso
     *
     * @param boolean $ingreso
     *
     * @return SegPermisoGrupo
     */
    public function setIngreso($ingreso) {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return boolean
     */
    public function getIngreso() {
        return $this->ingreso;
    }

    /**
     * Set nuevo
     *
     * @param boolean $nuevo
     *
     * @return SegPermisoGrupo
     */
    public function setNuevo($nuevo) {
        $this->nuevo = $nuevo;

        return $this;
    }

    /**
     * Get nuevo
     *
     * @return boolean
     */
    public function getNuevo() {
        return $this->nuevo;
    }

    /**
     * Set editar
     *
     * @param boolean $editar
     *
     * @return SegPermisoGrupo
     */
    public function setEditar($editar) {
        $this->editar = $editar;

        return $this;
    }

    /**
     * Get editar
     *
     * @return boolean
     */
    public function getEditar() {
        return $this->editar;
    }

    /**
     * Set eliminar
     *
     * @param boolean $eliminar
     *
     * @return SegPermisoGrupo
     */
    public function setEliminar($eliminar) {
        $this->eliminar = $eliminar;

        return $this;
    }

    /**
     * Get eliminar
     *
     * @return boolean
     */
    public function getEliminar() {
        return $this->eliminar;
    }

    /**
     * Set autorizar
     *
     * @param boolean $autorizar
     *
     * @return SegPermisoGrupo
     */
    public function setAutorizar($autorizar) {
        $this->autorizar = $autorizar;

        return $this;
    }

    /**
     * Get autorizar
     *
     * @return boolean
     */
    public function getAutorizar() {
        return $this->autorizar;
    }

    /**
     * Set desautorizar
     *
     * @param boolean $desautorizar
     *
     * @return SegPermisoGrupo
     */
    public function setDesautorizar($desautorizar) {
        $this->desautorizar = $desautorizar;

        return $this;
    }

    /**
     * Get desautorizar
     *
     * @return boolean
     */
    public function getDesautorizar() {
        return $this->desautorizar;
    }

    /**
     * Set aprobar
     *
     * @param boolean $aprobar
     *
     * @return SegPermisoGrupo
     */
    public function setAprobar($aprobar) {
        $this->aprobar = $aprobar;

        return $this;
    }

    /**
     * Get aprobar
     *
     * @return boolean
     */
    public function getAprobar() {
        return $this->aprobar;
    }

    /**
     * Set desaprobar
     *
     * @param boolean $desaprobar
     *
     * @return SegPermisoGrupo
     */
    public function setDesaprobar($desaprobar) {
        $this->desaprobar = $desaprobar;

        return $this;
    }

    /**
     * Get desaprobar
     *
     * @return boolean
     */
    public function getDesaprobar() {
        return $this->desaprobar;
    }

    /**
     * Set anular
     *
     * @param boolean $anular
     *
     * @return SegPermisoGrupo
     */
    public function setAnular($anular) {
        $this->anular = $anular;

        return $this;
    }

    /**
     * Get anular
     *
     * @return boolean
     */
    public function getAnular() {
        return $this->anular;
    }

    /**
     * Set desanular
     *
     * @param boolean $desanular
     *
     * @return SegPermisoGrupo
     */
    public function setDesanular($desanular) {
        $this->desanular = $desanular;

        return $this;
    }

    /**
     * Get desanular
     *
     * @return boolean
     */
    public function getDesanular() {
        return $this->desanular;
    }

    /**
     * Set imprimir
     *
     * @param boolean $imprimir
     *
     * @return SegPermisoGrupo
     */
    public function setImprimir($imprimir) {
        $this->imprimir = $imprimir;

        return $this;
    }

    /**
     * Get imprimir
     *
     * @return boolean
     */
    public function getImprimir() {
        return $this->imprimir;
    }

    /**
     * Set grupoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegGrupo $grupoRel
     *
     * @return SegPermisoGrupo
     */
    public function setGrupoRel(\Brasa\SeguridadBundle\Entity\SegGrupo $grupoRel = null) {
        $this->grupoRel = $grupoRel;

        return $this;
    }

    /**
     * Get grupoRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegGrupo
     */
    public function getGrupoRel() {
        return $this->grupoRel;
    }


    /**
     * Set permitir
     *
     * @param boolean $permitir
     *
     * @return SegPermisoGrupo
     */
    public function setPermitir($permitir)
    {
        $this->permitir = $permitir;

        return $this;
    }

    /**
     * Get permitir
     *
     * @return boolean
     */
    public function getPermitir()
    {
        return $this->permitir;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel
     *
     * @return SegPermisoGrupo
     */
    public function setDocumentoRel(\Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * Set permisoEspecialRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoEspecial $permisoEspecialRel
     *
     * @return SegPermisoGrupo
     */
    public function setPermisoEspecialRel(\Brasa\SeguridadBundle\Entity\SegPermisoEspecial $permisoEspecialRel = null)
    {
        $this->permisoEspecialRel = $permisoEspecialRel;

        return $this;
    }

    /**
     * Get permisoEspecialRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegPermisoEspecial
     */
    public function getPermisoEspecialRel()
    {
        return $this->permisoEspecialRel;
    }
}
