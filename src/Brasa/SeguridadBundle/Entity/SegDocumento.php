<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_documento")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegDocumentoRepository")
 */
class SegDocumento {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     */
    private $codigoDocumentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="tipo", type="string", length=30, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="modulo", type="string", length=30, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\OneToMany(targetEntity="SegPermisoDocumento", mappedBy="documentoRel")
     */
    protected $permisosDocumentosDocumentoRel;

    /**
     * @ORM\OneToMany(targetEntity="SegPermisoGrupo", mappedBy="documentoRel")
     */
    protected $permisosGruposDocumentoRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\GeneralBundle\Entity\GenLog", mappedBy="documentoRel")
     */
    protected $logsDocumentoRel;

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permisosDocumentosDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permisosGruposDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->logsDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoDocumentoPk
     *
     * @param integer $codigoDocumentoPk
     *
     * @return SegDocumento
     */
    public function setCodigoDocumentoPk($codigoDocumentoPk)
    {
        $this->codigoDocumentoPk = $codigoDocumentoPk;

        return $this;
    }

    /**
     * Get codigoDocumentoPk
     *
     * @return integer
     */
    public function getCodigoDocumentoPk()
    {
        return $this->codigoDocumentoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegDocumento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return SegDocumento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set modulo
     *
     * @param string $modulo
     *
     * @return SegDocumento
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo
     *
     * @return string
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * Add permisosDocumentosDocumentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosDocumentoRel
     *
     * @return SegDocumento
     */
    public function addPermisosDocumentosDocumentoRel(\Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosDocumentoRel)
    {
        $this->permisosDocumentosDocumentoRel[] = $permisosDocumentosDocumentoRel;

        return $this;
    }

    /**
     * Remove permisosDocumentosDocumentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosDocumentoRel
     */
    public function removePermisosDocumentosDocumentoRel(\Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosDocumentoRel)
    {
        $this->permisosDocumentosDocumentoRel->removeElement($permisosDocumentosDocumentoRel);
    }

    /**
     * Get permisosDocumentosDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosDocumentosDocumentoRel()
    {
        return $this->permisosDocumentosDocumentoRel;
    }

    /**
     * Add permisosGruposDocumentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposDocumentoRel
     *
     * @return SegDocumento
     */
    public function addPermisosGruposDocumentoRel(\Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposDocumentoRel)
    {
        $this->permisosGruposDocumentoRel[] = $permisosGruposDocumentoRel;

        return $this;
    }

    /**
     * Remove permisosGruposDocumentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposDocumentoRel
     */
    public function removePermisosGruposDocumentoRel(\Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposDocumentoRel)
    {
        $this->permisosGruposDocumentoRel->removeElement($permisosGruposDocumentoRel);
    }

    /**
     * Get permisosGruposDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosGruposDocumentoRel()
    {
        return $this->permisosGruposDocumentoRel;
    }

    /**
     * Add logsDocumentoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLog $logsDocumentoRel
     *
     * @return SegDocumento
     */
    public function addLogsDocumentoRel(\Brasa\GeneralBundle\Entity\GenLog $logsDocumentoRel)
    {
        $this->logsDocumentoRel[] = $logsDocumentoRel;

        return $this;
    }

    /**
     * Remove logsDocumentoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLog $logsDocumentoRel
     */
    public function removeLogsDocumentoRel(\Brasa\GeneralBundle\Entity\GenLog $logsDocumentoRel)
    {
        $this->logsDocumentoRel->removeElement($logsDocumentoRel);
    }

    /**
     * Get logsDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogsDocumentoRel()
    {
        return $this->logsDocumentoRel;
    }
}
