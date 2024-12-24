<?php

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GestorHTTP {

// Propiedades estáticas
  public static $Solicitud;
  public static $Respuesta;
  public static $datos;

// Métodos adicionales que necesites
// Métodos adicionales que necesites
  public static function solicitud($Solicitud) {
    self::$Solicitud = $Solicitud;
    self::$datos = $Solicitud->getParsedBody();
  }

  public static function obtenerSolicitud() : HTTPSolicitud {
    return self::$Solicitud;
  }

  public static function respuesta($Respuesta) {
    self::$Respuesta = $Respuesta;
  }

  public static function obtenerRespuesta() {
    return self::$Respuesta ?? new \Slim\Psr7\Response();
  }

// Por ejemplo, si necesitas acceder a algún valor de la solicitud
  public static function parametro($campo) {
    return self::$datos[$campo] ?? null;  // Devuelve null si el campo no existe
  }

}