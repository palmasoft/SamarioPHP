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

function alerta($mensaje, $datos = []) {
    return Respuesta::alerta($mensaje, $datos);
}

function error($mensaje, $datos = []) {
    return Respuesta::error($mensaje, $datos);
}

function exito($mensaje, $datos = []) {
    return Respuesta::exito($mensaje, $datos);
}
