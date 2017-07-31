<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenFavorito
 *
 * @ORM\Table(name="gen_favorito")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenFavoritoRepository")
 */
class GenFavorito {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_favorito_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFavoritoPk;

    /**
     * @var int
     *
     * @ORM\Column(name="usuario", type="string", length=255)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="url_documento", type="string", length=500)
     */
    private $urlDocumento;

    /**
     * @var string
     * 
     * @ORM\Column(name="parametros", type="text", nullable=true)
     */
    private $parametros;

    /**
     * Get codigoFavoritoPk
     *
     * @return integer
     */
    public function getCodigoFavoritoPk() {
        return $this->codigoFavoritoPk;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return GenFavorito
     */
    public function setUsuario($usuario) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenFavorito
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
     * Set urlDocumento
     *
     * @param string $urlDocumento
     *
     * @return GenFavorito
     */
    public function setUrlDocumento($urlDocumento) {
        $this->urlDocumento = $urlDocumento;

        return $this;
    }

    /**
     * Get urlDocumento
     *
     * @return string
     */
    public function getUrlDocumento() {
        return $this->urlDocumento;
    }


    /**
     * Set parametros
     *
     * @param string $parametros
     *
     * @return GenFavorito
     */
    public function setParametros($parametros)
    {
        $this->parametros = $parametros;

        return $this;
    }

    /**
     * Get parametros
     *
     * @return string
     */
    public function getParametros()
    {
        return json_decode($this->parametros,true);
    }
}
