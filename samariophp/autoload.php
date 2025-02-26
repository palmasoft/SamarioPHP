<?php

require_once DIR_FRAMEWORK . '/vendor/autoload.php';

/**
 * Autoloader personalizado para cargar clases desde las carpetas definidas.
 */
spl_autoload_register(function ($nombreClase) {
    // Extraer solo el nombre de la clase (última parte del namespace)
    $nombreClaseSimple = basename(str_replace('\\', '/', $nombreClase));

    // Obtener la ruta completa con la extensión .php
    $rutaClaseRelativa = str_replace('\\', '/', $nombreClase) . '.php';

    // Obtener solo la ruta de la carpeta (sin el nombre de la clase)
    $rutaCarpeta = strtolower(dirname(str_replace('\\', '/', $nombreClase)) . SEPARA_CARPETA);

    // Construir la ruta con el nombre de la clase al final
    $rutaConNombre = $rutaCarpeta . $nombreClaseSimple;

    // Verificar si la clase pertenece a SamarioPHP
    if (strpos($nombreClase, 'SamarioPHP') === 0) {
        $rutaEsperada = DIR_FRAMEWORK . $rutaCarpeta . $nombreClaseSimple . ".php";
        if (file_exists($rutaEsperada)) {
            require_once $rutaEsperada;
            return;
        }
    }




//    echo "buscando.....{$nombreClaseSimple}........<br />";
    // Si no se encuentra en DIR_SPHP, buscar en las otras carpetas definidas
    $rutasExploradas = [];
    $directorios = [DIR_SYS, DIR_MODELOS, DIR_APP,];
    foreach ($directorios as $directorioBase) {
        $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directorioBase, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $archivo) {
            $rutaActual = $archivo->getPathname();
            array_push($rutasExploradas, $rutaActual);
            if (basename($rutaActual, '.php') === $nombreClaseSimple) {
                require_once $rutaActual;
                return;
            }
        }
    }

    $msg = "Autoload Error: No se encontró la clase '{$nombreClase}'. Ruta esperada: {$rutaClaseRelativa}";
    // Si no se encuentra la clase, registrar el error
    error_log($msg);
});
