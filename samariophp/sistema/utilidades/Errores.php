<?php
namespace SamarioPHP\Sistema\Utilidades;

use SamarioPHP\Sistema\Utilidades\Log;

class Errores {

  private $configuracion;

  public function __construct(array $configuracion) {
    $this->configuracion = $configuracion;
  }

  public function inicializar() {
    // Manejo de excepciones no controladas 
    set_exception_handler([$this, 'manejarExcepcion']);

    // Manejo de errores PHP
    set_error_handler([$this, 'manejarError']);

    // Manejo de errores fatales
    register_shutdown_function([$this, 'manejarErrorFatal']);
  }

  public function manejarExcepcion($excepcion) {
    $mensaje = "Excepción no controlada: " . $excepcion->getMessage();
    $datos = [
        'archivo' => $excepcion->getFile(),
        'línea' => $excepcion->getLine(),
        'traza' => $excepcion->getTraceAsString() 
    ];
    $this->mostrarMensaje($mensaje, $datos);
    Log::error($mensaje, $datos, 'errores');
    $this->finalizarEjecucion();
  }

  public function manejarError($nivel, $mensaje, $archivo, $línea) {
    $mensajeError = "Error PHP: $mensaje";
    $datos = [
        'nivel' => $nivel,
        'archivo' => $archivo,
        'línea' => $línea
    ];
    $this->mostrarMensaje($mensajeError, $datos);
    Log::warning($mensajeError, $datos, 'errores');
  }

  public function manejarErrorFatal() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
      $mensaje = "Error fatal: {$error['message']} en {$error['file']}:{$error['line']}";
      $this->mostrarMensaje($mensaje);
      Log::critico($mensaje, [], 'errores');
      $this->finalizarEjecucion();
    }
  }

  private function mostrarMensaje($mensaje, $datos = []) {
    if ($this->configuracion['entorno'] === 'desarrollo') {
      echo "<pre>";
      echo "<strong>$mensaje</strong><br>";
      foreach ($datos as $clave => $valor) {
        echo ucfirst($clave) . ": $valor<br>";
      }
      echo "</pre>";
    } else {
      http_response_code(500);
      echo "Ocurrió un error interno. Inténtalo más tarde.";
    }
  }

  private function finalizarEjecucion() {
    exit;
  }
}
