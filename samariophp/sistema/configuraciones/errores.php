<?php
/**
 * Configuración para manejo de errores y excepciones
 *
 * @param Logger $logger Instancia del logger para registrar errores
 * @param array $configuracion Configuración general del sistema
 */
return function ($configuracion, $logger) { 

// Manejo de excepciones globales
  set_exception_handler(function ($excepcion) use ($logger, $configuracion) {
    if ($configuracion['aplicacion']['entorno'] === 'desarrollo') {
      echo "<pre>";
      echo "Excepción no controlada: " . $excepcion->getMessage() . "<br>";
      echo "Archivo: " . $excepcion->getFile() . "<br>";
      echo "Línea: " . $excepcion->getLine() . "<br>";
      echo "Traza: " . nl2br($excepcion->getTraceAsString()) . "<br>";
      echo "</pre>";
    }

    // Registro del error en el log
    $logger->error('Excepción no controlada: ' . $excepcion->getMessage(), [
        'archivo' => $excepcion->getFile(),
        'línea' => $excepcion->getLine(),
        'traza' => $excepcion->getTraceAsString(),
        'peticion' => $_SERVER['REQUEST_URI'],
        'entorno' => $configuracion['aplicacion']['entorno']
    ]);

    // En producción, mostramos un mensaje genérico
    if ($configuracion['aplicacion']['entorno'] === 'produccion') {
      http_response_code(500);
      echo "Ocurrió un error interno. Inténtalo más tarde.";
    }

    exit;
  });

// Manejo de errores PHP
  set_error_handler(function ($nivel, $mensaje, $archivo, $línea) use ($logger, $configuracion) {
    if ($configuracion['aplicacion']['entorno'] === 'desarrollo') {
      echo "<pre>";
      echo "Error PHP: $mensaje<br>";
      echo "Archivo: $archivo<br>";
      echo "Línea: $línea<br>";
      echo "</pre>";
    }

    // Registro del error en el log
    $logger->warning("Error PHP: $mensaje", [
        'nivel' => $nivel,
        'archivo' => $archivo,
        'línea' => $línea,
        'entorno' => $configuracion['aplicacion']['entorno']
    ]);

    if ($configuracion['aplicacion']['entorno'] === 'produccion') {
      http_response_code(500);
      echo "Ocurrió un error interno. Inténtalo más tarde.";
    }
  });
};

