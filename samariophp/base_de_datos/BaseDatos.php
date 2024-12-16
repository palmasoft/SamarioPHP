<?php
namespace SamarioPHP\BaseDeDatos;

class BaseDatos {

  private static $conexion;
  private static $tabla;
  private static $condiciones = [];
  private static $orden = [];
  private static $grupo = [];
  private static $joins = [];

  // Inicializar conexión
  public static function iniciar($configuracion_global) {
    if (!self::$conexion) {
      self::$conexion = new Conexion($configuracion_global);
    }
    return self::$conexion;
  }

  // Verificar si la base de datos está vacía
  public static function estaVacia() {
    return self::consultarSQL(
            "SELECT * FROM information_schema.tables "
            . "WHERE table_schema = '{$GLOBALS['configuracion']['base_de_datos']['nombre_basedatos']}' "
            . "AND table_name NOT LIKE 'phinx%'; "
    );
  }

  // Consulta directa SQL
  public static function consultarSQL($consulta) {
    return self::$conexion->query($consulta)->fetchAll();
  }

  // Seleccionar tabla principal
  public static function tabla($nombre) {
    self::$tabla = $nombre;
    self::resetConfiguraciones();
    return new static();
  }

  // Agregar condiciones WHERE
  public static function donde(array $condiciones) {
    self::$condiciones = $condiciones;
    return new static();
  }

  // Agregar ordenamiento ORDER BY
  public static function ordenadoPor(array $orden) {
    self::$orden = $orden;
    return new static();
  }

  // Agregar agrupamiento GROUP BY
  public static function agrupadoPor(array $grupo) {
    self::$grupo = $grupo;
    return new static();
  }

  // Agregar uniones JOIN
  public static function unir($tabla, $condicion, $tipo = 'INNER') {
    self::$joins[] = [$tabla, $condicion, $tipo];
    return new static();
  }

  // Ejecutar consulta y devolver resultados
  public static function obtener($columnas = '*') {
    $queryOpciones = [
        'ORDER' => self::$orden,
        'GROUP' => self::$grupo,
        'JOIN' => self::procesarJoins(),
        'WHERE' => self::$condiciones,
    ];

    $resultados = self::$conexion->select(self::$tabla, $columnas, $queryOpciones);

    // Reiniciar configuraciones después de la consulta
    self::resetConfiguraciones();

    return $resultados;
  }

  // Contar registros
  public static function contar() {
    return self::$conexion->count(self::$tabla, self::$condiciones);
  }

  // Procesar los joins para Medoo
  private static function procesarJoins() {
    $joinsProcesados = [];
    foreach (self::$joins as $join) {
      [$tabla, $condicion, $tipo] = $join;
      $joinsProcesados[$tipo][$tabla] = $condicion;
    }
    return $joinsProcesados;
  }

  // Reiniciar configuraciones internas
  private static function resetConfiguraciones() {
    self::$condiciones = [];
    self::$orden = [];
    self::$grupo = [];
    self::$joins = [];
  }
}