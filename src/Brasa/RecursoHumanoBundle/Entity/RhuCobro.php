<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cobro")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCobroRepository")
 */
class RhuCobro
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cobro_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCobroPk;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;         
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;       
    
    /**
     * @ORM\Column(name="vr_basico", type="float")
     */
    private $vrBasico = 0;     

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCliente", inversedBy="cobrosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuServicioCobrar", mappedBy="cobroRel")
     */
    protected $serviciosCobrarCobroRel;    
    
    /**
     * Get codigoCobroPk
     *
     * @return integer
     */
    public function getCodigoCobroPk()
    {
        return $this->codigoCobroPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return RhuCobro
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCobro
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
     * Set vrBasico
     *
     * @param float $vrBasico
     *
     * @return RhuCobro
     */
    public function setVrBasico($vrBasico)
    {
        $this->vrBasico = $vrBasico;

        return $this;
    }

    /**
     * Get vrBasico
     *
     * @return float
     */
    public function getVrBasico()
    {
        return $this->vrBasico;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel
     *
     * @return RhuCobro
     */
    public function setClienteRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCobro
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
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosCobrarCobroRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add serviciosCobrarCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel
     *
     * @return RhuCobro
     */
    public function addServiciosCobrarCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel)
    {
        $this->serviciosCobrarCobroRel[] = $serviciosCobrarCobroRel;

        return $this;
    }

    /**
     * Remove serviciosCobrarCobroRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel
     */
    public function removeServiciosCobrarCobroRel(\Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar $serviciosCobrarCobroRel)
    {
        $this->serviciosCobrarCobroRel->removeElement($serviciosCobrarCobroRel);
    }

    /**
     * Get serviciosCobrarCobroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosCobrarCobroRel()
    {
        return $this->serviciosCobrarCobroRel;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuCobro
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }
}
