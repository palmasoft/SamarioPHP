<?php
namespace SamarioPHP\Sistema\Servicios;

use SamarioPHP\Basededatos\Modelos\Usuario;
use SamarioPHP\Basededatos\Modelos\Perfil;

class UsuarioServicio {

    public function registrar($correo, $contrasena, $nombre = null) {
        if (Usuario::para('correo', $correo)) {
            return Respuesta::error("El correo ya está registrado.");
        }
        $Usuario = new Usuario();
        $Usuario->correo = $correo;
        $Usuario->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
        $Usuario->nombre = $this->generarNombreUsuario($nombre);
        $Usuario->nuevo();

        $Perfil = new Perfil();
        $Perfil->nombre_completo = $nombre;
        $Perfil->usuario_id = $Usuario->id;
        $Perfil->guardar();

        $Usuario->Perfil = $Perfil;
        return Respuesta::exito("Usuario registrado con éxito.", ['usuario' => $Usuario]);
    }

    public function recuperarContrasena($correo) {
        $Usuario = Usuario::para('correo', $correo) ?? null;
        if (!$Usuario) {
            return Respuesta::error("Correo no encontrado.");
        }

        $token = bin2hex(random_bytes(32));
        $Usuario->rellenar(['token_recuperacion' => $token]);
        $Usuario->guardar();

        // Aquí puedes integrar el envío de correo con el token
        return Respuesta::exito("Se ha enviado un correo para restablecer la contraseña.", ['token' => $token]);
    }

    public function restablecerContrasena($token, $nuevaContrasena) {
        $Usuario = Usuario::para('token_recuperacion', $token) ?? null;
        if (!$Usuario) {
            return Respuesta::error("Token no válido.");
        }

        $Usuario->rellenar([
            'contrasena' => password_hash($nuevaContrasena, PASSWORD_BCRYPT),
            'token_recuperacion' => null
        ]);
        $Usuario->guardar();

        return Respuesta::exito("Contraseña restablecida con éxito.");
    }

    private function generarNombreUsuario($nombreCompleto = null) {
        $nombreCompleto = \GestorNombres::normalizar_string($nombreCompleto ?? uniqid("nuevo"));
        $nombreUsuario = strtolower(str_replace(' ', '_', $nombreCompleto));
        return substr($nombreUsuario, 0, 21);
    }

}