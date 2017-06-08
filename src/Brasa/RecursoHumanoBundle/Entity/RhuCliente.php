<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cliente")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuClienteRepository")
 */
class RhuCliente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;             
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150)
     */
    private $nombreCorto;                             
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;     
    
    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=120, nullable=true)
     */
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;     
    
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
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="cobro_examen", type="string", length=1, nullable=true)
     */
    private $cobroExamen;    
    
    /**     
     * @ORM\Column(name="regimen_simplificado", type="boolean")
     */    
    private $regimenSimplificado = false;      

    /**     
     * @ORM\Column(name="regimen_comun", type="boolean")
     */    
    private $regimenComun = false; 
    
    /**     
     * @ORM\Column(name="autorretenedor", type="boolean")
     */    
    private $autorretenedor = false;    

    /**     
     * @ORM\Column(name="retencion_iva", type="boolean")
     */    
    private $retencionIva = false;

    /**     
     * @ORM\Column(name="retencion_fuente", type="boolean")
     */    
    private $retencionFuente = false;
    
     /**
     * @ORM\Column(name="vr_precio_seleccion", type="float")
     */
    private $vrPrecioSeleccion = 0;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;          
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="rhuClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;         

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="rhuClientesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCentroCosto", mappedBy="clienteRel")
     */
    protected $centrosCostosClienteRel; 

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="clienteRel")
     */
    protected $contratosClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="clienteRel")
     */
    protected $incapacidadesClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuVisita", mappedBy="clienteRel")
     */
    protected $visitasClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPrueba", mappedBy="clienteRel")
     */
    protected $pruebasClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPoligrafia", mappedBy="clienteRel")
     */
    protected $poligrafiasClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSucursal", mappedBy="clienteRel") 
     */ 
    protected $sucursalClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="clienteRel") 
     */ 
    protected $examenesClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCobro", mappedBy="clienteRel") 
     */ 
    protected $cobrosClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="clienteRel") 
     */ 
    protected $serviciosCobrarClienteRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="clienteRel") 
     */ 
    protected $seleccionesClienteRel; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->centrosCostosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visitasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pruebasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->poligrafiasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sucursalClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cobrosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosCobrarClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seleccionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuCliente
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return RhuCliente
     */
    public function setDigitoVerificacion($digitoVerificacion)
    {
        $this->digitoVerificacion = $digitoVerificacion;

        return $this;
    }

    /**
     * Get digitoVerificacion
     *
     * @return string
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuCliente
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
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return RhuCliente
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return RhuCliente
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return RhuCliente
     */
    public function setCodigoAsesorFk($codigoAsesorFk)
    {
        $this->codigoAsesorFk = $codigoAsesorFk;

        return $this;
    }

    /**
     * Get codigoAsesorFk
     *
     * @return integer
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuCliente
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return RhuCliente
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuCliente
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuCliente
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
     * @return RhuCliente
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
     * Set fax
     *
     * @param string $fax
     *
     * @return RhuCliente
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return RhuCliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gerente
     *
     * @param string $gerente
     *
     * @return RhuCliente
     */
    public function setGerente($gerente)
    {
        $this->gerente = $gerente;

        return $this;
    }

    /**
     * Get gerente
     *
     * @return string
     */
    public function getGerente()
    {
        return $this->gerente;
    }

    /**
     * Set celularGerente
     *
     * @param string $celularGerente
     *
     * @return RhuCliente
     */
    public function setCelularGerente($celularGerente)
    {
        $this->celularGerente = $celularGerente;

        return $this;
    }

    /**
     * Get celularGerente
     *
     * @return string
     */
    public function getCelularGerente()
    {
        return $this->celularGerente;
    }

    /**
     * Set financiero
     *
     * @param string $financiero
     *
     * @return RhuCliente
     */
    public function setFinanciero($financiero)
    {
        $this->financiero = $financiero;

        return $this;
    }

    /**
     * Get financiero
     *
     * @return string
     */
    public function getFinanciero()
    {
        return $this->financiero;
    }

    /**
     * Set celularFinanciero
     *
     * @param string $celularFinanciero
     *
     * @return RhuCliente
     */
    public function setCelularFinanciero($celularFinanciero)
    {
        $this->celularFinanciero = $celularFinanciero;

        return $this;
    }

    /**
     * Get celularFinanciero
     *
     * @return string
     */
    public function getCelularFinanciero()
    {
        return $this->celularFinanciero;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return RhuCliente
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return RhuCliente
     */
    public function setCelularContacto($celularContacto)
    {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return RhuCliente
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return RhuCliente
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
     * Set cobroExamen
     *
     * @param string $cobroExamen
     *
     * @return RhuCliente
     */
    public function setCobroExamen($cobroExamen)
    {
        $this->cobroExamen = $cobroExamen;

        return $this;
    }

    /**
     * Get cobroExamen
     *
     * @return string
     */
    public function getCobroExamen()
    {
        return $this->cobroExamen;
    }

    /**
     * Set regimenSimplificado
     *
     * @param boolean $regimenSimplificado
     *
     * @return RhuCliente
     */
    public function setRegimenSimplificado($regimenSimplificado)
    {
        $this->regimenSimplificado = $regimenSimplificado;

        return $this;
    }

    /**
     * Get regimenSimplificado
     *
     * @return boolean
     */
    public function getRegimenSimplificado()
    {
        return $this->regimenSimplificado;
    }

    /**
     * Set regimenComun
     *
     * @param boolean $regimenComun
     *
     * @return RhuCliente
     */
    public function setRegimenComun($regimenComun)
    {
        $this->regimenComun = $regimenComun;

        return $this;
    }

    /**
     * Get regimenComun
     *
     * @return boolean
     */
    public function getRegimenComun()
    {
        return $this->regimenComun;
    }

    /**
     * Set autorretenedor
     *
     * @param boolean $autorretenedor
     *
     * @return RhuCliente
     */
    public function setAutorretenedor($autorretenedor)
    {
        $this->autorretenedor = $autorretenedor;

        return $this;
    }

    /**
     * Get autorretenedor
     *
     * @return boolean
     */
    public function getAutorretenedor()
    {
        return $this->autorretenedor;
    }

    /**
     * Set retencionIva
     *
     * @param boolean $retencionIva
     *
     * @return RhuCliente
     */
    public function setRetencionIva($retencionIva)
    {
        $this->retencionIva = $retencionIva;

        return $this;
    }

    /**
     * Get retencionIva
     *
     * @return boolean
     */
    public function getRetencionIva()
    {
        return $this->retencionIva;
    }

    /**
     * Set retencionFuente
     *
     * @param boolean $retencionFuente
     *
     * @return RhuCliente
     */
    public function setRetencionFuente($retencionFuente)
    {
        $this->retencionFuente = $retencionFuente;

        return $this;
    }

    /**
     * Get retencionFuente
     *
     * @return boolean
     */
    public function getRetencionFuente()
    {
        return $this->retencionFuente;
    }

    /**
     * Set vrPrecioSeleccion
     *
     * @param float $vrPrecioSeleccion
     *
     * @return RhuCliente
     */
    public function setVrPrecioSeleccion($vrPrecioSeleccion)
    {
        $this->vrPrecioSeleccion = $vrPrecioSeleccion;

        return $this;
    }

    /**
     * Get vrPrecioSeleccion
     *
     * @return float
     */
    public function getVrPrecioSeleccion()
    {
        return $this->vrPrecioSeleccion;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCliente
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
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return RhuCliente
     */
    public function setFormaPagoRel(\Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel = null)
    {
        $this->formaPagoRel = $formaPagoRel;

        return $this;
    }

    /**
     * Get formaPagoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormaPago
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuCliente
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesor $asesorRel
     *
     * @return RhuCliente
     */
    public function setAsesorRel(\Brasa\GeneralBundle\Entity\GenAsesor $asesorRel = null)
    {
        $this->asesorRel = $asesorRel;

        return $this;
    }

    /**
     * Get asesorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenAsesor
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * Add centrosCostosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel
     *
     * @return RhuCliente
     */
    public function addCentrosCostosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel)
    {
        $this->centrosCostosClienteRel[] = $centrosCostosClienteRel;

        return $this;
    }

    /**
     * Remove centrosCostosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel
     */
    public function removeCentrosCostosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosClienteRel)
    {
        $this->centrosCostosClienteRel->removeElement($centrosCostosClienteRel);
    }

    /**
     * Get centrosCostosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosCostosClienteRel()
    {
        return $this->centrosCostosClienteRel;
    }

    /**
     * Add contratosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClienteRel
     *
     * @return RhuCliente
     */
    public function addContratosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClienteRel)
    {
        $this->contratosClienteRel[] = $contratosClienteRel;

        return $this;
    }

    /**
     * Remove contratosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClienteRel
     */
    public function removeContratosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosClienteRel)
    {
        $this->contratosClienteRel->removeElement($contratosClienteRel);
    }

    /**
     * Get contratosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosClienteRel()
    {
        return $this->contratosClienteRel;
    }

    /**
     * Add incapacidadesClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesClienteRel
     *
     * @return RhuCliente
     */
    public function addIncapacidadesClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesClienteRel)
    {
        $this->incapacidadesClienteRel[] = $incapacidadesClienteRel;

        return $this;
    }

    /**
     * Remove incapacidadesClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesClienteRel
     */
    public function removeIncapacidadesClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesClienteRel)
    {
        $this->incapacidadesClienteRel->removeElement($incapacidadesClienteRel);
    }

    /**
     * Get incapacidadesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesClienteRel()
    {
        return $this->incapacidadesClienteRel;
    }

    /**
     * Add visitasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasClienteRel
     *
     * @return RhuCliente
     */
    public function addVisitasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasClienteRel)
    {
        $this->visitasClienteRel[] = $visitasClienteRel;

        return $this;
    }

    /**
     * Remove visitasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasClienteRel
     */
    public function removeVisitasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuVisita $visitasClienteRel)
    {
        $this->visitasClienteRel->removeElement($visitasClienteRel);
    }

    /**
     * Get visitasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitasClienteRel()
    {
        return $this->visitasClienteRel;
    }

    /**
     * Add pruebasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasClienteRel
     *
     * @return RhuCliente
     */
    public function addPruebasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasClienteRel)
    {
        $this->pruebasClienteRel[] = $pruebasClienteRel;

        return $this;
    }

    /**
     * Remove pruebasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasClienteRel
     */
    public function removePruebasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuPrueba $pruebasClienteRel)
    {
        $this->pruebasClienteRel->removeElement($pruebasClienteRel);
    }

    /**
     * Get pruebasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasClienteRel()
    {
        return $this->pruebasClienteRel;
    }

    /**
     * Add poligrafiasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasClienteRel
     *
     * @return RhuCliente
     */
    public function addPoligrafiasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasClienteRel)
    {
        $this->poligrafiasClienteRel[] = $poligrafiasClienteRel;

        return $this;
    }

    /**
     * Remove poligrafiasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasClienteRel
     */
    public function removePoligrafiasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuPoligrafia $poligrafiasClienteRel)
    {
        $this->poligrafiasClienteRel->removeElement($poligrafiasClienteRel);
    }

    /**
     * Get poligrafiasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPoligrafiasClienteRel()
    {
        return $this->poligrafiasClienteRel;
    }

    /**
     * Add facturasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasClienteRel
     *
     * @return RhuCliente
     */
    public function addFacturasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasClienteRel)
    {
        $this->facturasClienteRel[] = $facturasClienteRel;

        return $this;
    }

    /**
     * Remove facturasClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasClienteRel
     */
    public function removeFacturasClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturasClienteRel)
    {
        $this->facturasClienteRel->removeElement($facturasClienteRel);
    }

    /**
     * Get facturasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * Add sucursalClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSucursal $sucursalClienteRel
     *
     * @return RhuCliente
     */
    public function addSucursalClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSucursal $sucursalClienteRel)
    {
        $this->sucursalClienteRel[] = $sucursalClienteRel;

        return $this;
    }

    /**
     * Remove sucursalClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSucursal $sucursalClienteRel
     */
    public function removeSucursalClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSucursal $sucursalClienteRel)
    {
        $this->sucursalClienteRel->removeElement($sucursalClienteRel);
    }

    /**
     * Get sucursalClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSucursalClienteRel()
    {
        return $this->sucursalClienteRel;
    }

    /**
     * Add examenesClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesClienteRel
     *
     * @return RhuCliente
     */
    public function addExamenesClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesClienteRel)
    {
        $this->examenesClienteRel[] = $examenesClienteRel;

        return $this;
    }

    /**
     * Remove examenesClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesClienteRel
     */
    public function removeExamenesClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesClienteRel)
    {
        $this->examenesClienteRel->removeElement($examenesClienteRel);
    }

    /**
     * Get examenesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesClienteRel()
    {
        return $this->examenesClienteRel;
    }

    /**
     * Add cobrosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosClienteRel
     *
     * @return RhuCliente
     */
    public function addCobrosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosClienteRel)
    {
        $this->cobrosClienteRel[] = $cobrosClienteRel;

        return $this;
    }

    /**
     * Remove cobrosClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosClienteRel
     */
    public function removeCobrosClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCobro $cobrosClienteRel)
    {
        $this->cobrosClienteRel->removeElement($cobrosClienteRel);
    }

    /**
     * Get cobrosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCobrosClienteRel()
    {
        return $this->cobrosClienteRel;
    }

    /**
     * Add serviciosCobrarClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarClienteRel
     *
     * @return RhuCliente
     */
    public function addServiciosCobrarClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarClienteRel)
    {
        $this->serviciosCobrarClienteRel[] = $serviciosCobrarClienteRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarClienteRel
     */
    public function removeServiciosCobrarClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarClienteRel)
    {
        $this->serviciosCobrarClienteRel->removeElement($serviciosCobrarClienteRel);
    }

    /**
     * Get serviciosCobrarClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarClienteRel()
    {
        return $this->serviciosCobrarClienteRel;
    }

    /**
     * Add seleccionesClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesClienteRel
     *
     * @return RhuCliente
     */
    public function addSeleccionesClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesClienteRel)
    {
        $this->seleccionesClienteRel[] = $seleccionesClienteRel;

        return $this;
    }

    /**
     * Remove seleccionesClienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesClienteRel
     */
    public function removeSeleccionesClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesClienteRel)
    {
        $this->seleccionesClienteRel->removeElement($seleccionesClienteRel);
    }

    /**
     * Get seleccionesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesClienteRel()
    {
        return $this->seleccionesClienteRel;
    }
}
