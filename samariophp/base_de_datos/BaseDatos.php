<?php
namespace SamarioPHP\BaseDeDatos;

class BaseDatos {

  private static $conexion;

  // Inicializar conexión
  public static function iniciar($configuracion) {
    if (!self::$conexion) {
      self::$conexion = new Conexion($configuracion);
    }
    return self::$conexion;
  }

  // Comprobar si la base de datos está vacía
  public static function estaVacia() {
    $consulta = "SELECT COUNT(*) as total FROM information_schema.tables 
             WHERE table_schema = ? 
             AND table_name NOT LIKE 'phinx%'";

     $nombreBaseDatos = $GLOBALS['configuracion']['base_de_datos']['nombre_basedatos'];
    // Ejecutar la consulta usando Medoo
    // Asegúrate de que el índice de los parámetros comience en 1
    $resultado = self::$conexion->query($consulta, [
        1 => $GLOBALS['configuracion']['base_de_datos']['nombre_basedatos']
    ]);

    $total = $resultado->fetchColumn(); // Obtiene la primera columna del resultado

    return $total;
  }

  // Flujo para consultas personalizadas
  public static function tabla($nombre) {
    return self::$conexion->tabla($nombre);
  }

  // Consulta directa
  public static function consultarSQL($consulta) {
    return self::$conexion->consultarSQL($consulta);
  }
}