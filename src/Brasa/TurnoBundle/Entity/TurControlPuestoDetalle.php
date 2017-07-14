<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_control_puesto_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurControlPuestoDetalleRepository")
 */
class TurControlPuestoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_control_puesto_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoControlPuestoDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_control_puesto_fk", type="integer")
     */    
    private $codigoControlPuestoFk;
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;
    
    /**
     * @ORM\Column(name="codigo_tipo_novedad_fk", type="integer", nullable=true)
     */    
    private $codigoTipoNovedadFk;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;     
    
    /**     
     * @ORM\Column(name="estado_novedad", type="boolean")
     */    
    private $estadoNovedad = false;
    
    /**     
     * @ORM\Column(name="estado_solucionado", type="boolean")
     */    
    private $estadoSolucionado = false;
    
    /**
     * @ORM\Column(name="novedad", type="string", length=1000, nullable=true)
     */    
    private $novedad;

    /**
     * @ORM\Column(name="solucion", type="string", length=1000, nullable=true)
     */    
    private $solucion;    
    
    /**
     * @ORM\Column(name="numero_comunicacion", type="string", length=30, nullable=true)
     */    
    private $numeroComunicacion;  
    
     /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;   
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="fecha_solucion", type="datetime", nullable=true)
     */    
    private $fechaSolucion;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="controlesPuestosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurControlPuesto", inversedBy="controlesPuestosDetallesControlPuestoRel")
     * @ORM\JoinColumn(name="codigo_control_puesto_fk", referencedColumnName="codigo_control_puesto_pk")
     */
    protected $controlPuestoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurControlPuestoDetalleTipoNovedad", inversedBy="controlesPuestosDetallesTipoNovedadRel")
     * @ORM\JoinColumn(name="codigo_tipo_novedad_fk", referencedColumnName="codigo_tipo_novedad_pk")
     */
    protected $tipoNovedadRel;
    

    /**
     * Get codigoControlPuestoDetallePk
     *
     * @return integer
     */
    public function getCodigoControlPuestoDetallePk()
    {
        return $this->codigoControlPuestoDetallePk;
    }

    /**
     * Set codigoControlPuestoFk
     *
     * @param integer $codigoControlPuestoFk
     *
     * @return TurControlPuestoDetalle
     */
    public function setCodigoControlPuestoFk($codigoControlPuestoFk)
    {
        $this->codigoControlPuestoFk = $codigoControlPuestoFk;

        return $this;
    }

    /**
     * Get codigoControlPuestoFk
     *
     * @return integer
     */
    public function getCodigoControlPuestoFk()
    {
        return $this->codigoControlPuestoFk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurControlPuestoDetalle
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurControlPuestoDetalle
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurControlPuestoDetalle
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoNovedad
     *
     * @param boolean $estadoNovedad
     *
     * @return TurControlPuestoDetalle
     */
    public function setEstadoNovedad($estadoNovedad)
    {
        $this->estadoNovedad = $estadoNovedad;

        return $this;
    }

    /**
     * Get estadoNovedad
     *
     * @return boolean
     */
    public function getEstadoNovedad()
    {
        return $this->estadoNovedad;
    }

    /**
     * Set estadoSolucionado
     *
     * @param boolean $estadoSolucionado
     *
     * @return TurControlPuestoDetalle
     */
    public function setEstadoSolucionado($estadoSolucionado)
    {
        $this->estadoSolucionado = $estadoSolucionado;

        return $this;
    }

    /**
     * Get estadoSolucionado
     *
     * @return boolean
     */
    public function getEstadoSolucionado()
    {
        return $this->estadoSolucionado;
    }

    /**
     * Set novedad
     *
     * @param string $novedad
     *
     * @return TurControlPuestoDetalle
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return string
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set solucion
     *
     * @param string $solucion
     *
     * @return TurControlPuestoDetalle
     */
    public function setSolucion($solucion)
    {
        $this->solucion = $solucion;

        return $this;
    }

    /**
     * Get solucion
     *
     * @return string
     */
    public function getSolucion()
    {
        return $this->solucion;
    }

    /**
     * Set numeroComunicacion
     *
     * @param string $numeroComunicacion
     *
     * @return TurControlPuestoDetalle
     */
    public function setNumeroComunicacion($numeroComunicacion)
    {
        $this->numeroComunicacion = $numeroComunicacion;

        return $this;
    }

    /**
     * Get numeroComunicacion
     *
     * @return string
     */
    public function getNumeroComunicacion()
    {
        return $this->numeroComunicacion;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurControlPuestoDetalle
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurControlPuestoDetalle
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaSolucion
     *
     * @param \DateTime $fechaSolucion
     *
     * @return TurControlPuestoDetalle
     */
    public function setFechaSolucion($fechaSolucion)
    {
        $this->fechaSolucion = $fechaSolucion;

        return $this;
    }

    /**
     * Get fechaSolucion
     *
     * @return \DateTime
     */
    public function getFechaSolucion()
    {
        return $this->fechaSolucion;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurControlPuestoDetalle
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set controlPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuesto $controlPuestoRel
     *
     * @return TurControlPuestoDetalle
     */
    public function setControlPuestoRel(\Brasa\TurnoBundle\Entity\TurControlPuesto $controlPuestoRel = null)
    {
        $this->controlPuestoRel = $controlPuestoRel;

        return $this;
    }

    /**
     * Get controlPuestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurControlPuesto
     */
    public function getControlPuestoRel()
    {
        return $this->controlPuestoRel;
    }

    /**
     * Set codigoTipoNovedadFk
     *
     * @param integer $codigoTipoNovedadFk
     *
     * @return TurControlPuestoDetalle
     */
    public function setCodigoTipoNovedadFk($codigoTipoNovedadFk)
    {
        $this->codigoTipoNovedadFk = $codigoTipoNovedadFk;

        return $this;
    }

    /**
     * Get codigoTipoNovedadFk
     *
     * @return integer
     */
    public function getCodigoTipoNovedadFk()
    {
        return $this->codigoTipoNovedadFk;
    }

    /**
     * Set tipoNovedadRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurControlPuestoDetalleTipoNovedad $tipoNovedadRel
     *
     * @return TurControlPuestoDetalle
     */
    public function setTipoNovedadRel(\Brasa\TurnoBundle\Entity\TurControlPuestoDetalleTipoNovedad $tipoNovedadRel = null)
    {
        $this->tipoNovedadRel = $tipoNovedadRel;

        return $this;
    }

    /**
     * Get tipoNovedadRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurControlPuestoDetalleTipoNovedad
     */
    public function getTipoNovedadRel()
    {
        return $this->tipoNovedadRel;
    }
}
