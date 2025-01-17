<?php
namespace SamarioPHP\Aplicacion\Servicios;

use SamarioPHP\BaseDeDatos\BaseDatos;
use SamarioPHP\Aplicacion\Modelos\Usuario; // Importa el modelo Usuario
use SamarioPHP\Aplicacion\Modelos\Perfil;

class Autenticacion {

  // Obtener el ID del usuario logueado
  public function usuarioID() {
    return isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
  }

  public function registrar($correo, $contrasena, $nombre = null) {
    // Crear una nueva instancia del modelo Usuario
    $Usuario = new Usuario();
    // Rellenar los datos y guardar
    $Usuario->correo = $correo;
    $Usuario->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
    $Usuario->nombre = \Utilidades::generarNombreUsuario($nombre);
    $Usuario->nuevo();

    $Perfil = new Perfil();
    $Perfil->nombre_completo = $nombre;
    $Perfil->usuario_id = $Usuario->id;
    $Perfil->guardar();

    $Usuario->Perfil = $Perfil;

    return $Usuario;
  }

  public function verificarCorreo($token) {
    $Usuario = (new Usuario())::porTokenVerificacion($token) ?? null;
    if (!$Usuario) {
      throw new \Exception("Token no válido.");
    }
    if ($Usuario->correo_verificado) {
      throw new \Exception('El correo ya estaba verificado.');
    }
    // Usar el modelo Usuario para actualizar
    $Usuario->verificarCorreo();
    $Usuario->guardar();
    return $Usuario;
  }

  //
  //
  //

  public function validarCredenciales($correo, $contrasena) {
    // Usar el modelo Usuario para buscar al usuario
    $Usuario = ( new Usuario() )::para('correo', $correo) ?? null;
    if (!$Usuario) {
      return ['error' => true, 'message' => "Correo no encontrado."];
    }

    if (!password_verify($contrasena, $Usuario->contrasena)) {
      return ['error' => true, 'message' => "Contraseña incorrecta."];
    }

    // Verificar el estado del usuario
    if ($Usuario->estado !== 'activo') {
      $msj = $this->obtenerMensajePorEstado($Usuario->estado);
      $mensaje = $msj ?? 'Tu cuenta no está activa.';
      return ['error' => true, 'message' => $mensaje];
    }
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

  //
  //
  //
  //
  //
  //
  //
  public function recuperarContrasena($correo) {
    $Usuario = Usuario::donde('correo', '=', $correo)[0] ?? null;

    if (!$Usuario) {
      throw new \Exception("Correo no encontrado.");
    }

    $token = bin2hex(random_bytes(32));
    $Usuario->rellenar(['token_recuperacion' => $token]);
    $Usuario->guardar();

    // Aquí puedes enviar un correo con el token
    return $token;
  }

  public function restablecerContrasena($token, $nuevaContrasena) {
    $Usuario = Usuario::donde('token_recuperacion', '=', $token)[0] ?? null;

    if (!$Usuario) {
      throw new \Exception("Token no válido.");
    }

    $Usuario->rellenar([
        'contrasena' => password_hash($nuevaContrasena, PASSWORD_BCRYPT),
        'token_recuperacion' => null,
    ]);
    $Usuario->guardar();

    return true;
  }

}