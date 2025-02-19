<?php
namespace SamarioPHP\Aplicacion\Modelos;

use SamarioPHP\Basededatos\Modelo;

class Usuario extends Modelo {

    protected $obligatorios = [
        'nombre',
        'correo',
        'contrasena',
    ];

    // Método para obtener un usuario por correo (único)
    public static function porCorreo($correo) {
        return self::para('correo', $correo) ?? null;
    }

    // Método para obtener un usuario por token de verificación
    public static function porTokenVerificacion($token): Usuario {
        return self::para('token_verificacion', $token) ?? null;
    }

    // Método para obtener un usuario por token de recuperación de contraseña
    public static function porTokenRecuperacion($token) {
        return self::para('token_recuperacion', $token) ?? null;
    }

    // Método para crear un nuevo usuario
    public function nuevo() {
        // Si es un usuario nuevo, se asignan valores predeterminados como el token de verificación
        if (!$this->id) {
            $this->token_verificacion = bin2hex(random_bytes(16));  // Token de verificación único
            $this->correo_verificado = 0;
        }
        $this->guardar();
    }

    // Método para verificar el correo (cambiar estado de correo_verificado)
    public function verificarCorreo() {
        $this->estado = 'activo';
        $this->correo_verificado = 1;
        $this->token_verificacion = null;
        $this->guardar();
    }

    public function perfil() {
        return $this->tieneUn(Perfil::class);
    }

    public function roles() {
        return $this->perteneceAMuchos('Rol', 'usuarios_roles', 'usuario_id', 'rol_id');
    }

}