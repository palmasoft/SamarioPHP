<?php
require_once DIR_SYS . '/vendor/autoload.php';
/**
 * Autoloader para las clases en la carpeta Ayudas.
 * Registra las clases automáticamente para evitar el uso de "use".
 */
spl_autoload_register(function ($nombreClase) {
  // Convertir el nombre de clase a una ruta relativa
  $rutaClase = __DIR__ . '/sistema/ayudas/' . basename(str_replace('\\', '/', $nombreClase)) . '.php';
  // Verificar si el archivo existe y cargarlo
  if (file_exists($rutaClase)) {
    include_once $rutaClase;
  } else {
    GestorLog::log("aplicacion", "error", "No se encontró la clase: {$nombreClase}, ruta esperada: {$rutaClase}");
  }
});
