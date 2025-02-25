<?php

//cargar librerias de composer
require_once DIR_FRAMEWORK . '/samariophp/constantes.php';
require_once RUTA_AUTOLOAD;
require_once RUTA_FUNCIONES;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use SamarioPHP\Sistema\Middleware\GestorHTTPMiddleware;
use SamarioPHP\Sistema\Middleware\AutenticacionMiddleware;
use SamarioPHP\Sistema\Auth;

//use SamarioPHP\Sistema\Aplicacion;
//(Aplicacion::obtenerInstancia())
//    ->arrancar();
//    

$app = AppFactory::create();
$app->add(new GestorHTTPMiddleware());
$app->add(new AutenticacionMiddleware());

$gestorRutas = new GestorRutas();

$app->any('/{ruta:.*}', function ($request, $response, $args) use ($gestorRutas) {
    $ruta = $args['ruta']; // Ruta solicitada
    $metodo = $request->getMethod();

    // Normalizar la ruta vacía
    if ($ruta === "/") {
        $ruta = "";
    }

    if (esVistaPublica($ruta)) {
        return vista($ruta);
    }

    if (esVistaApp($ruta)) {
        return $gestorRutas->resolverRuta($ruta, $metodo);
    }
    
    if (esVistaApp($ruta)) {
        return $gestorRutas->resolverRuta($ruta, $metodo);
    }
    return Rutas::rutaNoValida();
});

$app->run();
