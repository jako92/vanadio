<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="seg_grupo")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegGrupoRepository")
 */
class SegGrupo {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGrupoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="SegPermisoGrupo", mappedBy="grupoRel")
     */
    protected $permisosGruposGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="grupoRel")
     */
    protected $usersGrupoRel;

    /**
     * Constructor
     */
    public function __construct() {
        $this->permisosGruposGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoGrupoPk
     *
     * @return integer
     */
    public function getCodigoGrupoPk() {
        return $this->codigoGrupoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegGrupo
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add permisosGruposGrupoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposGrupoRel
     *
     * @return SegGrupo
     */
    public function addPermisosGruposGrupoRel(\Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposGrupoRel) {
        $this->permisosGruposGrupoRel[] = $permisosGruposGrupoRel;

        return $this;
    }

    /**
     * Remove permisosGruposGrupoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposGrupoRel
     */
    public function removePermisosGruposGrupoRel(\Brasa\SeguridadBundle\Entity\SegPermisoGrupo $permisosGruposGrupoRel) {
        $this->permisosGruposGrupoRel->removeElement($permisosGruposGrupoRel);
    }

    /**
     * Get permisosGruposGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosGruposGrupoRel() {
        return $this->permisosGruposGrupoRel;
    }


    /**
     * Add usersGrupoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usersGrupoRel
     *
     * @return SegGrupo
     */
    public function addUsersGrupoRel(\Brasa\SeguridadBundle\Entity\User $usersGrupoRel)
    {
        $this->usersGrupoRel[] = $usersGrupoRel;

        return $this;
    }

    /**
     * Remove usersGrupoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usersGrupoRel
     */
    public function removeUsersGrupoRel(\Brasa\SeguridadBundle\Entity\User $usersGrupoRel)
    {
        $this->usersGrupoRel->removeElement($usersGrupoRel);
    }

    /**
     * Get usersGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersGrupoRel()
    {
        return $this->usersGrupoRel;
    }
}
