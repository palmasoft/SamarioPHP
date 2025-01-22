<?php
namespace SamarioPHP\Sistema\Ayudas;

class GestorLog {

  private static $loggers = [];

  /**
   * Inicializar un logger.
   *
   * @param string $nombre Nombre del logger.
   * @param object $instancia Instancia del logger (Monolog\Logger).
   * @return void
   */
  public static function registrarLogger($nombre, $instancia) {
    self::$loggers[$nombre] = $instancia;
  }

  /**
   * Obtener un logger registrado.
   *
   * @param string $nombre Nombre del logger.
   * @return object|null Retorna la instancia del logger o null si no existe.
   */
  public static function obtenerLogger($nombre) {
    return self::$loggers[$nombre] ?? null;
  }

  /**
   * Registrar una entrada de log en un logger específico.
   *
   * @param string $nombre Nombre del logger.
   * @param string $nivel Nivel del log ('info', 'warning', 'error', etc.).
   * @param string $mensaje Mensaje del log.
   * @param array $contexto Contexto adicional para el log.
   * @return void
   */
  public static function log($nombre, $nivel, $mensaje, array $contexto = []) {
    if (isset(self::$loggers[$nombre])) {
      $logger = self::$loggers[$nombre];
      if (method_exists($logger, $nivel)) {
        $logger->$nivel($mensaje, $contexto);
      } else {
        throw new \Exception("Nivel de log '{$nivel}' no soportado.");
      }
    } else {
      throw new \Exception("Logger '{$nombre}' no encontrado.");
    }
  }
}