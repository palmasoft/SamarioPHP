<?php
namespace SamarioPHP\Middleware;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareGestorHTTP implements MiddlewareInterface {

    public function process(HTTPSolicitud $request, RequestHandlerInterface $handler): HTTPRespuesta {

        // Llamar al GestorHTTP para manejar la solicitud antes de pasarla al siguiente middleware
        \GestorHTTP::solicitud($request);

        // Pasar la solicitud al siguiente middleware o controlador
        $respuesta = $handler->handle($request);

        // Llamar al GestorHTTP para manejar la respuesta después de que haya sido procesada
        \GestorHTTP::respuesta($respuesta);

        // Retornar la respuesta procesada
        return $respuesta;
    }

}