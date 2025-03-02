<?php
namespace SamarioPHP\Aplicacion\Controladores;

class AppControlador extends Controlador {
  
  public function mostrarPanelAdministracion() {
    // Suponiendo que $this->logAplicacion y otras dependencias están correctamente configuradas
    $mensaje = "Mensaje de Bienvenida.";
    return vista(VISTA_ADMIN, ['mensaje' => $mensaje]);
  }
  
}