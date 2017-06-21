<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TurClienteContacto
 *
 * @ORM\Table(name="tur_cliente_contacto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurClienteContactoRepository")
 */
class TurClienteContacto {

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_cliente_contacto_pk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClienteContactoPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer")
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="gerente", type="string", length=80, nullable=true)
     */
    private $gerente;

    /**
     * @ORM\Column(name="calular_gerente", type="string", length=20, nullable=true)
     */
    private $celularGerente;

    /**
     * @ORM\Column(name="financiero", type="string", length=80, nullable=true)
     */
    private $financiero;

    /**
     * @ORM\Column(name="calular_financiero", type="string", length=20, nullable=true)
     */
    private $celularFinanciero;

    /**
     * @ORM\Column(name="contacto", type="string", length=80, nullable=true)
     */
    private $contacto;

    /**
     * @ORM\Column(name="calular_contacto", type="string", length=20, nullable=true)
     */
    private $celularContacto;

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=20, nullable=true)
     */
    private $telefonoContacto;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="clienteContactosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * Get codigoClienteContactoPk
     *
     * @return integer
     */
    public function getCodigoClienteContactoPk() {
        return $this->codigoClienteContactoPk;
    }

    /**
     * Set gerente
     *
     * @param string $gerente
     *
     * @return TurClienteContacto
     */
    public function setGerente($gerente) {
        $this->gerente = $gerente;

        return $this;
    }

    /**
     * Get gerente
     *
     * @return string
     */
    public function getGerente() {
        return $this->gerente;
    }

    /**
     * Set celularGerente
     *
     * @param string $celularGerente
     *
     * @return TurClienteContacto
     */
    public function setCelularGerente($celularGerente) {
        $this->celularGerente = $celularGerente;

        return $this;
    }

    /**
     * Get celularGerente
     *
     * @return string
     */
    public function getCelularGerente() {
        return $this->celularGerente;
    }

    /**
     * Set financiero
     *
     * @param string $financiero
     *
     * @return TurClienteContacto
     */
    public function setFinanciero($financiero) {
        $this->financiero = $financiero;

        return $this;
    }

    /**
     * Get financiero
     *
     * @return string
     */
    public function getFinanciero() {
        return $this->financiero;
    }

    /**
     * Set celularFinanciero
     *
     * @param string $celularFinanciero
     *
     * @return TurClienteContacto
     */
    public function setCelularFinanciero($celularFinanciero) {
        $this->celularFinanciero = $celularFinanciero;

        return $this;
    }

    /**
     * Get celularFinanciero
     *
     * @return string
     */
    public function getCelularFinanciero() {
        return $this->celularFinanciero;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return TurClienteContacto
     */
    public function setContacto($contacto) {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto() {
        return $this->contacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return TurClienteContacto
     */
    public function setCelularContacto($celularContacto) {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto() {
        return $this->celularContacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return TurClienteContacto
     */
    public function setTelefonoContacto($telefonoContacto) {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto() {
        return $this->telefonoContacto;
    }


    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurClienteContacto
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurClienteContacto
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }
}
