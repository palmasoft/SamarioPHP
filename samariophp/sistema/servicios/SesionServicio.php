<?php
namespace SamarioPHP\Sistema\Servicios;

class SesionServicio {
  
  public static function iniciar($Usuario) {
    self::arrancar();
    session_regenerate_id(true);
    $_SESSION['USUARIO'] = $Usuario;
    $_SESSION['usuario_id'] = $Usuario->id;
  }

  public static function cerrar() {
    self::arrancar();
    session_unset();
    session_destroy();
  }

  public static function usuarioID() {
    return self::obtenerSesion('usuario_id');
  }

  public static function iniciada() {
    return self::usuarioID() !== null;
  }

  private static function obtenerSesion($clave) {
    self::arrancar();
    return $_SESSION[$clave] ?? null;
  }

  public static function arrancar() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start([
          'cookie_httponly' => true,
          'cookie_secure' => true,
          'use_strict_mode' => true
      ]);
    }
  }
}
