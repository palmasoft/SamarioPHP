<?php
namespace SamarioPHP\Sistema;

use SamarioPHP\Sistema\Servicios\AutenticacionServicio;
use SamarioPHP\Sistema\Servicios\UsuarioServicio;

class Auth {

    protected static $autenticacionServicio;

    public static function arrancarServicio() {
        self::$autenticacionServicio = new AutenticacionServicio(new UsuarioServicio());
    }

    public static function __callStatic($metodo, $argumentos) {
        if (!self::$autenticacionServicio) {
            throw new \Exception("El servicio de autenticación no ha sido configurado.");
        }
        if (!method_exists(self::$autenticacionServicio, $metodo)) {
            throw new \BadMethodCallException("El método {$metodo} no existe en el servicio de autenticación.");
        }
        return call_user_func_array([self::$autenticacionServicio, $metodo], $argumentos);
    }

}