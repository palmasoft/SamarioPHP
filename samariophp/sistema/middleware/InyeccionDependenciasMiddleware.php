<?php
namespace SamarioPHP\Middleware;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use GestorHTTP;

class InyeccionDependenciasMiddleware {

  public function __invoke(HTTPSolicitud $solicitud, HTTPRespuesta $respuesta, callable $next) {
    // Aquí puedes agregar cualquier lógica adicional si es necesario
    GestorHTTP::registrarSolicitud($solicitud);
    GestorHTTP::registrarRespuesta($respuesta);
    return $next($solicitud, $respuesta);
  }

}