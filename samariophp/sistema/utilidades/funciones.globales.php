<?php
use SamarioPHP\Sistema\Utilidades\Vistas;

function vista(string $vista, array $datos = []): \Psr\Http\Message\ResponseInterface {
    return Vistas::renderizar($vista, $datos);
}

function redirigir($ruta) {
    header("Location: " . $ruta);
    exit;
}

// Obtiene el valor de una variable de entorno o una configuración
function config($clave) {
    $config = require RUTA_CONFIGURACION;
    return $config[$clave] ?? null;
}
