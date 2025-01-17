<?php
namespace SamarioPHP\Aplicacion\Servicios;

class Sesion {

  private function obtenerSesion($clave) {
    $this->arrancar();
    return $_SESSION[$clave] ?? null;
  }

  private function establecerSesion($clave, $valor) {
    $this->arrancar();
    $_SESSION[$clave] = $valor;
  }

  private function eliminarSesion($clave) {
    $this->arrancar();
    unset($_SESSION[$clave]);
  }

  public function iniciar($Usuario) {
    $this->arrancar();
    session_regenerate_id(true);
    $this->establecerSesion('USUARIO', $Usuario);
    $this->establecerSesion('usuario_id', $Usuario->id);
  }

  public function cerrar() {
    $this->arrancar();
    session_unset();
    session_destroy();
    return true;
  }

  public function usuarioID() {
    return $this->obtenerSesion('usuario_id');
  }

  public function iniciada() {
    return $this->usuarioID() !== null;
  }

  private function arrancar() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start([
          'cookie_httponly' => true,
          'cookie_secure' => true,
          'use_strict_mode' => true
      ]);
    }
  }

}