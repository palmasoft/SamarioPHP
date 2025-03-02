<?php
//cargar librerias de composer
require_once DIR_FRAMEWORK . '/samariophp/constantes.php';
require_once RUTA_AUTOLOAD;
require_once RUTA_FUNCIONES;
use SamarioPHP\Sistema\Middleware\VerificarInstalacionMiddleware;
use SamarioPHP\Sistema\Middleware\GestorHTTPMiddleware;
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use SamarioPHP\Sistema\Middleware\AutenticacionMiddleware;
use Slim\Factory\AppFactory;

// Al iniciar la aplicación
Rutas::cargarRutasDesdeComponentes();

//use SamarioPHP\Sistema\Aplicacion;
//(Aplicacion::obtenerInstancia())
//    ->arrancar();
//    
$app = AppFactory::create();
//$app->add(new GestorHTTPMiddleware());
$app->add(new VerificarInstalacionMiddleware());
$app->add(new AutenticacionMiddleware());

$app->any('/{ruta:.*}', function (HTTPSolicitud $request, HTTPRespuesta $response, $args) {
    GestorHTTP::solicitud($request);
    GestorHTTP::respuesta($response);
    $ruta = $args['ruta'];
    $metodo = $request->getMethod();
    $Ruta = new Ruta($ruta, $metodo);
    if ($Ruta->esValida()) {
        return Rutas::resolverRuta($Ruta);
    }
    return Rutas::rutaNoValida();
});

$app->run();
