<?php

class selects extends MySQL {

    var $code = "";

    function cargar1() {
        $consulta = parent::consulta("SELECT codigo_cliente_pk,  nombre_corto FROM rhu_cliente ORDER BY nombre_corto ASC");
        $num_total_registros = parent::num_rows($consulta);
        if ($num_total_registros > 0) {
            $clientes = array();
            while ($cliente = parent::fetch_assoc($consulta)) {
                $code = $cliente["codigo_cliente_pk"];
                $name = $cliente["nombre_corto"];
                $clientes[$code] = $name;
            }
            return $clientes;
        } else {
            return false;
        }
    }

    function cargarSucursal() {
        $consulta = parent::consulta("SELECT codigo_sucursal_pk, nombre FROM rhu_sucursal WHERE codigo_sucursal_pk='". $this->code ."'");
        $num_total_registros = parent::num_rows($consulta);
        if ($num_total_registros > 0) {
            $estados = array();
            while ($estado = parent::fetch_assoc($consulta)) {
                $code = $estado["codigo_sucursal_pk"];
                $nombre = $estado["nombre"];
                $estados[$code] = $nombre;
            }
            return $estados;
        } else {
            return false;
        }
    }

    function cargarCentro() {
        $consulta = parent::consulta("SELECT codigo_centro_trabajo_pk, nombre FROM rhu_centro_trabajo WHERE codigo_centro_trabajo_pk='" . $this->code . "'");
        $num_total_registros = parent::num_rows($consulta);
        if ($num_total_registros > 0) {
            $centros = array();
            while($centro = parent::fetch_assoc($consulta))
                    {
                 $code = $centro["codigo_centro_trabajo_pk"];
                $nombre = $centro["nombre"];
                 $centros[$code]= $nombre;       
                    }
                    return $centros;
            }else{
                return false;
            }
        }
    }

?>