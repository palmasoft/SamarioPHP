<?php

//cargar librerias de composer
require_once RUTA_AUTOLOAD; //
require_once RUTA_FUNCIONES;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//use SamarioPHP\Sistema\Aplicacion;
//(Aplicacion::obtenerInstancia())
//    ->arrancar();

$app = AppFactory::create();
$gestorRutas = new GestorRutas();

$app->any('/{ruta:.*}', function ($request, $response, $args) use ($gestorRutas) {
    $ruta = $args['ruta']; // Ruta solicitada
    $metodo = $request->getMethod();
        
    // Normalizar la ruta vacía
    if ($ruta === "/") {
        $ruta = "";
    }

    // Si la ruta es fija, ejecuta su controlador
    if ($gestorRutas->esRutaFija($ruta, $metodo)) {
        return $gestorRutas->ejecutarRutaFija($ruta, $metodo);
    }

    // Si es dinámica, determinar si devuelve JSON o vista
    if ($metodo === 'POST') {
        json("Respuesta para POST en $ruta", ["ejemplo" => "datos"]);
    } else {
        vista($ruta, ["ejemplo" => "datos"]);
    }
});

$app->run();
