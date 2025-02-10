<?php
require_once DIR_FRAMEWORK . '/vendor/autoload.php';

/**
 * Autoloader personalizado para cargar clases desde las carpetas definidas.
 */
spl_autoload_register(function ($nombreClase) {
  // Convertir el nombre de la clase a una ruta relativa
  $rutaClaseRelativa = str_replace('\\', '/', $nombreClase) . '.php';

  // Verificar si la clase pertenece a SamarioPHP
  if (strpos($nombreClase, 'SamarioPHP') === 0) {
    $rutaEsperada = DIR_SPHP . '' . strtolower(str_replace('SamarioPHP', '', $rutaClaseRelativa));
    if (file_exists($rutaEsperada)) {
      include_once $rutaEsperada;
      return;
    }
  }


  // Extraer solo el nombre de la clase (última parte del namespace)
  $nombreClaseSimple = basename(str_replace('\\', '/', $nombreClase));

  // Si no se encuentra en DIR_SPHP, buscar en las otras carpetas definidas
  $rutasExploradas = [];
  $directorios = [DIR_SYS, DIR_MODELOS, DIR_APP,];
  foreach ($directorios as $directorioBase) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directorioBase, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $archivo) {
      $rutaActual = $archivo->getPathname();
      $rutasExploradas[] = $rutaActual;
      if (basename($rutaActual, '.php') === $nombreClaseSimple) {
        include_once $rutaActual;
        return;
      }
    }
  }

  //
  // Si no se encuentra la clase, registrar el error
  error_log("Autoload Error: No se encontró la clase '{$nombreClase}'. Ruta esperada: {$rutaClaseRelativa}");
});

