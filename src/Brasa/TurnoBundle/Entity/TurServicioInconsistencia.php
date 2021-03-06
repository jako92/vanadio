<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio_inconsistencia")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioInconsistenciaRepository")
 */
class TurServicioInconsistencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioInconsistenciaPk;             

    /**
     * @ORM\Column(name="inconsistencia", type="string", length=100, nullable=true)
     */    
    private $inconsistencia;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=600, nullable=true)
     */    
    private $detalle;                    

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */    
    private $numeroIdentificacion;     

    /**
     * @ORM\Column(name="dia", type="integer", nullable=true)
     */    
    private $dia = 0;  
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes = 0;    
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio = 0;     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    

    /**
     * @ORM\Column(name="codigo_recurso_grupo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoGrupoFk;    
    
    /**
     * @ORM\Column(name="zona", type="string", length=100, nullable=true)
     */    
    private $zona;    
    


    /**
     * Get codigoServicioInconsistenciaPk
     *
     * @return integer
     */
    public function getCodigoServicioInconsistenciaPk()
    {
        return $this->codigoServicioInconsistenciaPk;
    }

    /**
     * Set inconsistencia
     *
     * @param string $inconsistencia
     *
     * @return TurServicioInconsistencia
     */
    public function setInconsistencia($inconsistencia)
    {
        $this->inconsistencia = $inconsistencia;

        return $this;
    }

    /**
     * Get inconsistencia
     *
     * @return string
     */
    public function getInconsistencia()
    {
        return $this->inconsistencia;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurServicioInconsistencia
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return TurServicioInconsistencia
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set dia
     *
     * @param integer $dia
     *
     * @return TurServicioInconsistencia
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return integer
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return TurServicioInconsistencia
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurServicioInconsistencia
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurServicioInconsistencia
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
     * Set codigoRecursoGrupoFk
     *
     * @param integer $codigoRecursoGrupoFk
     *
     * @return TurServicioInconsistencia
     */
    public function setCodigoRecursoGrupoFk($codigoRecursoGrupoFk)
    {
        $this->codigoRecursoGrupoFk = $codigoRecursoGrupoFk;

        return $this;
    }

    /**
     * Get codigoRecursoGrupoFk
     *
     * @return integer
     */
    public function getCodigoRecursoGrupoFk()
    {
        return $this->codigoRecursoGrupoFk;
    }

    /**
     * Set zona
     *
     * @param string $zona
     *
     * @return TurServicioInconsistencia
     */
    public function setZona($zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return string
     */
    public function getZona()
    {
        return $this->zona;
    }
}
