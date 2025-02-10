<?php
namespace SamarioPHP\Sistema\Servicios;

use SamarioPHP\Basededatos\Modelos\Usuario;
use SamarioPHP\Basededatos\Modelos\Perfil;

class UsuarioServicio {
    
  /**
   * Genera un nombre de usuario a partir de un nombre completo.
   * 
   * @param string $nombreCompleto El nombre completo del usuario.
   * @return string El nombre de usuario generado.
   */
  public function generarNombreUsuario($nombreCompleto) {
    // Eliminar acentos y caracteres especiales
    $nombreCompleto = \GestorNombres::normalizar_string($nombreCompleto);

    // Convertir a minúsculas
    $nombreCompleto = strtolower($nombreCompleto);

    // Reemplazar espacios con guiones bajos
    $nombreUsuario = str_replace(' ', '_', $nombreCompleto);

    // Limitar la longitud del nombre de usuario (opcional)
    $nombreUsuario = substr($nombreUsuario, 0, 21); // Limitar a 21 caracteres

    return $nombreUsuario;
  }

  public function registrar($correo, $contrasena, $nombre = null) {
    $Usuario = new Usuario();
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
      'token_recuperacion' => null
    ]);
    $Usuario->guardar();
    return true;
  }
}
