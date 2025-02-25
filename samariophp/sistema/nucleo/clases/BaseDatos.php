<?php

namespace SamarioPHP\Sistema;

use SamarioPHP\BaseDeDatos\Conexion;

class BaseDatos {

    // La instancia estática para la conexión
    private static $Instancia;
    public $conexion;

    // Método para obtener la instancia de la conexión
    public static function iniciar() {
        // Verificar si ya existe una conexión
        if (self::$Instancia === null) {
            $configuracion = require_once RUTA_CONFIG_MEEDO;
            // Si no existe, crear una nueva instancia de la clase BaseDatos
            self::$Instancia = new self($configuracion);
        }
        return self::$Instancia;  // Retorna la instancia de BaseDatos
    }

    // Constructor que inicializa la conexión
    function __construct($configuracion) {
        $this->conexion = new Conexion($configuracion);  // Conexión real
    }

    // Previene la clonación de la instancia
    function __clone() {
        
    }

    // Previene la deserialización de la instancia
    function __wakeup() {
        
    }

    // Método para comprobar si la base de datos está vacía
    public function estaVacia() {
        $consulta = "SELECT COUNT(*) as total FROM information_schema.tables 
                 WHERE table_schema = ? 
                 AND table_name NOT LIKE 'phinx%'";

        $nombreBaseDatos = $GLOBALS['configuracion']['base_de_datos']['nombre_basedatos'];

        // Ejecutar la consulta usando Medoo (u otra forma de manejar la consulta)
        $resultado = $this->conexion->query($consulta, [
            1 => $nombreBaseDatos
        ]);

        // Obtener el total de las tablas
        $total = $resultado->fetchColumn();

        return $total == 0; // Si no hay tablas, la base de datos está vacía
    }

    // Método para acceder a una tabla específica
    public function tabla($nombre) {
        return $this->conexion->tabla($nombre);
    }

    // Método para ejecutar consultas SQL personalizadas
    public function consultarSQL($consulta) {
        return $this->conexion->consultarSQL($consulta);
    }

    // Método para comprobar si la base de datos está vacía
    public static function vacia() {
        $consulta = "SELECT COUNT(*) as total FROM information_schema.tables 
                     WHERE table_schema = ? 
                     AND table_name NOT LIKE 'phinx%'";

        $conexion = self::obtenerConexion();
        $nombreBaseDatos = $GLOBALS['configuracion']['base_de_datos']['nombre_basedatos'];

        $resultado = $conexion->query($consulta, [$nombreBaseDatos]);
        $total = $resultado->fetchColumn();

        return $total == 0;
    }

    // Método estático para acceder a la conexión desde métodos estáticos
    public static function obtenerConexion() {
        if (self::$Instancia === null) {
            throw new \Exception("La conexión aún no se ha iniciado.");
        }
        return self::$Instancia->conexion;
    }

    // Método estático para ejecutar consultas SQL
    public static function consultar($consulta, $parametros = []) {
        $conexion = self::obtenerConexion();
        return $conexion->query($consulta, $parametros);
    }
}
