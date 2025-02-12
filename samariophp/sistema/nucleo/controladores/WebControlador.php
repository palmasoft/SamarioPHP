<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Aplicacion\Servicios\Autenticacion;
use SamarioPHP\Aplicacion\Servicios\CorreoElectronico;
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class WebControlador extends Controlador {

    // Acción para mostrar la página de inicio
    public function mostrarInicio() {
        // Suponiendo que $this->logAplicacion y otras dependencias están correctamente configuradas
        $mensaje = "Mensjae de Bienvenida.";
        return vista(VISTA_INICIO, ['mensaje' => $mensaje]);
    }

}