<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tur_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurRecursoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación")
 */
class TurRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recurso_pk", type="integer")
     */
    private $codigoRecursoPk;    
    
    /**
     * @ORM\Column(name="codigo_recurso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoTipoFk;    

    /**
     * @ORM\Column(name="codigo_recurso_grupo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoGrupoFk;                   
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)     
     */         
    private $numeroIdentificacion;    
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=120, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */    
    private $celular; 
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */    
    private $direccion;
    
    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */    
    private $correo; 
    
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */ 
    private $fechaNacimiento;               
    
    /**
     * @ORM\Column(name="ruta_foto", type="string", length=250, nullable=true)
     */    
    private $rutaFoto;    
    
    /**     
     * @ORM\Column(name="pago_promedio", type="boolean")
     */    
    private $pagoPromedio = false;    

    /**     
     * @ORM\Column(name="pago_variable", type="boolean")
     */    
    private $pagoVariable = false;                
    
    /**
     * @ORM\Column(name="apodo", type="string", length=80, nullable=true)
     */    
    private $apodo;     
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = true;           
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;     
    
    /**
     * @ORM\Column(name="fecha_retiro", type="date", nullable=true)
     */ 
    private $fechaRetiro;     
    
    /**     
     * @ORM\Column(name="estado_retiro", type="boolean")
     */    
    private $estadoRetiro = false;    
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_nomina_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijoNominaFk;              
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_descanso_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijoDescansoFk;    
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_31_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijo31Fk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", inversedBy="turRecursosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecursoTipo", inversedBy="recursosRecursoTipoRel")
     * @ORM\JoinColumn(name="codigo_recurso_tipo_fk", referencedColumnName="codigo_recurso_tipo_pk")
     */
    protected $recursoTipoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TurRecursoGrupo", inversedBy="recursosRecursoGrupoRel")
     * @ORM\JoinColumn(name="codigo_recurso_grupo_fk", referencedColumnName="codigo_recurso_grupo_pk")
     */
    protected $recursoGrupoRel;        
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="recursoRel")
     */
    protected $programacionesDetallesRecursoRel;    

   /**
     * @ORM\OneToMany(targetEntity="TurSoportePago", mappedBy="recursoRel")
     */
    protected $soportesPagosRecursoRel;      
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="recursoRel")
     */
    protected $soportesPagosDetallesRecursoRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleRecurso", mappedBy="recursoRel")
     */
    protected $pedidosDetallesRecursosRecursoRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleRecurso", mappedBy="recursoRel")
     */
    protected $serviciosDetallesRecursosRecursoRel;             
    
    /**
     * @ORM\OneToMany(targetEntity="TurNovedad", mappedBy="recursoRel")
     */
    protected $novedadesRecursoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurNovedad", mappedBy="recursoReemplazoRel")
     */
    protected $novedadesRecursoReemplazoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurSimulacionDetalle", mappedBy="recursoRel")
     */
    protected $simulacionesDetallesRecursoRel; 

    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionAlterna", mappedBy="recursoRel")
     */
    protected $programacionesAlternasRecursoRel;     
    

}
