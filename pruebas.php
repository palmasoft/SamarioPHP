<?php
function listarArchivosYCarpetas($directorio, $ignorar = [], $nivel = 0) {
    // Comprobamos si el directorio existe
    if (!is_dir($directorio)) {
        echo "El directorio no existe.";
        return;
    }

    // Abrimos el directorio
    $contenido = scandir($directorio);

    // Arrays para almacenar archivos y carpetas
    $archivos = [];
    $carpetas = [];

    // Iteramos sobre los contenidos del directorio
    foreach ($contenido as $archivo) {
        // Excluimos los directorios . y .. y los elementos ignorados
        if ($archivo !== '.' && $archivo !== '..') {
            $rutaCompleta = $directorio . DIRECTORY_SEPARATOR . $archivo;

            // Comprobamos si la carpeta o archivo está en la lista de ignorados
            if (in_array($archivo, $ignorar)) {
                continue; // Saltamos a la siguiente iteración si está en la lista de ignorados
            }

            // Verificamos si es una carpeta o un archivo y los almacenamos en el array correspondiente
            if (is_dir($rutaCompleta)) {
                $carpetas[] = $archivo;
            } else {
                $archivos[] = $archivo;
            }
        }
    }

    // Imprimimos primero los archivos
    foreach ($archivos as $archivo) {
        $indentacion = str_repeat('    ', $nivel);
        echo $indentacion . "└── $archivo" . PHP_EOL;
    }

    // Luego, imprimimos las carpetas
    foreach ($carpetas as $carpeta) {
        $indentacion = str_repeat('    ', $nivel);
        echo $indentacion . "└── $carpeta/" . PHP_EOL;
        // Llamada recursiva para explorar subcarpetas
        listarArchivosYCarpetas($directorio . DIRECTORY_SEPARATOR . $carpeta, $ignorar, $nivel + 1);
    }
}

// Usamos la función con el directorio raíz deseado y un vector de carpetas/archivos a ignorar
$directorioRaiz = __DIR__; // Cambia esta ruta por el directorio que quieras explorar
$ignorar = ['.git', '.gitattributes', '.gitignore', '.gitattributes', '.user.ini', '.well-known', 'cgi-bin', 'vendor', 'pruebas.php']; // Especifica las carpetas/archivos a ignorar
listarArchivosYCarpetas($directorioRaiz, $ignorar);
