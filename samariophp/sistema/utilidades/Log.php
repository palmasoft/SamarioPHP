<?php
namespace SamarioPHP\Sistema\Utilidades;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {

  protected static $canales = [];
  protected static $canalPredeterminado;
  protected static $inicializado = false;

  /**
   * Inicializa los canales de log basados en la configuración.
   */
  public static function inicializar() {
    if (self::$inicializado) {
      return;
    }

    if (!defined('RUTA_CONFIG_LOGS') || !file_exists(RUTA_CONFIG_LOGS)) {
      throw new \Exception("La ruta de configuración de logs no está definida o no existe.");
    }

    $config = require RUTA_CONFIG_LOGS;
    self::$canalPredeterminado = $config['default'] ?? 'default';

    foreach ($config['canales'] as $nombre => $canal) {
      $ruta = $canal['ruta'] ?? "/logs/{$nombre}.log";
      $nivel = self::nivelMonolog($canal['nivel'] ?? 'error');
      self::crearCanal($nombre, $ruta, $nivel);
    }

    self::$inicializado = true;
  }

  /**
   * Crea un canal de log personalizado.
   */
  protected static function crearCanal($nombre, $ruta, $nivel) {
    $logger = new Logger($nombre);
    $logger->pushHandler(new StreamHandler($ruta, $nivel));
    self::$canales[$nombre] = $logger;
  }

  /**
   * Escribe un mensaje en el canal especificado.
   */public static function log($canal, $nivel, $mensaje, array $contexto = []) {
    try {
      $canal = $canal ?? self::$canalPredeterminado;

      if (!isset(self::$canales[$canal])) {
        throw new \Exception("El canal de log '{$canal}' no está configurado.");
      }

      $logger = self::$canales[$canal];
      $nivel = strtolower($nivel);

      if (method_exists($logger, $nivel)) {
        $logger->$nivel($mensaje, $contexto);
      } else {
        $logger->log(self::nivelMonolog($nivel), $mensaje, $contexto);
      }
    } catch (\Exception $e) {
      // Manejar el error registrándolo en el canal de errores generales
      if (isset(self::$canales['errores'])) {
        self::$canales['errores']->error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
      } else {
        error_log($e->getMessage()); // Alternativa si el canal de errores no está disponible
      }
    }
  }

  /**
   * Métodos rápidos para diferentes niveles de log.
   */
  public static function info($mensaje, array $contexto = [], $canal = null) {
    self::log($canal, 'info', $mensaje, $contexto);
  }

  public static function error($mensaje, array $contexto = [], $canal = null) {
    self::log($canal, 'error', $mensaje, $contexto);
  }

  public static function critical($mensaje, array $contexto = [], $canal = null) {
    self::log($canal, 'critical', $mensaje, $contexto);
  }

  public static function sql($mensaje, array $contexto = []) {
    self::log('basededatos', 'info', $mensaje, $contexto);
  }

  public static function auth($mensaje, array $contexto = []) {
    self::log('autenticacion', 'warning', $mensaje, $contexto);
  }

  /**
   * Convierte un nivel de texto a nivel de Monolog.
   */
  private static function nivelMonolog($nivel) {
    $niveles = [
        'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::NOTICE,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alert' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];
    return $niveles[$nivel] ?? Logger::ERROR;
  }

}