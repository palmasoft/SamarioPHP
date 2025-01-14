<?php
namespace SamarioPHP\Aplicacion\Servicios;

use SamarioPHP\BaseDeDatos\BaseDatos;
use SamarioPHP\Aplicacion\Modelos\Usuario; // Importa el modelo Usuario

class Autenticacion {

  // Obtener el ID del usuario logueado
  public function usuarioID() {
    return isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
  }

  public function registrar($correo, $contrasena, $nombre = null) {
    // Crear una nueva instancia del modelo Usuario
    $usuario = new Usuario();

    // Rellenar los datos y guardar
    $usuario->rellenar([
        'correo' => $correo,
        'contrasena' => password_hash($contrasena, PASSWORD_BCRYPT),
        'nombre' => $nombre,
    ]);
    $usuario->guardar();
    return $usuario;
  }

  public function verificarCorreo($token) {
    $usuario = Usuario::porTokenVerificacion($token) ?? null;
    if (!$usuario) {
      throw new \Exception("Token no válido.");
    }

    if ($usuario->correo_verificado) {
      throw new \Exception('El correo ya estaba verificado.');
    }

    // Usar el modelo Usuario para actualizar
    $usuario->verificarCorreo();
    $usuario->guardar();
    return $usuario;
  }

  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //
  //


  public function iniciarSesion($correo, $contrasena) {
    // Usar el modelo Usuario para buscar al usuario
    $usuario = Usuario::donde('correo', '=', $correo)[0] ?? null;

    if (!$usuario) {
      throw new \Exception("Usuario no encontrado.");
    }

    if (!password_verify($contrasena, $usuario['contrasena'])) {
      throw new \Exception("Contraseña incorrecta.");
    }

    // Iniciar sesión (manejo de sesión aquí)
    session_start();
    $_SESSION['usuario_id'] = $usuario['id'];

    return true;
  }

  public function cerrarSesion() {
    session_start();
    session_unset();
    session_destroy();
    return true;
  }

  //
  //
  //
  //
  //
  //
  //
  public function recuperarContrasena($correo) {
    $usuario = Usuario::donde('correo', '=', $correo)[0] ?? null;

    if (!$usuario) {
      throw new \Exception("Correo no encontrado.");
    }

    $token = bin2hex(random_bytes(32));
    $usuario->rellenar(['token_recuperacion' => $token]);
    $usuario->guardar();

    // Aquí puedes enviar un correo con el token
    return $token;
  }

  public function restablecerContrasena($token, $nuevaContrasena) {
    $usuario = Usuario::donde('token_recuperacion', '=', $token)[0] ?? null;

    if (!$usuario) {
      throw new \Exception("Token no válido.");
    }

    $usuario->rellenar([
        'contrasena' => password_hash($nuevaContrasena, PASSWORD_BCRYPT),
        'token_recuperacion' => null,
    ]);
    $usuario->guardar();

    return true;
  }

}