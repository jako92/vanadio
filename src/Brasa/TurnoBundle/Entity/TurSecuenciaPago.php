<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_secuencia_pago")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSecuenciaPagoRepository")
 */
class TurSecuenciaPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_secuencia_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSecuenciaPagoPk;             
    
    /**
     * @ORM\Column(name="filas", type="integer", nullable=true)
     */    
    private $filas = 0;    
      


    /**
     * Get codigoSecuenciaPagoPk
     *
     * @return integer
     */
    public function getCodigoSecuenciaPagoPk()
    {
        return $this->codigoSecuenciaPagoPk;
    }

    /**
     * Set filas
     *
     * @param integer $filas
     *
     * @return TurSecuenciaPago
     */
    public function setFilas($filas)
    {
        $this->filas = $filas;

        return $this;
    }

    /**
     * Get filas
     *
     * @return integer
     */
    public function getFilas()
    {
        return $this->filas;
    }
}
