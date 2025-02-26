<?php

namespace SamarioPHP\Sistema\Utilidades;

use SamarioPHP\BaseDeDatos\BaseDatos;

class BD {

    // Método para comprobar si la base de datos está vacía
    public static function vacia() {
        $conexion = self::obtenerConexion();
        $nombreBaseDatos = config('base_de_datos.nombre_basedatos');
        $consulta = "SELECT COUNT(*) as total FROM information_schema.tables 
                 WHERE table_schema = :nombre_basededatos 
                 AND table_name NOT LIKE 'phinx%'";
        // Ejecutar la consulta usando Medoo (u otra forma de manejar la consulta)
        $resultado = $conexion->consultarSQL($consulta, [":nombre_basededatos" => $nombreBaseDatos]);
        if ($resultado) {
            if ($resultado[0]) {                
                return $resultado[0]->total ?? 0;
            }
        }
        throw new Exception("algo fallo al intentar consultar en la base de datos.");
    }

    // Método para comprobar si la base de datos está vacía
    public static function obtenerConexion(): \SamarioPHP\BaseDeDatos\Conexion {
        $instancia = BaseDatos::inicializar();
        return $instancia->obtenerConexion();
    }

    public static function __callStatic($metodo, $argumentos) {
        $instancia = BaseDatos::inicializar();
        if (method_exists($instancia->conexion, $metodo)) {
            return call_user_func_array([$instancia->conexion, $metodo], $argumentos);
        }
        throw new \Exception("Método $metodo no encontrado en la clase Conexion.");
    }
}
