<?php
namespace SamarioPHP\Sistema\Servicios;

use SamarioPHP\Aplicacion\Modelos\Usuario;

class AutenticacionServicio {
  
  protected $sesion;

  public function __construct(SesionServicio $sesion) {
    $this->sesion = $sesion;
  }

  public function validarCredenciales($correo, $contrasena) {
    $Usuario = Usuario::para('correo', $correo) ?? null;
    if (!$Usuario || !password_verify($contrasena, $Usuario->contrasena)) {
      return ['error' => true, 'message' => "Credenciales incorrectas."];
    }

    if ($Usuario->estado !== 'activo') {
      return ['error' => true, 'message' => $this->obtenerMensajePorEstado($Usuario->estado)];
    }

    $this->sesion->iniciar($Usuario);
    return ['error' => false, 'Usuario' => $Usuario];
  }

  private function obtenerMensajePorEstado($estado) {
    $mensajes = [
      'inactivo' => 'Tu cuenta está inactiva. Por favor, verifica tu correo.',
      'suspendido' => 'Tu cuenta ha sido suspendida. Contacta al soporte.',
      'eliminado' => 'Tu cuenta fue eliminada. No puedes iniciar sesión.'
    ];
    return $mensajes[$estado] ?? 'Estado desconocido. Contacta al soporte.';
  }

  public function cerrarSesion() {
    $this->sesion->cerrar();
  }
}
