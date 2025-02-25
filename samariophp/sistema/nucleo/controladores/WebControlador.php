<?php
namespace SamarioPHP\Aplicacion\Controladores;

class WebControlador extends Controlador {

    // Acción para mostrar la página de inicio
    public function mostrarInicio() {
        // Suponiendo que $this->logAplicacion y otras dependencias están correctamente configuradas
        $mensaje = "Mensjae de Bienvenida.";
        return vista(VISTA_INICIO, ['mensaje' => $mensaje]);
    }

}