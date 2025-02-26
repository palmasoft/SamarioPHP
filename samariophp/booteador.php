<?php
//cargar librerias de composer
require_once DIR_FRAMEWORK . '/samariophp/constantes.php';
require_once RUTA_AUTOLOAD;
require_once RUTA_FUNCIONES;

use SamarioPHP\Sistema\Middleware\VerificarInstalacionMiddleware;

use SamarioPHP\Sistema\Middleware\GestorHTTPMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use SamarioPHP\Sistema\Middleware\AutenticacionMiddleware;

use Slim\Factory\AppFactory;

//use SamarioPHP\Sistema\Aplicacion;
//(Aplicacion::obtenerInstancia())
//    ->arrancar();
//    
$app = AppFactory::create();
$app->add(new VerificarInstalacionMiddleware());
$app->add(new GestorHTTPMiddleware());
$app->add(new AutenticacionMiddleware());

$app->any('/{ruta:.*}', function (Request $request, Response $response, $args) {
    $ruta = $args['ruta'];
    // Normalizar la ruta vacía
    if ($ruta === "/") {
        $ruta = "";
    }

    if (Ruta::esPublica($ruta)) {
        return vista($ruta);
    }

    $met = GestorHTTP::$Solicitud->getMethod();
    print_r($met);

    $metodo = $request->getMethod();
    if (Ruta::esWeb($ruta)) {
        return Ruta::resolverRuta($ruta, $metodo);
    }

    if (Ruta::esPrivada($ruta)) {
        return Ruta::ejecutarRuta($ruta, $metodo);
    }

    return Rutas::rutaNoValida();
});

$app->run();
