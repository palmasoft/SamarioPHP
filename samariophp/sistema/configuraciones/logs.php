<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use SamarioPHP\Ayudas\GestorLog;
/**
 * Configuración de logs para la aplicación.
 *
 * @return array Conjunto de loggers configurados.
 */
return function () {
  // Logger para errores de aplicación
  $logAplicacion = new Logger('aplicacion');
  $logAplicacion->pushHandler(new StreamHandler(RUTA_LOGS . '/errores_aplicacion.log', Logger::ERROR));

  // Logger para errores de servidor
  $logServidor = new Logger('servidor');
  $logServidor->pushHandler(new StreamHandler(RUTA_LOGS . '/errores_servidor.log', Logger::CRITICAL));

  // Logger para errores de ejecución
  $logEjecucion = new Logger('ejecucion');
  $logEjecucion->pushHandler(new StreamHandler(RUTA_LOGS . '/errores_ejecucion.log', Logger::WARNING));

  // Logger general para eventos informativos
  $logEventos = new Logger('eventos');
  $logEventos->pushHandler(new StreamHandler(RUTA_LOGS . '/eventos.log', Logger::INFO));

  // Configurar loggers
  GestorLog::registrarLogger('aplicacion', $logAplicacion);
  GestorLog::registrarLogger('servidor', $logServidor);
  GestorLog::registrarLogger('ejecucion', $logEjecucion);
  GestorLog::registrarLogger('eventos', $logEventos);

  // Retornar loggers configurados
  return [
  'aplicacion' => $logAplicacion,
  'servidor' => $logServidor,
  'ejecucion' => $logEjecucion,
  'eventos' => $logEventos,
  ];
};
