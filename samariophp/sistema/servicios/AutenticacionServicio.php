<?php
namespace SamarioPHP\Sistema\Servicios;

use SamarioPHP\Basededatos\Modelos\Usuario;

class AutenticacionServicio {

    protected $sesion;
    protected $usuarioServicio;

    public function __construct(SesionServicio $sesion, UsuarioServicio $usuarioServicio) {
        $this->sesion = $sesion;
        $this->usuarioServicio = $usuarioServicio;
    }

    public function validarCredenciales($correo, $contrasena) {
        $Usuario = Usuario::para('correo', $correo) ?? null;
        if (!$Usuario || !password_verify($contrasena, $Usuario->contrasena)) {
            return Respuesta::error("Credenciales incorrectas.");
        }

        if ($Usuario->estado !== 'activo') {
            return Respuesta::alerta($this->obtenerMensajePorEstado($Usuario->estado));
        }

        $this->sesion->iniciar($Usuario);
        return Respuesta::exito("Inicio de sesión exitoso.", ['usuario' => $Usuario]);
    }

    public function registrarYAutenticar($correo, $contrasena, $nombre) {
        $respuestaRegistro = $this->usuarioServicio->registrar($correo, $contrasena, $nombre);
        if ($respuestaRegistro->tipo !== 'exito') {
            return $respuestaRegistro;
        }

        $Usuario = $respuestaRegistro->datos['usuario'];
        $this->sesion->iniciar($Usuario);
        return Respuesta::exito("Usuario registrado e iniciado sesión.", ['usuario' => $Usuario]);
    }

    private function obtenerMensajePorEstado($estado) {
        $mensajes = [
            'inactivo' => 'Tu cuenta está inactiva. Verifica tu correo.',
            'suspendido' => 'Tu cuenta ha sido suspendida. Contacta al soporte.',
            'eliminado' => 'Tu cuenta fue eliminada. No puedes iniciar sesión.'
        ];
        return $mensajes[$estado] ?? 'Estado desconocido. Contacta al soporte.';
    }

}