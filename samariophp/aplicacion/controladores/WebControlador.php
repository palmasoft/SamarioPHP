<?php
namespace SamarioPHP\Aplicacion\Controladores;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class WebControlador extends Controlador {

  // Acción para mostrar la página de inicio
  public function mostrarInicio() {
    // Suponiendo que $this->logAplicacion y otras dependencias están correctamente configuradas
    $mensaje = "Mensjae de Bienvenida.";
    return $this->renderizar('inicio', ['mensaje' => $mensaje]);
  }

}