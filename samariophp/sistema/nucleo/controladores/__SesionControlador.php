<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Aplicacion\Servicios\Autenticacion;
use SamarioPHP\Aplicacion\Servicios\CorreoElectronico;

class SesionControlador {

  protected $autenticacion;
  protected $correoServicio;

  public function __construct(Autenticacion $autenticacion, CorreoElectronico $correoServicio) {
    $this->autenticacion = $autenticacion;
    $this->correoServicio = $correoServicio;
  }

  public function registrarUsuario($correo, $contrasena, $rcontrasena, $params = []) {
    try {
      if ($contrasena !== $rcontrasena) {
        throw new \Exception("Las contraseñas no coinciden.");
      }

      $token = $this->autenticacion->registrar($correo, $contrasena, $params['nombre'] ?? null);

      // Enviar correo de verificación
      $asunto = "Verifica tu correo";
      $cuerpo = "Haz clic en el siguiente enlace para verificar tu cuenta: <a href='https://tudominio.com/verificar?token={$token}'>Verificar cuenta</a>";
      $this->correoServicio->enviarCorreo($correo, $asunto, $cuerpo);

      return ['exito' => true, 'mensaje' => 'Usuario registrado y correo enviado'];
    } catch (\Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

  public function recuperarContrasena($correo) {
    try {
      $token = $this->autenticacion->recuperarContrasena($correo);

      // Enviar correo con el token de recuperación
      $asunto = "Recuperación de contraseña";
      $cuerpo = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='https://tudominio.com/restablecer?token={$token}'>Restablecer contraseña</a>";
      $this->correoServicio->enviarCorreo($correo, $asunto, $cuerpo);

      return ['exito' => true, 'mensaje' => 'Correo de recuperación enviado'];
    } catch (\Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

  public function verificarUsuario($token) {
    try {
      $this->autenticacion->verificarCorreo($token);
      return ['exito' => true, 'mensaje' => 'Correo verificado correctamente'];
    } catch (\Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

  public function iniciar($correo, $contrasena) {
    try {
      $resultado = $this->autenticacion->iniciarSesion($correo, $contrasena);

      if (!$resultado) {
        return ['exito' => false, 'mensaje' => 'Inicio de sesión fallido'];
      }

      return ['exito' => true, 'mensaje' => 'Inicio de sesión exitoso'];
    } catch (\Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

  public function cerrar() {
    try {
      session_start();
      if (isset($_SESSION['usuario_id'])) {
        $this->autenticacion->cerrarSesion();
        session_unset();
        session_destroy();
        return ['exito' => true, 'mensaje' => 'Sesión cerrada'];
      }
      return ['exito' => false, 'mensaje' => 'No hay sesión activa'];
    } catch (\Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

  public function restablecerContrasena($token, $nuevaContrasena) {
    try {
      $this->autenticacion->restablecerContrasena($token, $nuevaContrasena);
      return ['exito' => true, 'mensaje' => 'Contraseña restablecida'];
    } catch (\Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

  public function estaAutenticado() {
    session_start();
    return isset($_SESSION['usuario_id']);
  }

  public function obtenerUsuario() {
    session_start();
    return $_SESSION['usuario_id'] ?? null;
  }

}