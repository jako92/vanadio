<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cierre_mes")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCierreMesRepository")
 */
class TurCierreMes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesPk;             
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;               
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = false;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCostoServicio", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $costosServiciosCierreMesRel;         

    /**
     * @ORM\OneToMany(targetEntity="TurCosto", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $costosCierreMesRel;    
    

}
