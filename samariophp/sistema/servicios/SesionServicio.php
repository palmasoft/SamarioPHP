<?php
namespace SamarioPHP\Sistema\Servicios;

class SesionServicio {

    public static function iniciar(\Usuario $usuario) {
        self::arrancar();
        session_regenerate_id(true);
        $_SESSION['USUARIO'] = $usuario;
        $_SESSION['usuario_id'] = $usuario->id;
    }

    public static function cerrar() {
        self::arrancar();
        session_unset();
        session_destroy();
    }

    public static function usuarioID() {
        return self::obtenerSesion('usuario_id');
    }

    public static function usuario() {
        self::arrancar();

        if (!isset($_SESSION['USUARIO'])) {
            $id = self::usuarioID();
            if (!$id) {
                return null;
            }
            $_SESSION['USUARIO'] = Usuario::para('id', $id);
        }

        return $_SESSION['USUARIO'];
    }

    public static function refrescarUsuario() {
        self::arrancar();
        if (isset($_SESSION['usuario_id'])) {
            $_SESSION['USUARIO'] = Usuario::para('id', $_SESSION['usuario_id']);
        }
    }

    public static function iniciada() {
        return self::usuarioID() !== null;
    }

    private static function obtenerSesion($clave) {
        self::arrancar();
        return $_SESSION[$clave] ?? null;
    }

    private static function arrancar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => true,
                'use_strict_mode' => true
            ]);
        }
    }

}