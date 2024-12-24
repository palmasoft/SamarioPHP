<?php
require_once DIR_SYS . '/vendor/autoload.php';
/**
 * Autoloader para las clases en la carpeta Ayudas.
 * Registra las clases automáticamente para evitar el uso de "use".
 */
spl_autoload_register(function ($nombreClase) {
  // Convertir el nombre de clase en una ruta relativa  
  $rutaClaseRelativa = str_replace('\\', '/', $nombreClase) . '.php';

  // Buscar recursivamente dentro del directorio raíz
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(DIR_APP_SYS));
  foreach ($iterator as $archivo) {
    if ($archivo->isFile() && $archivo->getFilename() === basename($rutaClaseRelativa)) {
      include_once $archivo->getPathname();
      return;
    }
  }

  // Si no se encuentra el archivo, logear el error
  GestorLog::log("aplicacion", "error", "No se encontró la clase: {$nombreClase}, ruta esperada: {$rutaClaseRelativa}");
});