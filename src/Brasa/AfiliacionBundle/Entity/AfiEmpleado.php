<?php

namespace Brasa\AfiliacionBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_empleado")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiEmpleadoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación") 
 */

class AfiEmpleado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoPk;
    
    /**
     * @ORM\Column(name="codigo_tipo_identificacion_fk", type="integer")
     */    
    private $codigoTipoIdentificacionFk;     
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)
     */         
    private $numeroIdentificacion;    
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;    

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     */    
    private $nombre1;        
    
    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     */    
    private $nombre2;    
    
    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     */    
    private $apellido1;    

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     */    
    private $apellido2;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */    
    private $celular; 
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion; 
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;    
    
    /**
     * @ORM\Column(name="barrio", type="string", length=100, nullable=true)
     */    
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */    
    private $codigoRhPk;     
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */    
    private $codigoSexoFk;     
    
    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */    
    private $correo;     
        
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */ 
    
    private $fechaNacimiento;     
    
     /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="string", length=1, nullable=true)
     */
    
    private $codigoEstadoCivilFk;
                  
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $VrSalario = 0;                  
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = 1;          
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;             
        
    /**
     * @ORM\Column(name="codigo_contrato_activo", type="integer", nullable=true)
     */
    private $codigoContratoActivo; 
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
        
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
        
        
    
    

    /**
     * Get codigoEmpleadoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoPk()
    {
        return $this->codigoEmpleadoPk;
    }

    /**
     * Set codigoTipoIdentificacionFk
     *
     * @param integer $codigoTipoIdentificacionFk
     *
     * @return AfiEmpleado
     */
    public function setCodigoTipoIdentificacionFk($codigoTipoIdentificacionFk)
    {
        $this->codigoTipoIdentificacionFk = $codigoTipoIdentificacionFk;

        return $this;
    }

    /**
     * Get codigoTipoIdentificacionFk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionFk()
    {
        return $this->codigoTipoIdentificacionFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return AfiEmpleado
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return AfiEmpleado
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set nombre1
     *
     * @param string $nombre1
     *
     * @return AfiEmpleado
     */
    public function setNombre1($nombre1)
    {
        $this->nombre1 = $nombre1;

        return $this;
    }

    /**
     * Get nombre1
     *
     * @return string
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * Set nombre2
     *
     * @param string $nombre2
     *
     * @return AfiEmpleado
     */
    public function setNombre2($nombre2)
    {
        $this->nombre2 = $nombre2;

        return $this;
    }

    /**
     * Get nombre2
     *
     * @return string
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     *
     * @return AfiEmpleado
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     *
     * @return AfiEmpleado
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return AfiEmpleado
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return AfiEmpleado
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return AfiEmpleado
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return AfiEmpleado
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
     *
     * @return AfiEmpleado
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set codigoRhPk
     *
     * @param integer $codigoRhPk
     *
     * @return AfiEmpleado
     */
    public function setCodigoRhPk($codigoRhPk)
    {
        $this->codigoRhPk = $codigoRhPk;

        return $this;
    }

    /**
     * Get codigoRhPk
     *
     * @return integer
     */
    public function getCodigoRhPk()
    {
        return $this->codigoRhPk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return AfiEmpleado
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return AfiEmpleado
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return AfiEmpleado
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param string $codigoEstadoCivilFk
     *
     * @return AfiEmpleado
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return string
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return AfiEmpleado
     */
    public function setVrSalario($vrSalario)
    {
        $this->VrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->VrSalario;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return AfiEmpleado
     */
    public function setEstadoActivo($estadoActivo)
    {
        $this->estadoActivo = $estadoActivo;

        return $this;
    }

    /**
     * Get estadoActivo
     *
     * @return boolean
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiEmpleado
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set codigoContratoActivo
     *
     * @param integer $codigoContratoActivo
     *
     * @return AfiEmpleado
     */
    public function setCodigoContratoActivo($codigoContratoActivo)
    {
        $this->codigoContratoActivo = $codigoContratoActivo;

        return $this;
    }

    /**
     * Get codigoContratoActivo
     *
     * @return integer
     */
    public function getCodigoContratoActivo()
    {
        return $this->codigoContratoActivo;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return AfiEmpleado
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiEmpleado
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
}
