<?php
namespace SamarioPHP\Sistema\Utilidades;

use SamarioPHP\Basededatos\BaseDatos;

class BD {

    public static function __callStatic($metodo, $argumentos) {
        $instancia = BaseDatos::iniciar(config('base_de_datos'));
        if (method_exists($instancia->conexion, $metodo)) {
            return call_user_func_array([$instancia->conexion, $metodo], $argumentos);
        }
        throw new \Exception("Método $metodo no encontrado en la clase Conexion.");
    }

}