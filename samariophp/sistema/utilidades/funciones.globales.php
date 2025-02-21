<?php

function vista(string $vista, array $datos = []): \Psr\Http\Message\ResponseInterface {
    return SamarioPHP\Sistema\Utilidades\Vistas::renderizar($vista, $datos);
}

function redirigir($ruta) {
    header("Location: " . $ruta);
    exit;
}

// Obtiene el valor de una variable de entorno o una configuración// Obtiene el valor de una variable de entorno o configuración con soporte para arrays anidados
function config($clave) {
    static $config = null;

    if ($config === null) {
        $config = require RUTA_CONFIGURACION;
    }

    $claves = explode('.', $clave);
    $valor = $config;

    foreach ($claves as $segmento) {
        if (!isset($valor[$segmento])) {
            return null;
        }
        $valor = $valor[$segmento];
    }

    return $valor;
}

function alerta($mensaje, $datos = []) {
    return Respuesta::alerta($mensaje, $datos);
}

function error($mensaje, $datos = []) {
    return Respuesta::error($mensaje, $datos);
}

function exito($mensaje, $datos = []) {
    return Respuesta::exito($mensaje, $datos);
}
