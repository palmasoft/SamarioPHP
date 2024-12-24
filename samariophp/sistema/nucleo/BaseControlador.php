<?php
// BaseControlador.php
namespace SamarioPHP\Aplicacion\Controladores;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class BaseControlador {

  // Propiedades comunes
  protected $solicitud;
  protected $respuesta;

  public function __construct(HTTPSolicitud $solicitud, HTTPRespuesta $respuesta) {
    $this->solicitud = $solicitud;
    $this->respuesta = $respuesta;
  }

  // Métodos auxiliares
  public function obtenerSolicitud() {
    return $this->solicitud;
  }

  public function obtenerRespuesta() {
    return $this->respuesta;
  }

}