<?php
namespace SamarioPHP\Sistema\Servicios;

class AutenticacionServicio {

    protected $usuarioServicio;

    public function __construct(UsuarioServicio $usuarioServicio) {
        $this->usuarioServicio = $usuarioServicio;
    }

    public function registrar($correo, $contrasena, $nombre) {
        $respuestaRegistro = $this->usuarioServicio->registrar($correo, $contrasena, $nombre);

        if ($respuestaRegistro->tipo !== 'exito') {
            return $respuestaRegistro;
        }

        SesionServicio::iniciar($respuestaRegistro->datos['usuario']);
        return exito("Usuario registrado e iniciado sesión.", ['usuario' => $respuestaRegistro->datos['usuario']]);
    }

    public function existeUsuario($correo) { 
        return $this->usuarioServicio->existeCorreo($correo);
    }

    public function validarCredenciales($correo, $contrasena) {
        $Usuario = \Usuario::para('correo', $correo) ?? null;

        if (!$Usuario || !password_verify($contrasena, $Usuario->contrasena)) {
            return error("Credenciales incorrectas.");
        }

        if ($Usuario->estado !== 'activo') {
            return alerta($this->obtenerMensajePorEstado($Usuario->estado));
        }

        SesionServicio::iniciar($Usuario);
        return exito("Inicio de sesión exitoso.", ['usuario' => $Usuario]);
    }

    public function cerrarSesion() {
        SesionServicio::cerrar();
        return exito("Sesión cerrada.");
    }

    public function obtenerUsuarioAutenticado() {
        return SesionServicio::usuario();
    }

    private function obtenerMensajePorEstado($estado) {
        return [
            'inactivo' => 'Tu cuenta está inactiva. Verifica tu correo.',
            'suspendido' => 'Tu cuenta ha sido suspendida. Contacta al soporte.',
            'eliminado' => 'Tu cuenta fue eliminada. No puedes iniciar sesión.'
            ][$estado] ?? 'Estado desconocido. Contacta al soporte.';
    }

}