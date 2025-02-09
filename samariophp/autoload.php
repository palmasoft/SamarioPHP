<?php
require_once DIR_FRAMEWORK . '/vendor/autoload.php';

/**
 * Autoloader para las clases en las carpetas definidas (DIR_SYS y DIR_APP).
 */
spl_autoload_register(function ($nombreClase) {
    // Convertir el nombre de clase en una ruta relativa con '/'
    $rutaClaseRelativa = str_replace('\\', '/', $nombreClase) . '.php';

    // Buscar en las carpetas DIR_SYS y DIR_APP
    $directorios = [DIR_SYS, DIR_APP];
    foreach ($directorios as $directorioBase) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directorioBase, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $archivo) {
            if ($archivo->isFile() && realpath($archivo->getPathname()) === realpath($directorioBase . '/' . $rutaClaseRelativa)) {
                include_once $archivo->getPathname();
                return;
            }
        }
    }

    // Si no se encuentra el archivo, logear el error
    GestorLog::log("aplicacion", "error", "No se encontró la clase: {$nombreClase}, ruta esperada: {$rutaClaseRelativa}");
});
