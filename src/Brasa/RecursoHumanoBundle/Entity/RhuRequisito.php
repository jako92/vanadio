<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="rhu_requisito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRequisitoRepository")
 */
class RhuRequisito {

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */
    private $codigoCargoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_requisito_tipo_fk", type="integer", nullable=true)
     */
    private $codigoRequisitoTipoFk;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */
    private $estadoCerrado = 0;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_aplicado", type="boolean")
     */
    private $estadoAplicado = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="requisitosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     * @Assert\NotNull(message="Seleccione un elemento")
     */
    protected $cargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisitoTipo", inversedBy="requisitosRequisitoTipoRel")
     * @ORM\JoinColumn(name="codigo_requisito_tipo_fk", referencedColumnName="codigo_requisito_tipo_pk")
     * @Assert\NotNull(message="Seleccione un elemento")
     */
    protected $requisitoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="requisitosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoDetalle", mappedBy="requisitoRel", cascade={"persist", "remove"})
     */
    protected $requisitosDetallesRequisitoRel;

    /**
     * Constructor
     */
    public function __construct() {
        $this->requisitosDetallesRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRequisitoPk
     *
     * @return integer
     */
    public function getCodigoRequisitoPk() {
        return $this->codigoRequisitoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuRequisito
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuRequisito
     */
    public function setNumeroIdentificacion($numeroIdentificacion) {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion() {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuRequisito
     */
    public function setNombreCorto($nombreCorto) {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto() {
        return $this->nombreCorto;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuRequisito
     */
    public function setCodigoCargoFk($codigoCargoFk) {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk() {
        return $this->codigoCargoFk;
    }

    /**
     * Set codigoRequisitoTipoFk
     *
     * @param integer $codigoRequisitoTipoFk
     *
     * @return RhuRequisito
     */
    public function setCodigoRequisitoTipoFk($codigoRequisitoTipoFk) {
        $this->codigoRequisitoTipoFk = $codigoRequisitoTipoFk;

        return $this;
    }

    /**
     * Get codigoRequisitoTipoFk
     *
     * @return integer
     */
    public function getCodigoRequisitoTipoFk() {
        return $this->codigoRequisitoTipoFk;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuRequisito
     */
    public function setEstadoAutorizado($estadoAutorizado) {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado() {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuRequisito
     */
    public function setEstadoCerrado($estadoCerrado) {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado() {
        return $this->estadoCerrado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuRequisito
     */
    public function setComentarios($comentarios) {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios() {
        return $this->comentarios;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuRequisito
     */
    public function setCodigoUsuario($codigoUsuario) {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario() {
        return $this->codigoUsuario;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuRequisito
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null) {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel() {
        return $this->cargoRel;
    }

    /**
     * Set requisitoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoTipo $requisitoTipoRel
     *
     * @return RhuRequisito
     */
    public function setRequisitoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoTipo $requisitoTipoRel = null) {
        $this->requisitoTipoRel = $requisitoTipoRel;

        return $this;
    }

    /**
     * Get requisitoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoTipo
     */
    public function getRequisitoTipoRel() {
        return $this->requisitoTipoRel;
    }

    /**
     * Add requisitosDetallesRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel
     *
     * @return RhuRequisito
     */
    public function addRequisitosDetallesRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel) {
        $this->requisitosDetallesRequisitoRel[] = $requisitosDetallesRequisitoRel;

        return $this;
    }

    /**
     * Remove requisitosDetallesRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel
     */
    public function removeRequisitosDetallesRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoRel) {
        $this->requisitosDetallesRequisitoRel->removeElement($requisitosDetallesRequisitoRel);
    }

    /**
     * Get requisitosDetallesRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosDetallesRequisitoRel() {
        return $this->requisitosDetallesRequisitoRel;
    }


    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuRequisito
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuRequisito
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoAplicado
     *
     * @param boolean $estadoAplicado
     *
     * @return RhuRequisito
     */
    public function setEstadoAplicado($estadoAplicado)
    {
        $this->estadoAplicado = $estadoAplicado;

        return $this;
    }

    /**
     * Get estadoAplicado
     *
     * @return boolean
     */
    public function getEstadoAplicado()
    {
        return $this->estadoAplicado;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuRequisito
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }
}
